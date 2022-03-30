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

include_once '../../php/crypto.php';
 
$database = new Database();
$db = $database->getConnection();
 
$creature = new Creature($db);

$mailer = new Mailer();

$Crypto = new Crypto();
 
// get posted data
$data = (object) $_POST["creature"];
$sendMailApproved = false;

if(isset($data->newApproved) && $data->newApproved == "true" || $data->newApproved == true) {
    $sendMailApproved = true;
}
 
// make sure data is not empty
if(
    !empty($data->name)
){
 
    // set product property values
    $creature->id = $data->id;
    $creature->name = $data->name;
    $creature->approved = $data->approved;
    $creature->parts = array();

    foreach ($data->parts as $key => $value) {
        $part = new Part($db);
        $part->id = $value["id"];
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
            $anchor->id = $anchorValue["id"];
            $anchor->type = $anchorKey;
            $anchor->col = $anchorValue["column"];
            $anchor->row = $anchorValue["row"];
            array_push($part->anchors, $anchor);
        }
        array_push($creature->parts, $part);
    }

    // create the product
    if($creature->update()){
        /*if($sendMailApproved) {
            if($creature->approved > 0) {
                $code = $Crypto->encrypt($data->id . ";" . $data->id . ";" . $data->id . ";" . $data->id);

                $html = $mailer->GetTemplateApproved();
                $html = str_replace("[NAME]", $data->name, $html);
                $html = str_replace("[CODE]", $code, $html);
                $ok = $mailer->SendMail("Pixel Creature approved!", $html, $data->authorEmail);
            } else if($creature->approved < 0) {

                $html = $mailer->GetTemplateRejected($creature->approved);
                $html = str_replace("[NAME]", $data->name, $html);
                $html = str_replace("[ID]", $data->id, $html);
                $ok = $mailer->SendMail("Pixel Creature rejected", $html, $data->authorEmail);
            }
        }*/
 
        // set response code - 201 created
        //http_response_code(201);
        header("HTTP/1.1 200 OK");
 
        // tell the user
        echo json_encode(array("message" => "Creature was updated."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        //http_response_code(503);
        header('HTTP/1.1 503 Service Temporarily Unavailable');
 
        // tell the user
        echo json_encode(array("message" => "Unable to update creature."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    //http_response_code(400);
    header('HTTP/1.1 400 Bad Request');
 
    // tell the user
    echo json_encode(array("message" => "Unable to update creature. Data is incomplete."));
}
?>