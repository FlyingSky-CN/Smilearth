<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * 通用文件
 */

define('DIR', '/www/wwwroot/fsky7.robots/2c30e5/');

require_once DIR.'app/lib/Error.function.php';
require_once DIR.'app/lib/Sys.function.php';
require_once DIR.'app/lib/CQhttp.class.php';
require_once DIR.'app/lib/Smilearth.class.php';

$Config = array(
    'db' => array(
        'server'   => 'localhost',
        'user'     => 'root',
        'password' => '',
        'database' => 'root'
    ),
    'cq' => array(
        'api' => 'http://127.0.0.1:8888/'
    )
);

$DB = mysqli_connect(
    $Config['db']['server'],
    $Config['db']['user'],
    $Config['db']['password']
);

if (!$DB) {
	header('HTTP/1.1 500');
	exit();
}

mysqli_select_db($DB,$Config['db']['database']);
mysqli_query($DB,"set names utf8mb4");

$CQ = new CQhttp($Config['cq']['api']);