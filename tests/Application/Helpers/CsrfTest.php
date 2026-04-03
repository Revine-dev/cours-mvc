<?php

declare(strict_types=1);

namespace Tests\Application\Helpers;

use App\Application\Helpers\Csrf;
use App\Application\Response\Exception\ExpiredPageException;
use Tests\TestCase;

class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
    }

    /**
     * @runInSeparateProcess
     */
    public function testGenerateToken()
    {
        $formId = 'test_form';
        $token = Csrf::generateToken($formId);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        $this->assertArrayHasKey('_csrf_tokens', $_SESSION);
        $this->assertArrayHasKey($formId, $_SESSION['_csrf_tokens']);
        $this->assertEquals($token, $_SESSION['_csrf_tokens'][$formId]['token']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetTokenInput()
    {
        $formId = 'test_form';
        $input = Csrf::getTokenInput($formId);

        $this->assertStringContainsString('type="hidden"', $input);
        $this->assertStringContainsString('name="csrf_form_id"', $input);
        $this->assertStringContainsString('name="csrf_token"', $input);
        $this->assertStringContainsString('value="' . $formId . '"', $input);
    }

    /**
     * @runInSeparateProcess
     */
    public function testValidateTokenSuccess()
    {
        $formId = 'test_form';
        $token = Csrf::generateToken($formId);

        $result = Csrf::validateToken($formId, $token, false);
        $this->assertTrue($result);
        
        // Token should STILL BE there after validation (not one-time use by default)
        $this->assertArrayHasKey($formId, $_SESSION['_csrf_tokens']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testValidateTokenOneTimeSuccess()
    {
        $formId = 'test_form';
        $token = Csrf::generateToken($formId);

        $result = Csrf::validateToken($formId, $token, false, true);
        $this->assertTrue($result);
        
        // Token should be removed after validation if explicitly asked
        $this->assertArrayNotHasKey($formId, $_SESSION['_csrf_tokens']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testValidateTokenFailure()
    {
        $formId = 'test_form';
        Csrf::generateToken($formId);

        $this->expectException(ExpiredPageException::class);
        $this->expectExceptionMessage('CSRF token mismatch');
        
        Csrf::validateToken($formId, 'wrong_token');
    }

    /**
     * @runInSeparateProcess
     */
    public function testValidateTokenExpired()
    {
        $formId = 'test_form';
        Csrf::generateToken($formId, -10); // Expired 10 seconds ago

        $this->expectException(ExpiredPageException::class);
        $this->expectExceptionMessage('CSRF token has expired');
        
        Csrf::validateToken($formId, $_SESSION['_csrf_tokens'][$formId]['token']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutoInject()
    {
        $html = '<html><body><form method="POST" action="/submit"><input type="text"></form></body></html>';
        $injectedHtml = Csrf::autoInject($html);

        $this->assertStringContainsString('name="csrf_form_id"', $injectedHtml);
        $this->assertStringContainsString('name="csrf_token"', $injectedHtml);
        $this->assertStringContainsString('value="/submit"', $injectedHtml);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutoInjectMultipleForms()
    {
        $html = '
            <form method="POST" action="/form1"></form>
            <form method="POST" action="/form2"></form>
        ';
        $injectedHtml = Csrf::autoInject($html);

        $this->assertStringContainsString('value="/form1"', $injectedHtml);
        $this->assertStringContainsString('value="/form2"', $injectedHtml);
        
        // Count occurrences of csrf_token
        $count = substr_count($injectedHtml, 'name="csrf_token"');
        $this->assertEquals(2, $count);
    }
}
