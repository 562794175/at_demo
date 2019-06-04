<?php

ini_set('date.timezone','Asia/Shanghai');
function getPeriodMap()
{
    return [
        '1H'=>["00:00","01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"],
        '4H'=>["01:00","05:00","09:00","13:00","17:00","21:00"],
        '1D'=>["00:00"],
    ];
}
function getPeroidTime($sPeroidKey='1H',$nDefault=0)
{
    $aPeroid=getPeriodMap();
    $aPeroid=empty($aPeroid[$sPeroidKey])?[]:$aPeroid[$sPeroidKey];
    $sTime="";
    foreach ($aPeroid as $key => $value) {
        $sSelected="";
        if($nDefault===$key) $sSelected="selected";
        $sTime.="<option value='".$key."' ".$sSelected.">".$value."</option>";
    }
    return $sTime;
}
function getMarketState()
{
    
}