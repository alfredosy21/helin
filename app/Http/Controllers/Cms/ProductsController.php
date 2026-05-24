<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductMedia;
use App\Models\Activities;
use App\Services\FileUploadService;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Vital para la carga de imágenes/documentos
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Class ProductsController
 * * Orchestrates the complete lifecycle of medical products in the Helin catalog.
 * Reactive implementation for inventory, media management, and attributes.
 * * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Catálogo de Productos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ProductsController extends Component {

    use WithPagination,
        WithFileUploads;

    // --- Propiedades del Formulario (Vinculadas al modelo Product) ---
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public string $sku = '';
    public ?int $category_id = null;
    public ?int $brand_id = null;
    public string $description = '';
    public string $clinical_specs = '';
    public float $price = 0.00;
    public string $currency = 'USD';
    public int $stock = 0;
    public string $unit = 'Und';
    public ?string $meta_title = '';
    public ?string $meta_description = '';
    public ?string $meta_keywords = '';
    public bool $is_active = true;
    public bool $is_featured = false;
    public bool $is_new = true;
    public bool $is_on_sale = false;
    public ?float $sale_price = null;
    public ?string $sale_start_date = null;
    public ?string $sale_end_date = null;
    public ?string $published_at = null;
    // --- Propiedades de Relaciones ---
    public ?int $system_product_id = null;
    public ?int $product_platform_id = null;
    // --- Gestión de Archivos (Temporary uploads) ---
    public $featured_image;
    public $gallery = [];
    public $documents = [];
    // --- Filtros y UI ---
    public string $search = '';
    public ?int $filterCategory = null;
    public ?int $filterBrand = null;
    public string $filterStatus = 'all';
    public ?bool $filterFeatured = null;
    public int $perPage = 15;
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public bool $isLoading = false;
    protected string $paginationTheme = 'tailwind';

    /**
     * Reglas de validación dinámicas.
     */
    protected function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $this->editingId,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|max:2048', // 2MB Max
            'gallery.*' => 'nullable|image|max:2048',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:5120', // 5MB Max
        ];
    }

    public function mount(): void {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.products'));
        }
    }

    public function resetFilters(): void {
        $this->reset(['search', 'filterCategory', 'filterBrand', 'filterFeatured']);
        $this->perPage = 25;
    }

    public function render(): View {
        $products = Product::with(['category', 'brand', 'media'])
                ->when($this->search, function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
                })
                ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
                ->when($this->filterBrand, fn($q) => $q->where('brand_id', $this->filterBrand))
                ->when($this->filterStatus !== 'all', function ($q) {
                    match ($this->filterStatus) {
                        'active' => $q->where('is_active', true),
                        'inactive' => $q->where('is_active', false),
                        'featured' => $q->where('is_featured', true),
                    };
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);

        return view('cms.products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'brands' => Brand::all(),
            'systemProducts' => \App\Models\SystemProduct::all(),
            'productPlatforms' => \App\Models\ProductPlatform::all(),
        ]);
    }

    public function create(): void {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save(FileUploadService $fileUpload): void {
        $this->validate();
        $this->isLoading = true;

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'sku' => strtoupper($this->sku),
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'description' => $this->description,
                'clinical_specs' => $this->clinical_specs,
                'price' => $this->price,
                'currency' => $this->currency,
                'stock' => $this->stock,
                'unit' => $this->unit,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
                'is_new' => $this->is_new,
                'is_on_sale' => $this->is_on_sale,
                'sale_price' => $this->sale_price,
                'sale_start_date' => $this->sale_start_date,
                'sale_end_date' => $this->sale_end_date,
                'published_at' => $this->published_at ?: ($this->is_active ? now() : null),
            ];

            if ($this->editingId) {
                $product = Product::findOrFail($this->editingId);
                $product->update($payload);
                $msg = __('cms.controllers.products.updated');
            } else {
                $product = Product::create($payload);
                $msg = __('cms.controllers.products.created');
            }

            // --- Procesamiento de Archivos (Usando tu Service) ---
            // 1. Imagen Principal
            if ($this->featured_image) {
                // Limpiar anterior si existe
                $product->media()->where('is_main', true)->delete();

                $upload = $fileUpload->save($this->featured_image, 'products/featured');
                $product->media()->create([
                    'file_path' => $upload['path'],
                    'file_name' => $upload['name'],
                    'mime_type' => $upload['mime_type'],
                    'file_size' => $upload['size'],
                    'type' => 'image',
                    'is_main' => true,
                    'position' => 0
                ]);
            }

            // 2. Galería
            if (!empty($this->gallery)) {
                foreach ($this->gallery as $img) {
                    $upload = $fileUpload->save($img, 'products/gallery');
                    $product->media()->create([
                        'file_path' => $upload['path'],
                        'mime_type' => $upload['mime_type'],
                        'type' => 'image',
                        'is_main' => false,
                        'position' => 99
                    ]);
                }
            }

            // 3. Documentos
            if (!empty($this->documents)) {
                foreach ($this->documents as $doc) {
                    $upload = $fileUpload->save($doc, 'products/documents');
                    $product->media()->create([
                        'file_path' => $upload['path'],
                        'type' => 'document',
                        'title' => $doc->getClientOriginalName(),
                        'is_main' => false
                    ]);
                }
            }

            Activities::saveActivity(__('cms.controllers.products.activity_managed', ['name' => $product->name, 'sku' => $product->sku]));
            DB::commit();

            $this->dispatch('toast', message: $msg, type: 'success');
            $this->cancel();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Product Error: " . $e->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.products.process_error', ['message' => $e->getMessage()]), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    public function edit(int $id): void {
        $product = Product::findOrFail($id);
        $this->editingId = $id;

        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->description = $product->description ?? '';
        $this->clinical_specs = $product->clinical_specs ?? '';
        $this->price = (float) $product->price;
        $this->currency = $product->currency ?? 'USD';
        $this->stock = (int) $product->stock;
        $this->unit = $product->unit ?? 'Und';
        $this->meta_title = $product->meta_title ?? '';
        $this->meta_description = $product->meta_description ?? '';
        $this->meta_keywords = $product->meta_keywords ?? '';
        $this->is_active = $product->is_active;
        $this->is_featured = $product->is_featured;
        $this->is_new = $product->is_new;
        $this->is_on_sale = $product->is_on_sale;
        $this->sale_price = $product->sale_price;
        $this->sale_start_date = $product->sale_start_date ? $product->sale_start_date->format('Y-m-d') : null;
        $this->sale_end_date = $product->sale_end_date ? $product->sale_end_date->format('Y-m-d') : null;
        $this->published_at = $product->published_at ? $product->published_at->format('Y-m-d\TH:i') : null;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    public function duplicate(int $id): void {
        try {
            $original = Product::findOrFail($id);
            $new = $original->replicate();
            $new->name = $original->name . __('cms.controllers.products.copy_suffix');
            $new->sku = $original->sku . '-' . strtoupper(Str::random(4));
            $new->slug = Str::slug($new->name) . '-' . strtolower(Str::random(6));
            $new->is_active = false;
            $new->save();

            // Clonar relaciones de media
            foreach ($original->media as $m) {
                $newMedia = $m->replicate();
                $newMedia->product_id = $new->id;
                $newMedia->save();
            }

            Activities::saveActivity(__('cms.controllers.products.activity_duplicated', ['sku' => $new->sku]));
            $this->dispatch('toast', message: __('cms.controllers.products.duplicated'), type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: __('cms.controllers.products.duplicate_error'), type: 'error');
        }
    }

    public function toggleFeatured(int $id): void {
        try {
            $product = Product::findOrFail($id);
            $product->is_featured = !$product->is_featured;
            $product->save();

            $message = $product->is_featured
                ? __('cms.products.featured_added')
                : __('cms.products.featured_removed');

            Activities::saveActivity(__('cms.products.activity_featured_toggled', [
                'name' => $product->name,
                'status' => $product->is_featured ? 'featured' : 'unfeatured'
            ]));

            $this->dispatch('toast', message: $message, type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: __('cms.products.featured_error'), type: 'error');
        }
    }

    public function openDeleteModal(int $id): void {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(FileUploadService $fileUpload): void {
        if (!$this->deleteId)
            return;

        $product = Product::findOrFail($this->deleteId);

        foreach ($product->media as $media) {
            $fileUpload->delete($media->file_path);
        }

        $product->delete();
        Activities::saveActivity(__('cms.controllers.products.activity_deleted', ['name' => $product->name]));
        $this->dispatch('toast', message: __('cms.controllers.products.deleted'), type: 'success');

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function cancel(): void {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    private function resetForm(): void {
        $this->reset([
            'name', 'slug', 'sku', 'category_id', 'brand_id', 'description',
            'clinical_specs', 'price', 'currency', 'stock', 'unit',
            'meta_title', 'meta_description', 'meta_keywords',
            'is_active', 'is_featured', 'is_new', 'is_on_sale',
            'sale_price', 'sale_start_date', 'sale_end_date', 'published_at',
            'system_product_id', 'product_platform_id',
            'featured_image', 'gallery', 'documents', 'editingId'
        ]);
        $this->resetValidation();
    }
}
