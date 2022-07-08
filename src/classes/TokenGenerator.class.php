<?php

require_once(dirname(__FILE__).'/../init.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;


/**
 * Class DBI add facilities for database usage
 */
class TokenGenerator {
    

    private function generateHS256Token(array $payload, string $key): string {
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    private function decodeHS256Token(array $jwt, string $key): string {

        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return $decoded;
    }

    public function getToken(Array $envData): string {
        global $config;
        
        $displayName = $envData['HTTP_DISPLAYNAME'];
        $email = $envData['HTTP_MAIL'];
        $gravatarHash = md5( strtolower( trim( $email  ) ) ); 

        $payload = [
            'context' => [
                'user' => [
                    'avatar' => "https://www.gravatar.com/avatar/$gravatarHash?d=404&size=200",
                    'name'   => $displayName,
                    'email'  => $email
                ]
            ],
            'iss'  => $config['token_generator']['app_id'],
            'aud'  => $config['token_generator']['app_id'],
            'sub'  => $config['jitsi_domain'],
            'room' => '*'
        ];
        $key = $config['token_generator']['key'];

        $token = $this->generateHS256Token($payload,$key);

        return $token;

    }

    public function decodeToken(string $tokenEncrypted): string {
        $result = $this->decode($tokenEncrypted,$key);
        $key = "my_jitsi_secret";
        return $token;
    }
}


