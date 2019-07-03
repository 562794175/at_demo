<?php
require_once 'function.php';
//info
$peroid = REQUEST("Peroid");
$symbol = REQUEST("Symbol");
$table = getDBPre()."_".$symbol."_".$peroid."_order";
$account = REQUEST("Account");
$ordernum = REQUEST("OrderNum");
$targetid = REQUEST("TargetID");
$action = REQUEST("Action");//1-b,2-s
$operate = REQUEST("Operate");//0-null,1-open,2-close,3-stoploss
$profit = REQUEST("Profit",0);
$gmttime = REQUEST("GMTTime");
$localtime = REQUEST("LocalTime");
if($peroid==null || $symbol==null) {
    echo -1;
    die();
}
//save
try {
    $db=getDBConn();
    $result=$db->query("SHOW TABLES LIKE '". $table."'");
    //判断表是否存在，不存在就创建
    if(mysqli_num_rows($result)!==1)
    {
        $sql = "CREATE TABLE ".$table." (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        account VARCHAR(30) NOT NULL,
        ordernum VARCHAR(30) NOT NULL,
        timegmt VARCHAR(30) NOT NULL,
        timelocal VARCHAR(30) NOT NULL,
        targetid INT(11),
        action INT(11),
        state INT(11),
        profit INT(11),
        created INT(11),
        updated INT(11)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if($db->query($sql)===FALSE) {
            echo -2;
            die();
            //die('数据表创建失败: ' . mysqli_error($db));
        }
    }
    //INSERT
    $nInsertId=0;
    if($operate==1) {
        $sBulkString="('".$account."','".$ordernum."','".$gmttime."','".$localtime."',".$targetid.",".$action.",".$operate.",0,".time().",".time().")";
        $sql="insert into ".$table." (account,ordernum,timegmt,timelocal,targetid,action,state,profit,created,updated) values".$sBulkString;
        if($db->query($sql)===FALSE) {
            echo -3;
            //die();
            die('数据插入失败: ' . mysqli_error($db));
        }
        $nInsertId=mysqli_insert_id($db);
    } else if($operate==2 || $operate==3) {
        $sql="update ".$table." set state=".$operate.",profit=".$profit.",updated=".time()." where ordernum=".$ordernum;
        if($db->query($sql)===FALSE) {
            echo -4;
            //die();
            die('数据修改失败: ' . mysqli_error($db));
        }
        $nInsertId=1;
    } else {
        $nInsertId=-5;
    }
    
    
} catch (Exception $e) {
    $nInsertId=-6;
}

echo $nInsertId;
