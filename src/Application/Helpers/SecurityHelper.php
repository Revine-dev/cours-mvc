<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class SecurityHelper
{
    private string $storageFile = ROOT . DS . 'logs' . DS . 'login_attempts.json';
    private int $maxAttempts = 5;
    private int $lockoutTime = 900; // 15 minutes

    /**
     * Get the path to the storage file.
     */
    public function getStorageFile(): string
    {
        return $this->storageFile;
    }

    /**
     * Check if the IP is allowed to attempt a login.
     */
    public function isLoginAllowed(string $ip): bool
    {
        $data = $this->loadData();
        if (!isset($data[$ip])) {
            return true;
        }

        $attempts = $data[$ip]['count'];
        $lastAttempt = $data[$ip]['last_attempt'];

        // If lockout time has passed, reset attempts
        if (time() - $lastAttempt > $this->lockoutTime) {
            unset($data[$ip]);
            $this->saveData($data);
            return true;
        }

        return $attempts < $this->maxAttempts;
    }

    /**
     * Register a failed login attempt for an IP.
     */
    public function registerFailedLogin(string $ip): void
    {
        $data = $this->loadData();
        if (!isset($data[$ip])) {
            $data[$ip] = ['count' => 0, 'last_attempt' => 0];
        }

        $data[$ip]['count']++;
        $data[$ip]['last_attempt'] = time();

        $this->saveData($data);
    }

    /**
     * Clear login attempts for an IP (after successful login).
     */
    public function clearLoginAttempts(string $ip): void
    {
        $data = $this->loadData();
        if (isset($data[$ip])) {
            unset($data[$ip]);
            $this->saveData($data);
        }
    }

    /**
     * Load data from JSON file with safe locking.
     */
    private function loadData(): array
    {
        if (!file_exists($this->storageFile)) {
            return [];
        }

        $content = file_get_contents($this->storageFile);
        if (!$content) {
            return [];
        }

        return json_decode($content, true) ?: [];
    }

    /**
     * Save data to JSON file with safe locking.
     */
    private function saveData(array $data): void
    {
        // Cleanup old entries to keep file small
        foreach ($data as $ip => $entry) {
            if (time() - $entry['last_attempt'] > $this->lockoutTime * 2) {
                unset($data[$ip]);
            }
        }

        file_put_contents($this->storageFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    }
}
