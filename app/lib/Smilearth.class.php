<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * Smilearth
 */

define('SDIR', dirname(__FILE__).'/'.'Smilearth'.'/');

require SDIR.'Library/langid.class.php';
require SDIR.'Library/hanlp.class.php';
require SDIR.'Library/language.class.php';
require SDIR.'Library/tuling.class.php';
require SDIR.'Library/xiaosi.class.php';

class Smilearth {
    private $DIR;
    private $_new = false;
    private $_profile;
    private $_updateInformation;
    private $profile;
    public $debug = false;
    public $error = false;
    public $output = array();

    public function __construct() {

        /* 设置数据目录 */
        $this->DIR = SDIR;

    }

    /**
     * 初始化
     */
    public function init() {
        
        /* 校验数据目录 */
        if (is_dir($this->DIR)) {

            /* 是否为新面孔 */
            if ($this->_new) {

                /* 用档案模板建立新档案 */
                $this->profile = json_decode(
                    file_get_contents($this->DIR.'Default/Profile.json'),
                    true
                );

            } else {

                /* 加载档案 */
                if (!$this->loadProfile()) {

                    /* 无法加载档案 */
                    $this->error = 10002;

                }

            }

            /* 刷新档案 */
            if (is_array($this->_updateInformation)) {
                foreach($this->_updateInformation as $key => $value) {
                    if (@end($this->profile[$key])['data'] != $value)
                    array_push(
                        $this->profile[$key], 
                        array(
                            'data' => $value, 
                            'time' => time()
                        )
                    );
                }
            }

        } else {

            /* 无法找到数据目录 */
            $this->error = 10001;

        }

    }

    /**
     * 消息处理
     */
    public function input($input) {

        if (isset($this->profile['options']['logMsg'])) 
        if ($this->profile['options']['logMsg'])
        $this->profile['message'][time()] = $input;

        if (isset($this->profile['options']['useXiaosi'])) 
        if ($this->profile['options']['useXiaosi']) {

            $result = Smilearth_Lib_xiaosi::input($this->_getText($input));
            if (isset($this->profile['options']['logReply'])) 
            if ($this->profile['options']['logReply'])
            $this->profile['reply'][time()] = $result;
            $this->output[] = $result;
            return true;

        }

        if (isset($this->profile['options']['tuling'])) 
        if ($this->profile['options']['tuling']) {

            $TL = new Smilearth_Lib_tuling(
                'herewasthetulingkey', 
                $this->profile['user_id'][0]['data']
            );
            $this->output[] = $TL->tuling($this->_getText($input));
            return true;

        }

        $lang = $this->detectLang($input);
        $tokenizer = Smilearth_Lib_hanlp::tokenizer($this->_getText($input));
        $mod = SDIR.'Language/zh.json';

        $language = new Smilearth_Lib_language();
        
        $result = $language->process($input, $tokenizer, $lang, $mod, $this->debug);

        if (is_array($result['output'])) foreach ($result['output'] as $msg) {
            array_push(
                $this->output,
                $msg
            );
        } else {
            array_push(
                $this->output,
                'Something error...'
            );
        }
        if (is_array($result['action'])) {
            if (is_array($result['action']['user']))
            foreach ($result['action']['user'] as $action) {
                if (!is_array($this->profile['recent_action']['user'])) 
                $this->profile['recent_action']['user'] = array();
                $this->profile['recent_action']['user'][time()] = $action;
            }
            if (is_array($result['action']['root']))
            foreach ($result['action']['root'] as $action) {
                if (!is_array($this->profile['recent_action']['root'])) 
                $this->profile['recent_action']['root'] = array();
                $this->profile['recent_action']['root'][time()] = $action;
            }
        }

        return true;
    }

    /**
     * 获取纯文本消息内容
     */
    private function _getText($array=array()) {
        if (!is_array($array)) return $array;
        $msg = '';
        foreach ($array as $inner) {
            if ($inner['type'] == 'text') {
                $msg .= $inner['data']['text'];
            }
        }
        return $msg;
    }

    /**
     * 识别消息语言
     */
    private function detectLang($input) {

        $msg = (is_array($input)) ? $this->_getText($input) : $input;
        $response = Smilearth_Lib_langid::detect($msg);

        if (!isset($this->profile['language'][$response['language']]))
        $this->profile['language'][$response["language"]] = 0;

        $this->profile['language'][$response["language"]] -= $response["confidence"];
        
        arsort($this->profile['language']);

        return $response["language"];

    }

    /**
     * 保存档案
     */
    public function save() {
        return file_put_contents($this->_profile, json_encode($this->profile, JSON_UNESCAPED_UNICODE)) ? true : false;
    }

    /**
     * 设置档案文件目录
     */
    public function setProfile($file = '') {
        if (file_exists($file)) {
            $this->_profile = $file;
            return true;
        } else {
            $this->_new = true;
            if (file_put_contents($file, json_encode(array('init')))) {
                $this->_profile = $file;
                return true;
            } else {
                $this->_profile = false;
                return false;
            }
        }
    }

    /** 
     * 设置档案更新数据
     */
    public function setUpdateInformation($array = array()) {
        if (is_array($array)) {
            $this->_updateInformation = $array;
            return true;
        } else {
            return false;
        }
    }

    private function loadProfile() {
        if (empty($this->_profile)) {
            return false;
        } else {
            $this->profile = json_decode(
                file_get_contents($this->_profile),
                true
            );
            return $this->profile ? true : false;
        }
    }

    private function error() {
        //TODO
    }

}