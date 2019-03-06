<?php

function getThreeOperation($count,$pre='<li>',$equal='=') {
    $sym=["+","-","×","÷"];
    $items="";
    for($i=0;$i<$count;$i++) {
        $s=mt_rand(0,3);
        if($s==3) {
            $li=getThreeDivision($pre,$equal);
            $items.=$li;
        }
        if($s==0) {
            $li=getThreeAdd($pre,$equal);
            $items.=$li;
        }
        if($s==1) {
            $li=getThreeSub($pre,$equal);
            $items.=$li;
        }
        if($s==2) {
            $li=getThreeMulti($pre,$equal);
            $items.=$li;
        }
    }
    return $items;
}

function getThreeMulti($pre='<li>',$equal='=') {
    $ct=[0,0,0];
    $s=["",""];
    $s[0]="×";
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = mt_rand(1,999);
        $ct[1] = mt_rand(1,9);
        $s[1]=$sym[mt_rand(0,3)];
        if($s[1]=="-") {
            $mod=$ct[0]*$ct[1];
            $mod=$mod>999?999:$mod;
            $ct[2] = mt_rand(0,$mod);
            $mod=0;
        } else if($s[1]=="÷") {
            $ct[2] = mt_rand(1,9);
            $mod=$ct[1]*$ct[2]/$ct[2];
        } else if($s[1]=="×") {
            $ct[2] = mt_rand(1,9);
            $mod=0;
        } else if($s[1]=="+") {
            $ct[2] = mt_rand(1,999);
            $mod=0;
        }
        if($mod!=0) continue;
		//答案
		$e=$ct[0].$s[0].$ct[1].$s[1].$ct[2];
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$s[1].$ct[2].$equal.$mod; 
}

function getThreeSub($pre='<li>',$equal='=') {
    $ct=[0,0,0];
    $s=["",""];
    $s[0]="-";
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = mt_rand(1,999);
        $ct[1] = mt_rand(1,999);
        $mod=$ct[0]-$ct[1];
        if($mod<0) continue;
        $s[1]=$sym[mt_rand(0,3)];
        if($s[1]=="-") {
            $mod=$ct[0]-$ct[1];
            $ct[2] = mt_rand(0,$mod);
            $mod=0;
        } else if($s[1]=="÷") {
            $ct[2] = mt_rand(1,9);
            $mod=$ct[1]%$ct[2];
        } else if($s[1]=="×") {
            $ct[2] = mt_rand(1,9);
            $mod=$ct[0]>$ct[1]*$ct[2]?0:1;
        } else if($s[1]=="+") {
            $ct[2] = mt_rand(1,999);
            $mod=0;
        }
        if($mod!=0) continue;
		//答案
		$e=$ct[0].$s[0].$ct[1].$s[1].$ct[2];
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$s[1].$ct[2].$equal.$mod;   
}

function getThreeAdd($pre='<li>',$equal='=') {
    $ct=[0,0,0];
    $s=["",""];
    $s[0]="+";
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = mt_rand(1,999);
        $ct[1] = mt_rand(1,999);
        $s[1]=$sym[mt_rand(0,3)];
        if($s[1]=="-") {
            $mod=$ct[0]+$ct[1];
            $ct[2] = mt_rand(0,$mod);
            $mod=0;
        } else if($s[1]=="÷") {
            $ct[2] = mt_rand(1,9);
            $mod=$ct[1]%$ct[2];
        } else if($s[1]=="×") {
            $ct[2] = mt_rand(1,9);
            $mod=0;
        } else if($s[1]=="+") {
            $ct[2] = mt_rand(1,999);
            $mod=0;
        }
        if($mod!=0) continue;
		//答案
		$e=$ct[0].$s[0].$ct[1].$s[1].$ct[2];
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$s[1].$ct[2].$equal.$mod;
}

function getThreeDivision($pre='<li>',$equal='=') {
    $ct=[0,0,0];
    $s=["",""];
    $s[0]="÷";
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = mt_rand(1,99);
        $ct[1] = mt_rand(1,9);
        $mod=$ct[0]%$ct[1];
        if($mod!=0) continue;
        $s[1]=$sym[mt_rand(0,3)];
        if($s[1]=="-") {
            $ct[2] = mt_rand(1,99);
            $mod=0;
        } else if($s[1]=="÷") {
            $ct[2] = mt_rand(1,9);
            $mod=$ct[0]%$ct[1]%$ct[2];
        } else if($s[1]=="+") {
            $ct[2] = mt_rand(1,999);
            $mod=0;
        } else if($s[1]=="×") {
            $ct[2] = mt_rand(1,9);
            $mod=0;
        }
        if($mod!=0) continue;
		//答案
		$e=$ct[0].$s[0].$ct[1].$s[1].$ct[2];
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
        break;
    }
    return $pre.$ct[0].$s[0].$ct[1].$s[1].$ct[2].$equal.$mod;
}