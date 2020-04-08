<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * Smilearth > Lib > language
 */

class Smilearth_Lib_language {
    private $input;
    private $tokenizer;
    private $lang;
    private $mod;
    private $output = [];
    private $action = array('user' => array(), 'root' => array());
    private $match = array('default' => 0);
    private $debug = true;

    public function process($input, $tokenizer = array(), $lang = 'zh', $mod, $debug = false) {
        $this->input = $input;
        $this->tokenizer = $tokenizer;
        $this->debug = $debug;

        if (file_exists($mod)) {
            $this->mod = json_decode(file_get_contents($mod), true);
        } else return false;

        if ($this->debug) $this->output[] = $lang;
        if ($this->debug) $this->output[] = json_encode($tokenizer, JSON_UNESCAPED_UNICODE);

        /* 不支持的语言 */
        if ($lang != 'zh') {
            $this->endUnknowLang();
            return array(
                'output' => $this->output,
                'action' => $this->action
            );
        }

        /* 词数上限 */
        if (count($tokenizer) > 32) {
            $this->endToomuch();
            return array(
                'output' => $this->output,
                'action' => $this->action
            );
        }

        $this->match();
        
        $this->processGood();
        $this->processNowTime();
        $this->processDefault();

        /* 返回 */
        return array(
            'output' => $this->output,
            'action' => $this->action
        );
    }

    /**
     * 语义匹配
     */
    private function match() {
        if (!is_array($this->mod['match'])) return false;
        foreach ($this->mod['match'] as $key => $array) {
            if (!is_array($array)) return false;
            foreach ($array as $keyword) {
                if (in_array($keyword, $this->tokenizer)) {
                    if (!isset($this->match[$key])) $this->match[$key] = 0;
                    $this->match[$key]++;
                }
            }
        }
        arsort($this->match);
        if ($this->debug) $this->output[] = json_encode($this->match, JSON_UNESCAPED_UNICODE);
        return true;
    }

    /**
     * 词数超限 
     */
    private function endToomuch() {
        $this->randreply('toomuch');
        return true;
    }

    /**
     * 不支持的语言 
     */
    private function endUnknowLang() {
        $this->randreply('unknowlang');
        return true;
    }

    /**
     * 随机一个回复短语 
     */
    private function randreply($key) {
        $this->output[] = $this->mod["reply"][$key][rand(0, (count($this->mod["reply"][$key]) - 1))];
        return true;
    }

    /**
     * 处理问好消息
     */
    private function processGood() {
        if (!(
            isset($this->match['goodmorning']) ||
            isset($this->match['goodnoon']) ||
            isset($this->match['goodafternoon']) ||
            isset($this->match['goodnight']) ||
            isset($this->match['gosleep'])
        )) return false;
        if (
            array_keys($this->match)[0] == 'goodmorning' ||
            array_keys($this->match)[0] == 'goodnoon' ||
            array_keys($this->match)[0] == 'goodafternoon' ||
            array_keys($this->match)[0] == 'goodnight' ||
            array_keys($this->match)[0] == 'gosleep'
        ) {
            if (
                @$this->match['goodmorning'] == @$this->match['goodnoon'] &&
                @$this->match['goodnight'] == @$this->match['goodnoon'] 
            ) {
                $this->randreply('good');
                $this->action['user'][] = 'good';
                $this->action['root'][] = 'good';
                return true;
            }
            if (array_keys($this->match)[0] == 'gosleep') {
                $this->randreply('gosleep');
                $this->action['user'][] = 'gosleep';
                $this->action['root'][] = 'gosleep';
            } else {
                $this->randreply(array_keys($this->match)[0]);
                $this->action['user'][] = array_keys($this->match)[0];
                $this->action['root'][] = array_keys($this->match)[0];
            }
            return true;
        } else return false;
    }

    /* 处理现在时间 */
    private function processNowTime() {
        if (array_keys($this->match)[0] == 'nowtime' or @$this->match['nowtime'] > 3 ) {

        } else return false;
    }

    private function processDefault() {
        if (count($this->match) == 1 and array_keys($this->match)[0] == 'default') {
            $this->randreply('default');
            $this->action['user'][] = 'default';
            $this->action['root'][] = 'default';
        } else return false;
    }
}