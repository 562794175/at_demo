<?php
error_reporting(E_ALL^E_NOTICE);
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
$pageindex=$_GET["page"]==null?0:$_GET["page"];
$page = new page($c,1);
$sql = "select * from xau_15 ".$page->limit;
$result = $db->query($sql);
$arr = $result->fetch_all();
$id=0;
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
    
    $data_price = json_decode($data_price,TRUE);
    $highData = explode(",", $data_price["high"]);
    $lowData = explode(",", $data_price["low"]);
    $openData = explode(",", $data_price["open"]);
    $closeData = explode(",", $data_price["close"]);
    
    getRate($highData,$lowData,$openData,$closeData);
    
//    $dataY0[]=time_length($data_obv);
//    $dataY1[]=price_width($data_price);
    $times[]=date("H",strtotime($end)).'.'.date("i",strtotime($end));
    $labels[]=$i++;
}
$head = "<a href='xau_sample.php'>sample</a> - <a href='xau_complex.php'>complex</a> - <a href='xau_svm.php'>svm</a>";
echo "<div align='center'>".$head."<br>".$page->fpage()."</div>";//显示分页信息

$filename="png".PATHSEP."simple".PATHSEP."sample_close".$pageindex.".png";
$filename1="png".PATHSEP."simple".PATHSEP."sample_close".$pageindex."_1.png";
$dataY = $closeData;
$line_list=[];
$start_pos=0;
$end_pos=count($dataY);
while($start_pos<$end_pos) {
    $line=[];
    for($i=$start_pos;$i<$end_pos;$i++) {
        $correlation=0;
        $line[]=$dataY[$i];
        if(count($line)>=2) {
            $c = new XYChart(450, 420, 0xFF000000);
            $c->setPlotArea(55, 65, 350, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);
            $c->addLineLayer($line);
            $trendLayer = $c->addTrendLayer($line);
            $trendLayer->addConfidenceBand(CONFIDENCE,0x806666ff);
            $trendLayer->addPredictionBand(CONFIDENCE,0x8066ff66);
            $correlation = abs(round($trendLayer->getCorrelation(),2));
            unset($c);
            

            if($correlation>=CONFIDENCE) {
                $start_pos= $i==$end_pos-1?$end_pos:$i;
                if($start_pos==$end_pos) {
                    $line_list[] = $line;
                }
            }else if(count($line)>2 ) {
                $start_pos= $i-1;
                array_pop( $line );
                $line_list[] = $line;
                break;
            }
           
        }//end if(count($line)>=2)

    }//end for($i=$start_pos;$i<$end_pos;$i++)
}
//echo "\t - ".count($line_list);
$line_count=count($line_list);
$chart_width=$chart_height=0;
if($line_count<25) {
    $chart_width=$chart_height=300;
} else if($line_count>=25 && $line_count<35) {
    $chart_width=$chart_height=400;
} else if($line_count>=35 && $line_count<45) {
    $chart_width=$chart_height=500;
} else if($line_count>=45 && $line_count<55) {
    $chart_width=$chart_height=600;
} else if($line_count>=55) {
    $chart_width=$chart_height=700;
}

$offset=0;
$c = new XYChart($chart_width, $chart_height+10, 0xFF000000);
$c->setPlotArea(0, 0, $chart_width, $chart_height, 0xffffff, -1, 0xFF000000, 0xFF000000, -1);
$c->yAxis()->setWidth(0);
$line=[];
for($i=0;$i<count($line_list);$i++) {
    $size=count($line_list[$i]);
    for($n=0;$n<$size;$n++) {
        $line[$offset]=$line_list[$i][$n];
        $offset++;
    }
    $offset--;
    $c->addAreaLayer($line);
}
$image_file=show_png_default($c,$filename);
?>



<html>
    <body align='center'>

        
        <table align='center'><tr><td>
            <?php echo $image_file; ?>
        </td></tr></table>
    </body>
</html>

<?php
function getPosEnd($sample_arr,$begin_pos)
{
    if(empty($sample_arr)) return "";
    foreach($sample_arr as $v) { 
        $sample= explode("+", $v);
        $begin=$sample[0];
        $end=$sample[1];
        $type=$sample[2];
        if($begin==$begin_pos) return $v;
    }
}

function getRate($highData,$lowData,$openData,$closeData)
{
    $r=0;
    $f=0;
    foreach ($highData as $key => $value) {
        if($openData[$key]>$closeData[$key]) $f++;
        if($openData[$key]<$closeData[$key]) $r++;
    }
    echo "r:".$r." - ".round($r/($r+$f),2);
    
    echo "f:".$f.' - '.round($f/($r+$f),2);
}
?>