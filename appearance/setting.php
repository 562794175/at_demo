<?php 
require_once("function.php");
require_once("page.class.php");
$db = new MySQLi("localhost","root","123456","at_demo");
$db->query("SET NAMES utf8");
$sqlall = "select * from appearance";
$result = $db->query($sqlall);
$appear_id=empty($_GET["appear_id"])?1:$_GET["appear_id"];
echo "<select onchange='window.location=this.value;'>";
foreach($result as $v){
    if($appear_id==$v['id']) {
        echo "<option value='?appear_id=".$v['id']."' selected>".$v['name'].'</option>';
    } else {
        echo "<option value='?appear_id=".$v['id']."'>".$v['name'].'</option>';
    }
}
echo "</select><hr>";
/////
$sqlall = "select * from creation where appear_id like '%,".$appear_id.",%'";
$result = $db->query($sqlall);
echo "<table border='1' cellspacing='1' cellpadding='1'>";
foreach($result as $v){
    
    $sql="select * from aspect where creation_id=".$v['id'];
    $aspect = $db->query($sql);
    $str="<ul>";
    foreach($aspect as $vv) {
        $str.="<li>".$vv['name'].'</li>';
    }

        
    echo "<tr><td>".$v['name'].'</td><td>'.$str.'</td></tr>';
}
echo "</table>";
