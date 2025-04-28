<?php
require_once('config.inc.php');

$params = http_build_query([
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'state' => 'xyz'
]);

header('Location: '.$authorize_uri.'?' . $params);
