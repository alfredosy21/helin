<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Custom Mail Service
 *
 * Centralized email handling service for the Helin Latam CMS application.
 * Provides methods for sending transactional emails and system notifications.
 *
 * @package App\Services
 * @author  Helin Latam Development Team
 * @version 1.1.0
 */
class CustomMail
{
    /**
     * Send password reset email with secure token link.
     *
     * @param string $email Recipient email address
     * @param string $token Secure password reset token
     * @param string|null $name Recipient name (defaults to 'Usuario')
     * @return bool Success status of the operation
     */
    public static function passwordReset(string $email, string $token, ?string $name = null): bool
    {
        try {
            $data = [
                'name'       => $name ?? 'Usuario',
                'resetLink'  => route('password.reset', ['token' => $token, 'email' => $email]),
                'expiration' => config('auth.passwords.users.expire', 60),
                'company'    => config('app.name')
            ];

            Mail::send('emails.password-reset', $data, function ($message) use ($email) {
                $message->to($email)
                    ->subject('Reset Password - ' . config('app.name'));
            });

            return true;
        } catch (Exception $ex) {
            Log::error("Failed to send password reset to {$email}: " . $ex->getMessage());
            return false;
        }
    }

    /**
     * Send welcome email to new users with login credentials.
     *
     * @param array{name: string, email: string} $user User basic information
     * @param string|null $temporaryPassword Generated password for first access
     * @return bool Success status
     */
    public static function welcome(array $user, ?string $temporaryPassword = null): bool
    {
        try {
            $data = [
                'name'      => $user['name'],
                'email'     => $user['email'],
                'password'  => $temporaryPassword,
                'loginLink' => route('login'),
                'company'   => config('app.name')
            ];

            Mail::send('emails.welcome', $data, function ($message) use ($user) {
                $message->to($user['email'])
                    ->subject('Welcome to ' . config('app.name') . ' CMS');
            });

            return true;
        } catch (Exception $ex) {
            Log::error("Failed to send welcome email to {$user['email']}: " . $ex->getMessage());
            return false;
        }
    }

    /**
     * Send generic system notification for alerts or updates.
     *
     * @param string $email Recipient email address
     * @param string $subject Email subject line
     * @param string $content Main message body
     * @return bool Success status
     */
    public static function systemNotification(string $email, string $subject, string $content): bool
    {
        try {
            Mail::send('emails.system-notification', ['content' => $content], function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });

            return true;
        } catch (Exception $ex) {
            Log::error("Failed to send system notification to {$email}: " . $ex->getMessage());
            return false;
        }
    }

    /**
     * Validate current mail configuration and connectivity.
     *
     * @return array{status: bool, driver: string, host: string, timestamp: string}
     */
    public static function checkHealth(): array
    {
        $transport = config('mail.default');
        $host = config("mail.mailers.{$transport}.host", 'N/A');

        return [
            'status'    => !empty($host),
            'driver'    => $transport,
            'host'      => $host,
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
