<?php
require_once("phpchartdir.php");
require_once("function.php");
require_once("page.class.php");
//$db = new MySQLi("localhost","root","123456","test");
//
//$sqlall = "select count(*) from xau_15";
//$resultall = $db->query($sqlall);
//$arr1 = $resultall->fetch_row();
//$c = $arr1[0];
//$page = new page($c,100);
//$sql = "select * from xau_15 ".$page->limit;
//$result = $db->query($sql);
//$arr = $result->fetch_all();
//$dataY0=[];
//$dataY1=[];
//$labels=[];
//$times=[];
//$i=1;
//foreach($arr as $v){
//    $id=$v[0];
//    $timeframe=$v[1];
//    $begin=$v[2];
//    $end=$v[3];
//    $data_price=$v[4];
//    $data_volume=$v[5];
//    $data_obv=$v[6];
//    $data_rsi=$v[7];
//    $data_bolling=$v[8];
//    $dataY0[]=time_length($data_obv);
//    $dataY1[]=price_width($data_price);
//
//    $times[]=date("H",strtotime($end)).'.'.date("i",strtotime($end));
//    $labels[]=$i++;
//}
//echo "<div align='center'>".$page->fpage()."</div>";//显示分页信息
# The data for the line chart


//$dataY = array(30, 28, 40, 55, 75, 68, 54, 60, 50, 62, 75, 65, 75, 91, 60, 55, 53, 35, 50, 66, 56,
//    48, 52, 65, 62);

//$dataY = array(23, 25, 28, 29, 36, 35, 37, 39, 42, 40, 41, 45, 39, 32, 40, 45, 46, 44, 47, 48, 50,
//    48, 52, 65, 62);
//
//$dataY = array(23, 25, 28, 29, 36, 35, 37, 39, 42, 40, 41, 45, 46, 47, 48, 49, 52, 53, 58, 59, 60,
//    61, 64, 65, 62);

//$dataX = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
//    21, 22, 23, 24);


$dataY = array(23, 28, 29);

$dataX = array(1, 2, 3);

# The XY data of the first data series
//$dataX = array(50, 55, 37, 24, 42, 49, 63, 72, 83, 59);
//$dataY = array(3.6, 2.8, 2.5, 2.3, 3.8, 3.0, 3.8, 5.0, 6.0, 3.3);

# Create a XYChart object of size 450 x 420 pixels
$c = new XYChart(450, 420, 0xFF000000 );

# Set the plotarea at (55, 65) and of size 350 x 300 pixels, with white background and a light grey
# border (0xc0c0c0). Turn on both horizontal and vertical grid lines with light grey color
# (0xc0c0c0)
$c->setPlotArea(55, 65, 350, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);

# Add a title to the chart using 18 point Times Bold Itatic font.
$c->addTitle("Server Performance", "timesbi.ttf", 18);

# Add titles to the axes using 12pt Arial Bold Italic font
$c->yAxis->setTitle("Response Time (sec)", "arialbi.ttf", 12);
$c->xAxis->setTitle("Server Load (TPS)", "arialbi.ttf", 12);

# Set the axes line width to 3 pixels
$c->yAxis->setWidth(3);
$c->xAxis->setWidth(3);

# Add a scatter layer using (dataX, dataY)
$c->addScatterLayer($dataX, $dataY, "", DiamondSymbol, 11, 0x008000);

# Add a trend line layer for (dataX, dataY)
$trendLayer = $c->addTrendLayer2($dataX, $dataY, 0x008000);

# Set the line width to 3 pixels
$trendLayer->setLineWidth(3);

# Add a 95% confidence band for the line
$trendLayer->addConfidenceBand(0.95, 0x806666ff);

# Add a 95% confidence band (prediction band) for the points
$trendLayer->addPredictionBand(0.95, 0x8066ff66);

# Add a legend box at (50, 30) (top of the chart) with horizontal layout. Use 10pt Arial Bold Italic
# font. Set the background and border color to Transparent.
$legendBox = $c->addLegend(50, 30, false, "arialbi.ttf", 10);
$legendBox->setBackground(Transparent);

# Add entries to the legend box
$legendBox->addKey("95% Line Confidence", 0x806666ff);
$legendBox->addKey("95% Point Confidence", 0x8066ff66);

# Display the trend line parameters as a text table formatted using CDML
$textbox = $c->addText(56, 65, sprintf(
    "<*block*>Slope\nIntercept\nCorrelation\nStd Error<*/*>   <*block*>%.4f sec/tps\n%.4f sec\n".
    "%.4f\n%.4f sec<*/*>", $trendLayer->getSlope(), $trendLayer->getIntercept(),
    $trendLayer->getCorrelation(), $trendLayer->getStdError()), "arialbd.ttf", 8);

# Set the background of the text box to light grey, with a black border, and 1 pixel 3D border
$textbox->setBackground(0xc0c0c0, 0, 1);



show_png($c,"test.png");
?>