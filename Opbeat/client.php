<?php
    /**
     * Created by PhpStorm.
     * User: lorenzo
     * Date: 12/07/16
     * Time: 16:57
     */

    require_once dirname(__FILE__).'/http.php';
    require_once dirname(__FILE__).'/exception.php';

    class Opbeat_Client {

        /**
         * Using cURL, it send error to Opbeat API
         *
         * @param $errStr string a message
         * @param $level string the error level, conform to Opbeat standard
         * @param $errFile string
         * @param $errLine int
         * @param $cleanedTrace array the stack trace, conform to Opbeat standard
         * @param null|array|false $httpRequest
         * @param null|array $user
         * @param null|array $extra
         * @param null|array $exception
         *
         * @throws Exception
         */
        public static function sendError(
            $errStr,
            $level,
            $errFile,
            $errLine,
            $cleanedTrace,
            $httpRequest=null,
            $user=null,
            $extra=null,
            $exception=null
        ) {


            $data = self::prepareData(
                $errStr,
                $level,
                $errFile,
                $errLine,
                $cleanedTrace,
                $httpRequest,
                $user,
                $extra,
                $exception
            );
            $dataString = json_encode($data);
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => self::getAPIUrl(),
                CURLOPT_POST => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $dataString,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".OPBEATOPT_SECRET_TOKEN,
                    "Content-Type: application/json",
                    "Content-Length: " . strlen($dataString),
                    "User-Agent: opbeat-php-backwardcompatible/1.0"
                )
            ));
            $result = curl_exec($ch);
            $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            $errNo = curl_errno($ch);
            curl_close($ch);

            if ($error) {
                throw new Exception($error);
            }
            $result = json_decode($result, true);
            if (!$result || !isset($result['status'])|| $result['status']!=202) {
                throw new Exception($result);
            }
        }

        /**
         * @param int $errNo  A PHP error code
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
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                    $level = 'fatal';
                    break;
                default:
                    $level = 'notice';
            }
            return $level;
        }

        /**
         * @param string $errStr
         * @param string $level
         * @param string $errFile
         * @param int $errLine
         * @param array $cleanedTrace
         * @param null|array|false $httpRequest
         * @param null|array $user
         * @param null|array $extra
         * @param null|array $exception
         *
         * @return array
         */
        private static function prepareData($errStr,
            $level,
            $errFile,
            $errLine,
            $cleanedTrace,
            $httpRequest=null,
            $user=null,
            $extra=null,
            $exception=null
        ) {

            // Add default parameters
            $data = array(
                'message' => $errStr,
                'level' => $level,
                'culprit' => $errFile.":".$errLine,
                'timestamp' => time(),
                'stacktrace' => array(
                    'frames' => $cleanedTrace
                )
            );

            if ($httpRequest!==false) {
                if ($httpRequest===null) {
                    $httpRequest = Opbeat_Http::generateHttp();
                }
                if (is_array($httpRequest) && count($httpRequest)>0) {
                    $data['http'] = $httpRequest;
                }
            }

            if ($user!==null && is_array($user) && count($user)>0) {
                $data['user'] = $user;
            }

            if ($extra!==null && is_array($extra) && count($extra)>0) {
                $data['extra'] = $extra;
            }

            $exception = Opbeat_Exception::getException($exception);
            if ($exception!==null) {
                $data['exception'] = $exception;
            }

            return $data;
        }

        /**
         * @return string the API URL
         */
        private static function getAPIUrl () {
            return "https://intake.opbeat.com/api/v1/organizations/".OPBEATOPT_ORGANIZATION_ID
                   ."/apps/".OPBEATOPT_APP_ID."/errors/";
        }

    }