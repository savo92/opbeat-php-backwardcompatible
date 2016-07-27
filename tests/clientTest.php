<?php
    require_once dirname(dirname(__FILE__)).'/Opbeat/client.php';

    class ClientTest extends PHPUnit_Framework_TestCase {

        public function testGetErrorLevel() {
            $this->assertEquals(Opbeat_Client::getErrorLevel(E_WARNING), 'warning');
            $this->assertEquals(Opbeat_Client::getErrorLevel(E_ERROR), 'fatal');
            $this->assertEquals(Opbeat_Client::getErrorLevel(E_COMPILE_ERROR), 'fatal');
            $this->assertEquals(Opbeat_Client::getErrorLevel(E_NOTICE), 'notice');
        }

    }