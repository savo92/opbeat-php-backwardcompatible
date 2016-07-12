<?php

    /**
     * Class TraceGenerator
     * Return a trace that could be sent to Opbeat
     * @TODO complete implementation to be more conformed to Algolia API
     */
    class TraceGenerator {

        /**
         * Use the php function debug_backtrace
         * @return array
         */
        public static function getTrace() {
            $trace = debug_backtrace();
            $cleanedTrace = array();
            foreach ($trace as $frame) {
                if ($frame['line']===null) continue; //@TODO improve control
                array_push($cleanedTrace, array(
                    'filename' => $frame['file'],
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
        }

    }