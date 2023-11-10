<?php
$config['version'] = '0.1';

// ---------------------------------------------------------------------------
$config['token_generator'] = array(
    'key'    => '${JWT_APP_SECRET}',
    'app_id' => '${JWT_APP_ID}',
    'token_mode' => '${JWT_TOKEN_MODE}'
);

$config['jitsi_domain'] = '${JITSI_DOMAIN}';

$config['auth_error_page'] = '${AUTH_ERROR_PAGE}';

$config['syslog'] = array(
    'enabled' => false,
    'debug' => true,
    'identifier' => 'Token Generator'
);
