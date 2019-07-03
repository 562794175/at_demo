<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$gmttime = REQUEST("GMTTime");
$localtime = REQUEST("LocalTime");
$table = getDBPre()."_".$symbol."_".$peroid;
$targetid = REQUEST("TargetID");
$account = REQUEST("Account");
$state = REQUEST("State");
if($peroid==null || $symbol==null || $targetid==null) {
    echo -1;
    die();
}
//1-b,2-s
$action=0;
//0-null,1-open,2-close,3-sl
$operate=0;
//B0,S0,B1,S1,B2,S2,B3,S3
//0,10,20,11,21,12,22,13,23

$aTarget=getTargetById($table,$targetid);
if($state==1){
    $action = getStrategyByS($aTarget);
} else if($state==2) {
    $action = getStrategyByK($aTarget);
} else if($state==3) {
    $action = getStrategyByD($aTarget);
} else if($state==4) {
    $action = getStrategyByZ($aTarget);
}

$aOrder = getAccountOrder($table,$account);
if(!empty($aOrder) && $aOrder['action']==$action && $state==3) {
    //if a acount had an order same action then return 0
    $operate=3;
} else if(!empty($aOrder) && $aOrder['action']==$action) {
    $operate=0;
} else if(!empty($aOrder) && $aOrder['action']!=$action) {
    $operate=2;
} else {
    $operate=1;
}
echo $action.$operate;