<?php

declare(strict_types=1);

namespace Tests\Application\Actions;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use Tests\DatabaseTestCase;

class LoginTest extends DatabaseTestCase
{
    public function testLoginSuccess()
    {
        $em = $this->getEntityManager();
        $password = 'password123';
        $user = new User([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => UserRole::ADMIN
        ]);
        $em->persist($user);
        $em->flush();

        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/back-office')
            ->withParsedBody([
                'email' => 'admin@example.com',
                'password' => $password
            ]);

        $response = $app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/admin', $response->getHeaderLine('Location'));

        $this->assertEquals($user->id, $_SESSION['user_id']);
        $this->assertEquals(UserRole::ADMIN->value, $_SESSION['user_role']);
    }

    public function testLoginFailure()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/back-office')
            ->withParsedBody([
                'email' => 'wrong@example.com',
                'password' => 'wrong'
            ]);

        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Identifiants invalides', (string)$response->getBody());
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }
}
