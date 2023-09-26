<?php

require_once('../init.php');

/**
 * @var array $config
 */


try {
    if (array_key_exists('token_mode',$config['token_generator']) && $config['token_generator']['token_mode'] == 'advanced')
        $tokenGenerator = new AdvancedTokenGenerator();    
    else 
        $tokenGenerator = new TokenGenerator();
    $jsonResp =  $tokenGenerator->getToken($_SERVER,$_REQUEST);
    $room=$_GET['room'];
    $tenant=$_GET['tenant'];
    
    $extra="#";
    if(array_key_exists('jitsi_meet_external_api_id',$_REQUEST)){
        $extra.="jitsi_meet_external_api_id=";
        $extra.=$_REQUEST['jitsi_meet_external_api_id'];
    }
         
    $URL="https://".$config['jitsi_domain']."/".$tenant.$room."?jwt=".$jsonResp.$extra;
    header("Location: $URL", true, 302); 
    return;
} catch (Exception $e){
    error_log($e->getMessage());
    RestResponse::send($e->getMessage(), 500);
    return;
}




