<?php

class Smilearth_Lib_tuling {
    private $apiKey;
    private $text;
    private $userId;
    private $selfInfo;

    public function __construct($apiKey, $userId = 1, $selfInfo = array()) {
        $this->apiKey = $apiKey;
        $this->userId = $userId;
        $this->selfInfo = $selfInfo;        
    }

    public function tuling($text, $raw = false){

        $this->text = $text;    

        $param = [
            'perception' => [
                'inputText' => [
                    'text' => $this->text,
                ],
                'selfInfo' => $this->selfInfo
            ],
            'userInfo' => [
                'apiKey' => $this->apiKey,
                'userId' => $this->userId,
            ]
        ];

        $result = json_decode('['.$this->post('http://openapi.tuling123.com/openapi/api/v2',json_encode($param)).']',true);

        return $raw ? $result : $result[0]['results'][0]['values']['text'];
    }

    private function post($url,$data) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 2000);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;  
    }
}
