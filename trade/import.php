<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$gmttime = REQUEST("GMTTime");
$localtime = REQUEST("LocalTime");
$table = getDBPre()."_".$symbol."_".$peroid;

if($peroid==null || $symbol==null) {
    echo -1;
    die();
}

//price
$highData = REQUEST("High");
$lowData = REQUEST("Low");
$openData = REQUEST("Open");
$closeData = REQUEST("Close");
if($highData==null || $lowData==null) {
    echo -1;
    die();
}
$data_price=array("open"=>$openData,"close"=>$closeData,"high"=>$highData,"low"=>$lowData);
$json_price= json_encode($data_price);

//bands
$bandslowData = REQUEST("BandsLower");
$bandmainData = REQUEST("BandsMain");
$bandupperData = REQUEST("BandsUpper");
if($highData!==null || $lowData!==null) {
    echo -1;
    die();
}
$data_bands=array("lower"=>$bandslowData,"main"=>$bandmainData,"upper"=>$bandupperData);
$json_bands= json_encode($data_bands);


//obv
$obvData = REQUEST("OBV");

//ac
$json_ac="";

//stoch
$json_stoch="";

//sar
$json_sar="";

//fisher
$json_fisher="";

try {
    $db=getDBConn();
    $result=$db->query("SHOW TABLES LIKE '". $table."'");
    //判断表是否存在，不存在就创建
    if(mysqli_num_rows($result)!==1)
    {
        $sql = "CREATE TABLE ".$table." (".
        "id INT(11) NOT NULL AUTO_INCREMENT,".
        "timegmt VARCHAR(30) NOT NULL,".
        "timelocal VARCHAR(30) NOT NULL,".
        "price TEXT,".
        "bands TEXT,".
        "obv TEXT,".
        "ac TEXT,".
        "stoch TEXT,".
        "sar TEXT,".
        "fisher TEXT,".
        "PRIMARY KEY (id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if($db->query($sql)===FALSE) {
            echo -2;
            die();
            //die('数据表创建失败: ' . mysqli_error($db));
        }
    }

    //INSERT
    $nTargetId=0;
    $sBulkString="('".$gmttime."','".$localtime."','".$json_price."','".$obvData."','".$json_bands."','".$json_ac."','".$json_stoch."','".$json_sar."','".$json_fisher."')";
    $sql="insert into ".$table." (timegmt,timelocal,price,obv,bands,ac,stoch,sar,fisher) values".$sBulkString;
    if($db->query($sql)===FALSE) {
        echo -3;
        die();
        //die('数据插入失败: ' . mysqli_error($db));
    }
    $nTargetId=mysqli_insert_id($db);
} catch (Exception $e) {
    $nTargetId=-4;
}

echo $nTargetId;