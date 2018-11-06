<?php

define("CONFIDENCE", 0.98);

if(isOnWindows()) {
    define("PATHSEP", "\\");
} else {
    define("PATHSEP", "/");
 }

function price_width($json_price) {
    $data_price = json_decode($json_price,TRUE);
    $highData = explode(",", $data_price["high"]);
    $lowData = explode(",", $data_price["low"]);
    $openData = explode(",", $data_price["open"]);
    $closeData = explode(",", $data_price["close"]);
    $max_high = max($highData);
    $min_low = min($lowData);
    $width = round($max_high-$min_low,2);
    return $width;
}

function time_length($data_obv){
    $arr_obv=explode(",",$data_obv);
    return count($arr_obv);
}



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

function show_price_png($id,$period,$data_json) {
    $filename=$id."_".$period."_price.png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."price".PATHSEP."".$filename;
    $sitepath="png".PATHSEP."price".PATHSEP."".$filename;
    if(file_exists($sitepath) && $period!="last") return $sitepath;
    $data_price = json_decode($data_json,TRUE);
    $highData = explode(",", $data_price["high"]);
    $lowData = explode(",", $data_price["low"]);
    $openData = explode(",", $data_price["open"]);
    $closeData = explode(",", $data_price["close"]);
    $c = new XYChart(100, 110);
    # Set the plotarea at (50, 25) and of size 500 x 250 pixels. Enable both the horizontal and vertical
    # grids by setting their colors to grey (0xc0c0c0)
    $plotAreaObj = $c->setPlotArea(0, 0, 100, 100);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $layer = $c->addCandleStickLayer($highData, $lowData, $openData, $closeData, 0x8B0000, 0x006400);
    $layer->setLineWidth(2);
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, 100, 100, $realpath);
    return $sitepath;
}

function show_price_close_png($id,$period,$data_json) {
    $filename=$id."_".$period."_price_close.png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."price".PATHSEP."".$filename;
    $sitepath="png".PATHSEP."price".PATHSEP."".$filename;
    if(file_exists($sitepath) && $period!="last") return $sitepath;
    $data_price = json_decode($data_json,TRUE);
    $closeData = explode(",", $data_price["close"]);
    $c = new XYChart(100, 110);
    $plotAreaObj =$c->setPlotArea(0, 0, 100, 100);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $c->addLineLayer($closeData);
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, 100, 100, $realpath);
    return $sitepath;
}

function show_price_bands_png($id,$period,$data_json,$price_json) {
    
    $filename=$id."_".$period."_price_bands.png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."bands".PATHSEP."".$filename;
    $sitepath="png".PATHSEP."bands".PATHSEP."".$filename;
    if(file_exists($sitepath) && $period!="last") return $sitepath;
    $data_bands = json_decode($data_json,TRUE);
    $lowerData = explode(",", $data_bands["lower"]);
    $mainData = explode(",", $data_bands["main"]);
    $upperData = explode(",", $data_bands["upper"]);
    
    $data_price = json_decode($price_json,TRUE);
    $highData = explode(",", $data_price["high"]);
    $lowData = explode(",", $data_price["low"]);
    $openData = explode(",", $data_price["open"]);
    $closeData = explode(",", $data_price["close"]);
    $c = new XYChart(100, 110);
    # Set the plotarea at (50, 25) and of size 500 x 250 pixels. Enable both the horizontal and vertical
    # grids by setting their colors to grey (0xc0c0c0)
    $plotAreaObj = $c->setPlotArea(0, 0, 100, 100);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $c->addLineLayer($lowerData);
    $c->addLineLayer($mainData);
    $c->addLineLayer($upperData);
    $layer = $c->addCandleStickLayer($highData, $lowData, $openData, $closeData, 0x8B0000, 0x006400);
    $layer->setLineWidth(2);
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, 100, 100, $realpath);
    return $sitepath;
}

function show_price_close_bands_png($id,$period,$data_json,$price_json) {
    $filename=$id."_".$period."_price_close_bands.png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."bands".PATHSEP."".$filename;
    $sitepath="png".PATHSEP."bands".PATHSEP."".$filename;
    if(file_exists($sitepath) && $period!="last") return $sitepath;
    $data_bands = json_decode($data_json,TRUE);
    $lowerData = explode(",", $data_bands["lower"]);
    $mainData = explode(",", $data_bands["main"]);
    $upperData = explode(",", $data_bands["upper"]);
    
    $data_price = json_decode($price_json,TRUE);
    $closeData = explode(",", $data_price["close"]);
    $c = new XYChart(100, 110);
    $plotAreaObj =$c->setPlotArea(0, 0, 100, 100);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $c->addLineLayer($closeData);
    $c->addLineLayer($lowerData);
    $c->addLineLayer($mainData);
    $c->addLineLayer($upperData);
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, 100, 100, $realpath);
    return $sitepath;
}

function show_obv_png($id,$period,$string_data) {
    $filename=$id."_".$period."_obv.png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."obv".PATHSEP."".$filename;
    $sitepath="png".PATHSEP."obv".PATHSEP."".$filename;
    if(file_exists($sitepath) && $period!="last") return $sitepath;
    $obvData = explode(",", $string_data);
    $c = new XYChart(100, 110);
    $plotAreaObj =$c->setPlotArea(0, 0, 100, 100);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $c->addLineLayer($obvData);
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, 100, 100, $realpath);
    return $sitepath;
}

