<?php

define('APP_ROOT', dirname(__FILE__));

// Config file
$configFile = APP_ROOT.'/../config/config.php';
if (!file_exists($configFile))
    die('Config file not found'.$configFile);

require_once($configFile);

// Init global config
global $config;

// composer libs
if (!is_dir(APP_ROOT.'/../lib/vendor'))
    die('Composer not initialised');

require_once APP_ROOT.'/../lib/vendor/autoload.php';

// Generator files
$classes = ['TokenGenerator','AdvancedTokenGenerator', 'RestResponse'];
foreach ($classes as $class) {
    if (file_exists(APP_ROOT.'/classes/'.$class.'.class.php'))
        require_once(APP_ROOT.'/classes/'.$class.'.class.php');
}

if ($config['syslog']['enabled']) {
    openlog($config['syslog']['identifier'], LOG_PID, LOG_LOCAL0);
}
