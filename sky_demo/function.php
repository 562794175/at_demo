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

function show_price_png($id,$peroid,$data_json) {
    $filename=$id."_".$peroid."_price.png";
    $realpath=realpath('.')."\\png\\price\\".$filename;
    $sitepath="png/price/".$filename;
    if(file_exists($sitepath)) return $sitepath;
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

function show_price_close_png($id,$peroid,$data_json) {
    $filename=$id."_".$peroid."_price_close.png";
    $realpath=realpath('.')."\\png\\price\\".$filename;
    $sitepath="png/price/".$filename;
    if(file_exists($sitepath)) return $sitepath;
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

function show_price_bands_png($id,$peroid,$data_json,$price_json) {
    
    $filename=$id."_".$peroid."_price_bands.png";
    $realpath=realpath('.')."\\png\\bands\\".$filename;
    $sitepath="png/bands/".$filename;
    if(file_exists($sitepath)) return $sitepath;
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

function show_price_close_bands_png($id,$peroid,$data_json,$price_json) {
    $filename=$id."_".$peroid."_price_close_bands.png";
    $realpath=realpath('.')."\\png\\bands\\".$filename;
    $sitepath="png/bands/".$filename;
    if(file_exists($sitepath)) return $sitepath;
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

function show_obv_png($id,$peroid,$string_data) {
    $filename=$id."_".$peroid."_obv.png";
    $realpath=realpath('.')."\\png\\obv\\".$filename;
    $sitepath="png/obv/".$filename;
    if(file_exists($sitepath)) return $sitepath;
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