<?php

require_once(dirname(__FILE__).'/../init.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;


/**
 * Class TokenGenerator to generate JWT token
 */
class TokenGenerator {
    

    public function generateHS256Token(array $payload, string $key): string {
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public function decodeHS256Token(array $jwt, string $key): string {

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        } catch (Exception $e) {
            error_log("Token decode error: ".$e->getMessage(), 0);
            return "";
        }    
        return $decoded;
    }


    public function isTokenValid(Array $requestData): bool {
        global $config;
        $key = $config['token_generator']['key'];
        if (!array_key_exists('jwt',$requestData)) {
            error_log("No JWT token provided", 0);
            return false;
        }
        try {
            JWT::decode($requestData['jwt'], new Key($key, 'HS256'));
            return true;
        } catch (Exception $e) {
            error_log("Token validation error: ".$e->getMessage(), 0);
            return false;
        }    
    }

    public function getToken(Array $envData,Array $requestData): string {
        global $config;
        
        $displayName = "anonymous";
        if (array_key_exists('HTTP_DISPLAYNAME',$envData))
            $displayName = $envData['HTTP_DISPLAYNAME'];
            
        if (array_key_exists('HTTP_MAIL',$envData)){
            $email = $envData['HTTP_MAIL'];    
            if ($displayName=="anonymous")
                 $displayName =  $email;
        } 
        else {
            error_log("No Email Provided in Headers, we can genrate a token", 0);
            return "";
        }
            

        if (array_key_exists('room',$requestData))
            $room=$requestData['room'];
        else 
            $room='*';

        if (array_key_exists('tenant',$requestData))
            $tenant=rtrim($requestData['tenant'],"/");
        else 
            $tenant=explode(':',$config['jitsi_domain'])[0];

        if (array_key_exists('validity_timestamp',$requestData) && $config['enable_setting_validity'] === true )
            $validity=intval($requestData['validity_timestamp']);
        else {
            if (array_key_exists('default_validity',$config) && $config['default_validity'] > 0 )
                $validity=time() +$config['default_validity'];
            else
                $validity=0;
        }

        $gravatarHash = md5( strtolower( trim( $email  ) ) ); 

        $payload = [
            'context' => [
                'user' => [
                    'avatar' => "https://www.gravatar.com/avatar/$gravatarHash?d=404&size=200",
                    'name'   => $displayName,
                    'email'  => $email
                ],
                'features' => [
                    'livestreaming' => false,
                    'recording'     => true
                ]
            ],
            'iss'  => $config['token_generator']['app_id'],
            'aud'  => $config['token_generator']['app_id'],
            'sub'  => $tenant,
            'room' => $room
        ];
        if ($validity>0)
            $payload['exp']=$validity;

        $key = $config['token_generator']['key'];

        $token = $this->generateHS256Token($payload,$key);

        return $token;

    }

}