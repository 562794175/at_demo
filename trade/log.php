<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$gmttime = REQUEST("GMTTime");
$localtime = REQUEST("LocalTime");
$account = REQUEST("Account");
$targetid = REQUEST("TargetID");
$message = REQUEST("Msg");
$table = getDBPre()."_".$symbol."_".$peroid."_log";
if($peroid==null || $symbol==null) {
    echo -1;
    die();
}
try {
    $db=getDBConn();
    $result=$db->query("SHOW TABLES LIKE '". $table."'");
    //判断表是否存在，不存在就创建
    if(mysqli_num_rows($result)!==1)
    {
        $sql = "CREATE TABLE ".$table." (".
        "id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,".
        "timegmt VARCHAR(30) NOT NULL,".
        "timelocal VARCHAR(30) NOT NULL,".
        "account VARCHAR(30) NOT NULL,".
        "targetid INT(11),".
        "created INT(11),".
        "message TEXT".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if($db->query($sql)===FALSE) {
            echo -2;
            die();
            //die('数据表创建失败: ' . mysqli_error($db));
        }
    }

    //INSERT
//    $nTargetId=0;
//    $sBulkString="('".$gmttime."','".$localtime."','".$account."',".$targetid.",".time().",'".$message."')";
//    $sql="insert into ".$table." (timegmt,timelocal,account,targetid,created,message) values".$sBulkString;
//    if($db->query($sql)===FALSE) {
//        echo -3;
//        die();
//        //die('数据插入失败: ' . mysqli_error($db));
//    }
//    $nTargetId=mysqli_insert_id($db);
    $nTargetId=logInsert($table,$account,$gmttime,$localtime,$targetid,$message);
    
} catch (Exception $e) {
    $nTargetId=-4;
}

echo $nTargetId;