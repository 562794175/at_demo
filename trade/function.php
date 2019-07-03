<?php
ini_set('date.timezone','Asia/Shanghai');
require_once("phpchartdir.php");
if(isOnWindows()) {
    define("PATHSEP", "\\");
} else {
    define("PATHSEP", "/");
 }
 
if(!function_exists("getAccountOrder")) {
    function getAccountOrder($dt)
    {
        return 0;
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
 
 
if(!function_exists("getStrategyByZ")) {
    function getStrategyByZ($dt)
    {
        return 0;
    }
}
 
 
if(!function_exists("getStrategyByD")) {
    function getStrategyByD($dt)
    {
        return 0;
    }
}
 
 
if(!function_exists("getStrategyByK")) {
    function getStrategyByK($dt)
    {
        return 0;
    }
}
 
if(!function_exists("getStrategyByS")) {
    function getStrategyByS($dt)
    {
        return 0;
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
    function REQUEST($param)
    {
        return empty($_REQUEST[$param])?null:$_REQUEST[$param];
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

