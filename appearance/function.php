<?php
ini_set('date.timezone','Asia/Shanghai');
if(!function_exists("insertRecord")) {
    function insertRecord($sDate,$sTime)
    {
        $db=getDBConn();
        $sql="select id from market_record where date='".$sDate."' and time='".$sTime."'";
        $dt=mysqli_fetch_array($db->query($sql),MYSQLI_ASSOC);
        if(empty($dt)) {
            $sql="insert into market_record (date,time,created_time,updated_time) values('".$sDate."','".$sTime."',".time().",".time().")";
            $db->query($sql);
        }
    }
}


if(!function_exists("updateRecordTargetState")) {
    function updateRecordTargetState($sRecordID,$sPeroid,$sStateCode)
    {
        $db=getDBConn();
        $sql="select id from market_record_target where record_id=".$sRecordID." and peroid='".$sPeroid."'";
        $dt=fetchArray($db->query($sql));
        if(empty($dt)) {
            $sql="insert into market_record_target (record_id,peroid,state_code) values(".$sRecordID.",'".$sPeroid."',".$sStateCode.")";
        } else {
            $sql="update market_record_target set state_code=".$sStateCode." where record_id=".$sRecordID." and peroid='".$sPeroid."'";
        }
        $db->query($sql);
    }
}

if(!function_exists("getRecord")) {
    function getRecord($sDate,$sTime)
    {
        $db=getDBConn();
        $sql="select id,locked from market_record where date='".$sDate."' and time='".$sTime."'";
        $dt=$db->query($sql);
        //只要取一个
        return mysqli_fetch_array($dt,MYSQLI_ASSOC);
    }
}
if(!function_exists("getRecordTarget")) {
    function getRecordTarget($nRecord)
    {
        $db=getDBConn();
        $sql="select * from market_record_target where record_id=".$nRecord;
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
if(!function_exists("getDBConn")) {
    function getDBConn()
    {
        return new MySQLi("localhost","root","123456","atsys");
    }
}


if(!function_exists("getPeroid")) {
    function getPeroid($index)
    {
        $aPd=['1D','4H','1H'];
        return empty($aPd[$index])?0:$aPd[$index];
    }
}
if(!function_exists("getPeriodMap")) {
    function getPeriodMap()
    {
        return [
            '1H'=>["00:00","01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"],
            '4H'=>["01:00","05:00","09:00","13:00","17:00","21:00"],
            '1D'=>["00:00"],
        ];
    }
}
if(!function_exists("getPeroidTime")) {
    function getPeroidTime($sPeroidKey='1H',$nDefault=0,$bOption=true)
    {
        $aPeroid=getPeriodMap();
        $aPeroid=empty($aPeroid[$sPeroidKey])?[]:$aPeroid[$sPeroidKey];
        if(!$bOption) {
            return $aPeroid[$nDefault];
        }
        $sTime="";
        foreach ($aPeroid as $key => $value) {
            $sSelected="";
            if($nDefault===$key) $sSelected="selected";
            $sTime.="<option value='".$key."' ".$sSelected.">".$value."</option>";
        }
        return $sTime;
    }
}

if(!function_exists("getPeroidState")) {
    function getPeroidState($nDefault=0)
    {
        $aState= [11=>'State1',12=>'State2',21=>'State3',22=>'State4',31=>'State5',32=>'State6',41=>'State7',42=>'State8'];
        $sOption="";
        foreach ($aState as $key => $value) {
            $sSelected="";
            if($nDefault===$key) $sSelected="selected";
            $sOption.="<option value='".$key."' ".$sSelected.">".$value."</option>";
        }
        return $sOption;
    }
}