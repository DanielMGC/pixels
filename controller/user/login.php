<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
 
include_once '../config/database.php';
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();

$filter = (object) $_POST;
 
$user = new User($db);

$user_found = null;
 
$stmt = $user->read($filter);
$num = $stmt->rowCount();

$result = false;
 
if($num>0){
 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    $user_found = array(
        "id" => $id,
        "username" => $username,
        "email" => $email,
        "admin" => $admin
    );

    $_SESSION["username"] = $username;
    $_SESSION["admin"] = $admin;
    $_SESSION["userid"] = $id;
    $result = true;
}
 
else{
 
    $_SESSION["username"] = null;
    unset($_SESSION["username"]);

    $_SESSION["admin"] = null;
    unset($_SESSION["admin"]);

    $_SESSION["userid"] = null;
    unset($_SESSION["userid"]);
}

header("HTTP/1.1 200 OK");

echo json_encode(array("user" => $user_found, "result" => $result));
?>