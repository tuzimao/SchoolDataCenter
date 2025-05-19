<?php
function generateTicket() {
    return 'ST-' . md5(uniqid(rand(), true));
}

function saveTicket($ticket, $username) {
    file_put_contents(__DIR__ . "/tickets/$ticket", $username);
}

function validateTicket($ticket) {
    $file = __DIR__ . "/tickets/$ticket";
    if (file_exists($file)) {
        $username = file_get_contents($file);
        unlink($file); // 一次性票据
        return $username;
    }
    return false;
}

?>