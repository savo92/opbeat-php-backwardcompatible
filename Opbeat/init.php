<?php
    require_once dirname(__FILE__) . '/utils.php';
    require_once dirname(__FILE__) . '/client.php';
    require_once dirname(__FILE__) . '/trace.php';

    /**
     * Class OpbeatInitializer
     * Check dependencies and configuration. Then registers the error handler and register shutdown hooks
     */
    class OpbeatInitializer {

        private static $initialized = false;
        private static $hookCallback = null;

        /**
         * @param bool|TRUE $willRegisterHooks define if registers set_error_handler and register_shutdown_function or not
         * @param null|callable $hookCallback the callable that will be executed at the end of the hook (if $willRegisterHooks is true)
         */
        public static function load ($willRegisterHooks=true, $hookCallback=null) {
            if (self::$initialized===true) return;

            OpbeatUtils::checkSystem();
            if ($willRegisterHooks===true) {
                self::registerHooks($hookCallback);
            }
            self::$initialized = true;
        }

        /**
         * @param null|callable $hookCallback the callable that will be executed at the end of the hook
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
         * @param $errNo string
         * @param $errStr int
         * @param $errFile string
         * @param $errLine int
         */
        public static function errorHandler ($errNo, $errStr, $errFile, $errLine) {
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
         * The standard register_shutdown_function callable
         */
        public static function shutdownHandler () {
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
         * @param $errNo string
         * @param $errStr int
         * @param $errFile string
         * @param $errLine int
         * @param null|array|false $http
         * @param null|array $user
         */
        public static function sendStandardPhpError ($errNo, $errStr, $errFile, $errLine, $http=null, $user=null) {
            self::load(true);
            $cleanedTrace = OpbeatTraceGenerator::getTrace();
            $level = OpbeatClient::getErrorLevel($errNo);
            OpbeatClient::sendError(
                $errStr,
                $level,
                $errFile,
                $errLine,
                $cleanedTrace,
                $http,
                $user
            );
        }

        /**
         * @param $errStr string
         * @param $level string
         * @param $cleanedTrace array
         * @param null|string $errFile
         * @param null|int $errLine
         * @param null|array|false $http
         * @param null|array $user
         */
        public static function sendPrettyError ($errStr, $level, $cleanedTrace, $errFile=null, $errLine=null, $http=null, $user=null) {
            self::load(true);
            OpbeatClient::sendError($errStr, $level, $errFile, $errLine, $cleanedTrace, $http, $user);
        }

        /**
         * @param $e \Exception
         * @param null|array|false $http
         * @param null|array $user
         */
        public static function sendException ($e, $http=null, $user=null) {
            self::load(true);
            OpbeatClient::sendError(
                $e->getMessage(),
                'error',
                $e->getFile(),
                $e->getLine(),
                OpbeatTraceGenerator::getTraceByException($e),
                $http,
                $user
            );
        }

    }
