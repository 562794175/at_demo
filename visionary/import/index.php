<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$gmttime = REQUEST("GMTTime");
$localtime = REQUEST("LocalTime");
$table = getDBPre()."_".$symbol."_".$peroid;

//price
$highData = REQUEST("High");
$lowData = REQUEST("Low");
$openData = REQUEST("Open");
$closeData = REQUEST("Close");
$data_price=array("open"=>$openData,"close"=>$closeData,"high"=>$highData,"low"=>$lowData);
$json_price= json_encode($data_price);

//obv
$obvData = REQUEST("OBV");

//bands
$bandslowData = REQUEST("BandsLower");
$bandmainData = REQUEST("BandsMain");
$bandupperData = REQUEST("BandsUpper");
$data_bands=array("lower"=>$bandslowData,"main"=>$bandmainData,"upper"=>$bandupperData);
$json_bands= json_encode($data_bands);

//ac
$json_ac="";

//stoch
$json_stoch="";

//sar
$json_sar="";

//fisher
$json_fisher="";

if(empty($peroid) || empty($symbol))
{
    echo -1;
}

try {
    $db=getDBConn();
    //判断表是否存在，不存在就创建
    if(mysqli_num_rows(mysqli_query("SHOW TABLES LIKE '". $table."'")!==1))
    {
        $sql = "CREATE TABLE ".$table." (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        gmttime VARCHAR(30) NOT NULL,
        localtime VARCHAR(30) NOT NULL,
        price text,
        bands text,
        ac text,
        sar text,
        fisher text
        )";
        $db->query($sql);
    }

    //INSERT
    $nTargetId=0;
    $sBulkString="('".$time."','".$json_price."','".$obvData."','".$json_bands."','".$json_ac."','".$json_stoch."','".$json_sar."','".$json_fisher."')";
    $sql="insert into ".$table." (gmttime,localtime,price,obv,bands,ac,stoch,sar,fisher) values".$sBulkString;
    $db->query($sql);
    $nTargetId=mysqli_insert_id($db);
} catch (Exception $e) {
    $nTargetId=-1;
}

echo $nTargetId;