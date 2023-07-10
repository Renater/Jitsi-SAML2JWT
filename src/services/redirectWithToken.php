<?php

require_once('../init.php');

/**
 * @var array $config
 */


try {
    if ($config['token_generator']['token_mode'] == 'advanced')
        $tokenGenerator = new AdvancedTokenGenerator();    
    else 
        $tokenGenerator = new TokenGenerator();
    $jsonResp =  $tokenGenerator->getToken($_SERVER,$_REQUEST);
    $room=$_GET['room'];
    $tenant=$_GET['tenant'];
    $URL="https://".$config['jitsi_domain']."/".$tenant.$room."?jwt=".$jsonResp;
    header("Location: $URL", true, 302); 
    return;
} catch (Exception $e){
    error_log($e->getMessage());
    RestResponse::send($e->getMessage(), 500);
    return;
}




