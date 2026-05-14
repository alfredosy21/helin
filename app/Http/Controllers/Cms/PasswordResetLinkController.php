<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\User;
use App\Utils\Messages;
use App\Services\CustomMail;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
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
#[Title('Reset Password | Helin CMS')]
#[Layout('cms.layouts.auth')]
class PasswordResetLinkController extends Component
{
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
    public function render(): View
    {
        return view('cms.auth.forgot-password');
    }

    /**
     * Execute the password reset link request logic.
     * * Implements security checks for user existence and active status
     * without revealing sensitive account information (OWASP standard).
     * * @return void
     */
    public function sendResetLink(): void
    {
        $this->validate();

        // 1. Rate Limiting Check
        if ($this->isRateLimited()) {
            $this->addError('email', Messages::get('password.reset.too_many_attempts'));
            return;
        }

        try {
            // 2. Locate and Verify User
            $user = User::query()->where('email', strtolower(trim($this->email)))->first();

            if (!$user) {
                // Return success even if user doesn't exist to prevent email harvesting
                $this->requestSent = true;
                return;
            }

            if (!$user->is_active) {
                $this->dispatch('toast', message: Messages::get('password.reset.inactive'), type: 'error');
                return;
            }

            // 3. Generate and Store Secure Token
            $token = Str::random(60);
            cache()->put('password-reset-' . $user->id, Hash::make($token), 3600); // 1 hour expiry

            // 4. Send Custom Notification
            $this->sendNotification($user, $token);

            // 5. Audit Logging
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Password reset requested via Livewire Component');

            // 6. Set Cooldown for this IP
            cache()->put('rate-limit-reset-' . request()->ip(), true, 60);

            $this->requestSent = true;

        } catch (Exception $e) {
            Log::error("Password Reset Flow Failure: " . $e->getMessage());
            $this->dispatch('toast', message: 'An internal error occurred. Please try later.', type: 'error');
        }
    }

    /**
     * Dispatches the custom reset email via Helin Mail Service.
     * * @param User $user Recipient user
     * @param string $token Plain-text secure token
     * @return void
     */
    protected function sendNotification(User $user, string $token): void
    {
        try {
            CustomMail::passwordReset($user->email, $token, $user->name);
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
    private function isRateLimited(): bool
    {
        return cache()->has('rate-limit-reset-' . request()->ip());
    }

    /**
     * Reset the component state to allow a new attempt.
     * * @return void
     */
    public function resetForm(): void
    {
        $this->reset(['email', 'requestSent']);
    }
}