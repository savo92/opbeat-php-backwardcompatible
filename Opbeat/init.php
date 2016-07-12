<?php
    require_once dirname(__FILE__) . '/utils.php';
    require_once dirname(__FILE__) . '/client.php';

    /**
     * Class OpbeatInitializer
     * Check dependencies and configuration. Then registers the error handler and register shutdown hooks
     */
    class OpbeatInitializer {

        private static $hookCallback = null;

        /**
         * @param bool|TRUE $willRegisterHooks define if registers set_error_handler and register_shutdown_function or not
         * @param null $hookCallback the callable that will be executed at the end of the hook (obviously, if $willRegisterHooks is true)
         */
        public static function load($willRegisterHooks=true, $hookCallback=null) {
            SystemControl::check();
            if ($willRegisterHooks===true) {
                self::registerHooks($hookCallback);
            }
        }

        /**
         * @param null $hookCallback the callable that will be executed at the end of the hook
         */
        private static function registerHooks ($hookCallback=null) {
            if ($hookCallback!==null && is_callable($hookCallback)!==false) {
                self::$hookCallback = $hookCallback;
            }

            set_error_handler(array('OpbeatInitializer', 'errorHandler'));
            register_shutdown_function(array('OpbeatInitializer', 'shutdownHandler'));
        }

        /**
         * The standard set_error_handler callable
         *
         * @param $errNo
         * @param $errStr
         * @param $errFile
         * @param $errLine
         */
        public static function errorHandler($errNo, $errStr, $errFile, $errLine) {
            $php_not_logged_error_codes = array(
                E_NOTICE,
                E_USER_NOTICE,
                E_STRICT,
                E_DEPRECATED,
                E_USER_DEPRECATED
            );
            if (in_array($errNo, $php_not_logged_error_codes)) return;
            self::sendStandardPhpError($errNo, $errStr, $errFile, $errLine);
            if (self::$hookCallback!==null) {
                call_user_func(self::$hookCallback);
            }
        }

        /**
         * THe standard register_shutdown_function callable
         */
        public static function shutdownHandler() {
            $error = error_get_last();
            if ($error!==null) {
                // Invoke self::errorHandler and pass the properly value from $error
                self::errorHandler(
                    $error['type'],
                    $error['message'],
                    $error['file'],
                    $error['line']
                );
            }
        }

        /**
         * @param $errNo
         * @param $errStr
         * @param $errFile
         * @param $errLine
         */
        public static function sendStandardPhpError($errNo, $errStr, $errFile, $errLine) {
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
            switch ($errNo) {
                case E_WARNING:
                case E_COMPILE_WARNING:
                case E_CORE_WARNING:
                case E_USER_WARNING:
                    $level = 'warning';
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                    $level = 'error';
                    break;
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                default:
                    $level = 'fatal';
            }
            OpbeatClient::internalSendError($errStr, $level, $cleanedTrace);
        }

        /**
         * @param $errStr
         * @param $level
         * @param $cleanedTrace
         */
        public static function sendPrettyError($errStr, $level, $cleanedTrace) {
            OpbeatClient::internalSendError($errStr, $level, $cleanedTrace);
        }

        /**
         * @param $e
         *
         * @throws Exception @TODO not implemented yet
         */
        public static function sendException($e) {
            //@TODO
            throw new Exception('Not implemented yet');
        }

    }
