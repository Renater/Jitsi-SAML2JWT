<?php

require_once(dirname(__FILE__).'/../init.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class Advabced TokenGenerator to generate JWT token
 */
class AdvancedTokenGenerator extends TokenGenerator{
    

    private static function generateId(string $input, int $length = 8): string {
        $hashBase64 = base64_encode(hash('sha256', $input, true));
        $hashSafeUrl = strtr($hashBase64, '+/', '--');
        $hashSafeUrl = rtrim($hashSafeUrl, '=');
        return substr($hashSafeUrl, 0, $length);
    }

    public function emailValidConference(string $room, string $email){
        $room_pattern = "/.(*)__(.*)_[0-9a-f]{6}-[0-9a-f]{6}-[0-9a-f]{6}$/";
        if (preg_match($room_pattern, $room, $match)){
            $roomName = $match[1];
            $uid = $match[2];
            $email_uid = strtolower( AdvancedTokenGenerator::generateId($roomName . $email, 12) );
            error_log("Check $uid  :  $email_uid", 0);
            if ($uid != $email_uid)
                return false;
        }
        return true;
    }


    public function getToken(Array $envData,Array $requestData): string {
        global $config;
        
        if (array_key_exists('HTTP_DISPLAYNAME',$envData))
            $displayName = $envData['HTTP_DISPLAYNAME'];
        else 
           $displayName = "anonymous";

        if (array_key_exists('HTTP_MAIL',$envData))
            $email = $envData['HTTP_MAIL'];    
        else {
            error_log("No Email Provided in Headers, we can genrate a token", 0);
            return '';
        }
        
        if (array_key_exists('room',$requestData))
            $room=$requestData['room'];
        else 
            $room='*';

        if (array_key_exists('tenant',$requestData))
            $tenant=rtrim($requestData['tenant'],"/");
        else 
            $tenant=explode(':',$config['jitsi_domain'])[0];


        // guest  + admin
        if (array_key_exists('affiliation',$requestData))
            $affiliation=$requestData['affiliation'];
        else 
            $affiliation='none';

        // private 
        if (array_key_exists('tenant',$requestData) && str_contains($tenant,"private")){
            if ( !$this->emailValidConference($room,$email) )
                return '';
        }

        if (array_key_exists('validity_timestamp',$requestData))
            $validity=intval($requestData['validity_timestamp']);
        else 
            $validity=0;

        $gravatarHash = md5( strtolower( trim( $email  ) ) ); 

        $payload = [
            'context' => [
                'user' => [
                    'avatar' => "https://www.gravatar.com/avatar/$gravatarHash?d=404&size=200",
                    'name'   => $displayName,
                    'email'  => $email,
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

        if ($affiliation != 'none')
            $payload['context']['user']['affiliation']=$affiliation;

        if ($validity>0)
            $payload['exp']=$validity;

        $key = $config['token_generator']['key'];

        $token = $this->generateHS256Token($payload,$key);

        return $token;

    }

}