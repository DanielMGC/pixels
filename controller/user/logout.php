<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
 
$_SESSION["username"] = null;
unset($_SESSION["username"]);

$_SESSION["admin"] = null;
unset($_SESSION["admin"]);

$_SESSION["userid"] = null;
unset($_SESSION["userid"]);

header("HTTP/1.1 200 OK");

echo json_encode(array("result" => true));

?>