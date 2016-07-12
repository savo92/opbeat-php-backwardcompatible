<?php
    /**
     * Created by PhpStorm.
     * User: lorenzo
     * Date: 12/07/16
     * Time: 16:57
     */

    class OpbeatClient {

        private static function _sendError($errStr, $level, $cleanedTrace) {
            $data_string = json_encode(array(
                'message' => $errStr,
                'level' => $level,
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
                    "User-Agent: PHP custom script 1.0"
                )
            ));
            $result = curl_exec($ch);
            if (curl_error($ch)) {
                $error = curl_error($ch);
            } else {
                $error = "Unexpected error";
            }
            curl_close($ch);
        }

        private static function getAPIUrl () {
            return "https://intake.opbeat.com/api/v1/organizations/".OPBEAT_ORGANIZATION_ID
                   ."/apps/".OPBEAT_APP_ID."/errors/";
        }

        public static function internalSendError($errStr, $level, $cleanedTrace) {
            self::_sendError($errStr, $level, $cleanedTrace);
        }

    }