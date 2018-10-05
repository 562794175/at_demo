 <?php
    require_once("phpchartdir.php");
    require_once("function.php");
    require_once("page.class.php");
    $db = new MySQLi("localhost","root","","test");
    $tj = " 1=1 ";
    $name="";
    if(!empty($_GET["begin"])){
        $name = $_GET["begin"];
        $tj = " begin like '%{$name}%'";
    }
    $sqlall = "select count(*) from xau where {$tj}" ;//获取总条数
    $resultall = $db->query($sqlall);
    $arr1 = $resultall->fetch_row();//获取一个数组  只有一个值的数组
    $c = $arr1[0];//用一个变量获取这个数组的值
    $page = new page($c,100);//一共多少条 每页显示多少条
    $sql = "select * from xau where {$tj} " .$page->limit;//拼接sql语句  分页显示
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
        <input type="submit" value="查询"  name="chaxun"/>
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
            echo"<tr>";
            echo"<td>".$v[0]."</td>";
            echo"<td>".$v[1]."</td>";
            echo"<td>".$v[2]."</td>";
            echo"<td>".$v[3]."</td>";
            //价格
            echo"<td>";
            echo"<img src='".show_price_bands_png($v[0],$v[1],$v[8],$v[4])."'><img src='".show_price_close_bands_png($v[0],$v[1],$v[8],$v[4])."'><br>";
            echo"时长：";
            echo"幅度：";
            echo"状态：";
            echo"斜率：<br>";
            echo"突破压力/支撑次数：";
            echo"变分自编码器：";
            echo"</td>";
            //obv
            echo"<td>";
            echo"<img src='".show_obv_png($v[0],$v[1],$v[6])."'>";
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