<?php
    require_once dirname(__FILE__) . '/utils.php';

    /**
     * Class OpbeatInitializer
     * Check dependencies and configuration. Then registers the error handler and register shutdown hooks
     */
    class OpbeatInitializer {




        public static function load() {
            SystemControl::check();

        }

    }
