<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * Smilearth > Lib > langid
 */

class Smilearth_Lib_langid {

    /**
     * 判断语言
     */
    static function detect($input = '') {

        $_langid = '/usr/lib/python2.7/site-packages/langid/langid.py';
        $_http = 'http://127.0.0.1:9008/detect';

        $response = file_get_contents(
            $_http.
            '?q='.
            urlencode(
                substr(
                    $input,
                    0,64
                )
            )
        );
        if ($response) {
            $data = json_decode($response,true);
            return $data['responseData'];
        } else {
            return false;
        }
    }

}