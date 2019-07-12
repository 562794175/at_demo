<?php
require_once 'function.php';
require_once 'bands.php';
//$data["Peroid"]="1";
//$data["Symbol"]="eurusd";
//$data["TargetID"]=2;
//$state=taskPredict($data);

function taskPredict($data) {
    //info
    $peroid = REQUEST($data,"Peroid");
    $symbol = REQUEST($data,"Symbol");
    $table = getDBPre()."_".$symbol."_".$peroid;
    if($peroid==null || $symbol==null) {
        return -1;
    }
    $targetid = REQUEST($data,"TargetID");
    $state = 0;
    //bands
    $bandslowData = REQUEST($data,"BandsLower");
    $bandmainData = REQUEST($data,"BandsMain");
    $bandupperData = REQUEST($data,"BandsUpper");
    
    if($bandslowData!=null && $bandmainData!=null && $bandupperData!=null) {    
        
        $bands = new CBands(null,$bandslowData,$bandmainData,$bandupperData);
        $state = $bands->predictState();
        
    } else if($targetid!=null) {
        
        $aTarget = getTargetById($table,$targetid);
        
        if(!empty($aTarget)) {
            $dt=json_decode($aTarget['bands'],true);
            $bands = new CBands($dt);
            $state = $bands->predictState();
        }
        
        
    }
    return $state;
}