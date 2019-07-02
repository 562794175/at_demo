<?php
ini_set('date.timezone','Asia/Shanghai');
//require_once("phpchartdir.php");
//if(isOnWindows()) {
//    define("PATHSEP", "\\");
//} else {
//    define("PATHSEP", "/");
// }
// 
if(!function_exists("bulkInsertRecord")) {
    function bulkInsertRecord($sBulkString)
    {
        $db=getDBConn();
        $sql="insert into xau_bolling (lower,main,upper,learn_type,human_type) values".$sBulkString;
        $db->query($sql);
    }
}
 
if(!function_exists("insertRecord")) {
    function insertRecord($sLower,$sMain,$sUpper)
    {
        $db=getDBConn();
        $sql="insert into xau_bolling (lower,main,upper,learn_type,human_type) values('".$sLower."','".$sMain."','".$sUpper."',0,0)";
        $db->query($sql);
    }
}
 
if(!function_exists("showPng")) {
     function showPng($aBolling)
     {
        $aPngs=[];
        $nPeroid=20;
        foreach ($aBolling['l'] as $key => $value) {
            if(count($aBolling['l'])<($key+$nPeroid)) break;
            $aTmp['l']=array_slice($aBolling['l'],$key,$nPeroid);
            $aTmp['m']=array_slice($aBolling['m'],$key,$nPeroid);
            $aTmp['u']=array_slice($aBolling['u'],$key,$nPeroid);
            $aPngs[]=getBollingPng($aTmp,100,100);
        }
        $sTable="<table><tr>";
        foreach ($aPngs as $key => $value) {
            $sTable.="<td><img src='".$value."'></td>";
        }
        $sTable.="</tr></table>";
        echo $sTable;
     }
 }

if(!function_exists("getBollingPng")) {
    function getBollingPng($abolling,$width=50,$height=50)
    {
        $c = new XYChart($width, $height+10);
        $plotAreaObj =$c->setPlotArea(0, 0, $width, $height);
        $plotAreaObj->setGridColor(Transparent, Transparent);
        $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
        $c->yAxis()->setColors(Transparent, Transparent);
        //$layer = $c->addCandleStickLayer($data['h'],$data['l'], $data['o'], $data['c'], 0xFFFFFF, 0x000000);
        //$layer->setLineWidth(2);
        if(!empty($abolling)) {
            $c->addLineLayer($abolling['l']);
            $c->addLineLayer($abolling['m']);
            $c->addLineLayer($abolling['u']);
        }
        $filename=rand(5, 999).time()."_".rand(5, 999).".png";
        $realpath=realpath('.')."".PATHSEP."png".PATHSEP.$filename;
        $c->makeChart($realpath);
        cutPng($realpath, 0, 0, $width, $height, $realpath);
        $sitepath="png".PATHSEP.$filename;
        return $sitepath;
    }
}

if(!function_exists("cutPng")) {
    function cutPng($background, $cut_x, $cut_y, $cut_width, $cut_height, $location){
        $back=imagecreatefrompng($background);
        imagesavealpha($back,true);
        $new=imagecreatetruecolor($cut_width, $cut_height);
        imagealphablending($new,false);
        imagesavealpha($new,true);
        imagecopyresampled($new, $back, 0, 0, $cut_x, $cut_y, $cut_width, $cut_height,$cut_width,$cut_height);
        imagepng($new, $location);
        imagedestroy($new);
        imagedestroy($back);
    }
}


if(!function_exists("getRecordOnlyOne")) {
    function getRecordOnlyOne($nId,$aField=['*'])
    {
        $db=getDBConn();
        $sField=implode(',',$aField);
        $sql="select ".$sField." from xau_15 where id=".$nId;
        $dt=$db->query($sql);
        //只要取一个
        return mysqli_fetch_array($dt,MYSQLI_ASSOC);
    }
}
if(!function_exists("getRecordList")) {
    function getRecordList($sWhere,$aField=['*'])
    {
        $db=getDBConn();
        $sField=implode(',',$aField);
        $sql="select ".$sField." from xau_15 where ".$sWhere;
        $dt=$db->query($sql);
        //取全部
        return fetchArray($dt);
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

if(!function_exists("getREQUEST")) {
    function getREQUEST($param)
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

