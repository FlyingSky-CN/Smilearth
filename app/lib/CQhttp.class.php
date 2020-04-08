<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * CQhttp
 */

class CQhttp {
    private $_api;

    public function __construct($api) {
        $this->_api = $api;
    }

    public function sendPrivateMsg($user_id, $msg) {
        return file_get_contents($this->_api.'send_private_msg?user_id='.$user_id.'&message='.urlencode($msg));
    }

    public function sendGroupMsg($group_id, $msg) {
        return file_get_contents($this->_api.'send_group_msg?group_id='.$group_id.'&message='.urlencode($msg));
    }

    public function restartPlugin() {
        return file_get_contents($this->_api.'set_restart_plugin?delay=2000');
    }

    public function sendLike($user_id, $times=10) {
        return file_get_contents($this->_api."send_like?user_id=$user_id&times=$times");
    }

}