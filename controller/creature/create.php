<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/user.php';
include_once '../objects/creature.php';
include_once '../objects/part.php';
include_once '../objects/anchor.php';

include_once '../../php/mailer.php';
 
$database = new Database();
$db = $database->getConnection();
 
$creature = new Creature($db);

$mailer = new Mailer();
 
// get posted data
$data = (object) $_POST["creature"];
 
// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->author)
){
 
    // set product property values
    $creature->name = $data->name;
    $creature->author = (object)$data->author;
    $creature->parts = array();

    foreach ($data->parts as $key => $value) {
        $part = new Part($db);
        $part->type = $key;
        $part->pixels = "";
        $part->anchors = array();
        for ($i=0; $i < count($value["colors"]); $i++) { 
            for ($j=0; $j < count($value["colors"][$i]); $j++) { 
                $part->pixels .= $value["colors"][$i][$j];
                if($j < count($value["colors"][$i]) - 1) {
                    $part->pixels .= ";";
                }
            }
            if($i < count($value["colors"]) - 1) {
                $part->pixels .= "*";
            }
        }
        
        foreach ($value["anchors"] as $anchorKey => $anchorValue) {

            $anchor = new Anchor($db);
            $anchor->type = $anchorKey;
            $anchor->col = $anchorValue["column"];
            $anchor->row = $anchorValue["row"];
            array_push($part->anchors, $anchor);
        }
        array_push($creature->parts, $part);
    }

    // create the product
    $newId = $creature->create();
    if($newId > 0){

        //$html = $mailer->GetTemplateCreated();
        //$html = str_replace("[NAME]", $data->name, $html);
        //$html = str_replace("[ID]", $newId, $html);
        //$ok = $mailer->SendMail("New Pixel Creature created!", $html, "daniel.moises.gc@gmail.com");
 
        // set response code - 201 created
        //http_response_code(201);
        header("HTTP/1.1 200 OK");
 
        // tell the user
        echo json_encode(array("message" => "Creature was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        //http_response_code(503);
        header('HTTP/1.1 503 Service Temporarily Unavailable');
 
        // tell the user
        echo json_encode(array("message" => "Unable to create creature."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    //http_response_code(400);
    header('HTTP/1.1 400 Bad Request');
 
    // tell the user
    echo json_encode(array("message" => "Unable to create creature. Data is incomplete."));
}
?>