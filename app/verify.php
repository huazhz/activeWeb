<?php
header ( 'content-type:image/jpeg' );

session_start();
$image = imagecreatetruecolor ( 50, 20 );
$white = imagecolorallocate ( $image, 255, 255, 255 );
$black = imagecolorallocate ( $image, 0, 0, 0 );
imagefill ( $image, 0, 0, $white );
$rnd=mt_rand ( 1000, 9999 );
imagestring ( $image, 5, 8, 4, $rnd, $black );
imagejpeg ( $image );
imagedestroy ( $image );

$_SESSION['rnd']=$rnd;
