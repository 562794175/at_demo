<?php
require_once 'function.php';
//$data["Peroid"]="1";
//$data["Symbol"]="eurusd";
//$data["TargetID"]=9;
//$data["Account"]=933;
//$data["State"]=2;
//$data["Msg"]="test msg";
//taskLog($data);

//taskLog([]);
function taskLog($data) {
    //info
    $peroid = REQUEST($data,"Peroid");
    $symbol = REQUEST($data,"Symbol");
    $account = REQUEST($data,"Account");
    $targetid = REQUEST($data,"TargetID");
    $state = REQUEST($data,"State");
    $message = REQUEST($data,"Msg");
    $table = getDBPre()."_".$symbol."_".$peroid."_log";
    $nowtime="[".date("Y-m-d H:m:s")."]";
    if($peroid==null || $symbol==null) {
        echo $nowtime."LOG FAILED! EMPTY Peroid || Symbol!";
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
            "account VARCHAR(30) NOT NULL,".
            "targetid INT(11),".
            "MD5 VARCHAR(32),".
            "message TEXT,".
            "created INT(11),".
            "UNIQUE KEY atm(account,targetid,MD5)".
            ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            if($db->query($sql)===FALSE) {
                echo $nowtime."CREATE TABLE ".$table." FAILED!".mysqli_error($db);
                die();
            }
        }

        //INSERT
        $sBulkString="('".$account."',".$targetid.",".time().",'".$message."','". md5($message)."')";
        $sql="insert into ".$table." (account,targetid,created,message,MD5) values".$sBulkString;
//        if($db->query($sql)===FALSE) {
//            echo $nowtime."INSERT INTO FAILED! ".$sql." ".mysqli_error($db);
//            die();
//        }
        
        $db->query($sql);

    } catch (Exception $e) {
        echo $nowtime."LOG Exception!".$e->getMessage();
        die();
    }

}