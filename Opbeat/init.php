<?php
    require_once dirname(__FILE__) . '/utils.php';
    require_once dirname(__FILE__) . '/client.php';
    require_once dirname(__FILE__) . '/trace.php';

    /**
     * Class OpbeatInitializer
     * Check dependencies and configuration. Then registers the error handler and register shutdown hooks
     */
    class Opbeat_Initializer {

        private static $initialized = false;
        private static $hookCallback = null;
        private static $extra = null;

        /**
         * @param bool|TRUE $willRegisterHooks define if registers set_error_handler and register_shutdown_function or not
         * @param null|callable $hookCallback the callable that will be executed at the end of the hook (if $willRegisterHooks is true)
         * @param null|array|callable $extra
         */
        public static function load ($willRegisterHooks=true, $hookCallback=null, $extra=null) {
            if (self::$initialized===true) return;

            Opbeat_Utils::checkSystem();

            if ($willRegisterHooks===true) {
                self::registerHooks($hookCallback);
            }
            self::setExtra($extra);

            self::$initialized = true;
        }

        /**
         * @param null|callable $hookCallback the callable that will be executed at the end of the hook
         */
        private static function registerHooks ($hookCallback=null) {
            if ($hookCallback!==null && is_callable($hookCallback)!==false) {
                self::$hookCallback = $hookCallback;
            }

            set_error_handler(array('Opbeat_Initializer', 'errorHandler'));
            set_exception_handler(array('Opbeat_Initializer', 'exceptionHandler'));
            register_shutdown_function(array('Opbeat_Initializer', 'shutdownHandler'));
        }

        /**
         * The standard set_error_handler callable
         *
         * @param $errNo string
         * @param $errStr int
         * @param $errFile string
         * @param $errLine int
         * @param null|array $trace
         */
        public static function errorHandler ($errNo, $errStr, $errFile, $errLine, $errContext=null, $trace=null, $hideDefaultPage=false) {
            $php_not_logged_error_codes = array(
                E_NOTICE,
                E_USER_NOTICE,
                E_STRICT,
                E_DEPRECATED,
                E_USER_DEPRECATED
            );
            if (in_array($errNo, $php_not_logged_error_codes)) return;

            if ($trace===null) $trace = debug_backtrace();
            self::sendStandardPhpError($errNo, $errStr, $errFile, $errLine, $trace);
            if (self::$hookCallback!==null) {
                call_user_func(self::$hookCallback);
            }
            if ($hideDefaultPage===false) {
                self::displayDefaultErrorPage();
            }
        }

        /**
         * The standard register_shutdown_function callable
         */
        public static function shutdownHandler ($hideDefaultPage=false) {
            $error = error_get_last();
            if ($error!==null) {
                // Invoke self::errorHandler and pass the properly value from $error
                self::errorHandler(
                    $error['type'],
                    $error['message'],
                    $error['file'],
                    $error['line'],
                    null,
                    debug_backtrace(),
                    $hideDefaultPage
                );
            }
        }

        public static function exceptionHandler ($e, $hideDefaultPage=false) {
            self::sendException($e);
            if ($hideDefaultPage===false) {
                self::displayDefaultErrorPage();
            }
        }

        /**
         * @param $errNo string
         * @param $errStr int
         * @param $errFile string
         * @param $errLine int
         * @param array $trace
         */
        public static function sendStandardPhpError ($errNo, $errStr, $errFile, $errLine, $trace) {
            self::load(true);

            Opbeat_Client::sendError(
                $errStr,
                Opbeat_Client::getErrorLevel($errNo),
                $errFile,
                $errLine,
                Opbeat_TraceGenerator::getTrace($trace),
                null,
                null,
                self::getExtra()
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
         * @param null|array $extra
         */
        public static function sendPrettyError ($errStr, $level, $cleanedTrace, $errFile=null, $errLine=null,
                $http=null, $user=null, $extra=null) {
            self::load(true);

            Opbeat_Client::sendError($errStr, $level, $errFile, $errLine, $cleanedTrace, $http, $user);
        }

        /**
         * @param $e \Exception
         * @param null|array|false $http
         * @param null|array $user
         * @param null|array|false $extra
         */
        public static function sendException ($e, $http=null, $user=null, $extra=null) {
            self::load(true);

            if ($extra===null) {
                $extra = self::getExtra();
            } else if ($extra===false) {
                $extra = null;
            }

            Opbeat_Client::sendError(
                $e->getMessage(),
                'error',
                $e->getFile(),
                $e->getLine(),
                Opbeat_TraceGenerator::getTraceByException($e),
                $http,
                $user,
                $extra,
                $e
            );
        }

        /**
         * @return array|null
         */
        private static function getExtra () {
            if (is_array(self::$extra) && count(self::$extra)>0) {
                $extraArray = self::$extra;
            } elseif (is_callable(self::$extra)) {
                try {
                    $extraArray = call_user_func(self::$extra);
                    if ($extraArray===false || !is_array($extraArray) || count($extraArray)==0) {
                        throw new Exception();
                    }
                } catch(Exception $e) {
                    $extraArray = null;
                }
            } else {
                $extraArray = null;
            }
            return $extraArray;
        }

        /**
         * @param $extra null|array|callable
         */
        public function setExtra ($extra) {
            if ($extra!==null) {
                self::$extra = $extra;
            }
        }

        private static function displayDefaultErrorPage () {
            header('HTTP Status 500', true, 500);
            die('Error 500: Internal Server Error');
        }

    }
