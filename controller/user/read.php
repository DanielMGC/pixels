<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../config/database.php';
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();

$filter = (object) $_POST;
 
$user = new User($db);
 
$stmt = $user->read($filter);
$num = $stmt->rowCount();
 

$users_arr = array();
$users_arr["records"] = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $user_item=array(
        "id" => $id,
        "username" => $username,
        "admin" =>$admin,
        "email" => $email
    );

    array_push($users_arr["records"], $user_item);
}

header("HTTP/1.1 200 OK");

echo json_encode($users_arr);

?>