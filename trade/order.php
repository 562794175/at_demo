<?php
require_once 'function.php';
$data["Peroid"]="1";
$data["Symbol"]="eurusd";
$data["TargetID"]=9;
$data["Account"]=933;
$data["OrderNum"]=2;
$data["Action"]=1;
$data["Operate"]=2;
$data["Profit"]=33;
$data["SLTime"]="xx";
taskOrder($data);
function taskOrder($data) {
    //info
    $peroid = REQUEST($data,"Peroid");
    $symbol = REQUEST($data,"Symbol");
    $table = getDBPre()."_".$symbol."_".$peroid."_order";
    $account = REQUEST($data,"Account");
    $sltime = REQUEST($data,"SLTime");
    $ordernum = REQUEST($data,"OrderNum");
    $targetid = REQUEST($data,"TargetID");
    $action = REQUEST($data,"Action");//1-b,2-s
    $operate = REQUEST($data,"Operate");//0-null,1-open,2-close,3-stoploss
    $profit = REQUEST($data,"Profit",0);
    if($peroid==null || $symbol==null) {
        return -1;
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
            targetid INT(11),
            action INT(11),
            state INT(11),
            profit INT(11),
            slprice VARCHAR(30) NOT NULL,
            created INT(11),
            updated INT(11)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            if($db->query($sql)===FALSE) {
                return -2;
            }
        }
        //INSERT
        $nInsertId=0;
        if($operate==1) {
            $sBulkString="('".$account."','".$ordernum."',".$targetid.",".$action.",".$operate.",0,'".$sltime."',".time().",".time().")";
            $sql="insert into ".$table." (account,ordernum,targetid,action,state,profit,sltime,created,updated) values".$sBulkString;
            if($db->query($sql)===FALSE) {
                return -3;
            }
            $nInsertId=mysqli_insert_id($db);
        } else if($operate==2) {
            $sql="update ".$table." set state=".$operate.",profit=".$profit.",updated=".time()." where ordernum=".$ordernum;
            if($db->query($sql)===FALSE) {
                return -4;
            }
            $nInsertId=1;
        } else if($operate==3) {
            $sql="update ".$table." set state=".$operate.",sltime='".$sltime."',updated=".time()." where ordernum=".$ordernum;
            if($db->query($sql)===FALSE) {
                return -4;
            }
            $nInsertId=2;
        } else {
            $nInsertId=-5;
        }
    } catch (Exception $e) {
        $nInsertId=-6;
    }
    return $nInsertId;
}
