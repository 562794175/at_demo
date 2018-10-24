<?php
require_once("phpchartdir.php");
require_once("function.php");

$id=$_GET["id"];
$sn=$_GET["sn"];
$db = new MySQLi("localhost","root","123456","test");
$sql = "select * from xau_sample where orgin_id=".$id;
$result = $db->query($sql);
$arr = $result->fetch_all();
$line_arr= json_decode($arr[0][4]);
$line=$line_arr[$sn-1];
$filename1="sample_close_simple_".$sn;
$filename2="sample_close_simple_1_".$sn;
$chart_width=$chart_height=100;

$c = new XYChart($chart_width, $chart_height+10, 0xFF000000);
$c->setPlotArea(0, 0, $chart_width, $chart_height, 0xffffff, -1, 0xFF000000, 0xFF000000, -1);
$c->yAxis()->setWidth(0);
$c->addAreaLayer($line);
show_png($c,$filename1);

$chart_width=$chart_height=300;
$offset=0;
$line=[];
$c = new XYChart($chart_width, $chart_height+10, 0xFF000000);
$c->setPlotArea(0, 0, $chart_width, $chart_height, 0xffffff, -1, 0xFF000000, 0xFF000000, -1);
$c->yAxis()->setWidth(0);
for($i=0;$i<count($line_arr);$i++) {
    $tmp_line=$line_arr[$i];
    $size=count($tmp_line);
    for($n=0;$n<$size;$n++) {
        $line[$offset]=$tmp_line[$n];
        $offset++;
    }
    $offset--;
    $c->addAreaLayer($line);
}
show_png($c,$filename2);

$back = "<a href='xau_sample.php?page=".$id."'>back</a>";
$head = "<a href='xau_sample.php'>sample</a> - <a href='xau_complex.php'>complex</a>";
echo "<div align='center'>".$head."<br>".$back."</div>";