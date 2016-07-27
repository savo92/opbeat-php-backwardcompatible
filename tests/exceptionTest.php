<?php
    require_once dirname(dirname(__FILE__)).'/Opbeat/exception.php';

    class ExceptionTest extends PHPUnit_Framework_TestCase {

        /**
         * @covers Opbeat_Exception::getException
         */
        public function testGetException (){
            $e = new Exception('asd');
            $this->assertEquals(Opbeat_Exception::getException($e), array('type'=>'Exception', 'value'=>'asd'));
        }

    }