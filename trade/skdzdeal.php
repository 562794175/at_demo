<?php
require_once 'function.php';
require_once 'log.php';
//$data["Peroid"]="1";
//$data["Symbol"]="eurusd";
//$data["TargetID"]=9;
//$data["Account"]=933;
//$data["State"]=3;
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
        "ordernum" => 0,
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
    } else {
        return -2;
    }
    skdzlog($data,array2string($aEvent));
    
    $aOrder = getAccountOrder($table,$account);
    //有订单，单边状态下，没有突破转折线，并且止损价格不等于最新的止损价格
    if(!empty($aOrder) && $state==3 && $aEvent['action']==0 && $aOrder['sl']!==$aEvent['sl']) {
        $aEvent['action']=$aOrder['action'];
        $aEvent['operate']=3;
        $aEvent['ordernum']=$aOrder['ordernum'];
    //有订单, skz状态下，策略ACTION跟订单ACTION不相等时平仓
    } else if(!empty($aOrder) && $state!==3 && $aOrder['action']!=$aEvent['action']) {
        $aEvent['action']=$aOrder['action'];
        $aEvent['operate']=2;
        $aEvent['ordernum']=$aOrder['ordernum'];
    //有订单，skdz状态下，策略ACTION跟订单ACTION相等时没有操作
    } else if(!empty($aOrder) && $aOrder['action']==$aEvent['action']) {
        $aEvent['operate']=0;
        $aEvent['ordernum']=$aOrder['ordernum'];
    //无订单，skdz状态下，策略ACTION有值时开仓
    } else if(empty($aOrder) && $aEvent['action']!=0) {
        $aEvent['operate']=1;
    }   
    skdzlog($data,array2string($aEvent));
    return $aEvent;
}

function skdzlog($data,$msg)
{
    $data['Msg']=$msg;
    taskLog($data);
}