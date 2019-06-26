<?php
require_once 'function.php';
$db = getDBConn();
$sql = "select *  from xau_bolling where id=1141";
$result = $db->query($sql);
if($result){
    $arr = $result->fetch_all();
    foreach($arr as $v){
        $id=$v[0];
        $alower=explode(',',$v[1]);
        $amain=explode(',',$v[2]);
        $aupper=explode(',',$v[3]);
        $learntype=$v[4];
        $humantype=$v[5];

        $atmp= array_merge($alower,$amain);
        $atmp= array_merge($atmp,$aupper);
        echo "linear:".predict_linear($atmp);
        echo "<br>";
        echo "rbf:".predict_rbf($atmp);
    }
}