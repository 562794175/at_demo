<?php
//
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
$page = new page($c,1);
$sql = "select * from xau_15 ".$page->limit;
$result = $db->query($sql);
$arr = $result->fetch_all();
$kdata=[];
$highData=$lowData=$openData=$closeData=[];
$lowerDataBolling=$mainDataBolling=$upperDataBolling=[];
$id=0;
foreach($arr as $v){
    $id=$v[0];
    $timeframe=$v[1];
    $data_price=$v[4];
    $data_price = json_decode($data_price,TRUE);
    $highData=explode(",", $data_price["high"]);
    $lowData=explode(",", $data_price["low"]);
    $openData=explode(",", $data_price["open"]);
    $closeData=explode(",", $data_price["close"]);
    $data_bolling = $v[8];
    $data_bands = json_decode($data_bolling,TRUE);
    $lowerDataBolling = explode(",", $data_bands["lower"]);
    $mainDataBolling = explode(",", $data_bands["main"]);
    $upperDataBolling = explode(",", $data_bands["upper"]);
}
//查找下一条记录
$sql = "select * from xau_15 where id=".($id+1);
$result = $db->query($sql);
$arr_next = $result->fetch_all();
foreach($arr_next as $v){
    $data_price=$v[4];
    $data_price = json_decode($data_price,TRUE);
    $hd=explode(",", $data_price["high"]);
    $ld=explode(",", $data_price["low"]);
    $od=explode(",", $data_price["open"]);
    $cd=explode(",", $data_price["close"]);
//    $data_bolling = $v[8];
//    $data_bands = json_decode($data_bolling,TRUE);
//    $ldb = explode(",", $data_bands["lower"]);
//    $mdb = explode(",", $data_bands["main"]);
//    $udb = explode(",", $data_bands["upper"]);
    for($i=1;$i<4;$i++) {
        if($i>=count($hd)) break;
        $highData[]=$hd[$i];
        $lowData[]=$ld[$i];
        $openData[]=$od[$i];
        $closeData[]=$cd[$i];
//        $lowerDataBolling[]=$ldb[$i];
//        $mainDataBolling[]=$mdb[$i];
//        $upperDataBolling[]=$udb[$i];
    }
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
<style>
    td input { width:50px;}
    #long{ width:100px;}
</style>
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
    $bolling['l']=$lowerDataBolling;
    $bolling['m']=$mainDataBolling;
    $bolling['u']=$upperDataBolling;
    $imageall=getCandlePng($data,600,600,$bolling);
    echo "<div align='center' ><img src='".$imageall."'></div>";
?>
</form>
<?php
function getCandlePng($data,$width=50,$height=50,$bolling=null)
{
    $c = new XYChart($width, $height+10);
    $plotAreaObj =$c->setPlotArea(0, 0, $width, $height);
    $plotAreaObj->setGridColor(Transparent, Transparent);
    $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
    $c->yAxis()->setColors(Transparent, Transparent);
    $layer = $c->addCandleStickLayer($data['h'],$data['l'], $data['o'], $data['c'], 0xFFFFFF, 0x000000);
    $layer->setLineWidth(2);
    if(!empty($bolling)) {
        $c->addLineLayer($bolling['l']);
        $c->addLineLayer($bolling['m']);
        $c->addLineLayer($bolling['u']);
    }
    $filename=$data['id']."_".$data['tf']."_".$data['sn'].".png";
    $realpath=realpath('.')."".PATHSEP."png".PATHSEP."k".PATHSEP."".$filename;
    $c->makeChart($realpath);
    cut_png($realpath, 0, 0, $width, $height, $realpath);
    $sitepath="png".PATHSEP."k".PATHSEP."".$filename;
    return $sitepath;
}
?>