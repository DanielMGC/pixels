<?php

require "../php/AnimGif.php";


$im = imagecreatefrompng("m1.png");
$comp = imagecreatetruecolor(96, 96);
imagealphablending($comp, true);
imagesavealpha($comp, true);
$transparent = imagecolorallocatealpha( $comp, 0, 0, 0, 127 );
imagefill( $comp, 0, 0, $transparent ); 
imagecopy($comp, $im, 0, 0, 0, 0, 96, 96);

imagecolortransparent($comp, $transparent);

$im2 = imagecreatefrompng("m2.png");
$comp2 = imagecreatetruecolor(96, 96);
imagealphablending($comp2, true);
imagesavealpha($comp2, true);
$transparent2 = imagecolorallocatealpha( $comp2, 0, 0, 0, 127 );
imagefill( $comp2, 0, 0, $transparent2 ); 
imagecopy($comp2, $im2, 0, 0, 0, 0, 96, 96);

imagecolortransparent($comp2, $transparent2);

$im3 = imagecreatefrompng("m3.png");
$comp3 = imagecreatetruecolor(96, 96);
imagealphablending($comp3, true);
imagesavealpha($comp3, true);
$transparent3 = imagecolorallocatealpha( $comp3, 0, 0, 0, 127 );
imagefill( $comp3, 0, 0, $transparent3 ); 
imagecopy($comp3, $im3, 0, 0, 0, 0, 96, 96);

imagecolortransparent($comp3, $transparent3);

$frames = array(
    $comp,$comp2,$comp3
);

// Create an array containing the duration (in millisecond) of each frames (in order too)
$durations = array(40, 40, 40);

// Initialize and create the GIF !
$gc = new GifCreator\AnimGif();
$gc->create($frames, $durations);

$gifBinary = $gc->get();

$gc->save('ani.gif');




?>