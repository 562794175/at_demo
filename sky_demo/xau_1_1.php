<?php

require_once("phpchartdir.php");
require_once("function.php");

$filename=realpath('.')."\\png\\".time().".png";

$highData = explode(",", $_POST["High"]);

$lowData = explode(",", $_POST["Low"]);

$openData = explode(",", $_POST["Open"]);

$closeData = explode(",", $_POST["Close"]);

$c = new XYChart(500, 510);

# Set the plotarea at (50, 25) and of size 500 x 250 pixels. Enable both the horizontal and vertical
# grids by setting their colors to grey (0xc0c0c0)
$plotAreaObj = $c->setPlotArea(0, 0, 500, 500);
$plotAreaObj->setGridColor(Transparent, Transparent);
$plotAreaObj->setBackground(Transparent, Transparent, Transparent);
$c->yAxis()->setColors(Transparent, Transparent);
$layer = $c->addCandleStickLayer($highData, $lowData, $openData, $closeData, 0x00ff00, 0xff0000);
$layer->setLineWidth(2);

# Output the chart
header("Content-type: image/png");
$c->makeChart2(PNG);
$c->getDrawArea()->outPNG($filename);

cut_png($filename, 0, 0, 500, 500, $filename);

echo $filename;