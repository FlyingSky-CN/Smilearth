<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * 数据上报入口
 */

$IN = array(
    'SERVER' => $_SERVER,
    'POST' => $_POST,
    'GET' => $_GET,
    'REQUEST' => $_REQUEST,
    'INPUT' => json_decode(
        file_get_contents('php://input'),
        true
    )
);

$input = json_decode(
    file_get_contents('php://input'),
    true
);

require_once 'app/common.php';
require_once 'app/update.php';