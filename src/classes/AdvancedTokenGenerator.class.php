<?php

require_once(dirname(__FILE__).'/../init.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
/**
 * Class Advabced TokenGenerator to generate JWT token
 */
class AdvancedTokenGenerator extends TokenGenerator{
    

    private static function generateId(string $input, int $length = 8): string {
        $lowercaseInput = strtolower($input);
        $hashBase64 = base64_encode(hash('sha256', $lowercaseInput, true));
        $hashSafeUrl = str_replace(['+', '/'], '-', $hashBase64);
        $hashSafeUrl = rtrim($hashSafeUrl, '=');
        $hashSafeUrl = strtolower($hashSafeUrl);
        return substr($hashSafeUrl, 0, $length);
    }

    public function roomAlreadyStarted(string $room){
        global $config;
        error_log("Check Room Started :  $room", 0);
        foreach ($config['token_generator']['jicofo_room_endpoints'] as  $jicofo_ip) {
            $caller = new RESTCaller("http://".$jicofo_ip.":8888");     
            $result = $caller->get("/debug");
            error_log("Result $jicofo_ip $result", 0);
            if (strpos($result,$room) !== false )
                return true;
        }
        return false;
    }

    public function emailValidConference(string $room, string $email){
        $room_pattern = "/(.*)__(.*)_[0-9a-f]{6}-[0-9a-f]{6}-[0-9a-f]{6}$/";
        error_log("Check emal valid  :  $email", 0);
        if (preg_match($room_pattern, $room, $match)){
            $roomName = $match[1];
            $uid = $match[2];
            $email_uid = AdvancedTokenGenerator::generateId($roomName . $email, 12) ;
            error_log("Check $uid  :  $email_uid", 0);
            if ($uid != $email_uid)
                return false;
        }
        return true;
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
            error_log("No Email Provided in Headers, we can't genrate a token", 0);
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
        if (array_key_exists('tenant',$requestData) && strpos($tenant,"private") !== false ){
            $roomStarted = $this->roomAlreadyStarted($room);
            $emailValid = $this->emailValidConference($room,$email);

            error_log("Private   :  $room $email emailV=$emailValid roomV=$roomStarted", 0);

            if (!$roomStarted && !$emailValid )
                return '';
        }

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