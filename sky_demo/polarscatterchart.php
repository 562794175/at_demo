<?php
require_once("phpchartdir.php");
require_once("function.php");
require_once("page.class.php");
$db = new MySQLi("localhost","root","123456","test");

$sqlall = "select count(*) from xau_15";
$resultall = $db->query($sqlall);
$arr1 = $resultall->fetch_row();
$c = $arr1[0];
$page = new page($c,100);
$sql = "select * from xau_15 ".$page->limit;
$result = $db->query($sql);
$arr = $result->fetch_all();
$dataY0=[];
$dataY1=[];
$labels=[];
$times=[];
$i=1;
foreach($arr as $v){
    $id=$v[0];
    $timeframe=$v[1];
    $begin=$v[2];
    $end=$v[3];
    $data_price=$v[4];
    $data_volume=$v[5];
    $data_obv=$v[6];
    $data_rsi=$v[7];
    $data_bolling=$v[8];
    $dataY0[]=time_length($data_obv);
    $dataY1[]=price_width($data_price);
    
    
    $times[]=date("H",strtotime($end)).'.'.date("i",strtotime($end));
    $labels[]=$i++;
    
}
echo "<div align='center'>".$page->fpage()."</div>";//显示分页信息



$labels = array("North", "North\nEast", "East", "South\nEast", "South", "South\nWest", "West",
    "North\nWest");


# Create a PolarChart object of size 460 x 460 pixels
$c = new PolarChart(460, 460);


# Set center of plot area at (230, 240) with radius 180 pixels
$c->setPlotArea(230, 240, 180);

# Use alternative light grey/dark grey sector background color
$c->setPlotAreaBg(0xdddddd, 0xeeeeee, false);

# Set the grid style to circular grid
$c->setGridStyle(false);


# Set angular axis as 0 - 360, either 8 spokes
//$c->angularAxis->setLinearScale(0, 24, 1);

$c->angularAxis->setLinearScale(0, 50, 1);

//
//# Add a blue (0xff) polar line layer to the chart using (data0, angle0)
//$layer0 = $c->addLineLayer($dataY0, 0x0000ff, "Immortal Weed");
//$layer0->setAngles($times);
//
//$layer0->setLineWidth(0);
//$layer0->setDataSymbol(TriangleSymbol, 5);

# Add a red (0xff0000) polar line layer to the chart using (data1, angles1)
$layer1 = $c->addLineLayer($dataY1, 0xff0000, "Precious Flower");
$layer1->setAngles($dataY0);

# Disable the line by setting its width to 0, so only the symbols are visible
$layer1->setLineWidth(0);
$layer1->setDataSymbol(DiamondSymbol, 5);

# Output the chart
show_png($c,"test.png");
?>
