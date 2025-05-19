<?php
require 'utils.php';

$service = $_GET['service'] ?? '';
$ticket = $_GET['ticket'] ?? '';

if (!$ticket) {
    echo "no\n";
    exit;
}

$user = validateTicket($ticket);
if ($user) {
    echo "yes\n$user\n";
} else {
    echo "no\n";
}
