<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Utils\Messages;
use Illuminate\Support\Facades\Auth;

/**
 * Authenticate Middleware
 *
 * This middleware extends the core Laravel Authenticate middleware to add
 * custom session validation, API token checks, and detailed security logging
 * for the Helin Latam CMS.
 *
 * @package App\Http\Middleware
 * @author  Helin Latam Development Team
 * @version 1.1.0
 */
class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * Overrides the default handle method to inject custom session expiration
     * and multi-guard authentication logic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // 1. Validate custom inactivity session timeout
        if (Auth::check() && !$this->isSessionValid($request)) {
            return $this->handleSessionTimeout($request);
        }

        // 2. Pre-authentication logic for API endpoints
        if ($request->is('api/*')) {
            $apiCheck = $this->handleApiAuth($request);
            if ($apiCheck !== true) {
                return $apiCheck;
            }
        }

        // 3. Standard Laravel authentication execution
        $this->authenticate($request, $guards);

        // 4. Post-authentication logic for administrative routes
        if (in_array('admin', $guards)) {
            $adminCheck = $this->handleAdminAuth($request);
            if ($adminCheck !== true) {
                return $adminCheck;
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson() || $request->is('api/*')
            ? null
            : route('login');
    }

    /**
     * Handle unauthenticated requests.
     *
     * Customizes the response for unauthenticated users, providing JSON for APIs
     * and detailed redirect messages for web interfaces.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        $this->logUnauthenticatedAttempt($request, $guards);

        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json([
                'success' => false,
                'message' => Messages::get('auth.unauthenticated'),
                'code' => 401,
                'data' => [
                    'redirect' => route('login'),
                    'requires_auth' => true
                ]
            ], 401));
        }

        $message = $this->getRedirectMessage($request, $guards);

        abort(redirect()->route('login')
            ->with('warning', $message)
            ->with('intended', $request->fullUrl()));
    }

    /**
     * Log unauthenticated access attempts for security auditing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     */
    protected function logUnauthenticatedAttempt(Request $request, array $guards): void
    {
        $data = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route_name' => Route::currentRouteName(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'guards' => json_encode($guards),
            'created_at' => now(),
        ];

        Log::warning('Unauthenticated access attempt detected', $data);

        try {
            DB::table('security_attempts')->insert(array_merge($data, [
                'type' => 'unauthenticated_access'
            ]));
        } catch (Exception $e) {
            Log::error('Security logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle authentication logic specifically for API requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function handleApiAuth(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => Messages::get('auth.api_token_required'),
                'code' => 401,
            ], 401);
        }

        if (!$this->isValidApiToken($token)) {
            return response()->json([
                'success' => false,
                'message' => Messages::get('auth.api_token_invalid'),
                'code' => 401,
            ], 401);
        }

        return true;
    }

    /**
     * Handle authentication and authorization for admin-protected routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function handleAdminAuth(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Check for admin role and active status
        if (!$user || !$user->hasRole('admin')) {
            Auth::logout();
            session()->invalidate();
            return redirect()->route('login')->with('error', Messages::get('auth.admin_required'));
        }

        if (!$user->is_active) {
            Auth::logout();
            session()->invalidate();
            return redirect()->route('login')->with('error', Messages::get('auth.inactive'));
        }

        return true;
    }

    /**
     * Verify if the user's session is still within the valid time frame.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isSessionValid(Request $request): bool
    {
        $loginTime = session()->get('login_time');
        if (!$loginTime) {
            return true;
        }

        $sessionTimeout = config('session.lifetime', 120);

        return now()->diffInMinutes($loginTime) <= $sessionTimeout;
    }

    /**
     * Clear session data and redirect user upon session timeout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleSessionTimeout(Request $request)
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flash('auth.timeout', true);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => Messages::get('auth.session_expired'),
                'code' => 401
            ], 401);
        }

        return redirect()->route('login')->with('warning', Messages::get('auth.session_expired'));
    }

    /**
     * Validate the structure and requirements of the API token.
     *
     * @param  string  $token
     * @return bool
     */
    protected function isValidApiToken(string $token): bool
    {
        return strlen($token) >= 32 && preg_match('/^[a-zA-Z0-9._-]+$/', $token) === 1;
    }

    /**
     * Determine the appropriate redirect message based on the failure context.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return string
     */
    protected function getRedirectMessage(Request $request, array $guards): string
    {
        if (session()->has('auth.timeout')) {
            return Messages::get('auth.session_expired');
        }

        if (in_array('admin', $guards)) {
            return Messages::get('auth.admin_required');
        }

        return Messages::get('auth.required');
    }
}
