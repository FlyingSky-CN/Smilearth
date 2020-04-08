<?php

function error_handler($error_level, $error_message, $file, $line) {

    $exit = false;

    switch ($error_level) {

        case E_NOTICE:
        case E_USER_NOTICE:
            break;

        case E_WARNING:
        case E_USER_WARNING:
            $error_type = 'Warning';
            $exit = true;
            break;

        case E_ERROR:
        case E_USER_ERROR:
            $error_type = 'Fatal Error';
            $exit = true;
            break;

        default:
            $error_type = 'Unknown';
            $exit = true;
            break;
            
    }

    $data = json_encode(
        array(
            'error' => $error_type.': '.$error_message.' in '.$file.' on line '.$line,
            'sys_error' => array(
                'type' => $error_type,
                'msg' => $error_message,
                'file' => $file,
                'line' => $line
            )
        )
    );

    file_put_contents(
        DIR.'data/error/'.time().'.json',
        $data
    );

    if ($exit) {
        exit();
    }

}

set_error_handler('error_handler');
