<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/creature.php';
include_once '../objects/user.php';
include_once '../objects/part.php';
include_once '../objects/anchor.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$filter = (object) $_POST;
 
// initialize object
$creature = new Creature($db);
$part = new Part($db);
$anchor = new Anchor($db);
 
// query products
$stmt = $creature->read($filter);
$num = $stmt->rowCount();
 

$creature_arr = array();
$creature_arr["records"] = array();

while ($creature_row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $creature_item=array(
        "id" => $creature_row["id"],
        "name" => $creature_row["name"],
        "author" =>$creature_row["username"],
        "authorId" =>$creature_row["author"],
        "authorEmail" =>$creature_row["email"],
        "approved" =>$creature_row["approved"]
    );

    $stmt2 = $part->getFromCreature($creature_row["id"]);
    $num = $stmt->rowCount();

    if($num>0){
        
        // products array
        $parts = array();

        while ($part_row = $stmt2->fetch(PDO::FETCH_ASSOC)){
            
            $anchors = array();

            $stmt3 = $anchor->getFromPart($part_row["id"]);
            $num = $stmt3->rowCount();
        
            if($num>0){
                
                while ($anchor_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
                    $anchors[$anchor_row["type"]] = array("id" => $anchor_row["id"], "column" => $anchor_row["col"], "row" => $anchor_row["row"]);
                }

            }
            $rows = explode("*", $part_row["pixels"]);
            $colors = array();
            for ($i=0; $i < count($rows); $i++) { 
                array_push($colors, explode(";", $rows[$i]));
            }
            //echo " -- " . $part_row["type"] . " : " . count($rows) . " -- ";
            if($part_row["type"] == "LeftArm") {
                //echo " -- " . $part_row["type"] . " : " . $part_row["pixels"] . " -- ";
            }
            $parts[$part_row["type"]] = array("id" => $part_row["id"], "colors" => $colors, "anchors" => $anchors);
        }


        $creature_item["parts"] = $parts;
    }

    array_push($creature_arr["records"], $creature_item);
}
// set response code - 200 OK
//http_response_code(200);
header("HTTP/1.1 200 OK");

// show products data in json format
echo json_encode($creature_arr);

?>