<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
session_start();

// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// get posted data
$data = (object) $_POST;
 
// make sure data is not empty
if(
    !empty($data->username) &&
    !empty($data->password) &&
    !empty($data->email)
){
 
    // set product property values
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;
 
    // create the product
    $userId = $user->create();
    if($userId > 0){
 
        $_SESSION["username"] = $data->username;
        $_SESSION["admin"] = 0;
        $_SESSION["userid"] = $userId;

        header("HTTP/1.1 200 OK");
 
        // tell the user
        echo json_encode(array("message" => "User was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        //http_response_code(503);
        header('HTTP/1.1 503 Service Temporarily Unavailable');
 
        // tell the user
        echo json_encode(array("message" => "Unable to create user."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    //http_response_code(400);
    header('HTTP/1.1 400 Bad Request');
 
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete." . $_POST["username"]));
}
?>