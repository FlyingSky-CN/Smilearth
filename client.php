<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * Smilearth > Test > Client
 */

define('DIR', __DIR__.'/');

require DIR.'app/lib/Smilearth.class.php';

while (true):

    if (isset($AI)) unset($AI);

    echo '[user@Smilearth client]$ ';
    /* 获取输入 */
    $input = explode(' ',rtrim(fgets(STDIN)))[0];

    if ($input == 'exit') break; 

    $AI = new Smilearth();
    $AI->debug = true;
    $AI->setProfile(DIR.'data/person/client.json');
    $AI->init();
    $AI->input($input);
    $AI->save();

    foreach ($AI->output as $msg) {
        echo '[root@Smilearth client]# '.$msg."\n";
    }

endwhile;