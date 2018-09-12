<?php

function cut_png($background, $cut_x, $cut_y, $cut_width, $cut_height, $location){
    $back=imagecreatefrompng($background);
    imagesavealpha($back,true);
    $new=imagecreatetruecolor($cut_width, $cut_height);
    imagealphablending($new,false);
    imagesavealpha($new,true);
    imagecopyresampled($new, $back, 0, 0, $cut_x, $cut_y, $cut_width, $cut_height,$cut_width,$cut_height);
    imagepng($new, $location);
    imagedestroy($new);
    imagedestroy($back);
}