<?php

class Smilearth_Lib_xiaosi {
    static function input($input = '') {
        return json_decode(file_get_contents('https://api.ownthink.com/bot?spoken='.urlencode($input)), true)['data']['info']['text'];
    }
}