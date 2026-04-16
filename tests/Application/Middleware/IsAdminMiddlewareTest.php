<?php

declare(strict_types=1);

namespace Tests\Application\Middleware;

use App\Application\Middleware\IsAdminMiddleware;
use App\Application\Response\Exception\ExpiredPageException;
use App\Application\Response\Exception\UnauthorizedException;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Entity\User\UserRepository;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Tests\TestCase;
use Prophecy\Argument;

class IsAdminMiddlewareTest extends TestCase
{
    private $userRepository;
    private $handler;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->handler = $this->prophesize(RequestHandlerInterface::class);
    }

    public function testProcessAllowedForActiveAdmin()
    {
        $admin = new User(['id' => 1, 'role' => UserRole::ADMIN]);
        $this->userRepository->findOneBy('id', 1)->willReturn($admin);

        $_SESSION = [
            'user_id' => 1,
            'last_activity' => time(),
            'user_ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit'
        ];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        $middleware = new IsAdminMiddleware($this->userRepository->reveal());
        $request = $this->createRequest('GET', '/admin');

        $this->handler->handle(Argument::any())->willReturn($this->createRequest('GET', '/')->getAttribute('response') ?? (new \Slim\Psr7\Response()));

        $middleware->process($request, $this->handler->reveal());

        $this->assertGreaterThanOrEqual(time() - 1, $_SESSION['last_activity']);
    }

    public function testProcessThrowsExpiredExceptionAfterTimeout()
    {
        $this->expectException(ExpiredPageException::class);

        $_SESSION = [
            'user_id' => 1,
            'last_activity' => time() - 1000, // > 900 seconds
            'user_ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit'
        ];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        $middleware = new IsAdminMiddleware($this->userRepository->reveal());
        $request = $this->createRequest('GET', '/admin');

        $middleware->process($request, $this->handler->reveal());
    }

    public function testProcessThrowsUnauthorizedIfNoSession()
    {
        $this->expectException(HttpUnauthorizedException::class);

        $_SESSION = [];
        $middleware = new IsAdminMiddleware($this->userRepository->reveal());
        $request = $this->createRequest('GET', '/admin');

        $middleware->process($request, $this->handler->reveal());
    }

    public function testProcessThrowsUnauthorizedForNonAdmin()
    {
        $this->expectException(UnauthorizedException::class);

        $user = new User(['id' => 2, 'role' => UserRole::USER]);
        $this->userRepository->findOneBy('id', 2)->willReturn($user);

        $_SESSION = [
            'user_id' => 2,
            'last_activity' => time(),
            'user_ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit'
        ];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

        $middleware = new IsAdminMiddleware($this->userRepository->reveal());
        $request = $this->createRequest('GET', '/admin');

        $middleware->process($request, $this->handler->reveal());
    }
}
