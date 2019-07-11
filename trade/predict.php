<?php
require_once 'function.php';
//$data["Peroid"]="1";
//$data["Symbol"]="eurusd";
//$data["TargetID"]=9;
//$state=taskPredict($data);

function taskPredict($data) {
    //info
    $peroid = REQUEST($data,"Peroid");
    $symbol = REQUEST($data,"Symbol");
    $table = getDBPre()."_".$symbol."_".$peroid;
    if($peroid==null || $symbol==null) {
        return -1;
    }
    $atmp=[];
    $targetid = REQUEST($data,"TargetID");
    //bands
    $bandslowData = REQUEST($data,"BandsLower");
    $bandmainData = REQUEST($data,"BandsMain");
    $bandupperData = REQUEST($data,"BandsUpper");
    if($bandslowData!=null && $bandmainData!=null && $bandupperData!=null) {
        $alower= explode('|',$bandslowData);
        $amain = explode('|',$bandmainData);
        $aupper= explode('|',$bandupperData);
        array_pop($alower);
        array_pop($amain);
        array_pop($aupper);
        //combime
        $atmp= array_merge($alower,$amain);
        $atmp= array_merge($atmp,$aupper);
        //echo $atmp;die();
    } else if($targetid!=null) {
        //echo $targetid;die();
        $db = getDBConn();
        $sql = "select *  from ".$table." where id=".$targetid;
        $result = $db->query($sql);
        $state=0;
        if($result){
            $arr = $result->fetch_all();
            foreach($arr as $k => $v){
                $id=$v[0];
                $bands=json_decode($v[4],true);
                $alower= explode('|', $bands['lower']);
                $amain = explode('|', $bands['main']);
                $aupper= explode('|', $bands['upper']);
                array_pop($alower);
                array_pop($amain);
                array_pop($aupper);
                //combime
                $atmp= array_merge($alower,$amain);
                $atmp= array_merge($atmp,$aupper);

            }//end foreach
        }//end if($result)
    }
    //svm predict 1-s,2-k,3-d,4-z
    $state=getSVMPredict($atmp,'/model/model.linear.svm');
    return $state;
}