<?php
header("Content-type: image/gif");

$image = imagecreatefromgif('images/AdHound_Logo.gif');
$color = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 0, 14, 3, "v1.5", $color);
imagegif($image, 'images/AdHound_LogoV.gif');
imagedestroy($image);
?>