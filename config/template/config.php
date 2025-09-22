<?php
$config['version'] = '0.1';

// ---------------------------------------------------------------------------
$config['token_generator'] = array(
    'key'    => '${JWT_APP_SECRET}',
    'app_id' => '${JWT_APP_ID}',
    'token_mode' => '${JWT_TOKEN_MODE}',
    'jicofo_room_endpoints' =>  explode(',',getenv("JICOFO_ROOM_ENDPOINTS"))
);

$config['enable_setting_validity'] = '${ENABLE_VALIDITY_BY_REQUEST}';
$config['default_validity'] = '${DEFAULT_VALIDITY}'; // in seconds , set to 0 for infinite validity

$config['jitsi_domain'] = '${JITSI_DOMAIN}';

$config['auth_error_page'] = '${AUTH_ERROR_PAGE}';

$config['syslog'] = array(
    'enabled' => false,
    'debug' => true,
    'identifier' => 'Token Generator'
);
