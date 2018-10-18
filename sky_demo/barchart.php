<?php
require_once("phpchartdir.php");
require_once("function.php");
$db = new MySQLi("localhost","root","","test");
$sql = "select * from xau_15";
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


# Create a XYChart object of size 250 x 250 pixels
$c = new XYChart(2000, 1010);

# Set the plotarea at (30, 20) and of size 200 x 200 pixels
$c->setPlotArea(0, 0, 2000, 1000);

# Add a bar chart layer using the given data
$barLayerObj=$c->addBarLayer($dataY1);
$barLayerObj->setBarGap(1);


# Set the labels on the x axis.
$c->xAxis->setLabels($times);


show_png($c,"test.png");
?>
