<?php
    require_once dirname(dirname(__FILE__)).'/Opbeat/trace.php';

    class TraceTest extends PHPUnit_Framework_TestCase {

        /**
         * @covers Opbeat_TraceGenerator::getTrace
         */
        public function testGetTrace () {
            // Without args
            $sourceTrace = array(array(
                'file' => 'file',
                'line' => 123,
                'function' => 'func',
                'vars' => array()
            ));
            $destinationTrace = array(array(
                'abs_path' => 'file',
                'filename' => 'file',
                'lineno' => 123,
                'function' => 'func'
            ));
            $this->assertEquals(Opbeat_TraceGenerator::getTrace($sourceTrace), $destinationTrace);

            // With args
            $sourceTrace = array(array(
                'file' => 'file',
                'line' => 123,
                'function' => 'func',
                'vars' => array(
                    'asd' => 'asdddd',
                    'pippo' => 'pippo'
                )
            ));
            $destinationTrace = array(array(
                'abs_path' => 'file',
                'filename' => 'file',
                'lineno' => 123,
                'function' => 'func',
                'args' => (object) array(
                    'asd' => 'asdddd',
                    'pippo' => 'pippo'
                )
            ));
            $this->assertEquals(Opbeat_TraceGenerator::getTrace($sourceTrace), $destinationTrace);
        }

        public function testGetTraceByException () {
            // @TODO
        }

    }