<?php
$params = http_build_query([
    'response_type' => 'code',
    'client_id' => 'f4fd703bbd1582e689b5311840db55ed',
    'redirect_uri' => 'http://localhost/api/oauth/client/callback.php',
    'state' => 'xyz'
]);


header('Location: http://localhost/api/oauth/authorize.php?' . $params);
exit;
