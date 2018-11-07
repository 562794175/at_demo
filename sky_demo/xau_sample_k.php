<?php
require_once("phpchartdir.php");
require_once("function.php");
require_once("page.class.php");

$url=$_SERVER["REQUEST_URI"];
$dopost="";
if(strpos($url,"dopost")!== false) $dopost=$_REQUEST["dopost"];
$db = new MySQLi("localhost","root","123456","test");
$sqlall = "select count(*) from xau_15";
$resultall = $db->query($sqlall);
$arr1 = $resultall->fetch_row();
$c = $arr1[0];
$pageindex=empty($_GET["page"])?0:$_GET["page"];
$page = new page($c,2);
$sql = "select * from xau_15 ".$page->limit;
$result = $db->query($sql);
$arr = $result->fetch_all();
$kdata=[];
$highData=$lowData=$openData=$closeData=[];
$id=0;
foreach($arr as $v){
    $id=$v[0];
    $timeframe=$v[1];
    $data_price=$v[4];
    $data_price = json_decode($data_price,TRUE);
    $ar=explode(",", $data_price["high"]);
    array_pop($ar);
    $highData = array_merge($highData,$ar);
    
    $ar=explode(",", $data_price["low"]);
    array_pop($ar);
    $lowData = array_merge($lowData,$ar);
    
    $ar=explode(",", $data_price["open"]);
    array_pop($ar);
    $openData = array_merge($openData,$ar);
    
    $ar=explode(",", $data_price["close"]);
    array_pop($ar);
    $closeData = array_merge($closeData,$ar);
}

for($i=2;$i<count($highData);$i++) {
    for($m=2;$m>=0;$m--) {
        $kdata[$i]['h'][]=$highData[$i-$m];
        $kdata[$i]['l'][]=$lowData[$i-$m];
        $kdata[$i]['o'][]=$openData[$i-$m];
        $kdata[$i]['c'][]=$closeData[$i-$m];
        $kdata[$i]['id']=$id;
        $kdata[$i]['tf']=$timeframe;
        $kdata[$i]['sn']=$i;
    }
}//end for
$head = "<a href='xau_sample.php'>sample</a> - <a href='xau_complex.php'>complex</a> - <a href='xau_svm.php'>svm</a>";
echo "<div align='center'>".$head."<br>".$page->fpage()."</div>";//显示分页信息

?>
<form id="sampleForm" action="xau_sample_k.php?id=<?php echo $id;?>&dopost=ajaxsave&page=<?php echo $pageindex; ?>" method="post" >
<table border=1 align='center'>
    <tr>
<?php
foreach($kdata as $key=> $value) {
    
    $image=getCandlePng($value);

    echo "<td><img src='".$image."'></td><td>";
    echo "<input type='text' name='fname' />";
    echo "<button name='button1' type='button' onclick='addsample();'>采样</button></td>";
    if($key%10==0) echo "</tr><tr>";

}
?>
    </tr>
</table>
<?php
    $data['h']=$highData;
    $data['l']=$lowData;
    $data['o']=$openData;
    $data['c']=$closeData;
    $data['id']=$id;
    $data['tf']=$timeframe;
    $data['sn']="-1";
    $imageall=getCandlePng($data,600,600);
    echo "<div align='center' ><img src='".$imageall."'></div>";
?>
</form>
<?php
function getCandlePng($data,$width=50,$height=50)
{
    $c = new XYChart($width, $height+10);
    $plotAreaObj =$c->setPlotArea(0, 0, $width, $height);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $layer = $c->addCandleStickLayer($data['h'],$data['l'], $data['o'], $data['c'], 0xFFFFFF, 0x000000);
    $layer->setLineWidth(2);
    $filename=$data['id']."_".$data['tf']."_".$data['sn'].".png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."k".PATHSEP."".$filename;
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, $width, $height, $realpath);
    $sitepath="png".PATHSEP."k".PATHSEP."".$filename;
    return $sitepath;
}
?>