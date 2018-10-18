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
$times_count=[];
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
    
    $hi=date("H",strtotime($end)).'.'.date("i",strtotime($end));
    $times[]=$hi;
    if(array_key_exists($hi,$times_count)) {
        $times_count[$hi]+=1;
    }
    else {
        $times_count[$hi]=1;
    }
    $labels[]=$i++;
    
}

$c = new XYChart(2000, 960);


$c->setPlotArea(30, 0, 2000, 920, -1, -1, 0xc0c0c0, 0xc0c0c0, -1);


//$legendObj = $c->addLegend(50, 30, false, "timesbi.ttf", 5);
//$legendObj->setBackground(Transparent);


//$c->yAxis->setTitle("Length", "arialbi.ttf", 8);
//
//
//$c->xAxis->setTitle("SN", "arialbi.ttf", 8);

//$c->xAxis->setWidth(1);
//$c->yAxis->setWidth(1);


//$c->addScatterLayer($labels, $dataY0, "TIME LENGTH", DiamondSymbol, 5, 0xff9933);
//$c->addScatterLayer($labels, $dataY1, "PRICE WIDTH", TriangleSymbol, 5, 0x33ff33);


//$c->addScatterLayer($times, $dataY0, "TIME LENGTH", DiamondSymbol, 5, 0x33ff33);
$c->addScatterLayer($times, $dataY1, "PRICE WIDTH", TriangleSymbol, 3, 0xDC143C);


show_png($c,"test.png");
?>
