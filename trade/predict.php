<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$table = getDBPre()."_".$symbol."_".$peroid;
$targetid = REQUEST("TargetID");
if($peroid==null || $symbol==null || $targetid==null) {
    echo -1;
    die();
}

$db = getDBConn();
$sql = "select *  from ".$table." where id=".$targetid;
$result = $db->query($sql);
$state=0;
if($result){
    $arr = $result->fetch_all();
    foreach($arr as $k => $v){
        $id=$v[0];
        $bands=json_decode($v[4],true);
        $alower= explode(',', $bands['lower']);
        $amain = explode(',', $bands['main']);
        $aupper= explode(',', $bands['upper']);
        //预测
        $atmp= array_merge($alower,$amain);
        $atmp= array_merge($atmp,$aupper);
        //svm predict 1-s,2-k,3-d,4-z
        $state=getSVMPredict($atmp,'/model/model.linear.svm');
    }
}

echo $state;