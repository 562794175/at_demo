<?php
require_once 'function.php';

function taskImport($data) {
    
    //info
    $peroid = REQUEST($data,"Peroid");
    $symbol = REQUEST($data,"Symbol");
    $account = REQUEST($data,"Account");
    $charttime = REQUEST($data,"ChartTime");
    $localtime = REQUEST($data,"LocalTime");
    $table = getDBPre()."_".$symbol."_".$peroid;

    if($peroid==null || $symbol==null) {
        return -1;
    }

    //price
    $highData = REQUEST($data,"High");
    $lowData = REQUEST($data,"Low");
    $openData = REQUEST($data,"Open");
    $closeData = REQUEST($data,"Close");
    if($highData==null || $lowData==null) {
        return -1.1;
    }
    $data_price=array("open"=>$openData,"high"=>$highData,"low"=>$lowData,"close"=>$closeData);
    $json_price= json_encode($data_price);
    
    //Ichimoku
    $TenKanSen = REQUEST($data,"TenKanSen");
    $KiJunSen = REQUEST($data,"KiJunSen");
    $SenKouSpanA = REQUEST($data,"SenKouSpanA");
    $SenKouSpanB = REQUEST($data,"SenKouSpanB");
    $ChikouSpan = REQUEST($data,"ChikouSpan");
    $data_ichimoku=array("TenKanSen"=>$TenKanSen,"KiJunSen"=>$KiJunSen,"SenKouSpanA"=>$SenKouSpanA,"SenKouSpanB"=>$SenKouSpanB,"ChikouSpan"=>$ChikouSpan);
    $json_ichimoku= json_encode($data_ichimoku);

    //bands
    $bandslowData = REQUEST($data,"BandsLower");
    $bandmainData = REQUEST($data,"BandsMain");
    $bandupperData = REQUEST($data,"BandsUpper");
    if($bandslowData==null || $bandmainData==null || $bandupperData==null) {
        return -1.2;
    }
    $data_bands=array("lower"=>$bandslowData,"main"=>$bandmainData,"upper"=>$bandupperData);
    $json_bands= json_encode($data_bands);

    //obv
    $obvData = REQUEST($data,"OBV");

    //ac
    $json_ac=REQUEST($data,"AC");

    //stoch
    $stochmain=REQUEST($data,"StochMain");
    $stochsignal=REQUEST($data,"StochSIGNAL");
    $data_stoch=array("main"=>$stochmain,"signal"=>$stochsignal);
    $json_stoch=json_encode($data_stoch);

    //sar
    $json_sar=REQUEST($data,"SAR");

    //fisher
    $json_fisher=REQUEST($data,"Fisher");
    //osma
    $json_osma=REQUEST($data,"OsMA");

    try {
        $db=getDBConn();
        $result=$db->query("SHOW TABLES LIKE '". $table."'");
        //判断表是否存在，不存在就创建
        if(mysqli_num_rows($result)!==1)
        {
            $sql = "CREATE TABLE ".$table." (".
            "id INT(11) NOT NULL AUTO_INCREMENT,".
            "timechart VARCHAR(30) NOT NULL,".
            "timelocal VARCHAR(30) NOT NULL,".
            "price TEXT,".
            "bands TEXT,".
            "obv TEXT,".
            "ac TEXT,".
            "stoch TEXT,".
            "sar TEXT,".
            "fisher TEXT,".
            "osma TEXT,".
            "ichimoku TEXT,".
            "PRIMARY KEY (id)".
            ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            if($db->query($sql)===FALSE) {
                return -2;
            }
        }

        //INSERT
        $nTargetId=0;
        $sBulkString="('".$charttime."','".$localtime."','".$json_price."','".$obvData."','".$json_bands."','".$json_ac."','".$json_stoch."','".$json_sar."','".
                $json_fisher."','".$json_osma."','".$json_ichimoku."')";
        $sql="insert into ".$table." (timechart,timelocal,price,obv,bands,ac,stoch,sar,fisher,osma,ichimoku) values".$sBulkString;
        if($db->query($sql)===FALSE) {
            return -3;
        }
        $nTargetId=mysqli_insert_id($db);
    } catch (Exception $e) {
        return -4;
    }

    return $nTargetId;
}