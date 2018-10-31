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
$page = new page($c,1);
$sql = "select * from xau_15 ".$page->limit;
$result = $db->query($sql);
$arr = $result->fetch_all();
$kdata=[];
$id=0;
foreach($arr as $v){
    $id=$v[0];
    $timeframe=$v[1];
    $data_price=$v[4];
    $data_price = json_decode($data_price,TRUE);
    $highData = explode(",", $data_price["high"]);
    $lowData = explode(",", $data_price["low"]);
    $openData = explode(",", $data_price["open"]);
    $closeData = explode(",", $data_price["close"]);

    for($i=2;$i<count($highData);$i++) {
        for($m=2;$m>=0;$m--) {
            $kdata[$i]['h'][]=$highData[$i-$m];
            $kdata[$i]['l'][]=$lowData[$i-$m];
            $kdata[$i]['o'][]=$openData[$i-$m];
            $kdata[$i]['c'][]=$closeData[$i-$m];
        }
    }//end for
}
$head = "<a href='xau_sample.php'>sample</a> - <a href='xau_complex.php'>complex</a> - <a href='xau_svm.php'>svm</a>";
echo "<div align='center'>".$head."<br>".$page->fpage()."</div>";//显示分页信息

?>
<form id="sampleForm" action="xau_sample_k.php?id=<?php echo $id;?>&dopost=ajaxsave&page=<?php echo $pageindex; ?>" method="post" >
<table border=1 align='center'>
<?php
foreach($kdata as $value) {
    echo "<tr>";
    echo "<td>xc</td><td><input name=xx type='text'></td>";
    echo "</tr>";
}
?>
</table>
</form>
