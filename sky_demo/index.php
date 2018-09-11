<?php
//https://www.cnblogs.com/lhbryant/p/6929275.html
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
$sql = 'select * from class';
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
        foreach ($row as $key => $value) {
            $output_html.="<td>".$value."</td>";
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