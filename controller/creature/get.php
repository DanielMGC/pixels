<?php

function combineNames($name_array) {
    $name = "";


    $different_names = array();
    for ($i=0; $i < count($name_array); $i++) { 
        $exists = false;
        for ($j=0; $j < count($different_names); $j++) { 
            if($name_array[$i] == $different_names[$j]) {
                $exists = true;
            }
        }
        if(!$exists) {
            array_push($different_names, $name_array[$i]);
        }
    }

    if(count($different_names) == 1) {
        return $different_names[0];
    }

    for ($i=0; $i < count($different_names); $i++) { 
        $part = "";
        if(strlen($different_names[$i]) <= 2) {
            $part = $different_names[$i];
        }

        $size = strlen($different_names[$i]) / count($different_names);
        if($size < 2) {
            $size = 2;
        }
        $pos = $i * $size;
        if($pos + $size > strlen($different_names[$i])) {
            $pos = strlen($different_names[$i]) - $size;
        }

        $part = substr($different_names[$i], $pos, $size);

        $name .= $part;
    }

    $name = strtoupper(substr($name, 0, 1)) . strtolower(substr($name, 1));
    while(strpos($name, "  ") !== false) {
        $name = str_replace("  ", " ", $name);
    }

    return $name;
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/creature.php';
include_once '../objects/user.php';
include_once '../objects/part.php';
include_once '../objects/anchor.php';
include_once '../../php/crypto.php';

$database = new Database();
$db = $database->getConnection();

$Crypto = new Crypto();

$creature = new Creature($db);
$part = new Part($db);
$anchor = new Anchor($db);

$creature_item=array(
    "id" => -1,
    "name" => "MISSINGNO",
    "author" =>"UNKNOWN"
);
 
$part_array = array();

if(isset($_POST["code"])) {

    $val = $_POST["code"];

    if(!isset($_POST["decrypted"]) || $_POST["decrypted"] === false || $_POST["decrypted"] == "false") {
        $val = $Crypto->decrypt($val);
    } 
    
    $codeIds = explode(';', $val);

    if(count($codeIds) == 4) {

        $name_array = array();

        for ($i=0; $i < count($codeIds); $i++) { 

            $stmt = $creature->get($codeIds[$i]);
            
            $creature_row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            array_push($name_array, $creature_row["name"]);
        }

        $stmt3 = $part->getFromCreature($codeIds[0], "Head");
        if ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
            
        }

        $stmt3 = $part->getFromCreature($codeIds[1], "Body");
        if ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
        }

        $stmt3 = $part->getFromCreature($codeIds[2], "Arm");
        while ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
        }

        $stmt3 = $part->getFromCreature($codeIds[3], "Leg");
        while ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
        }

        $creature_item=array(
            "id" => -1,
            "name" => combineNames($name_array),
            "author" =>"RANDOM"
        );
    }

} else if(isset($_POST["id"])) {
    $getId = $_POST["id"];

    if($getId == "rand") {
        $name_array = array();

        $stmt2 = $creature->read((object)(array("approved" => 1)));
        $creature_array = array();
        while ($creature_row = $stmt2->fetch(PDO::FETCH_ASSOC)){
            array_push($creature_array, array("id" => $creature_row["id"], "name" => $creature_row["name"]));
        }

        $randPos = rand(0,count($creature_array) - 1);
        $rand_array = array_splice($creature_array, $randPos, 1);

        $stmt3 = $part->getFromCreature($rand_array[0]["id"], "Head");
        if ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
            array_push($name_array, $rand_array[0]["name"]);
        }

        $randPos = rand(0,count($creature_array) - 1);
        $rand_array = array_splice($creature_array, $randPos, 1);

        $stmt3 = $part->getFromCreature($rand_array[0]["id"], "Body");
        if ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
            array_push($name_array, $rand_array[0]["name"]);
        }

        $randPos = rand(0,count($creature_array) - 1);
        $rand_array = array_splice($creature_array, $randPos, 1);

        $stmt3 = $part->getFromCreature($rand_array[0]["id"], "Arm");
        while ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
        }
        array_push($name_array, $rand_array[0]["name"]);

        $randPos = rand(0,count($creature_array) - 1);
        $rand_array = array_splice($creature_array, $randPos, 1);

        $stmt3 = $part->getFromCreature($rand_array[0]["id"], "Leg");
        while ($part_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
            array_push($part_array, $part_row);
        }
        array_push($name_array, $rand_array[0]["name"]);

        $creature_item=array(
            "id" => -1,
            "name" => combineNames($name_array),
            "author" =>"RANDOM"
        );
    } else {

        $stmt = $creature->get($getId);

        $creature_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $creature_item=array(
            "id" => $creature_row["id"],
            "name" => $creature_row["name"],
            "author" =>$creature_row["username"],
            "authorId" =>$creature_row["author"],
            "authorEmail" =>$creature_row["email"],
            "approved" =>$creature_row["approved"]
        );

        $stmt2 = $part->getFromCreature($getId);
        $num = $stmt->rowCount();

        if($num>0){
            while ($part_row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                array_push($part_array, $part_row);
            }
        }
    }
}
    
if(count($part_array) > 0) {
 
    $parts = array();
    $codeIdArray = array(0,0,0,0);

    for($p = 0; $p < count($part_array); $p++) {
        
        $anchors = array();

        $stmt3 = $anchor->getFromPart($part_array[$p]["id"]);
        $num = $stmt3->rowCount();
    
        if($num>0){
            
            while ($anchor_row = $stmt3->fetch(PDO::FETCH_ASSOC)){
                $anchors[$anchor_row["type"]] = array("id" => $anchor_row["id"], "column" => $anchor_row["col"], "row" => $anchor_row["row"]);
            }

        }
        $rows = explode("*", $part_array[$p]["pixels"]);
        $colors = array();
        for ($i=0; $i < count($rows); $i++) { 
            array_push($colors, explode(";", $rows[$i]));
        }
        $parts[$part_array[$p]["type"]] = array("id" => $part_array[$p]["id"], "colors" => $colors, "anchors" => $anchors);
        if($part_array[$p]["type"] == "Head") {
            $codeIdArray[0] = $part_array[$p]["creature"];
        } else if($part_array[$p]["type"] == "Body") {
            $codeIdArray[1] = $part_array[$p]["creature"];
        } else if(strpos($part_array[$p]["type"], "Arm")) {
            $codeIdArray[2] = $part_array[$p]["creature"];
        } else if(strpos($part_array[$p]["type"], "Leg")) {
            $codeIdArray[3] = $part_array[$p]["creature"];
        } 
    }

    $creature_item["parts"] = $parts;

    $creature_item["code"] = $Crypto->encrypt($codeIdArray[0] . ";" . $codeIdArray[1] . ";" . $codeIdArray[2] . ";" . $codeIdArray[3]);

    $creature_item["creaturesIds"] = $codeIdArray;
}

if(isset($_POST["index"])) {
    $creature_item["index"] = $_POST["index"];
}

header("HTTP/1.1 200 OK");

echo json_encode($creature_item);

?>