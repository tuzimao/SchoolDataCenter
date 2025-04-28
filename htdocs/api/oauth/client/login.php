<?php
$params = http_build_query([
    'response_type' => 'code',
    'client_id' => 'd37d1c43f4cbe10548f80d755c18752f',
    'redirect_uri' => 'http://localhost/api/oauth/client/callback.php',
    'state' => 'xyz'
]);


header('Location: http://localhost/api/oauth/authorize.php?' . $params);
exit;
