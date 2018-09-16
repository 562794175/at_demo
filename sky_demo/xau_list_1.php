<?php
require_once("phpchartdir.php");
require_once("function.php");

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
$sql = 'select * from xau';
$result = mysqli_query($link,$sql);

if ($result && mysqli_num_rows($result)) {
    /*
    mysqli_fetch_row：获取一条数据的索引数组
    mysqli_fetch_assoc：获取一条数据的关联数组
    mysqli_fetch_array：获取一条数据的指定数组，
    类型取决于第二个参数
    mysqli_fetch_all：获取结果集中的所有数据，
    类型取决于第二个参数
    第二个参数：MYSQLI_NUM(索引数组)
    MYSQLI_ASSOC(关联数组)
    MYSQLI_BOTH(索引和关联都有)
    var_dump(mysqli_fetch_all($result,MYSQLI_ASSOC));
    */
    $output_html="<table>";
    while ($row = mysqli_fetch_array($result,MYSQLI_NUM)) {
        $output_html.="<tr>";
        $id=0;
        $peroid=0;
        $price_data="";
        foreach ($row as $key => $value) {
            if($key==4) $output_html.="<td><img src='".show_price_png($id,$peroid,$value)."'><img src='".show_price_close_png($id,$peroid,$value)."'></td>";
            else if($key==6) $output_html.="<td><img src='".show_obv_png($id,$peroid,$value)."'></td>";
            else if($key==8) $output_html.="<td><img src='".show_price_bands_png($id,$peroid,$value,$price_data)."'><img src='".show_price_close_bands_png($id,$peroid,$value,$price_data)."'></td>";
            else $output_html.="<td>".$value."</td>";
            
            if($key==0) $id=$value;
            if($key==1) $peroid=$value;
            if($key==4) $price_data=$value;
            
        }
        $output_html.="</tr>";
    }
    $output_html.="</table>";
} else {

}
echo $output_html;
mysqli_free_result($result);
mysqli_close($link);
?>