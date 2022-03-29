<?php

function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
    
    for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
        for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
            $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);

    return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
} 

header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
//header('Content-Type: image/gif');

require "../php/AnimGif.php";

$file = "";

if (count($_POST) && isset($_POST['images'])) {
    
    $id = $_POST["id"];
    $name = $_POST["name"];

    $frames = array();
    $durations = array();

    for ($i=0; $i < count($_POST["images"]); $i++) { 
        $img = $_POST['images'][$i];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = 'uploads/temp/img'.$id.'-'.$i.'.png';
        file_put_contents($file, $data);

        $im = imagecreatefrompng($file);
        $comp = imagecreatetruecolor(160, 200);
        imagealphablending($comp, true);
        imagesavealpha($comp, true);
        $transparent = imagecolorallocatealpha( $comp, 0, 0, 0, 127 );
        imagefill($comp, 0, 0, $transparent ); 
        imagecopyresampled($comp, $im, 0, 0, 0, 0, 160, 160, imagesx($im), imagesy($im));

        $box = imagettfbbox(15, 0, dirname(__FILE__) . "/Geo-Regular.ttf", $name);
        $width = $box[2] - $box[0];

        $posX = (160 - $width)/2;

        $textcolor = imagecolorallocate($comp, 255, 255, 255);
        $strokecolor = imagecolorallocate($comp, 0, 0, 0);
        imagettfstroketext($comp, 15, 0, $posX, 170, $textcolor, $strokecolor, dirname(__FILE__) . "/Geo-Regular.ttf", $name, 2);
        
        imagettftext($comp, 10, 0, 5, 190, $textcolor, dirname(__FILE__) . "/Geo-Regular.ttf", "Pixel Creatures by DanielMGC");

        imagecolortransparent($comp, $transparent);

        array_push($frames, $comp);
        array_push($durations, 5);

        unlink($file);
    }

    // Initialize and create the GIF !
    $gc = new GifCreator\AnimGif();
    $gc->create($frames, $durations);
    
    //$gifBinary = $gc->get();

    $file = 'uploads/'.$name.'.gif';

    $gc->save($file);

    if($_POST["mode"] == "download") {
    
        $gc->save($file);
        header('Content-Description: File Transfer');
        header('Content-Type: image/gif');
        header("Content-Disposition: attachment; filename=" . $name.".gif");
        header("Content-Length: " . filesize($file));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        flush(); 
        readfile($file);
    } else {
        header("HTTP/1.1 200 OK");
        
        //echo json_encode(array("url" => "http://entelodonte/daniel/teste/pixels/export/" . $file));
        echo json_encode(array("url" => "https://thebob.com.br/pixels/export/" . $file));
    }

    //echo $gifBinary;
    
} else {
    header('HTTP/1.1 400 Bad Request');
    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode(array("message" => "Error creating the file."));
}
?>