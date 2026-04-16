<?php

declare(strict_types=1);

namespace Tests\Application\Helpers;

use App\Application\Helpers\SecurityHelper;
use Tests\TestCase;
use ReflectionClass;

class SecurityHelperTest extends TestCase
{
    private string $testFile;
    private string $backupFile;
    private SecurityHelper $securityHelper;

    protected function setUp(): void
    {
        $this->securityHelper = new SecurityHelper();
        $this->testFile = $this->securityHelper->getStorageFile();
        $this->backupFile = $this->testFile . '.bak';

        // Backup existing file if any
        if (file_exists($this->testFile)) {
            rename($this->testFile, $this->backupFile);
        }
    }

    protected function tearDown(): void
    {
        // Remove test file
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }

        // Restore backup
        if (file_exists($this->backupFile)) {
            rename($this->backupFile, $this->testFile);
        }
    }

    public function testIsLoginAllowedInitially()
    {
        $this->assertTrue($this->securityHelper->isLoginAllowed('127.0.0.1'));
    }

    public function testRegisterFailedLoginIncrementsCount()
    {
        $ip = '192.168.1.1';
        $this->securityHelper->registerFailedLogin($ip);
        $this->assertTrue($this->securityHelper->isLoginAllowed($ip));

        // Register 4 more (total 5)
        for ($i = 0; $i < 4; $i++) {
            $this->securityHelper->registerFailedLogin($ip);
        }

        $this->assertFalse($this->securityHelper->isLoginAllowed($ip));
    }

    public function testClearLoginAttempts()
    {
        $ip = '10.0.0.1';
        for ($i = 0; $i < 5; $i++) {
            $this->securityHelper->registerFailedLogin($ip);
        }
        $this->assertFalse($this->securityHelper->isLoginAllowed($ip));

        $this->securityHelper->clearLoginAttempts($ip);
        $this->assertTrue($this->securityHelper->isLoginAllowed($ip));
    }

    public function testLockoutExpires()
    {
        $ip = '172.16.0.1';
        for ($i = 0; $i < 5; $i++) {
            $this->securityHelper->registerFailedLogin($ip);
        }
        $this->assertFalse($this->securityHelper->isLoginAllowed($ip));

        // Use reflection to manipulate the time in the saved data
        $reflection = new ReflectionClass($this->securityHelper);
        $method = $reflection->getMethod('loadData');
        $method->setAccessible(true);
        $data = $method->invoke($this->securityHelper);

        // Set last_attempt to 20 minutes ago
        $data[$ip]['last_attempt'] = time() - 1200;

        $saveMethod = $reflection->getMethod('saveData');
        $saveMethod->setAccessible(true);
        $saveMethod->invoke($this->securityHelper, $data);

        // Now it should be allowed again
        $this->assertTrue($this->securityHelper->isLoginAllowed($ip));
    }
}
