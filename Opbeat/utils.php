<?php

    /**
     * Class SystemControl
     * Provide generic functions
     */
    class OpbeatUtils {

        /**
         * @param string $file
         *
         * @return string
         */
        public static function getFilename ($file) {
            if (defined('OPBEATOPT_PROJECT_ABS_PATH')!==FALSE) {
                return str_replace(OPBEATOPT_PROJECT_ABS_PATH, '', $file);
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
        }

        /**
         * Handle the configurations' checkup
         * @throws ErrorException
         */
        private static function checkConstant() {
            if (defined(OPBEATOPT_ORGANIZATION_ID)===FALSE) {
                throw new ErrorException('Missing configuration: Organization ID (OPBEAT_ORGANIZATION_ID)');
            }
            if (defined(OPBEATOPT_APP_ID)===FALSE) {
                throw new ErrorException('Missing configuration: App ID (OPBEAT_APP_ID)');
            }
            if (defined(OPBEATOPT_SECRET_TOKEN)===FALSE) {
                throw new ErrorException('Missing configuration: Secret Token (OPBEAT_SECRET_TOKEN)');
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