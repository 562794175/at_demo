<?php
ini_set('date.timezone','Asia/Shanghai');
require_once("phpchartdir.php");
if(isOnWindows()) {
    define("PATHSEP", "\\");
} else {
    define("PATHSEP", "/");
 }
 
 if(!function_exists("logInsert")) {
    function logInsert($table,$account,$gmttime,$localtime,$targetid,$message)
    {
        //INSERT
        $sBulkString="('".$gmttime."','".$localtime."','".$account."',".$targetid.",".time().",'".$message."')";
        $sql="insert into ".$table." (timegmt,timelocal,account,targetid,created,message) values".$sBulkString;
        $db->query($sql);
        return mysqli_insert_id($db);
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
            $arr = fetchArray($result);
            $arr = $arr[0];
        }
        return $arr;
    }
}
 
if(!function_exists("getStrategyByZ")) {
    function getStrategyByZ($aTarget)
    {
        //fisher 
        //stoch
        //ac
        return 0;
    }
}
 
 
if(!function_exists("getStrategyByD")) {
    function getStrategyByD($aTarget)
    {
        //sar
        $asar= explode(',', $aTarget['sar']);
        $aac= explode(',', $aTarget['ac']);
        //1-b,2-s
        $action=0;
        if($aac[count($aac)-1]>$aac[count($aac)-2]) {
            $action=1;
        } else {
            $action=2;
        }
        return 0;
    }
}
 
 
if(!function_exists("getStrategyByK")) {
    function getStrategyByK($aTarget)
    {
        //fisher 
        //stoch
        //ac
        return 0;
    }
}
 
if(!function_exists("getStrategyByS")) {
    function getStrategyByS($aTarget)
    {
        $afisher= explode(',', $aTarget['fisher']);
        $astoch= json_decode($aTarget['stoch'],true);
        $astochl= explode(',', $astoch['low']);
        $astochq= explode(',', $astoch['quik']);
        $aac= explode(',', $aTarget['ac']);
        //1-b,2-s
        $action=0;
        //fisher last element bigger then 70 or smaller then -70
        $fisher=end($afisher);
        if($fisher>70 || $fisher<-70) {
            //stoch && ac
            if($astochl>$astochq && $aac[count($aac)-1]>$aac[count($aac)-2]) {
                $action=1;
            } else {
                $action=2;
            }
        }
        
        return $action;
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
    function REQUEST($param,$default=null)
    {
        return empty($_REQUEST[$param])?$default:$_REQUEST[$param];
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

