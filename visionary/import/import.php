<?php
require_once 'function.php';
die();
ini_set('memory_limit','256M');
$aRecord=getRecordList('id<5000',['data_bolling']);
$aLower=$aMain=$aUpper=[];
foreach ($aRecord as $key => $value) {
    $sBolling=json_decode($value['data_bolling'],TRUE);
    array_pop($aLower);
    array_pop($aMain);
    array_pop($aUpper);
    $aLower=array_merge($aLower,explode(",", $sBolling["lower"]));
    $aMain=array_merge($aMain, explode(",", $sBolling["main"]));
    $aUpper=array_merge($aUpper, explode(",", $sBolling["upper"]));
}
$aBolling=['l'=>$aLower,'m'=>$aMain,'u'=>$aUpper];
//$png=getBollingPng($aBolling,1000,800);
//echo "<img src='".$png."'>";
$aBulk=[];
$nPeroid=20;
$nFlag=0;
foreach ($aBolling['l'] as $key => $value) 
{
    if(count($aBolling['l'])<($key+$nPeroid)) break;
    $nFlag++;
    $sLower=implode(',',array_slice($aBolling['l'],$key,$nPeroid));
    $sMain=implode(',',array_slice($aBolling['m'],$key,$nPeroid));
    $sUpper=implode(',',array_slice($aBolling['u'],$key,$nPeroid));
    $aBulk[]="('".$sLower."','".$sMain."','".$sUpper."',0,0)";
    if($nFlag>=5000) {
        $sBulkString = implode(',',$aBulk);
        bulkInsertRecord($sBulkString);
        $nFlag=0;
        unset($aBulk);
    }
}
if($aBulk) {
    $sBulkString = implode(',',$aBulk);
    bulkInsertRecord($sBulkString);
    unset($aBulk);
}



?>

