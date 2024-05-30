<?php

require_once('../init.php');

/**
 * @var array $config
 */


try {
    $URL = "https://$_SERVER[HTTP_HOST]/redirectWithToken?$_SERVER[QUERY_STRING]";

    $script = <<<JS
    <html>
    <head>
    <script type="text/javascript">
        window.onload = function() {
            window.parent.location.href = "{$URL}"
        }
    </script>
    </head>
    </html>
    JS;

    header('Content-Type: text/html');
    echo $script;
    return;
} catch (Exception $e){
    error_log($e->getMessage());
    RestResponse::send($e->getMessage(), 500);
    return;
}




