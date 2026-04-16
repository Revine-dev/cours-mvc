<?php

declare(strict_types=1);

namespace Tests\Entity\User;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            [1, 'Bill Gates', 'bill.gates@microsoft.com', UserRole::ADMIN],
            [2, 'Steve Jobs', 'steve.jobs@apple.com', UserRole::USER],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $name
     * @param string $email
     * @param UserRole $role
     */
    public function testProperties(int $id, string $name, string $email, UserRole $role)
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
     * @param UserRole $role
     */
    public function testToData(int $id, string $name, string $email, UserRole $role)
    {
        $user = new User([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);

        $expectedData = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role->value,
        ];

        $this->assertEquals($expectedData, $user->toData());
    }

    public function testIsAdmin()
    {
        $admin = new User(['role' => UserRole::ADMIN]);
        $user = new User(['role' => UserRole::USER]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
}
