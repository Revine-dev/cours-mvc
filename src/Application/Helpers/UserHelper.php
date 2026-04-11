<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use App\Application\Response\Exception\HelperException;
use App\Entity\User\User;
use App\Entity\User\UserRepository;

class UserHelper
{
    private UserRepository $userRepository;
    private ?User $cachedUser = null;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get the current authenticated user.
     */
    public function auth(string $property = ""): User|string|null
    {
        if ($this->cachedUser === null) {
            if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
                session_start();
            }

            if (session_status() !== PHP_SESSION_ACTIVE) {
                return null;
            }

            $userId = $_SESSION['user_id'] ?? null;
            if ($userId === null) {
                return null;
            }

            try {
                $this->cachedUser = $this->userRepository->findUserOfId((int) $userId);
            } catch (\Exception $e) {
                return null;
            }
        }

        if ($property) {
            if ($property === "initials") {
                return strtoupper(
                    implode('', array_map(
                        fn($w) => $w[0] ?? '',
                        array_filter(explode(' ', trim($this->cachedUser->name)))
                    ))
                );
            }
            if (!\property_exists($this->cachedUser, $property)) {
                throw new HelperException("Property '{$property}' not found on User object.");
            }
            return $this->cachedUser->$property;
        }

        return $this->cachedUser;
    }

    /**
     * Check if user is authenticated.
     */
    public function isAuth(): bool
    {
        return $this->auth() !== null;
    }

    /**
     * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function is_auth(): bool
    {
        return $this->isAuth();
    }
    // phpcs:enable
}
