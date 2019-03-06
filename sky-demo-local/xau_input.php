<?php
$period = $_GET["period"];
$last = $_GET["last"];
$beginTime = $_POST["Begin"];
$endTime = $_POST["End"];
//price
$highData = $_POST["High"];
$lowData = $_POST["Low"];
$openData = $_POST["Open"];
$closeData = $_POST["Close"];
$data_price=array("open"=>$openData,"close"=>$closeData,"high"=>$highData,"low"=>$lowData);
$json_price= json_encode($data_price);
//obv
$obvData = $_POST["OBV"];

//bolling bands
$bandslowData = $_POST["BandsLower"];
$bandmainData = $_POST["BandsMain"];
$bandupperData = $_POST["BandsUpper"];
$data_bands=array("lower"=>$bandslowData,"main"=>$bandmainData,"upper"=>$bandupperData);
$json_bands= json_encode($data_bands);

$link = @mysqli_connect('localhost','root','');
if (!$link) {
    exit('error('.mysqli_connect_errno().'):'.mysqli_connect_error());
    //die
}
if (!mysqli_select_db($link,'test')) {
    echo 'error('.mysqli_errno($link).'):'.mysqli_error($link);
    mysqli_close($link);
    die;
}
mysqli_set_charset($link,'utf8');
if(empty($last)) {
    $last=$period;
}
$sql = "INSERT INTO `xau_".$last."`
            (`period`,
             `begin`,
             `end`,
             `data_price`,
             `data_volume`,
             `data_obv`,
             `data_rsi`,
             `data_bolling`)
            VALUES ('".$period."',
                    $beginTime,
                    $endTime,
                    '$json_price',
                    'data_volume',
                    '$obvData',
                    'data_rsi',
                    '$json_bands');";
$result = mysqli_query($link,$sql);
$output_html="";
if ($result ) {
    $output_html=mysqli_insert_id($link);
} else if($last=='last') {    
    $sql = "UPDATE `xau_".$last."`
            SET `begin` = $beginTime,
              `end` = $endTime,
              `data_price` = '$json_price',
              `data_volume` = 'data_volume',
              `data_obv` = '$obvData',
              `data_rsi` = 'data_rsi',
              `data_bolling` = '$json_bands'
            WHERE `period` = '".$period."'";
    mysqli_query($link,$sql);
    //echo $sql;
} else {
    $output_html="error!".$beginTime." - ".$endTime;
}
echo $output_html;
mysqli_close($link);
