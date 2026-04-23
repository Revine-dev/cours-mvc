<?php

declare(strict_types=1);

namespace Tests\Application\Actions;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Entity\Property\Property;
use App\Entity\Agent\Agent;
use Tests\DatabaseTestCase;

class AdminTest extends DatabaseTestCase
{
    private function loginAsAdmin(): User
    {
        $em = $this->getEntityManager();
        $user = new User([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'role' => UserRole::ADMIN
        ]);
        $em->persist($user);
        $em->flush();

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = UserRole::ADMIN->value;
        $_SESSION['last_activity'] = time();
        $_SESSION['user_ip'] = '127.0.0.1';
        $_SESSION['user_agent'] = 'PHPUnit';

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        return $user;
    }

    public function testDashboardAccess()
    {
        $this->loginAsAdmin();
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/admin')
            ->withAttribute('session', $_SESSION);
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Dashboard', (string)$response->getBody());
    }

    public function testAdsListAccess()
    {
        $this->loginAsAdmin();
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/admin/ads')
            ->withAttribute('session', $_SESSION);
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Annonces', (string)$response->getBody());
    }

    public function testStoreAd()
    {
        $this->loginAsAdmin();
        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/admin/ads/create')
            ->withAttribute('session', $_SESSION)
            ->withParsedBody([
                'title' => 'New Test Property',
                'description' => 'A beautiful test property',
                'price' => '250000',
                'type' => 'house',
                'status' => 'for_sale',
                'city' => 'Paris',
                'address' => '123 Rue de Test',
                'postal_code' => '75001'
            ]);

        $response = $app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());

        // Verify it was saved
        $em = $this->getEntityManager();
        $property = $em->getRepository(Property::class)->findOneBy(['title' => 'New Test Property']);
        $this->assertNotNull($property);
        $this->assertEquals('Paris', $property->location->city);
    }

    public function testUpdateAd()
    {
        $this->loginAsAdmin();
        $em = $this->getEntityManager();

        $property = new Property();
        $property->title = 'Old Title';
        $property->slug = 'old-title';
        $property->price = '100000';

        // Location and Agent are auto-initialized in constructor
        // We MUST set ID for Agent as it is not generated
        $property->location->city = 'Paris';
        $property->agent->id = 1;
        $property->agent->name = 'Test Agent';
        $property->agent->email = 'test@agent.com';

        $em->persist($property);
        $em->flush();

        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/admin/ads/edit/' . $property->id)
            ->withAttribute('session', $_SESSION)
            ->withParsedBody([
                'title' => 'Updated Title',
                'description' => 'Updated description',
                'price' => '150000',
                'type' => 'apartment',
                'status' => 'sold'
            ]);

        $response = $app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());

        $em->refresh($property);
        $this->assertEquals('Updated Title', $property->title);
        $this->assertEquals(150000, $property->price);
    }

    public function testPreviewAd()
    {
        $this->loginAsAdmin();
        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/admin/ads/preview')
            ->withAttribute('session', $_SESSION)
            ->withParsedBody([
                'title' => 'Preview Title',
                'price' => '999000',
                'city' => 'Lyon'
            ]);

        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Preview Title', (string)$response->getBody());
    }

    public function testAgentsListAccess()
    {
        $this->loginAsAdmin();
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/admin/agents')
            ->withAttribute('session', $_SESSION);
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Agents', (string)$response->getBody());
    }

    public function testStoreAgent()
    {
        $this->loginAsAdmin();
        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/admin/agents/create')
            ->withAttribute('session', $_SESSION)
            ->withParsedBody([
                'id' => 10,
                'name' => 'Agent Smith',
                'email' => 'smith@agency.com',
                'phone' => '0123456789'
            ]);

        $response = $app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());

        $em = $this->getEntityManager();
        $agent = $em->getRepository(Agent::class)->findOneBy(['email' => 'smith@agency.com']);
        $this->assertNotNull($agent);
        $this->assertEquals('Agent Smith', $agent->name);
    }

    public function testDeleteAgent()
    {
        $this->loginAsAdmin();
        $em = $this->getEntityManager();

        $agent = new Agent();
        $agent->id = 999;
        $agent->name = 'To Delete';
        $agent->email = 'delete@test.com';
        $em->persist($agent);
        $em->flush();

        $app = $this->getAppInstance();

        $request = $this->createRequest('POST', '/admin/agents/delete/' . $agent->id)
            ->withAttribute('session', $_SESSION);

        $response = $app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());

        $deletedAgent = $em->getRepository(Agent::class)->find(999);
        $this->assertNull($deletedAgent);
    }
}
