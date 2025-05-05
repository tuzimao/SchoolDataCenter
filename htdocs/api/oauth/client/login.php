<?
//SchoolAI 统一身份认证 Demo程序
//2025-05-05

require_once('config.inc.php');

$params = http_build_query([ 'response_type' => 'code', 'client_id' => $client_id, 'redirect_uri' => $redirect_uri, 'state' => 'xyz' ]);

header('Location: '.$authorize_uri.'?' . $params);
