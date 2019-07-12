<?php
ini_set('date.timezone','Asia/Shanghai');
require_once("phpchartdir.php");
require_once("OsMA.php");
require_once("ac.php");
require_once("fisher.php");
require_once("stoch.php");
require_once("ichimoku.php");
require_once("bands.php");
require_once("sar.php");
if(isOnWindows()) {
    define("PATHSEP", "\\");
} else {
    define("PATHSEP", "/");
 }
 
 if(!function_exists("array2string")) {
    function array2string($array,$split=':')
    {
        $string = [];
         if($array && is_array($array)){
            foreach ($array as $key=> $value){
                $string[] = $key.$split.$value;
            }
        }
        return implode(',',$string);
    }
}
 
if(!function_exists("getAccountOrder")) {
    function getAccountOrder($table,$account)
    {
        $db = getDBConn();
        $sql = "select *  from ".$table."_order where state<>2 account=".$account;
        $result = $db->query($sql);
        $arr=[];
        if($result){
            $arr = fetchArray($result);
            $arr = $arr[0];
        }
        return $arr;
    }
}

if(!function_exists("getSVMPredict")) {
    function getSVMPredict($dt,$modelfile='/model/model.linear.svm')
    {
        $res="";
        $MinValue=min($dt);
        $MaxValue=max($dt);
        $lower=-1;
        $upper=1;
        $data=[];
        foreach ($dt as $kl => $vl) {
            $one = $lower+($upper-$lower)*($vl-$MinValue)/($MaxValue-$MinValue);
            $data[$kl+1]=$one;
        }
        
        $model = new SVMModel();
        $model->load(dirname(__FILE__) . $modelfile);
        $class = $model->predict($data);
    
        return $class;
    }
}

if(!function_exists("getTargetById")) {
    function getTargetById($table,$id)
    {
        $db = getDBConn();
        $sql = "select *  from ".$table." where id=".$id;
        $result = $db->query($sql);
        $arr=[];
        if($result){
            $tmp = fetchArray($result);
            $arr = count($tmp)<=0?[]:array_shift($tmp);
        }
        return $arr;
    }
}
 
if(!function_exists("getStrategyByZ")) {
    function getStrategyByZ($aTarget)
    {
        $fisher = new CRAVIFisher(explode('|', $aTarget['fisher']));
        $stoch = new CStoch(json_decode($aTarget['stoch'],true));
        $ac = new CAC(explode('|', $aTarget['ac']));
        $osma = new COsMA(explode('|', $aTarget['osma']));
        $bands = new CBands(json_decode($aTarget['bands'],true));
        //1-b,2-s
        $action=0;
        //sl
        $slprice=0;
        if( $stoch->riseup() && 
            $fisher->riseup() && $fisher->istrend() && 
            $ac->riseup() && 
            $osma->riseup()) {
            $action=1;
            $slprice=$bands->getMinValue();
        } else if($stoch->fulldown() && 
                $fisher->fulldown() &&  $fisher->istrend() && 
                $ac->fulldown() && 
                $osma->fulldown()) {
            $action=2;
            $slprice=$bands->getMaxValue();
        }
        return ['action'=>$action,'sl'=>$slprice];
    }
}
 
 
if(!function_exists("getStrategyByD")) {
    function getStrategyByD($aTarget)
    {
        $sar = new CSar(explode('|', $aTarget['sar']));
        $ichimoku = new CIchimoku(json_decode($aTarget['ichimoku'],true),json_decode($aTarget['price'],true));
        //1-b,2-s
        $action=0;
        //sl
        $slprice=$sar->slprice();
        if($ichimoku->riseup_tks()) {
            $action=1;
        } else if($ichimoku->fulldown_tks()) {
            $action=2;
        }
        return ['action'=>$action,'sl'=>$slprice];
    }
}
 
 
if(!function_exists("getStrategyByK")) {
    function getStrategyByK($aTarget)
    {
        $fisher = new CRAVIFisher(explode('|', $aTarget['fisher']));
        $stoch = new CStoch(json_decode($aTarget['stoch'],true));
        $ac = new CAC(explode('|', $aTarget['ac']));
        $osma = new COsMA(explode('|', $aTarget['osma']));
        $bands = new CBands(json_decode($aTarget['bands'],true));
        //1-b,2-s
        $action=0;
        //sl
        $slprice=0;
        if( $stoch->riseup() && 
            $fisher->riseup() && $fisher->istrend() && 
            $ac->riseup() && 
            $osma->riseup()) {
            $action=1;
            $slprice=$bands->getMinValue();
        } else if($stoch->fulldown() && 
                $fisher->fulldown() &&  $fisher->istrend() && 
                $ac->fulldown() && 
                $osma->fulldown()) {
            $action=2;
            $slprice=$bands->getMaxValue();
        }
        return ['action'=>$action,'sl'=>$slprice];
    }
}
 
if(!function_exists("getStrategyByS")) {
    function getStrategyByS($aTarget)
    {
        $fisher = new CRAVIFisher(explode('|', $aTarget['fisher']));
        $stoch = new CStoch(json_decode($aTarget['stoch'],true));
        $ac = new CAC(explode('|', $aTarget['ac']));
        $osma = new COsMA(explode('|', $aTarget['osma']));
        $bands = new CBands(json_decode($aTarget['bands'],true));
        //1-b,2-s
        $action=0;
        //sl
        $slprice=0;
        if( $stoch->riseup() && 
            $fisher->riseup() && $fisher->istrend() && 
            $ac->riseup() && 
            $osma->riseup()) {
            $action=1;
            $slprice=$bands->getMinValue();
        } else if($stoch->fulldown() && 
                $fisher->fulldown() &&  $fisher->istrend() && 
                $ac->fulldown() && 
                $osma->fulldown()) {
            $action=2;
            $slprice=$bands->getMaxValue();
        }
        return ['action'=>$action,'sl'=>$slprice];
    }
}
 
if(!function_exists("fetchArray")) {
    function fetchArray($dt)
    {
        $query=[];
        while ($row = mysqli_fetch_array($dt,MYSQLI_ASSOC)) {
            $query[]=$row;
        }
        return $query;
    }
}

if(!function_exists("REQUEST")) {
    function REQUEST($data,$param,$default=null)
    {
        return empty($data[$param])?$default:$data[$param];
    }
}

if(!function_exists("getDBPre")) {
    function getDBPre()
    {
        return "at";
    }
}

if(!function_exists("getDBConn")) {
    function getDBConn()
    {
        return new MySQLi("localhost","root","123456","test");
    }
}

