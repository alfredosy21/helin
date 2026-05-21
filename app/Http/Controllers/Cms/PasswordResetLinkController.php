<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\User;
use App\Services\CustomMail;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Class PasswordResetLinkController
 * * Orchestrates the password reset lifecycle including token generation,
 * secure email dispatching via CustomMail, and rate limiting security.
 * * @package App\Http\Controllers\Cms
 * @version 1.0.0
 */
#[Title('Restablecer Contraseña | Helin CMS')]
#[Layout('cms.layouts.auth')]
class PasswordResetLinkController extends Component {

    /** @var string The email address for the reset request */
    public string $email = '';

    /** @var bool Flag to toggle success state in the UI */
    public bool $requestSent = false;

    /**
     * Component Validation Rules.
     * * @return array<string, string>
     */
    protected array $rules = [
        'email' => 'required|email',
    ];

    /**
     * Render the password reset request interface.
     * * @return View
     */
    public function render(): View {
        return view('cms.auth.forgot-password');
    }

    /**
     * Generate a random password, update the user, and send it via email.
     *
     * @return void
     */
    public function sendResetLink(): void {
        $this->validate();

        if ($this->isRateLimited()) {
            $this->addError('email', __('cms.messages.password_reset.too_many_attempts'));
            return;
        }

        try {
            $user = User::query()->where('email', strtolower(trim($this->email)))->first();

            if (!$user) {
                $this->dispatch('toast', message: __('cms.controllers.password_reset.email_not_found'), type: 'success');
                $this->requestSent = true;
                return;
            }

            if (!$user->is_active) {
                $this->dispatch('toast', message: __('cms.messages.password_reset.inactive'), type: 'error');
                return;
            }

            // 1. Generate random password
            $plainPassword = Str::password(12, true, true, true, false);

            // 2. Update user password
            $user->password = Hash::make($plainPassword);
            $user->save();

            // 3. Send email with new password
            $this->sendNotification($user, $plainPassword);

            // 4. Audit Logging
            activity()
                    ->causedBy($user)
                    ->performedOn($user)
                    ->withProperties([
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log('Password reset (auto-generated) via Livewire Component');

            // 5. Set Cooldown for this IP
            cache()->put('rate-limit-reset-' . request()->ip(), true, 60);

            $this->dispatch('toast', message: __('cms.controllers.password_reset.email_sent'), type: 'success');
            $this->requestSent = true;
        } catch (Exception $e) {
            Log::error("Password Reset Flow Failure: " . $e->getMessage());
            $this->dispatch('toast', message: __('cms.controllers.password_reset.internal_error'), type: 'error');
        }
    }

    /**
     * Dispatches the email with the new generated password.
     *
     * @param User $user Recipient user
     * @param string $plainPassword The generated plain-text password
     * @return void
     */
    protected function sendNotification(User $user, string $plainPassword): void {
        try {
            CustomMail::passwordReset($user->email, $plainPassword, $user->name);
        } catch (Exception $e) {
            Log::critical("Mail Service Failure: Reset email not sent to {$user->email}", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if the current IP address is exceeding the request frequency.
     * * @return bool
     */
    private function isRateLimited(): bool {
        return cache()->has('rate-limit-reset-' . request()->ip());
    }

    /**
     * Reset the component state to allow a new attempt.
     * * @return void
     */
    public function resetForm(): void {
        $this->reset(['email', 'requestSent']);
    }
}
