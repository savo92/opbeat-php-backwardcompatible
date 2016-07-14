<?php
    /**
     * Created by PhpStorm.
     * User: lorenzo
     * Date: 12/07/16
     * Time: 16:57
     */

    class OpbeatClient {

        /**
         * @param $errNo int A PHP error code
         *
         * @return string the error level, conform to Opbeat standard
         */
        public static function getErrorLevel($errNo) {
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
            return $level;
        }

        /**
         * Using cURL, it send error to Opbeat API
         *
         * @param $errStr string a message
         * @param $level string the error level, conform to Opbeat standard
         * @param $errFile string
         * @param $errLine int
         * @param $cleanedTrace array the stack trace, conform to Opbeat standard
         */
        public static function sendError($errStr, $level, $errFile, $errLine, $cleanedTrace) {
            // @TODO add other facoltative infos
            $data_string = json_encode(array(
                'message' => $errStr,
                'level' => $level,
                'culprit' => $errFile,
                'timestamp' => time(),
                'machine' => array(
                    'hostname' => $_SERVER['']
                ),
                'stacktrace' => array(
                    'frames' => $cleanedTrace
                )
            ));
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => self::getAPIUrl(),
                CURLOPT_POST => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".OPBEAT_SECRET_TOKEN,
                    "Content-Type: application/json",
                    "Content-Length: " . strlen($data_string),
                    "User-Agent: opbeat-php-backwardcompatible/1.0"
                )
            ));
            // @fixme curl error handling is wrong
            $result = curl_exec($ch);
            if (curl_error($ch)) {
                $error = curl_error($ch);
            } else {
                $error = "Unexpected error";
            }
            curl_close($ch);
        }

        /**
         * @return string the API URL
         */
        private static function getAPIUrl () {
            return "https://intake.opbeat.com/api/v1/organizations/".OPBEAT_ORGANIZATION_ID
                   ."/apps/".OPBEAT_APP_ID."/errors/";
        }

    }