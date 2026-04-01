<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            [1, 'Bill Gates', 'bill.gates@microsoft.com', 'Admin'],
            [2, 'Steve Jobs', 'steve.jobs@apple.com', 'User'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $name
     * @param string $email
     * @param string $role
     */
    public function testProperties(int $id, string $name, string $email, string $role)
    {
        $user = new User([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);

        $this->assertEquals($id, $user->id);
        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($role, $user->role);
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $name
     * @param string $email
     * @param string $role
     */
    public function testJsonSerialize(int $id, string $name, string $email, string $role)
    {
        $user = new User([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);

        $expectedPayload = json_encode([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }

    public function testIsAdmin()
    {
        $admin = new User(['role' => 'Admin']);
        $user = new User(['role' => 'User']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
}
