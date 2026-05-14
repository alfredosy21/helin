<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\User;
use App\Models\Activities;
use App\Utils\Messages;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Class AuthenticatedSessionController
 *
 * Orchestrates the complete authentication lifecycle, including session security,
 * screen locking, and activity auditing for the Helin administrative gateway.
 *
 * @package App\Http\Controllers\Cms
 * @version 1.1.0
 */
#[Title('Login | Helin CMS')]
#[Layout('cms.layouts.auth')]
class AuthenticatedSessionController extends Component
{
    /** @var string|null User email input */
    public ?string $email = '';

    /** @var string|null User password input */
    public ?string $password = '';

    /** @var bool Remember session toggle */
    public bool $remember = false;

    /** @var bool Component state for the Lock Screen */
    public bool $isLocked = false;

    /** @var string|null Encrypted credentials data */
    public ?string $encrypted_data = null;

    /** @var int|null Login timestamp for security */
    public ?int $login_timestamp = null;

    /**
     * Component Validation Rules.
     *
     * @return array<string, string>
     */
    protected array $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:8',
    ];

    /**
     * Initialize the component state, checking for locked sessions.
     *
     * @return void
     */
    public function mount(): void
    {
        if (session()->has('locked_user_id')) {
            $this->isLocked = true;
            $user = User::query()->find(session('locked_user_id'));
            $this->email = $user ? $user->email : '';
        }

        if (Auth::check() && !$this->isLocked) {
            $this->redirectIntended('/cms/dashboard', navigate: true);
        }
    }

    /**
     * Render the authentication or lock screen interface.
     *
     * @return View
     */
    public function render(): View
    {
        return view('cms.auth.login');
    }

    /**
     * Authenticate the user and initialize security telemetry.
     *
     * @return void
     */
    public function login(): void
    {
        $this->validate();

        try {
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                session()->regenerate();

                // Si remember está activado, configurar la cookie para que dure más tiempo
                if ($this->remember) {
                    // La cookie de sesión se extenderá automáticamente por Laravel
                    // cuando $remember es true en Auth::attempt()
                    Log::info('User session marked as remember me', ['email' => $this->email]);
                }

                /** @var User $user */
                $user = Auth::user();

                // Persist telemetry
                $user->update([
                    'last_login_at' => now(),
                    'last_login_ip' => request()->ip()
                ]);

                // Initialize professional workspace metadata
                Session::put([
                    'login_time' => now(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                Activities::saveActivity("User logged into the system: {$user->email}");

                $this->redirectIntended('/cms/dashboard');
                return;
            }

            $this->dispatch('toast', message: Messages::LABEL_ERROR_LOGIN, type: 'error');

        } catch (Exception $e) {
            Log::error("Authentication failure: " . $e->getMessage());
            $this->dispatch('toast', message: 'System error during authentication.', type: 'error');
        }
    }

    /**
     * Restore a locked session via credential verification.
     *
     * @return void
     */
    public function unlock(): void
    {
        $this->validate(['password' => 'required']);

        try {
            $lockedUserId = session('locked_user_id');

            if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'id' => $lockedUserId])) {
                session()->forget('locked_user_id');
                session()->regenerate();

                Activities::saveActivity("Session unlocked successfully for: {$this->email}");

                $this->redirectIntended('/cms/dashboard');
                return;
            }

            $this->addError('password', Messages::LABEL_ERROR_LOGIN);

        } catch (Exception $e) {
            Log::error("Unlock attempt failed: " . $e->getMessage());
            $this->redirectRoute('login');
        }
    }

    /**
     * Transition the current session into a locked state.
     *
     * @return void
     */
    public function lock(): void
    {
        if (Auth::check()) {
            session(['locked_user_id' => Auth::id()]);
            Auth::guard('web')->logout();
            $this->isLocked = true;
            $this->password = '';
        }
    }

    /**
     * Terminate the session and clear security tokens.
     *
     * @return void
     */
    public function logout(): void
    {
        $userEmail = Auth::user() ? Auth::user()->email : 'Unknown';

        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flush();

        Activities::saveActivity("User logged out: {$userEmail}");

        // Verificar si estamos en contexto Livewire o ruta normal
        if (request()->expectsJson() || request()->header('X-Livewire')) {
            // Contexto Livewire
            $this->redirectRoute('login');
        } else {
            // Contexto de ruta normal - hacer redirect tradicional
            redirect()->route('login')->send();
            exit;
        }
    }
}
