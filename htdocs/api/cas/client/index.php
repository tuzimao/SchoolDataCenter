<?php
session_start();


$CAS_CLIENT = "http://localhost:8888/api/cas/client";
$CAS_REDIRECT = "http://localhost:3000/cas?service=".$CAS_CLIENT;
$CAS_VALIDATE = "http://localhost:8888/api/cas/validate.php";

if($_GET['ticket'] == '')  {
    header('Location: ' . $CAS_REDIRECT);
}
else {
    //验证Ticket
    $ticket = $_GET['ticket'];
    $URL    = $CAS_VALIDATE."?service=".$CAS_CLIENT."&ticket=".$ticket;
    $Content = file_get_contents($URL);
    $Array = json_decode($Content, true);
    print "用户信息:<BR>";
    print_R($Array);
    exit;    
}


?>