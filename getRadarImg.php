<?php

$file = file_get_contents('http://api.wunderground.com/api/ff3e23e766c6adcf/animatedradar/q/image.gif?centerlat='.htmlspecialchars($_GET["centerlat"]).'&centerlon='.htmlspecialchars($_GET["centerlon"]).'&radius=100&width=280&height=280&newmaps=1&num=6&delay=50&interval=30');
Header( "Content-Type: image/jpeg");
echo $file;
//imagejpeg($file);
?>