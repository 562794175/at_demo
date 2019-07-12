<?php
require_once 'function.php';

//$data["Peroid"]="1";
//$data["Symbol"]="eurusd";
//$data["TargetID"]=9;
//$data["Account"]=933;
//$data["State"]=2;
//$state=taskSKDZDeal($data);
function taskSKDZDeal($data) {
    //info
    $peroid = REQUEST($data,"Peroid");
    $symbol = REQUEST($data,"Symbol");
    $table = getDBPre()."_".$symbol."_".$peroid;
    $targetid = REQUEST($data,"TargetID");
    $account = REQUEST($data,"Account");
    $state = REQUEST($data,"State");
    if($peroid==null || $symbol==null || $targetid==null) {
        return -1;
    }
    $aEvent=[
        "action" => 0,//1-b,2-s
        "sl" => 0,
        "operate" => 0,//0-null,1-open,2-close,3-sl
    ];
    $aTarget=getTargetById($table,$targetid);
    if($state==1 && $aTarget){
        $aEvent = getStrategyByS($aTarget);
    } else if($state==2 && $aTarget) {
        $aEvent = getStrategyByK($aTarget);
    } else if($state==3 && $aTarget) {
        $aEvent = getStrategyByD($aTarget);
    } else if($state==4 && $aTarget) {
        $aEvent = getStrategyByZ($aTarget);
    }

    $aOrder = getAccountOrder($table,$account);
    if(!empty($aOrder) && $aOrder['sltime']==$action && $state==3) {
        //if a acount had an order same action then return 0
        $aEvent['operate']=3;
    } else if(!empty($aOrder) && $aOrder['action']==$action) {
        $aEvent['operate']=0;
    } else if(!empty($aOrder) && $aOrder['action']!=$action) {
        $aEvent['action']=$aOrder['action'];
        $aEvent['operate']=2;
    } else {
        $aEvent['operate']=1;
    }
    return $aEvent;
}