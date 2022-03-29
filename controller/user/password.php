<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
 
include_once '../config/database.php';
include_once '../objects/user.php';

include_once '../../php/mailer.php';
 
$database = new Database();
$db = $database->getConnection();

$filter = (object) $_POST;
 
$user = new User($db);

$user_found = null;
$emailOk = false;
 
$stmt = $user->read($filter);
$num = $stmt->rowCount();

$mailer = new Mailer();

if($num==1){
 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    $user_found = array(
        "id" => $id,
        "username" => $username,
        "email" => $email,
        "admin" => $admin
    );

    $html = $mailer->GetTemplatePassword();
    $html = str_replace("[USERNAME]", $username, $html);
    $html = str_replace("[PASSWORD]", $password, $html);
    $emailOk  = $mailer->SendMail("Pixel Creatures - Login information", $html, $email);

}
 

header("HTTP/1.1 200 OK");

echo json_encode(array("user" => $user_found, "emailOk" => $emailOk));
?>