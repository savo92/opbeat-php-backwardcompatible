<?php

    /**
     * Class SystemControl
     * Provide generic functions
     */
    class Opbeat_Utils {

        /**
         * @param string $file
         *
         * @return string
         */
        public static function getFilename ($file) {
            if (defined('OPBEATOPT_PROJECT_ABS_PATH')!==false) {
                return str_replace(OPBEATOPT_PROJECT_ABS_PATH.'/', '', $file);
            } else {
                return $file;
            }
        }

        /**
         * Handle the system checkup
         * @throws ErrorException if missing dependencies or configurations
         */
        public static function checkSystem() {
            self::checkDependencies();
            self::checkConstant();
            return true;
        }

        /**
         * Handle the configurations' checkup
         * @throws ErrorException
         */
        private static function checkConstant() {
            if (defined('OPBEATOPT_ORGANIZATION_ID')===false) {
                throw new ErrorException('Missing configuration: Organization ID (OPBEATOPT_ORGANIZATION_ID)');
            }
            if (defined('OPBEATOPT_APP_ID')===false) {
                throw new ErrorException('Missing configuration: App ID (OPBEATOPT_APP_ID)');
            }
            if (defined('OPBEATOPT_SECRET_TOKEN')===false) {
                throw new ErrorException('Missing configuration: Secret Token (OPBEATOPT_SECRET_TOKEN)');
            }
        }

        /**
         * Handle the dependencies' checkup
         * @throws ErrorException
         */
        private static function checkDependencies() {
            if (function_exists('curl_version')===FALSE) {
                throw new ErrorException('Missing cURL. Please install it');
            }
        }

    }