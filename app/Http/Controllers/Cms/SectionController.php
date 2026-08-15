<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Sections;
use App\Models\Activities;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class SectionController
 *
 * Reactive management interface for Helin platform content blocks.
 * Provides real-time CRUD operations, media detachment logic, and audit logging.
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Secciones | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class SectionController extends Component {

    use WithPagination, WithFileUploads;

    /** Tipos de layout soportados por el editor de secciones */
    const LAYOUT_TYPES = [
        'text_simple', 'hero_badges', 'feedback_badges', 'stats_grid',
        'search_features', 'policy_points', 'mission_vision', 'value_grid',
        'brand_grid', 'cities_list', 'feature_box', 'hero_buttons', 'testimonials',
    ];

    /** Estilos de icono soportados */
    const ICON_STYLES = ['emoji', 'lucide', 'custom', 'none'];

    /** @var string Primary title of the section content block */
    #[Validate('required|string|max:255')]
    public string $title = '';

    /** @var string|null Subtítulo de la sección */
    public ?string $subtitle = '';

    /** @var string|null Main HTML or text content for the section */
    #[Validate('required|string')]
    public ?string $content = '';

    /** @var string|null Descripción de la sección (texto plano/HTML ligero) */
    public ?string $description = '';

    /** @var string|null Label for the Call to Action button */
    #[Validate('nullable|string|max:255')]
    public ?string $name_button = '';

    /** @var string|null Target URL or route for the button */
    #[Validate('nullable|string|max:500')]
    public ?string $url_button = '';

    /** @var string|null Slug de la categoría asociada (para secciones de productos) */
    #[Validate('nullable|string|max:255')]
    public ?string $category_slug = '';

    /** @var string Layout de presentación de la sección */
    public string $layout_type = 'text_simple';

    /** @var string Estilo de iconos usado por los items */
    public string $icon_style = 'emoji';

    /** @var array|null Items estructurados de la sección (repeater) */
    public ?array $items = [];

    /** @var string Clave del grupo JSON bajo la que se persisten los items */
    public string $itemsGroup = 'items';

    /** @var array|null Botones de la sección (repeater) */
    public ?array $buttons = [];

    /** @var array Uploaded image files */
    public $image = [];

    /** @var string|null Comma-separated list of image filenames */
    public ?string $imagePaths = '';

    /** @var bool Public visibility status */
    public bool $status = false;

    /** @var bool Content display toggle within the layout */
    public bool $status_content = false;

    /** @var int|null ID of the section currently being modified */
    public ?int $editingId = null;

    /** @var string Search query for filtering the section list */
    public string $search = '';

    /** @var int Pagination limit per page */
    public int $perPage = 20;

    /** @var bool Modal/Form visibility state */
    public bool $showEditForm = false;

    /** @var array Formatted photo collection for UI rendering */
    public array $photos = [];

    /** @var bool Global loading indicator state */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization Check.
     * Ensure only verified administrators can access the section management.
     */
    public function mount(): void {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.sections'));
        }
    }

    /**
     * Render the component view with paginated and filtered data.
     */
    public function render(): View {
        $sections = Sections::query()
                ->when($this->search, fn($query) => $query->where('title', 'like', "%{$this->search}%"))
                ->orderBy('id', 'asc')
                ->paginate($this->perPage);

        return view('cms.sections.index', [
            'sections' => $sections
        ]);
    }

    /**
     * Hydrate the form properties with existing section data.
     *
     * @param int $id
     */
    public function edit(int $id): void {
        $section = Sections::findOrFail($id);

        $this->editingId = $id;
        $this->title = $section->title;
        $this->subtitle = $section->subtitle;
        $this->content = $section->content;
        $this->description = $section->description;
        $this->name_button = $section->name_button;
        $this->url_button = $section->url_button;
        $this->category_slug = $section->category_slug;
        $this->layout_type = $section->layout_type ?: 'text_simple';
        $this->icon_style = $section->icon_style ?: 'emoji';
        $this->items = $this->decodeItems($section->items);
        $this->itemsGroup = $this->decodeItemsGroup($section->items) ?: $this->itemsGroupKeyFor($this->layout_type);
        $this->buttons = $this->decodeButtons($section->buttons);
        $this->imagePaths = $section->image;
        $this->status = (bool) $section->status;
        $this->status_content = (bool) $section->status_content;

        $this->loadPhotos();
        $this->showEditForm = true;
        $this->dispatch('open-edit-form');
    }

    /**
     * Commit section modifications to the database.
     */
    public function update(): void {
        $rules = [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => $this->contentRequired() ? 'required|string' : 'nullable|string',
            'description' => 'nullable|string',
            'name_button' => 'nullable|string|max:255',
            'url_button' => 'nullable|string|max:500',
            'category_slug' => 'nullable|string|max:255',
            'layout_type' => 'required|string|in:' . implode(',', self::LAYOUT_TYPES),
            'icon_style' => 'required|string|in:' . implode(',', self::ICON_STYLES),
            'items' => 'nullable|array|max:50',
            'items.*.icon' => 'nullable|string|max:50',
            'items.*.title' => 'nullable|string|max:255',
            'items.*.text' => 'nullable|string|max:255',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.url' => 'nullable|string|max:500',
            'items.*.number' => 'nullable|string|max:20',
            'buttons' => 'nullable|array|max:10',
            'buttons.*.text' => 'nullable|string|max:255',
            'buttons.*.url' => 'nullable|string|max:500',
            'buttons.*.style' => 'nullable|in:primary,secondary,outline',
            'image' => 'nullable|array|max:5',
            'image.*' => 'image|max:2048',
        ];

        $this->validate($rules);
        $this->isLoading = true;

        try {
            $section = Sections::findOrFail($this->editingId);

            // Process new images
            $imagePaths = $this->imagePaths;
            if (!empty($this->image)) {
                foreach ($this->image as $uploadedImage) {
                    $filename = Helpers::generateImageName($uploadedImage, 'section');
                    $path = $uploadedImage->storeAs('sections', $filename, 'public');

                    if ($imagePaths) {
                        $imagePaths .= ',' . $path;
                    } else {
                        $imagePaths = $path;
                    }
                }
            }

            $section->update([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => $this->content,
            'description' => $this->description,
            'name_button' => $this->name_button,
            'url_button' => $this->url_button,
            'category_slug' => $this->category_slug,
            'layout_type' => $this->layout_type,
            'icon_style' => $this->icon_style,
            'items' => $this->encodeItems(),
            'buttons' => $this->encodeButtons(),
            'image' => $imagePaths,
            'status' => $this->status ? 1 : 0,
            'status_content' => $this->status_content ? 1 : 0,
        ]);

            Activities::saveActivity(__('cms.controllers.sections.activity_updated', ['id' => $section->id, 'title' => $this->title]));

            $this->imagePaths = $imagePaths;
            $this->image = [];
            $this->loadPhotos();
            $this->dispatch('image-updated');
            $this->dispatch('toast', message: __('cms.controllers.sections.updated'), type: 'success');
            $this->cancelEdit();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.sections.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Trigger a confirmation event before deletion.
     *
     * @param int $id
     */
    public function delete(int $id): void {
        $this->dispatch('confirm-delete',
                id: $id,
                message: __('cms.controllers.sections.delete_confirm_message'),
                title: __('cms.controllers.sections.delete_confirm_title')
        );
    }

    /**
     * Permanently remove a section and record the audit trail.
     *
     * @param int $id
     */
    public function confirmDelete(int $id): void {
        try {
            $section = Sections::findOrFail($id);
            $title = $section->title;
            $section->delete();

            Activities::saveActivity(__('cms.controllers.sections.activity_deleted', ['title' => $title]));
            $this->dispatch('toast', message: __('cms.controllers.sections.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.sections.delete_error'), type: 'error');
        }
    }

    /**
     * Unlink a specific media file from the current section's record.
     *
     * @param string $photoName
     */
    public function removePhoto(string $photoName): void {
        if (!$this->imagePaths)
            return;

        try {
            $images = collect(explode(',', $this->imagePaths))
                    ->filter()
                    ->map(fn($img) => trim($img))
                    ->reject(fn($img) => $img === $photoName)
                    ->implode(',');

            $this->imagePaths = $images;

            if ($this->editingId) {
                Sections::query()->where('id', $this->editingId)->update(['image' => $images]);
                Activities::saveActivity(__('cms.controllers.sections.activity_photo_removed', ['id' => $this->editingId]));
            }

            $this->loadPhotos();
            $this->dispatch('toast', message: __('cms.controllers.sections.image_removed'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.sections.image_remove_error'), type: 'error');
        }
    }

    /**
     * Parse the raw image string into a renderable array for the UI.
     */
    private function loadPhotos(): void {
        $this->photos = collect(explode(',', $this->imagePaths ?? ''))
                ->filter()
                ->map(fn($img) => ['name' => trim($img)])
                ->values()
                ->toArray();
    }

    /**
     * Revert form state and hide management interface.
     */
    public function cancelEdit(): void {
        $this->resetForm();
        $this->showEditForm = false;
        $this->dispatch('close-edit-form');
    }

    /**
     * Reset internal state properties and validation errors.
     */
    protected function validationAttributes(): array {
        return [
            'title' => __('cms.validation_attributes.section_title'),
            'content' => __('cms.validation_attributes.section_content'),
            'name_button' => __('cms.validation_attributes.button_label'),
            'url_button' => __('cms.validation_attributes.button_url'),
            'category_slug' => __('cms.sections.category_slug_label'),
            'subtitle' => __('cms.sections.subtitle_label'),
            'description' => __('cms.sections.description_label'),
            'layout_type' => __('cms.sections.layout_type_label'),
            'icon_style' => __('cms.sections.icon_style_label'),
            'items' => __('cms.sections.items_label'),
            'buttons' => __('cms.sections.buttons_label'),
            'items.*.icon' => __('cms.sections.item_icon_label'),
            'items.*.title' => __('cms.sections.item_title_label'),
            'items.*.text' => __('cms.sections.item_text_label'),
            'items.*.description' => __('cms.sections.item_description_label'),
            'items.*.url' => __('cms.sections.item_url_label'),
            'items.*.number' => __('cms.sections.item_number_label'),
            'buttons.*.text' => __('cms.sections.button_text_label'),
            'buttons.*.url' => __('cms.sections.button_url_label'),
            'buttons.*.style' => __('cms.sections.button_style_label'),
        ];
    }

    private function resetForm(): void {
        $this->reset([
            'title', 'subtitle', 'content', 'description', 'name_button', 'url_button', 'category_slug',
            'layout_type', 'icon_style', 'items', 'itemsGroup', 'buttons',
            'image', 'imagePaths', 'status', 'status_content', 'editingId',
        ]);
        $this->resetValidation();
        $this->photos = [];
    }

    /**
     * Determine whether the content field is mandatory for the current layout.
     */
    private function contentRequired(): bool {
        return !in_array($this->layout_type, ['feature_box', 'hero_buttons', 'testimonials', 'brand_grid', 'value_grid']);
    }

    /**
     * Configuración de layout: clave de grupo JSON y campos editables por item.
     */
    private function layoutConfig(string $layoutType): array {
        return match ($layoutType) {
            'hero_badges'     => ['group' => 'hero_badges',     'fields' => ['icon', 'text']],
            'feature_box'     => ['group' => 'steps',           'fields' => ['icon', 'title', 'description', 'number']],
            'search_features' => ['group' => 'search_features', 'fields' => ['icon', 'title', 'description']],
            'value_grid'      => ['group' => 'value_grid',      'fields' => ['icon', 'title']],
            'policy_points'   => ['group' => 'policy_points',   'fields' => ['icon', 'title', 'description']],
            'mission_vision'  => ['group' => 'mission_vision',  'fields' => ['icon', 'title', 'description']],
            'cities_list'     => ['group' => 'cities_list',     'fields' => ['title', 'url']],
            'brand_grid'      => ['group' => 'brand_grid',      'fields' => ['title', 'url']],
            'feedback_badges' => ['group' => 'feedback_badges', 'fields' => ['icon', 'title', 'description']],
            'stats_grid'      => ['group' => 'stats_grid',      'fields' => ['icon', 'title', 'number']],
            'testimonials'    => ['group' => 'items',           'fields' => []],
            default           => ['group' => 'items',           'fields' => ['icon', 'title', 'description', 'url']],
        };
    }

    /**
     * Campos editables por cada item según el tipo de layout.
     */
    public function itemFields(string $layoutType): array {
        return $this->layoutConfig($layoutType)['fields'];
    }

    /**
     * Etiquetas traducidas de los campos editables de cada item.
     */
    public function itemFieldLabels(): array {
        return [
            'icon' => __('cms.sections.item_icon_label'),
            'title' => __('cms.sections.item_title_label'),
            'text' => __('cms.sections.item_text_label'),
            'description' => __('cms.sections.item_description_label'),
            'url' => __('cms.sections.item_url_label'),
            'number' => __('cms.sections.item_number_label'),
        ];
    }

    /**
     * Opciones traducidas para el selector de tipo de layout.
     */
    public function layoutOptions(): array {
        return [
            'text_simple' => __('cms.sections.layout_text_simple'),
            'hero_badges' => __('cms.sections.layout_hero_badges'),
            'feedback_badges' => __('cms.sections.layout_feedback_badges'),
            'stats_grid' => __('cms.sections.layout_stats_grid'),
            'search_features' => __('cms.sections.layout_search_features'),
            'policy_points' => __('cms.sections.layout_policy_points'),
            'mission_vision' => __('cms.sections.layout_mission_vision'),
            'value_grid' => __('cms.sections.layout_value_grid'),
            'brand_grid' => __('cms.sections.layout_brand_grid'),
            'cities_list' => __('cms.sections.layout_cities_list'),
            'feature_box' => __('cms.sections.layout_feature_box'),
            'hero_buttons' => __('cms.sections.layout_hero_buttons'),
            'testimonials' => __('cms.sections.layout_testimonials'),
        ];
    }

    /**
     * Opciones traducidas para el selector de estilo de icono.
     */
    public function iconStyleOptions(): array {
        return [
            'emoji' => __('cms.sections.icon_style_emoji'),
            'lucide' => __('cms.sections.icon_style_lucide'),
            'custom' => __('cms.sections.icon_style_custom'),
            'none' => __('cms.sections.icon_style_none'),
        ];
    }

    /**
     * Recalcula la clave de grupo JSON al cambiar el tipo de layout.
     */
    public function updatedLayoutType(string $value): void {
        $this->itemsGroup = $this->itemsGroupKeyFor($value);
    }

    /**
     * Clave del grupo JSON en la que se persisten los items.
     */
    public function itemsGroupKeyFor(string $layoutType): string {
        return $this->layoutConfig($layoutType)['group'];
    }

    /**
     * Clave del grupo JSON realmente almacenado, si el JSON es un objeto agrupado.
     */
    private function decodeItemsGroup(?string $raw): ?string {
        $decoded = $raw ? json_decode($raw, true) : null;
        if (!is_array($decoded) || array_is_list($decoded)) {
            return null;
        }
        return (string) array_key_first($decoded);
    }

    /**
     * Decodifica el JSON de items (objeto por grupo o lista plana) a un array.
     */
    private function decodeItems(?string $raw): array {
        $decoded = $raw ? json_decode($raw, true) : null;
        if (!is_array($decoded)) {
            return [];
        }
        if (array_is_list($decoded)) {
            return array_values($decoded);
        }
        $group = array_key_first($decoded);
        $items = is_array($decoded[$group] ?? null) ? $decoded[$group] : [];
        return array_values($items);
    }

    /**
     * Decodifica el JSON de botones a un array plano.
     */
    private function decodeButtons(?string $raw): array {
        $decoded = $raw ? json_decode($raw, true) : null;
        return is_array($decoded) ? array_values($decoded) : [];
    }

    /**
     * Codifica items descartando filas totalmente vacías.
     */
    private function encodeItems(): ?string {
        $items = array_values(array_filter($this->items ?? [], fn($item) => is_array($item) && $this->itemHasData($item)));
        if (count($items) === 0) {
            return null;
        }
        $group = $this->itemsGroup ?: $this->itemsGroupKeyFor($this->layout_type);
        return json_encode([$group => $items], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Codifica botones descartando filas sin texto ni URL.
     */
    private function encodeButtons(): ?string {
        $buttons = array_values(array_filter($this->buttons ?? [], function ($button) {
            if (!is_array($button)) {
                return false;
            }
            $text = trim((string) ($button['text'] ?? ''));
            $url = trim((string) ($button['url'] ?? ''));
            return $text !== '' || $url !== '';
        }));
        if (count($buttons) === 0) {
            return null;
        }
        return json_encode($buttons, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Un item se considera con datos si al menos un campo no está vacío.
     */
    private function itemHasData(array $item): bool {
        foreach (['icon', 'title', 'text', 'description', 'url', 'number'] as $field) {
            if (trim((string) ($item[$field] ?? '')) !== '') {
                return true;
            }
        }
        return false;
    }

    /**
     * Estructura vacía de un item según el layout actual.
     */
    private function emptyItem(): array {
        return array_fill_keys($this->itemFields($this->layout_type), '');
    }

    /**
     * Agrega un item al repeater.
     */
    public function addItem(): void {
        $this->items[] = $this->emptyItem();
    }

    /**
     * Elimina un item del repeater.
     */
    public function removeItem(int $index): void {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    /**
     * Reordena un item hacia arriba (-1) o abajo (1).
     */
    public function moveItem(int $index, int $direction): void {
        $this->moveArrayItem($this->items, $index, $direction);
    }

    /**
     * Agrega un botón al repeater.
     */
    public function addButton(): void {
        $this->buttons[] = ['text' => '', 'url' => '', 'style' => 'primary'];
    }

    /**
     * Elimina un botón del repeater.
     */
    public function removeButton(int $index): void {
        if (isset($this->buttons[$index])) {
            unset($this->buttons[$index]);
            $this->buttons = array_values($this->buttons);
        }
    }

    /**
     * Reordena un botón hacia arriba (-1) o abajo (1).
     */
    public function moveButton(int $index, int $direction): void {
        $this->moveArrayItem($this->buttons, $index, $direction);
    }

    /**
     * Intercambia el elemento del índice dado con su vecino según la dirección.
     */
    private function moveArrayItem(array &$array, int $index, int $direction): void {
        $target = $index + $direction;
        if (!isset($array[$index], $array[$target])) {
            return;
        }
        [$array[$index], $array[$target]] = [$array[$target], $array[$index]];
    }

    /**
     * Lifecycle listener: Reset pagination on search update.
     */
    public function updatedSearch(): void {
        $this->resetPage();
    }

    /**
     * Get formatted section list for legacy API compatibility.
     *
     * @return array
     */
    public function getSectionLists(): array {
        return Sections::orderBy('id', 'asc')->get()->map(fn($section) => [
                    'id' => $section->id,
                    'title' => strip_tags($section->title),
                    'created_at' => $section->created_at->format('m-d-Y H:i:s'),
                        ])->toArray();
    }
}
