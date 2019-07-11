<?php
ini_set('date.timezone','Asia/Shanghai');
require_once("phpchartdir.php");
if(isOnWindows()) {
    define("PATHSEP", "\\");
} else {
    define("PATHSEP", "/");
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
        $astoch= json_decode($aTarget['stoch'],true);
        $astochmain= explode('|', $astoch['StochMain']);
        $astochsignal= explode('|', $astoch['StochSIGNAL']);
        $aac= explode('|', $aTarget['ac']);
        $aosma= explode('|', $aTarget['osma']);
        array_pop($astochmain);
        array_pop($astochsignal);
        array_pop($aac);
        array_pop($aosma);
        //1-b,2-s
        $action=0;
        if(end($astochmain)>end($astochsignal) && 
            end($astochmain) < 80 &&     
            $aac[count($aac)-1]>$aac[count($aac)-2] && 
            $aosma[count($aosma)-1]>$aosma[count($aosma)-2] ) {
            $action=1;
        } else if(end($astochmain)<end($astochsignal) && 
                end($astochmain) > 20 && 
                $aac[count($aac)-1]<$aac[count($aac)-2] && 
                $aosma[count($aosma)-1]<$aosma[count($aosma)-2]) {
            $action=2;
        }
        return $action;
    }
}
 
 
if(!function_exists("getStrategyByD")) {
    function getStrategyByD($aTarget)
    {
        $aac= explode('|', $aTarget['ac']);
        $aosma= explode('|', $aTarget['osma']);
        array_pop($aac);
        array_pop($aosma);
        //1-b,2-s
        $action=0;
        if($aac[count($aac)-1]>$aac[count($aac)-2] && 
            $aosma[count($aosma)-1]>$aosma[count($aosma)-2]) {
            $action=1;
        } else if($aac[count($aac)-1]<$aac[count($aac)-2] && 
                $aosma[count($aosma)-1]<$aosma[count($aosma)-2]) {
            $action=2;
        }
        return 0;
    }
}
 
 
if(!function_exists("getStrategyByK")) {
    function getStrategyByK($aTarget)
    {
        $astoch= json_decode($aTarget['stoch'],true);
        $astochmain= explode('|', $astoch['StochMain']);
        $astochsignal= explode('|', $astoch['StochSIGNAL']);
        $aac= explode('|', $aTarget['ac']);
        $aosma= explode('|', $aTarget['osma']);
        array_pop($astochmain);
        array_pop($astochsignal);
        array_pop($aac);
        array_pop($aosma);
        //1-b,2-s
        $action=0;
        if(end($astochmain)>end($astochsignal) && 
            $aac[count($aac)-1]>$aac[count($aac)-2] && 
            $aosma[count($aosma)-1]>$aosma[count($aosma)-2] ) {
            $action=1;
        } else if(end($astochmain)<end($astochsignal) && 
                $aac[count($aac)-1]<$aac[count($aac)-2] && 
                $aosma[count($aosma)-1]<$aosma[count($aosma)-2]) {
            $action=2;
        }
        return $action;
    }
}
 
if(!function_exists("getStrategyByS")) {
    function getStrategyByS($aTarget)
    {
        //$afisher= explode('|', $aTarget['fisher']);
        $astoch= json_decode($aTarget['stoch'],true);
        $astochmain= explode('|', $astoch['StochMain']);
        $astochsignal= explode('|', $astoch['StochSIGNAL']);
        $aac= explode('|', $aTarget['ac']);
        $aosma= explode('|', $aTarget['osma']);
        array_pop($astochmain);
        array_pop($astochsignal);
        array_pop($aac);
        array_pop($aosma);
        //1-b,2-s
        $action=0;
        if( end($astochmain)>end($astochsignal) && 
            $aac[count($aac)-1]>$aac[count($aac)-2] && 
            $aosma[count($aosma)-1]>$aosma[count($aosma)-2] ) {
            $action=1;
        } else if(end($astochmain)<end($astochsignal) && 
                $aac[count($aac)-1]<$aac[count($aac)-2] && 
                $aosma[count($aosma)-1]<$aosma[count($aosma)-2]) {
            $action=2;
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

