<?php
    /**
     * Created by PhpStorm.
     * User: lorenzo
     * Date: 27/07/16
     * Time: 18:23
     */

    class OpbeatException {

        /**
         * @param \Exception $exception
         *
         * @return array|null
         */
        public static function getException($exception) {
            if (!is_a($exception, 'Exception')) return null;
            return array(
                'type' => get_class($exception),
                'value' => $exception->getMessage()
            );
        }

    }