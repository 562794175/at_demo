<?php
ini_set('date.timezone','Asia/Shanghai');
if(!function_exists("cut_png")) {
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
}


if(!function_exists("getPng")) {
    function getPng() {
        $noOfDays = 100;
        $extraDays = 30;
        $rantable = new RanTable(9, 6, $noOfDays + $extraDays);
        $rantable->setDateCol(0, chartTime(2011, 9, 4), 86400, true);
        $rantable->setHLOCCols(1, 100, -5, 5);
        $rantable->setCol(5, 50000000, 250000000);
        $timeStamps = $rantable->getCol(0);
        $highData = $rantable->getCol(1);
        $lowData = $rantable->getCol(2);
        $openData = $rantable->getCol(3);
        $closeData = $rantable->getCol(4);
        $volData = $rantable->getCol(5);
        $c = new FinanceChart(350);
        $c->setMargins(0,0,1,9);
        $c->setLegendStyle("normal", 2, Transparent, Transparent);
        $c->setData($timeStamps, $highData, $lowData, $openData, $closeData, $volData, $extraDays);
        //$c->yAxis->setColors(Transparent, Transparent);



        $c->addMainChart(200);
        $layer=$c->addCandleStick(0x00ff00, 0xff0000);
        $layer->setBorderColor(Transparent, softLighting(Left));
        //$c->addBollingerBand(20, 2, 0x9999ff, 0xc06666ff);
        $realpath=realpath('.')."\\test.png";
        $c->makeChart($realpath);
        cut_png($realpath,1,1,348,199,$realpath);
        return "test.png";
    }
}