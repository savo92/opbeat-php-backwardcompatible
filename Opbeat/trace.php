<?php

    require_once dirname(__FILE__).'/utils.php';

    /**
     * Class TraceGenerator
     * Return a trace that could be sent to Opbeat
     * @TODO complete implementation to be more conformed to Opbeat API
     */
    class Opbeat_TraceGenerator {

        /**
         * Use the php function debug_backtrace
         * @return array
         */
        public static function getTrace ($trace) {
            $cleanedTrace = array();
            foreach ($trace as $frame) {
                if ($frame['line']===null) continue; //@TODO improve control
                array_push($cleanedTrace, array(
                    'abs_path' => $frame['file'],
                    'filename' => Opbeat_Utils::getFilename($frame['file']),
                    'lineno' => $frame['line'],
                    'function' => $frame['function']
                ));
            }
            return $cleanedTrace;
        }

        /**
         * @param $e \Exception
         * @return array
         */
        public static function getTraceByException ($e) {
            $cleanedTrace = array();
            foreach ($e->getTrace () as $frame) {
                array_push($cleanedTrace, array(
                    'abs_path' => $frame['file'],
                    'filename' => Opbeat_Utils::getFilename($frame['file']),
                    'lineno' => $frame['line'],
                    'function' => $frame['function']
                ));
            }
            return $cleanedTrace;
        }

    }