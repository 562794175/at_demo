<?php
$log_str = $_POST["log_str"];

$link = @mysqli_connect('localhost','root','123456');
if (!$link) {
    exit('error('.mysqli_connect_errno().'):'.mysqli_connect_error());
    //die
}
if (!mysqli_select_db($link,'at_demo')) {
    echo 'error('.mysqli_errno($link).'):'.mysqli_error($link);
    mysqli_close($link);
    die;
}
mysqli_set_charset($link,'utf8');

$sql = "INSERT INTO `history_act`
            (`log_str`)
            VALUES ('$log_str');";
$result = mysqli_query($link,$sql);
$output_html="";
if ($result ) {
$output_html=mysqli_insert_id($link);
} else {
$output_html="error!".$beginTime." - ".$endTime;
}
echo $output_html;
mysqli_close($link);
