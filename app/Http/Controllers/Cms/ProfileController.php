<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\User;
use App\Models\Activities;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Class ProfileController
 *
 * Manages user profile information, including personal data updates,
 * profile asset management, password changes, and session security.
 *
 * @package App\Http\Controllers\Cms
 * @version 1.1.0
 */
#[Title('Mi Perfil | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ProfileController extends Component
{
    use WithFileUploads;

    /** @var string User's full name */
    public string $name = '';

    /** @var string User's email address */
    public string $email = '';

    /** @var string|null User's department */
    public ?string $department = '';

    /** @var string|null User's position */
    public ?string $position = '';

    /** @var string|null User's phone number */
    public ?string $phone = '';

    /** @var string|null User's biography */
    public ?string $biography = '';

    /** @var mixed Temporary upload for profile image */
    public $image;

    /** @var string|null Current profile image path stored in DB */
    public ?string $current_image = null;

    /** @var string Password verification field */
    public string $current_password = '';

    /** @var string New password field */
    public string $new_password = '';

    /** @var string Confirmation for new password */
    public string $password_confirmation = '';

    /**
     * Define validation rules for the component.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'department'=> 'nullable|string|max:255',
            'position'  => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'biography' => 'nullable|string|max:1000',
            'image'     => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    /**
     * Initialize component with current authenticated user data.
     *
     * @return void
     */
    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, __('cms.abort.profile'));
        }

        $this->name = $user->name;
        $this->email = $user->email;
        $this->department = $user->department ?? '';
        $this->position = $user->position ?? '';
        $this->phone = $user->phone ?? '';
        $this->biography = $user->biography ?? '';
        $this->current_image = $user->image;
    }

    /**
     * Render the profile management view.
     *
     * @return View
     */
    public function render(): View
    {
        return view('cms.profile.index');
    }

    /**
     * Persist updated profile information and handle image lifecycle.
     *
     * @return void
     */
    public function save(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            /** @var User $user */
            $user = Auth::user();
            $hasNewImage = false;

            $user->fill([
                'name'      => $this->name,
                'email'     => $this->email,
                'department'=> $this->department,
                'position'  => $this->position,
                'phone'     => $this->phone,
                'biography' => $this->biography,
            ]);

            // Handle Profile Image Upload
            if ($this->image) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $path = $this->image->store('profile-images', 'public');
                $user->image = $path;
                $hasNewImage = true;
            }

            // Save if data changed or a new image was uploaded
            if ($user->isDirty() || $hasNewImage) {
                $user->save();
                Activities::saveActivity(__('cms.controllers.profile.activity_updated'));
                $this->dispatch('toast', message: __('cms.controllers.profile.updated'), type: 'success');
            } else {
                $this->dispatch('toast', message: __('cms.controllers.profile.no_changes'), type: 'info');
            }

            DB::commit();

            $this->current_image = $user->image;
            $this->image = null;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Profile update failed: " . $e->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.profile.update_error'), type: 'error');
        }
    }

    /**
     * Update user password with specific validation rules.
     *
     * @return void
     */
    public function savePassword(): void
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', __('cms.controllers.profile.current_password_error'));
                return;
            }

            $user->update([
                'password' => Hash::make($this->new_password),
            ]);

            $this->reset(['current_password', 'new_password', 'password_confirmation']);

            Activities::saveActivity(__('cms.controllers.profile.activity_password'));
            $this->dispatch('toast', message: __('cms.controllers.profile.password_updated'), type: 'success');

        } catch (Exception $e) {
            Log::error("Password update failed: " . $e->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.profile.password_error'), type: 'error');
        }
    }

    /**
     * Remove the current profile image from storage and database.
     *
     * @return void
     */
    public function removeImage(): void
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if ($user->image) {
                Storage::disk('public')->delete($user->image);
                $user->image = null;
                $user->save();

                $this->current_image = null;
                Activities::saveActivity(__('cms.controllers.profile.activity_image_removed'));
                $this->dispatch('toast', message: __('cms.controllers.profile.image_removed'), type: 'info');
            }
        } catch (Exception $e) {
            Log::error("Profile image removal failed: " . $e->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.profile.image_remove_error'), type: 'error');
        }
    }

    /**
     * Clear all user sessions and redirect to login.
     *
     * @return mixed
     */
    public function closeAllSessions()
    {
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            DB::table('sessions')->where('user_id', $userId)->delete();

            Activities::saveActivity(__('cms.controllers.profile.activity_sessions_closed'));

            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();

            DB::commit();
            return redirect()->route('login');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Session termination failed: " . $e->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.profile.session_cleanup_error'), type: 'error');
        }
    }
}
