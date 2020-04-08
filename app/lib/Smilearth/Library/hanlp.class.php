<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * Smilearth > Lib > hanlp
 */

class Smilearth_Lib_hanlp {

    /**
     * 分词
     */
    static function tokenizer($input = '') {

        $_http = 'http://127.0.0.1:7005/hanlp';

        $response = file_get_contents(
            $_http.
            '?q='.
            urlencode(
                $input
            )
        );
        if ($response) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }

}