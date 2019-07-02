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
$json_price="";
if($highData==null || $lowData==null) {
    $data_price=array("open"=>$openData,"close"=>$closeData,"high"=>$highData,"low"=>$lowData);
    $json_price= json_encode($data_price);
}


//obv
$obvData = REQUEST("OBV");

//bands
$bandslowData = REQUEST("BandsLower");
$bandmainData = REQUEST("BandsMain");
$bandupperData = REQUEST("BandsUpper");
$json_bands="";
if($highData==null || $lowData==null) {
    $data_bands=array("lower"=>$bandslowData,"main"=>$bandmainData,"upper"=>$bandupperData);
    $json_bands= json_encode($data_bands);
}

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
        $sql = "CREATE TABLE ".$table." (
        id INT(11) UNSIGNED AUTO_INCREMENT,
        gmttime VARCHAR(30) NOT NULL,
        localtime VARCHAR(30) NOT NULL,
        price text,
        bands text,
        ac text,
        sar text,
        fisher text,
        PRIMARY KEY (id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if($db->query($sql)===TRUE) {
            echo -2;die();
        }
    }

    //INSERT
    $nTargetId=0;
    $sBulkString="('".$gmttime."','".$localtime."','".$json_price."','".$obvData."','".$json_bands."','".$json_ac."','".$json_stoch."','".$json_sar."','".$json_fisher."')";
    $sql="insert into ".$table." (gmttime,localtime,price,obv,bands,ac,stoch,sar,fisher) values".$sBulkString;
    $db->query($sql);
    $nTargetId=mysqli_insert_id($db);
} catch (Exception $e) {
    $nTargetId=-3;
}

echo $nTargetId;