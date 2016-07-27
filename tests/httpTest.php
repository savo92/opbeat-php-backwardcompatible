<?php
    require_once dirname(dirname(__FILE__)).'/Opbeat/http.php';

    class HttpTest extends PHPUnit_Framework_TestCase {

        /**
         * @covers Opbeat_Http::generateHttp
         */
        public function testGenerateHttp () {
            $this->assertNull(Opbeat_Http::generateHttp());
            // @TODO more tests
        }

    }