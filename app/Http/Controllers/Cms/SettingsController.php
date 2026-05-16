<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Settings;
use App\Models\Activities;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Class SettingsController
 *
 * Manages global system configurations including corporate identity,
 * social media links, and SEO metadata for Helin CMS.
 *
 * @version 1.1.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Configuración General | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class SettingsController extends Component
{
    use WithFileUploads;

    /** @var bool UI state controller to toggle between view and edit modes */
    public bool $isEditing = false;

    /** @var string Company official name */
    public string $name = '';

    /** @var string Primary contact email */
    public string $email = '';

    /** @var string Physical office address */
    public string $address = '';

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

    /** @var string Social media URLs */
    public string $facebook = '';
    public string $instagram = '';
    public string $linkedin = '';
    public string $youtube = '';

    /** @var string SEO Metadata properties */
    public string $keywords = '';
    public string $description = '';
    public string $settings_description = '';

    /**
     * Component Validation Rules.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Component Lifecycle: Security Access Control and Data Hydration.
     *
     * @return void
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin System Settings.');
        }

        $this->loadSettings();
    }

    /**
     * Hydrates the component properties from the Settings model.
     *
     * @return void
     */
    public function loadSettings(): void
    {
        $settings = Settings::firstOrNew(['id' => Settings::DEFAULT_SETTINGS]);

        $this->fill($settings->only([
            'name', 'email', 'address', 'phone', 'shedule', 'copy',
            'facebook', 'instagram', 'linkedin', 'youtube',
            'keywords', 'description', 'settings_description'
        ]));

        $this->current_image = $settings->image;
    }

    /**
     * Toggles the UI state between read-only and edit mode.
     *
     * @return void
     */
    public function toggleEdit(): void
    {
        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->loadSettings();
            $this->resetErrorBag();
        }
    }

    /**
     * Persist system-wide configuration updates.
     *
     * @return void
     */
    public function save(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $settings = Settings::firstOrNew(['id' => Settings::DEFAULT_SETTINGS]);

            $settings->fill([
                'name'                 => $this->name,
                'email'                => $this->email,
                'address'              => $this->address,
                'phone'                => $this->phone,
                'shedule'              => $this->shedule,
                'copy'                 => $this->copy,
                'facebook'             => $this->facebook,
                'instagram'            => $this->instagram,
                'linkedin'             => $this->linkedin,
                'youtube'              => $this->youtube,
                'keywords'             => $this->keywords,
                'description'          => $this->description,
                'settings_description' => $this->settings_description,
            ]);

            if ($this->image) {
                $this->processImage($settings);
            }

            $settings->save();

            Activities::saveActivity("Configuración del sistema actualizada por Usuario #" . Auth::id());
            DB::commit();

            $this->current_image = $settings->image;
            $this->image = null;
            $this->isEditing = false;

            // Enviar toast de éxito
            $this->dispatch('toast', message: 'Configuración actualizada correctamente', type: 'success');

            // Logging para debugging
            Log::info('Toast dispatched successfully: Configuración actualizada correctamente');

        } catch (Exception $ex) {
            DB::rollBack();
            Log::error("Settings Persistence Failure: " . $ex->getMessage());
            $this->dispatch('toast', message: 'Error al sincronizar los ajustes corporativos', type: 'error');
        }
    }

    /**
     * Handle corporate image storage lifecycle.
     *
     * @param \App\Models\Settings $settings
     * @return void
     */
    private function processImage(Settings $settings): void
    {
        if ($settings->image) {
            Storage::disk('public')->delete($settings->image);
        }

        $filename = 'logo-' . Str::random(12) . '.' . $this->image->getClientOriginalExtension();
        $path = $this->image->storeAs('settings', $filename, 'public');

        $settings->image = $path;
    }

    public function render(): View
    {
        return view('cms.settings.index', [
            'settings' => Settings::firstOrNew(['id' => Settings::DEFAULT_SETTINGS])
        ]);
    }
}
