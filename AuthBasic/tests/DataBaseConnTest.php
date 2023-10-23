<?php

require_once("app/libs/DataBaseConn.php");

use PHPUnit\Framework\TestCase;

class DataBaseConnTest extends TestCase {
    private $instance;

    public function setUp(): void {
        $this->instance = new DataBaseConn("localhost", "root", "", "authentication");
        $this->instance->connect();
    }

    public function tearDown(): void {
        $this->instance->disconnect();
        unset($this->instance);
    }

    public function testDatabase() {
        $id = rand(1, 10);
        $rand = rand(1, 20000);

        $tbl = "testdb";
        $col = array('id', 'rand');
        $val = array($id, $rand);

        $this->instance->put($tbl, $col, $val);

        $exp = array('id' => $id, 'rand' => $rand);
        $out = $this->instance->get($tbl, array(), array('where' => "id = $id AND rand = $rand"));

        $this->assertEquals($exp, $out[0]);
    }
}