function show_price_close_obv_png($id,$period,$obv_png,$price_close_bands_png) {
    $filename=$id."_".$period."_price_close_obv.png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."obv".PATHSEP."".$filename;
    $sitepath="png".PATHSEP."obv".PATHSEP."".$filename;
    if(file_exists($sitepath) && $period!="last") return $sitepath;
    $image_1 = imagecreatefrompng($price_close_bands_png);
    $image_2 = imagecreatefrompng($obv_png);
    $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
    setTransparency($image_3,$image_2); 
    imagecopymerge_alpha($image_1 ,$image_3, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), 0); 
    imagepng($image_1,$sitepath);
    imagedestroy($image_1);
    imagedestroy($image_2);
    imagedestroy($image_3);
    return $sitepath;
}

function show_png($c,$filename) {
    $realpath=realpath('.').PATHSEP.$filename;
    $sitepath="".$filename;
    $c->makeChart($realpath);
    $w = $c->getWidth();
    $h = $c->getHeight();
    cut_png($realpath, 0, 0, $w, $h-10, $realpath);
    return "<div align='center' ><img src='".$sitepath."'></div>";
}

function show_png_default($c,$filename) {
    $realpath=realpath('.').PATHSEP.$filename;
    $sitepath="".$filename;
    $c->makeChart($realpath);
    $w = $c->getWidth();
    $h = $c->getHeight();
    cut_png($realpath, 0, 0, $w, $h-10, $realpath);
    return "<img src='".$sitepath."'>";
}

function show_svm_simple_png($dataY,$sn,$attr,$orgin_id) {
    $filename="png".PATHSEP."svm".PATHSEP."sample_close_simple_".$sn."_".$attr."_".$orgin_id.".png";
    //return $filename;
    $chart_width=$chart_height=100;
   
    $line_list = getlinelist($dataY);
    
    $offset=0;
    $c = new XYChart($chart_width, $chart_height+10, 0xFF000000);
    $c->setPlotArea(0, 0, $chart_width, $chart_height, 0xffffff, -1, 0xFF000000, 0xFF000000, -1);
    $c->yAxis()->setWidth(0);
    $line=[];
    for($i=0;$i<count($line_list);$i++) {
        $size=count($line_list[$i]);
        for($n=0;$n<$size;$n++) {
            $line[$offset]=$line_list[$i][$n];
            $offset++;
        }
        $offset--;
        $c->addAreaLayer($line);
    }
    return show_png_default($c,$filename);
}

function getlinelist($dataY) {
    
    $line_list=[];
    $start_pos=0;
    $end_pos=count($dataY);
    while($start_pos<$end_pos) {
        $line=[];
        for($i=$start_pos;$i<$end_pos;$i++) {
            $correlation=0;
            $line[]=$dataY[$i];
            if(count($line)>=2) {
                $c = new XYChart(450, 420, 0xFF000000);
                $c->setPlotArea(55, 65, 350, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);
                $c->addLineLayer($line);
                $trendLayer = $c->addTrendLayer($line);
                $trendLayer->addConfidenceBand(CONFIDENCE,0x806666ff);
                $trendLayer->addPredictionBand(CONFIDENCE,0x8066ff66);
                $correlation = abs(round($trendLayer->getCorrelation(),2));
                unset($c);


                if($correlation>=CONFIDENCE) {
                    $start_pos= $i==$end_pos-1?$end_pos:$i;
                    if($start_pos==$end_pos) {
                        $line_list[] = $line;
                    }
                }else if(count($line)>2 ) {
                    $start_pos= $i-1;
                    array_pop( $line );
                    $line_list[] = $line;
                    break;
                }

            }//end if(count($line)>=2)

        }//end for($i=$start_pos;$i<$end_pos;$i++)
    }
    return $line_list;
}

function setTransparency($new_image,$image_source) 
{ 
    // make sure that the image will retain alpha when saved
    imagesavealpha($new_image, true);
    // fill with transparent pixels first or else you'll
    // get black instead of transparent
    imagefill($new_image, 0, 0, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
    $width = imagesx($image_source);
    $height = imagesy($image_source);
    // loop through all the pixels
    for ($y = 0; $y < $height; $y++)
    {
        for ($x = 0; $x < $width; $x++)
        {
            // get the current pixel's colour
            $currentColor = imagecolorat($image_source, $x, $y);

            // then break it into easily parsed bits
            $colorParts = imagecolorsforindex($image_source, $currentColor);

            // if it's NOT white
            if (!($colorParts['red'] == 255 &&
                $colorParts['green'] == 255 &&
                $colorParts['blue'] == 255))
            {
                // then keep the same colour
                imagesetpixel($new_image, $x, $y, $currentColor);
            }
        }
    }
} 

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    $opacity=$pct;
    // getting the watermark width
    $w = imagesx($src_im);
    // getting the watermark height
    $h = imagesy($src_im);

    // creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);
    // copying that section of the background to the cut
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    // inverting the opacity
    $opacity = 100 - $opacity;

    // placing the watermark now
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
    imagedestroy($cut);
}

