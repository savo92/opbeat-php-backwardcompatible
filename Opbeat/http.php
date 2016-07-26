<?php
    /**
     * Created by PhpStorm.
     * User: lorenzo
     * Date: 26/07/16
     * Time: 20:37
     */

    class OpbeatHttp {

        /**
         * Generate an http node for the json payload. It returns null if no HTTP request
         * @return array|null
         */
        public static function generateHttp () {
            if ($_SERVER['REQUEST_METHOD']) {
                return self::_generateHttpFrame();
            } else {
                return null;
            }
        }

        /**
         * @return array the 'http' node of the json payload
         */
        private static function _generateHttpFrame () {
            $frame = array(
                'url' => self::getFullURL(),
                'method' => $_SERVER['REQUEST_METHOD'],
                'secure' => isset($_SERVER['HTTPS']),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'http_host' => $_SERVER['HTTP_HOST']
            );

            $headers = self::getallheaders();
            if ($headers!==null && is_array($headers) && count($headers)>0) {
                $frame['headers'] = $headers;
            }

            if ($_SERVER['REQUEST_METHOD']=='POST') {
                $frame['data'] = $_POST;
            }

            $queryString = self::getQueryString();
            if ($queryString!==null) {
                $frame['query_string'] = $queryString;
            }

            return $frame;
        }

        /**
         * @return string Return the actual full URL: schema://host/uri without querystring
         */
        public static function getFullURL () {
            return 'http'.(isset($_SERVER['HTTPS']) ? 's' : '') . '://'
                   . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
        }

        /**
         * @return array|null
         */
        public static function getallheaders () {
            if (function_exists('getallheaders')===false) {
                $headers = array();
                foreach ($_SERVER as $name => $value){
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
            } else {
                $headers = getallheaders();
                $headers = $headers===false ? null : $headers;
            }
            return $headers;
        }

        /**
         * @return null|string
         */
        private static function getQueryString () {
            $queryString = "";
            foreach ($_GET as $k => $v) {
                $queryString .= (strlen($queryString)>0?'&':'') . $k . "=" . $v;
            }
            return strlen($queryString)>0 ? $queryString : null;
        }

    }