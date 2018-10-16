 <?php
    require_once("phpchartdir.php");
    require_once("function.php");
    require_once("page.class.php");
    $db = new MySQLi("localhost","root","","test");
    $tj = " 1=1 ";
    $name="";
    if(!empty($_GET["begin"])){
        $name = $_GET["begin"];
        $tj = " end like '%{$name}%'";
    }
    $period="last";
    if(!empty($_GET["period"])){
        $period=$_GET["period"];
    }
    //$sqlall = "select * from (select `id`,  `period`,  DATE_ADD(FROM_UNIXTIME(`begin`,'%Y-%m-%d %H:%i:%S'), INTERVAL -8 HOUR) AS `begin`, DATE_ADD(FROM_UNIXTIME(`end`,'%Y-%m-%d %H:%i:%S'), INTERVAL -8 HOUR) AS `end` ,  `data_price`,  `data_volume`,  `data_obv`,  `data_rsi`,  `data_bolling` from xau_".$period.") as a  where {$tj} order by a.begin desc" ;//获取总条数
    $sqlall = "select count(*) from xau_".$period."  where {$tj}";
    $resultall = $db->query($sqlall);
    $arr1 = $resultall->fetch_row();//获取一个数组  只有一个值的数组
    $c = $arr1[0];//用一个变量获取这个数组的值
    $page = new page($c,100);//一共多少条 每页显示多少条
    //$sql = "select * from (select `id`,  `period`,  DATE_ADD(FROM_UNIXTIME(`begin`,'%Y-%m-%d %H:%i:%S'), INTERVAL -8 HOUR) AS `begin`, DATE_ADD(FROM_UNIXTIME(`end`,'%Y-%m-%d %H:%i:%S'), INTERVAL -8 HOUR) AS `end` ,  `data_price`,  `data_volume`,  `data_obv`,  `data_rsi`,  `data_bolling` from xau_".$period.") as a  where {$tj} order by a.begin desc " .$page->limit;//拼接sql语句  分页显示
    $sql = "select *  from xau_".$period."  where {$tj} order by begin desc " .$page->limit;
    $result = $db->query($sql);
    

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1 align="center">XAU分页查询</h1>
    <form method="get" action="xau_list.php">
    <div align="center">
        请输入时间模糊查询：<input type="text" name="begin" value="<?php echo $name; ?>" />
<!--        <input type="submit" value="查询"  name="chaxun"/>-->
        周期：
        <input type="submit" value="last"  name="period"/>
        <input type="submit" value="15"  name="period"/>
        <input type="submit" value="60"  name="period"/>
        <input type="submit" value="1440"  name="period"/>
    </div>
    <br />
    </form>
    <div>
  <?php
    echo "<div align='center'>{$page->fpage()}</div>";//显示分页信息
?>
    <table align="center" width="60%" style="text-align:center;" border="1">
        <tr>
            <td>序号</td>
            <td>周期</td>
            <td>开始</td>
            <td>结束</td>
            <td>价格</td>
            <td>OBV</td>
            <td>操作</td>
        </tr>
<?php

    if($result){
        $arr = $result->fetch_all();
        foreach($arr as $v){
            $id=$v[0];
            $timeframe=$v[1];
            $begin=$v[2];
            $end=$v[3];
            $data_price=$v[4];
            $data_volume=$v[5];
            $data_obv=$v[6];
            $data_rsi=$v[7];
            $data_bolling=$v[8];
            echo"<tr>";
            echo"<td>".$id."</td>";
            echo"<td>".$timeframe."</td>";
            echo"<td>".$begin."</td>";
            echo"<td>".$end."</td>";
            //价格
            echo"<td>";
            $price_bands_path=show_price_bands_png($id,$period,$data_bolling,$data_price);
            $price_close_bands_path=show_price_close_bands_png($id,$period,$data_bolling,$data_price);
            echo"<img src='".$price_bands_path."'><img src='".$price_close_bands_path."'><br>";
            echo"时长：".time_length($data_obv);
            echo"幅度：".price_width($data_price);
//            echo"状态：";
//            echo"斜率：<br>";
//            echo"突破压力/支撑次数：";
//            echo"变分自编码器：";
            echo"</td>";
            //obv
            echo"<td>";
            $obv_path=show_obv_png($id,$period,$data_obv);
            $obv_price_close_bands_path=show_price_close_obv_png($id,$period,$obv_path,$price_close_bands_path);
            echo"<img src='".$obv_path."'><img src='".$obv_price_close_bands_path."'><br>";
            echo"";
            echo"</td>";
            echo"<td></td>";
            echo"</tr>";
        }
    }
?>
    </table>
    <br />
<?php
    echo "<div align='center'>{$page->fpage()}</div>";//显示分页信息
?>
    </div>
</body>
</html>