<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$table = getDBPre()."_".$symbol."_".$peroid;

if($peroid==null || $symbol==null) {
    echo -1;
    die();
}

$targetid = REQUEST("TargetID");

//svm predict 1-s,2-k,3-d,4-z
$state=getSVMPredict($table,$targetid);

echo $state;