<?php
$config['version'] = '0.1';

// ---------------------------------------------------------------------------
$config['token_generator'] = array(
    'key'    => '${JWT_APP_SECRET}',
    'app_id' => '${JWT_APP_ID}'
);

$config['jitsi_domain'] = '${JITSI_DOMAIN}';


$config['syslog'] = array(
    'enabled' => false,
    'debug' => true,
    'identifier' => 'Token Generator'
);
