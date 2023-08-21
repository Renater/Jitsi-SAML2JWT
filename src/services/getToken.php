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
    echo $jsonResp;
    return ;
} catch (Exception $e){
    error_log($e->getMessage());
    RestResponse::send($e->getMessage(), 500);
    return;
}




