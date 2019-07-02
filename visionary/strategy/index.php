<?php
require_once 'function.php';
//info
$peroid = $_POST["Peroid"];
$symbol = $_POST["Symbol"];
$gmttime = $_POST["GMTTime"];
$localtime = $_POST["LocalTime"];
$table = $symbol."_".$peroid;
$account = $_POST["Account"];

$targetid = $_POST["TargetID"];

//0-null,1-b,2-s
$action=0;

//if aacount had order then return
$order = getAccountOrder($account,$peroid);
if($order>0) {
    echo $action;
    die();
}

//svm predict 1-s,2-k,3-d,4-z
$state=getSVMPredict($table,$targetid);

if($state==1)
{
    $action = getStrategyByS($targetid);
} else if($state==2) {
    $action = getStrategyByK($targetid);
} else if($state==3) {
    $action = getStrategyByD($targetid);
} else if($state==4) {
    $action = getStrategyByZ($targetid);
}
echo $action;