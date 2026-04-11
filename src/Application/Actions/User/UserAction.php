<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Application\Response\Response;
use App\Application\Helpers\Helper;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Entity\User\UserRepository;

class UserAction extends Action
{
    private UserRepository $usersRepository;

    public function __construct(LoggerInterface $logger, Helper $helper, UserRepository $usersRepository)
    {
        parent::__construct($logger, $helper);
        $this->usersRepository = $usersRepository;
    }

    private function isExistingUser(string $email, string $password): ?\App\Entity\User\User
    {
        $user = $this->usersRepository->findOneBy('email', $email);
        return ($user && password_verify($password, $user->password)) ? $user : null;
    }

    private function createSession(\App\Entity\User\User $user): void
    {
        // Regenerate session ID to prevent session fixation attacks
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        // Securely store user data
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role;

        // Metadata for security validation
        $_SESSION['login_time'] = time();
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $_SESSION['last_activity'] = time();
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        return $this->redirect("home");
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $error = "";
        if ($this->request->getMethod() === 'POST') {
            $data = $this->getFormData();
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            $user = $this->isExistingUser($email, $password);
            if ($user) {
                $this->createSession($user);
                return $this->redirect("admin");
            }
            $error = 'Identifiants invalides ou compte restreint.';
        }
        return $this->render("admin/login", ["error" => $error]);
    }
}
