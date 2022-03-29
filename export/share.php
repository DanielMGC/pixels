<?php

header("Access-Control-Allow-Origin: *");

$file = 'uploads/'.$_GET["f"];

if (isset($_GET['f']) && file_exists($file)) {
    
    header('Content-Description: File Transfer');
    header('Content-Type: image/gif');
    header("Content-Disposition: attachment; filename=" . $_GET["f"]);
    header("Content-Length: " . filesize($file));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    flush(); 
    readfile($file);

   
    
} else {
    header('HTTP/1.1 400 Bad Request');
    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode(array("message" => "File not found."));
}
?>