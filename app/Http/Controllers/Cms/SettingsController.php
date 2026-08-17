<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Module;
use App\Models\Settings;
use App\Models\Submodule;
use App\Utils\CmsAccess;
use App\Utils\Helpers;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Class SettingsController
 *
 * Manages global system configurations including corporate identity,
 * social media links, and SEO metadata for Helin CMS.
 *
 * @version 1.1.0
 */
#[Title('Configuración General | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class SettingsController extends Component
{
    use WithFileUploads;

    /** @var bool UI state controller to toggle between view and edit modes */
    public bool $isEditing = true;

    /** @var string Company official name */
    public string $name = '';

    /** @var string Primary contact email */
    public string $email = '';

    /** @var string Physical office address */
    public string $address = '';

    /** @var string Tagline/Slogan */
    public string $tagline = '';

    /** @var string Contact address for contact page */
    public string $contact_address = '';

    /** @var string Main contact phone number */
    public string $phone = '';

    /** @var string Business hours and operation schedule */
    public string $shedule = '';

    /** @var string Copyright text for footer */
    public string $copy = '';

    /** @var mixed|null Uploaded image file instance */
    public $image;

    /** @var string|null Existing image path from storage */
    public ?string $current_image = null;

    /** @var mixed|null Uploaded default category image file instance */
    public $default_category_image;

    /** @var string|null Existing default category image path from storage */
    public ?string $current_default_category_image = null;

    /** @var mixed|null Uploaded default banner image file instance */
    public $default_banner_image;

    /** @var string|null Existing default banner image path from storage */
    public ?string $current_default_banner_image = null;

    /** @var string Social media URLs */
    public string $facebook = '';

    public string $instagram = '';

    public string $linkedin = '';

    public string $youtube = '';

    /** @var string SEO Metadata properties */
    public string $keywords = '';

    public string $description = '';

    public string $settings_description = '';

    /** @var string|null Analytics */
    public ?string $analytics_code = null;

    /** @var string|null Opinion/satisfaction survey URL */
    public ?string $opinion_url = null;

    /** @var array<int, array{name: string, url: string, whatsapp: string, active: bool}> Office list */
    public array $offices = [];

    /** @var array<int, array{value: string, label: string, active: bool}> Contact subject list */
    public array $contact_subjects = [];

    /**
     * Component Validation Rules.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:1024',
            'default_category_image' => 'nullable|image|max:1024',
            'default_banner_image' => 'nullable|image|max:2048',
            'opinion_url' => 'nullable|url|max:500',
            'offices' => 'nullable|array',
            'offices.*.name' => 'nullable|string|max:255',
            'offices.*.url' => 'nullable|string|max:500',
            'offices.*.whatsapp' => 'nullable|string|max:50',
            'offices.*.active' => 'boolean',
            'contact_subjects' => 'nullable|array',
            'contact_subjects.*.value' => 'nullable|string|max:100',
            'contact_subjects.*.label' => 'nullable|string|max:255',
            'contact_subjects.*.active' => 'boolean',
        ];
    }

    /**
     * Component Lifecycle: Security Access Control and Data Hydration.
     */
    public function mount(): void
    {
        CmsAccess::authorize(Module::SETTINGS, Submodule::GENERAL_SETTINGS, __('cms.abort.settings'));

        $this->loadSettings();
    }

    /**
     * Hydrates the component properties from the Settings model.
     */
    public function loadSettings(): void
    {
        $settings = Settings::firstOrNew(['id' => Settings::DEFAULT_SETTINGS]);

        $this->fill($settings->only([
            'name', 'email', 'address', 'tagline', 'contact_address', 'phone', 'shedule', 'copy',
            'facebook', 'instagram', 'linkedin', 'youtube',
            'keywords', 'description', 'settings_description', 'analytics_code',
            'opinion_url',
        ]));

        $this->offices = is_array($settings->offices) ? $settings->offices : [];

        $this->contact_subjects = is_array($settings->contact_subjects) ? $settings->contact_subjects : [];

        $this->current_image = $settings->image;
        $this->current_default_category_image = $settings->default_category_image;
        $this->current_default_banner_image = $settings->default_banner_image;
    }

    /**
     * Toggles the UI state between read-only and edit mode.
     */
    public function toggleEdit(): void
    {
        $this->isEditing = ! $this->isEditing;
        if (! $this->isEditing) {
            $this->loadSettings();
            $this->resetErrorBag();
        }
    }

    /**
     * Persist system-wide configuration updates.
     */
    public function save(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $settings = Settings::firstOrNew(['id' => Settings::DEFAULT_SETTINGS]);

            $settings->fill([
                'name' => $this->name,
                'email' => $this->email,
                'address' => $this->address,
                'tagline' => $this->tagline,
                'contact_address' => $this->contact_address,
                'phone' => $this->phone,
                'shedule' => $this->shedule,
                'copy' => $this->copy,
                'facebook' => $this->facebook,
                'instagram' => $this->instagram,
                'linkedin' => $this->linkedin,
                'youtube' => $this->youtube,
                'keywords' => $this->keywords,
                'description' => $this->description,
                'settings_description' => $this->settings_description,
                'analytics_code' => $this->analytics_code,
                'opinion_url' => $this->opinion_url,
                'offices' => array_values($this->offices),
                'contact_subjects' => array_values($this->contact_subjects),
            ]);

            if ($this->image) {
                $this->processImage($settings);
            }

            if ($this->default_category_image) {
                $filename = time().'_'.$this->default_category_image->getClientOriginalName();
                $settings->default_category_image = $this->default_category_image->storeAs('settings', $filename, 'public');
            }

            if ($this->default_banner_image) {
                $filename = time().'_'.$this->default_banner_image->getClientOriginalName();
                $settings->default_banner_image = $this->default_banner_image->storeAs('settings', $filename, 'public');
            }

            $settings->save();

            Activities::saveActivity(__('cms.controllers.settings.activity_updated', ['user_id' => Auth::id()]));
            DB::commit();

            $this->current_image = $settings->image;
            $this->image = null;
            $this->current_default_category_image = $settings->default_category_image;
            $this->default_category_image = null;
            $this->current_default_banner_image = $settings->default_banner_image;
            $this->default_banner_image = null;

            // Enviar toast de éxito
            $this->dispatch('toast', message: __('cms.controllers.settings.updated'), type: 'success');

            // Logging para debugging
            Log::info('Toast dispatched successfully: Configuración actualizada correctamente');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Settings Persistence Failure: '.$ex->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.settings.process_error'), type: 'error');
        }
    }

    /**
     * Add a new empty office row to the repeater.
     */
    public function addOffice(): void
    {
        $this->offices[] = ['name' => '', 'url' => '', 'whatsapp' => '', 'active' => true];
    }

    /**
     * Remove an office row from the repeater.
     */
    public function removeOffice(int $index): void
    {
        unset($this->offices[$index]);
        $this->offices = array_values($this->offices);
    }

    /**
     * Add a new empty contact subject row to the repeater.
     */
    public function addContactSubject(): void
    {
        $this->contact_subjects[] = ['value' => '', 'label' => '', 'active' => true];
    }

    /**
     * Remove a contact subject row from the repeater.
     */
    public function removeContactSubject(int $index): void
    {
        unset($this->contact_subjects[$index]);
        $this->contact_subjects = array_values($this->contact_subjects);
    }

    /**
     * Handle corporate image storage lifecycle.
     */
    private function processImage(Settings $settings): void
    {
        if ($settings->image) {
            Storage::disk('public')->delete($settings->image);
        }

        $filename = Helpers::generateImageName($this->image, 'setting');
        $path = $this->image->storeAs('settings', $filename, 'public');

        $settings->image = $path;
    }

    public function render(): View
    {
        return view('cms.settings.index', [
            'settings' => Settings::firstOrNew(['id' => Settings::DEFAULT_SETTINGS]),
        ]);
    }
}
