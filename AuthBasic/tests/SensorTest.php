<?php declare(strict_types = 1);

require_once("app/libs/Sensor.php");

use PHPUnit\Framework\TestCase;

class SensorTest extends TestCase {
    private $instance;

    public function setUp(): void {
        $this->instance = new Sensor();
    }

    public function tearDown(): void {
        unset($this->instance);
    }

    public function testIsLocal() {
        $out = $this->instance->isLocal();
        $this->assertFalse($out);
    }

    public function testAddrIp() {
        $out = $this->instance->addrIp();
        $this->assertNull($out);
    }

    public function testBrowser() {
        $out = $this->instance->browser();
        $this->assertEquals("", $out);
    }

    public function testSystem() {
        $out = $this->instance->system();
        $this->assertEquals("", $out);
    }

    public function testGenFingerprint() {
        $out = $this->instance->genFingerprint();
        $this->assertEquals("b936cee86c9f87aa5d3c6f2e84cb5a4239a5fe50480a6ec66b70ab5b1f4ac6730c6c515421b327ec1d69402e53dfb49ad7381eb067b338fd7b0cb22247225d47", bin2hex($out));
    }
}