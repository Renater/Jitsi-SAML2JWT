<?php

class RestResponse{
    
    /**
     * Send Rest Response
     *
     * @param $data
     * @param int $httpCode
     * @param array $headers
     */
    public static function send($data, $httpCode = 200, $headers = []){
        if (is_object($data))
            $data = (array) $data;
        
        if (is_array($data))
            $data = json_encode($data);
        
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
        ];
    
        foreach ($headers as $key => $value) {
            $defaultHeaders[$key] = $value;
        }
        
        header_remove();
        foreach ($defaultHeaders as $key => $value) {
            header($key.': '.$value);
        }
        http_response_code($httpCode);
    
        echo $data;
    }
}