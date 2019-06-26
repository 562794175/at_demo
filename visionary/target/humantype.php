<?php
require_once 'function.php';
$id=$_GET['id'];
$type=$_GET['type'];
$sql="update xau_bolling set human_type=".$type." where id=".$id;
$db = getDBConn();
$result = $db->query($sql);
echo $result."--".$id."--".$type;