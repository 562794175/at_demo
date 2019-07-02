<?php
require_once 'function.php';
//info
$peroid = $_POST["Peroid"];
$symbol = $_POST["Symbol"];
$time = $_POST["Time"];
$table = $symbol."_".$peroid."_order";
$account = $_POST["Account"];

$order = $_POST["OrderNum"];

$targetid = $_POST["TargetID"];

$action = $_POST["Action"];

//save
try {
    $db=getDBConn();
    //判断表是否存在，不存在就创建
    if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '". $table."'")!==1))
    {
        $sql = "CREATE TABLE ".$table." (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(50),
        reg_date TIMESTAMP
        )";
        $db->query($sql);
    }

    //INSERT
    $nInsertId=0;
    $sBulkString="('".$time."','".$json_price."','".$obvData."','".$json_bands."','".$json_ac."','".$json_stoch."','".$json_sar."','".$json_fisher."')";

    
    $sql="insert into ".$table." (time,price,obv,bands,ac,stoch,sar,fisher) values".$sBulkString;
    $db->query($sql);
    $nInsertId=mysqli_insert_id($db);
} catch (Exception $e) {
    $nInsertId=-1;
}

echo $nInsertId;
