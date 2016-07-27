<?php
    require_once dirname(dirname(__FILE__)).'/Opbeat/utils.php';

    class UtilsTest extends PHPUnit_Framework_TestCase {

        /**
         * @covers Opbeat_Utils::getFileName
         */
        public function testGetFilename () {
            define ('OPBEATOPT_PROJECT_ABS_PATH', '/path/to/script');
            $this->assertEquals(Opbeat_Utils::getFilename('/path/to/script/asdasd'), 'asdasd');
        }

        /**
         * @expectedException Exception
         */
        public function testCheckConstantFailure () {
            Opbeat_Utils::checkSystem();
        }

        /**
         * @covers Opbeat_Utils::checkSystem
         */
        public function testCheckConstantSuccess () {
            define ('OPBEATOPT_ORGANIZATION_ID', 'fake');
            define ('OPBEATOPT_APP_ID', 'fake');
            define ('OPBEATOPT_SECRET_TOKEN', 'fake');
            $this->assertTrue(Opbeat_Utils::checkSystem());
        }

    }