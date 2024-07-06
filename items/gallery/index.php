<?php

if( !isset($_GET["i"]) ){ die(); }

header('content-type:image/jpeg');

$watermark = imagecreatefrompng('../waternark.png');

$photo = imagecreatefromjpeg($_GET["i"]);

// center watermark on the photo

$wx = imagesx($photo)/2 - imagesx($watermark)/2;
$wy = imagesy($photo)/2 - imagesy($watermark)/2;


imagecopy($photo, $watermark, $wx, $wy, 0, 0, imagesx($watermark), imagesy($watermark));

imagejpeg($photo, NULL, 100);
