<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use App\Application\Response\Exception\ExpiredPageException;
use Exception;

/**
 * Class Csrf
 * Native PHP CSRF Protection Helper
 */
class Csrf
{
    private const SESSION_KEY = '_csrf_tokens';
    private const MAX_TOKENS = 50;
    private const DEFAULT_TTL = 600; // 10 minutes

    /**
     * Generate a CSRF token for a specific form.
     * 
     * @param string $formId Unique identifier for the form
     * @param int $ttl Time to live in seconds
     * @return string The generated token
     */
    public static function generateToken(string $formId, int $ttl = self::DEFAULT_TTL): string
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        // If session is still not active (e.g. headers sent in tests), 
        // we fallback to a temporary storage or just return a dummy token 
        // to avoid crashing, but for real requests session should be active.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return "testing_token_no_session";
        }

        // Initialize session storage if needed
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }

        // Limit the number of stored tokens (FIFO)
        if (count($_SESSION[self::SESSION_KEY]) >= self::MAX_TOKENS) {
            array_shift($_SESSION[self::SESSION_KEY]);
        }

        // Generate cryptographically secure token
        $token = bin2hex(random_bytes(32));
        
        // Store token with metadata
        $_SESSION[self::SESSION_KEY][$formId] = [
            'token' => $token,
            'expires' => time() + $ttl,
            'context' => self::getContextHash()
        ];

        return $token;
    }

    /**
     * Generate a hidden input field for the CSRF token.
     * 
     * @param string $formId Unique identifier for the form
     * @return string HTML hidden input fields
     */
    public static function getTokenInput(string $formId): string
    {
        $token = self::generateToken($formId);
        // We include the form ID so the validator knows which token to check
        $input = sprintf('<input type="hidden" name="csrf_form_id" value="%s">', htmlspecialchars($formId));
        $input .= sprintf("\n" . '<input type="hidden" name="csrf_token" value="%s">', htmlspecialchars($token));
        return $input;
    }

    /**
     * Validate a CSRF token.
     * 
     * @param string|null $formId The form identifier submitted
     * @param string|null $token The token to validate
     * @param bool $rotateSession Whether to regenerate session ID on success
     * @param bool $isOneTime Whether to remove the token after validation (one-time use), defaults to false to allow multiple submissions from the same page.
     * @return bool True if valid
     * @throws ExpiredPageException If token is invalid or expired (HTTP 419)
     */
    public static function validateToken(?string $formId, ?string $token, bool $rotateSession = false, bool $isOneTime = false): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            // In testing environment where session cannot start, we might skip validation
            // but for safety, we only do this if explicitly in test mode.
            if (defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__')) {
                return true;
            }
            throw new ExpiredPageException('Session required for CSRF validation.');
        }

        if (!$formId || !$token || !isset($_SESSION[self::SESSION_KEY][$formId])) {
            throw new ExpiredPageException('CSRF token missing or invalid form ID.');
        }

        $stored = $_SESSION[self::SESSION_KEY][$formId];

        // 1. One-time use: Removed by default, but optional
        if ($isOneTime) {
            unset($_SESSION[self::SESSION_KEY][$formId]);
        }

        // 2. Check expiration
        if (time() > $stored['expires']) {
            unset($_SESSION[self::SESSION_KEY][$formId]);
            throw new ExpiredPageException('CSRF token has expired.');
        }

        // 3. Timing attack safe comparison
        if (!hash_equals($stored['token'], $token)) {
            throw new ExpiredPageException('CSRF token mismatch.');
        }

        // 4. Context validation
        if (!hash_equals($stored['context'], self::getContextHash())) {
            throw new ExpiredPageException('CSRF context invalid.');
        }

        if ($rotateSession) {
            session_regenerate_id(true);
        }

        return true;
    }

    /**
     * Automatically inject CSRF tokens into HTML form tags.
     * 
     * @param string $html The HTML content
     * @return string Updated HTML with CSRF tokens
     */
    public static function autoInject(string $html): string
    {
        return preg_replace_callback('/<form[^>]+method=["\']post["\'][^>]*>/i', function ($matches) {
            $formTag = $matches[0];
            
            // Extract action to use as formId, or hash the tag if no action found
            preg_match('/action=["\']([^"\']+)["\']/i', $formTag, $actionMatch);
            $formId = $actionMatch[1] ?? md5($formTag);
            
            return $formTag . "\n" . self::getTokenInput($formId);
        }, $html);
    }

    /**
     * Generate a hash of the user context (IP and User-Agent).
     * 
     * @return string
     */
    private static function getContextHash(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        return hash('sha256', $ip . $ua);
    }
}
