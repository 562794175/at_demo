<?php

function curl_file_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getTwoOperation($count,$pre='<li>',$equal='=') {
    $sym=["+","-","×","÷"];
    $items="";
    for($i=0;$i<$count;$i++) {
        $s=mt_rand(0,3);
        if($s==3) {
            $li=getTwoDivision($pre,$equal);
            $items.=$li;
        }
        if($s==0) {
            $li=getTwoAdd(1,$pre,$equal);
            $items.=$li;
        }
        if($s==1) {
            $li=getTwoSub(1,$pre,$equal);
            $items.=$li;
        }
        if($s==2) {
            $li=getTwoMulti(1,$pre,$equal);
            $items.=$li;
        }
    }
    return $items;
}

function getTwoMulti($count,$pre='<li>',$equal='=') {
    $items="";
    for($i=0;$i<$count;$i++) {
        $ct[0] = mt_rand(1,9);//1位数
        $ct[1] = mt_rand(10,99);//2位数
        $ct[2] = mt_rand(100,999);//3位数
        $pos_1=mt_rand(0,2);//乘数的位数
        $pos_2=mt_rand(0,2);//被乘数的位数
        if($pos_1==1) {
            //乘数是2位数，那么被乘数可以是1/2位数
            $pos_2=mt_rand(0,1);
        } else if($pos_1==2) {
            //乘数是3位数，那么被乘数可以是1位数
            $pos_2=0;
        } else {
            //乘数是1位数，那么被乘数可以是1/2/3位数
            $pos_2=mt_rand(0,2);
        }
        $li=$ct[$pos_1]."×".$ct[$pos_2];
		
		
		//答案
		$e=$li;
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
		
		if($equal=='=') $items.=$pre.$li.$equal.$mod;
		else $items.=$pre.$li.$equal;
    }
    return $items;
}

function getTwoDivision($pre='<li>',$equal='=') {
    $ct=[0,0,0];
    $s=["",""];
    $s[0]="÷";
    $sym=["+","-","×","÷"];
    while(1) {
        $ct[0] = mt_rand(1,99);
        $ct[1] = mt_rand(1,9);
        $mod=$ct[0]%$ct[1];
        if($mod!=0) continue;
		
		//答案
		$e=$ct[0]/$ct[1];
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
		
        break;
    }
	if($equal=='=')
		return $pre.$ct[0].$s[0].$ct[1].$equal.$mod;
	else return $pre.$ct[0].$s[0].$ct[1].$equal;
}

function getTwoSub($count,$pre='<li>',$equal='=') {
    $items="";
    for($i=0;$i<$count;$i++) {
        $ct[0] = mt_rand(1,999);
        $ct[1] = mt_rand(1,$ct[0]);
        $li=$ct[0]."-".$ct[1];
		
		//答案
		$e=$li;
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
		if($equal=='=')
			$items.=$pre.$li.$equal.$mod;
		else $items.=$pre.$li.$equal;
    }
    return $items;
}

function getTwoAdd($count,$pre='<li>',$equal='=') {
    $items="";
    for($i=0;$i<$count;$i++) {
        $ct[0] = mt_rand(1,999);
        $ct[1] = mt_rand(1,999);
        $li=$ct[0]."+".$ct[1];
		
		//答案
		$e=$li;
		$e=str_replace('×','*',$e);
		$e=str_replace('÷','/',$e);
		$mod=eval("return ".$e.";");
		if($mod<0) continue;
		if(floor($mod)!=$mod) continue;
		if($equal=='=')
			$items.=$pre.$li.$equal.$mod;
		else $items.=$pre.$li.$equal;
    }

    return $items;
}
