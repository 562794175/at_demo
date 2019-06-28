<?php
set_time_limit(0);
require_once 'function.php';
$db = getDBConn();
$sql = "select count(*) from xau_bolling where human_type>0";
$result = $db->query($sql);
$arr1 = $result->fetch_row();
$count = $arr1[0];//用一个变量获取这个数组的值
$pages=ceil($count/100);
$aDis=[];
G('t');
//统计每个分页的正确率
for($i=0;$i<$pages;$i++) {
    $aRight=getRightRate($i);
  
    $aDis["class"][]=$aRight[0];
    $aDis["key"][]=$aRight[1];
    $aDis["labels"][]=$i+1;
}
G('t','r');
$data0=$aDis["class"];
$data1=$aDis["key"];
$labels=$aDis["labels"];

$width=1500;
$height=800;
G('m');
$c = new XYChart($width, $height+10);
$c->addTitle("     SVM Right Rate", "", 10);
$c->setPlotArea(50, 25, 1420, 740, 0xffffc0, 0xffffe0);
$legendObj = $c->addLegend(55, 18, false, "", 8);
$legendObj->setBackground(Transparent);
$c->xAxis->setLabels($labels);

# Add a multi-bar layer with 3 data sets and 3 pixels 3D depth
$layer = $c->addBarLayer2(Side, 3);
$layer->addDataSet($data0, 0xff8080, "Class #1");
$layer->addDataSet($data1, 0x80ff80, "Key #2");

$filename=time().".png";
$realpath=realpath('.')."".PATHSEP.$filename;
$sitepath=$filename;
$c->makeChart($realpath);
cutPng($realpath, 0, 0, $width, $height, $realpath);

echo "<img src='".$sitepath."'>";
G('m','i');