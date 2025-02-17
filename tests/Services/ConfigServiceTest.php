<?php

namespace Fiserv\Tests\Services;

use Fiserv\Services\ConfigService;
use PHPUnit\Framework\TestCase;

class ConfigServiceTest extends TestCase
{
    public function testGetReturnsNullForMissingFile()
    {
        $this->assertNull(ConfigService::get('nonexistent.section'));
    }

    public function testGetThrowsExceptionForInvalidKeyFormat()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Config key must be in format of "section.key"');

        ConfigService::get('invalidkey');
    }

    public function testGetReturnsValueForExistingConfig()
    {
        $configDir = __DIR__ . '/../../config/';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0777, true);
        }

        $configFile = $configDir . 'testing.php';
        file_put_contents($configFile, '<?php return ["foo" => "bar"];');

        $this->assertEquals('bar', ConfigService::get('testing.foo'));

        unlink($configFile);
    }

    public function testGetThrowsExceptionForMissingKeyInConfigFile()
    {
        $configDir = __DIR__ . '/../../config/';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0777, true);
        }

        $configFile = $configDir . 'testing.php';
        file_put_contents($configFile, '<?php return ["foo" => "bar"];');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Config key not found');

        ConfigService::get('testing.bar');

        unlink($configFile);
    }
}
