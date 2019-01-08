<?php
require_once 'operation_2.php';
require_once 'operation_3.php';

function getFourOperation($count,$pre='<li>',$equal='=') {
    $sym=["+","-","×","÷"];
    $items="";
    for($i=0;$i<$count;$i++) {
        $s=mt_rand(0,3);
        if($s==3) {
            $li=getFourDivision($pre,$equal);
            $items.=$li;
        }
        if($s==0) {
            $li=getFourAdd($pre,$equal);
            $items.=$li;
        }
        if($s==1) {
            $li=getFourSub($pre,$equal);
            $items.=$li;
        }
        if($s==2) {
            $li=getFourMulti($pre,$equal);
            $items.=$li;
        }
    }
    return $items;
}

function getFourMulti($pre='<li>',$equal='=') {
    $ct=[0,0];
    $s=[""];
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = getTwoMulti(1,"","");
        $s[0]=$sym[mt_rand(0,1)];//只能+/-
        $ct[1] = getTwoOperation(1,"","");
        $e=$ct[0].$s[0].$ct[1];
        $e=str_replace('×','*',$e);
        $e=str_replace('÷','/',$e);
        $mod=eval("return ".$e.";");
        if($mod<0) continue;
        if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$equal;  
}

function getFourSub($pre='<li>',$equal='=') {
    $ct=[0,0];
    $s=[""];
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = getTwoSub(1,"","");
        $s[0]=$sym[mt_rand(0,3)];//都可以
        $ct[1] = getTwoOperation(1,"","");
        //不能出现连乘/除
        $check=$s[0].$ct[1];
        if(substr_count($check,'×')==2 || substr_count($check,'÷')==2 || 
                (substr_count($check,'×')==1 && substr_count($check,'÷')==1 )) {
            continue;
        }
        $e=$ct[0].$s[0].$ct[1];
        $e=str_replace('×','*',$e);
        $e=str_replace('÷','/',$e);
        $mod=eval("return ".$e.";");
        if($mod<0) continue;
        if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$equal;  
}

function getFourAdd($pre='<li>',$equal='=') {
    $ct=[0,0];
    $s=[""];
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = getTwoAdd(1,"","");
        $s[0]=$sym[mt_rand(0,3)];//都可以
        $ct[1] = getTwoOperation(1,"","");
        //不能出现连乘/除
        $check=$s[0].$ct[1];
        if(substr_count($check,'×')==2 || substr_count($check,'÷')==2 || 
                (substr_count($check,'×')==1 && substr_count($check,'÷')==1 )) {
            continue;
        }
        $e=$ct[0].$s[0].$ct[1];
        $e=str_replace('×','*',$e);
        $e=str_replace('÷','/',$e);
        $mod=eval("return ".$e.";");
        if($mod<0) continue;
        if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$equal;
}

function getFourDivision($pre='<li>',$equal='=') {
    $ct=[0,0];
    $s=[""];
    $sym=["+","-","×","÷"];
    while(1) {
        $s[0]=$sym[mt_rand(0,1)];//只能+/-
        $ct[0] = getTwoDivision("","");
        $ct[1] = getTwoOperation(1,"","");
        $e=$ct[0].$s[0].$ct[1];
        $e=str_replace('×','*',$e);
        $e=str_replace('÷','/',$e);
        $mod=eval("return ".$e.";");
        if($mod<0) continue;
        if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$equal;
}