<?php

    /**
     * Class SystemControl
     * Check plugin dependencies and configuration
     * throws ErrorException if some dependencies or configuration are wrong
     */
    class SystemControl {

        public static function check() {
            self::checkDependencies();
            self::checkConstant();
        }

        private static function checkConstant() {
            if (defined(OPBEAT_ORGANIZATION_ID)===FALSE) {
                throw new ErrorException('Missing configuration: Organization ID (OPBEAT_ORGANIZATION_ID)');
            }
            if (defined(OPBEAT_APP_ID)===FALSE) {
                throw new ErrorException('Missing configuration: App ID (OPBEAT_APP_ID)');
            }
            if (defined(OPBEAT_SECRET_TOKEN)===FALSE) {
                throw new ErrorException('Missing configuration: Secret Token (OPBEAT_SECRET_TOKEN)');
            }
        }

        private static function checkDependencies() {
            if (function_exists('curl_version')===FALSE) {
                throw new ErrorException('Missing cURL. Please install it');
            }
        }

    }