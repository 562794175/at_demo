<?php
//error_reporting(E_ALL^E_NOTICE);
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

//    $dataY0[]=time_length($data_obv);
//    $dataY1[]=price_width($data_price);
    $times[]=date("H",strtotime($end)).'.'.date("i",strtotime($end));
    $labels[]=$i++;
}
echo "<div align='center'>".$head."<br>".$page->fpage()."</div>";//显示分页信息
$filename="png".PATHSEP."triangle".PATHSEP."t_".$pageindex.".png";
$filename1="png".PATHSEP."triangle".PATHSEP."t_".$pageindex."_1.png";
$dataY = $closeData;

//线性分析
$line_list=getLineListSet($dataY);
//线性集合
$line_count=count($line_list);
$chart_width=getImageWidth($line_count)[0];
$chart_height=getImageWidth($line_count)[1];
//显示线性集合
$image_file=getLineArea($line_list,$chart_width, $chart_height,$filename);
$str="";
$last_x=0;
for($i=0;$i<$line_count;$i++)
{
    $pos1=$i;
    $pos2=$pos1+1;
    $pt1_x=$last_x;
    $pt1_y=$line_list[$pos1][0];
    $pt2_x=$pt1_x+count($line_list[$pos1])-1;
    $pt2_y=end($line_list[$pos1]);
    $pt3_x=$pt2_x+count($line_list[$pos2])-1;;
    $pt3_y=end($line_list[$pos2]);
    
    $str.= $pt1_x."-".$pt1_y."|";
    $str.= $pt2_x."-".$pt2_y."|";
    $str.= $pt3_x."-".$pt3_y."|";
    
    $last_x=$pt2_x;
    
    //判断类型：Acute，Right,Obtuse
//    $a=getCalcuLength($line_list[$pos1]);
//    $b=getCalcuLength($line_list[$pos2]);
//    $c=getDistance($line_list[$pos1],$line_list[$pos2]);
//    $str.= $i."-";
//    //echo checkShapeAngle($a,$b,$c);
//    $str.= checkShape(round($a,2),round($b,2),round($c,2));
    $str.= "<br>";
}
function checkShape($a,$b,$c)
{
    $arr=[$a,$b,$c];
    $max=max($arr);
    $key=array_search($max ,$arr);
    array_splice($arr,$key,1);
    //a^2+b^2>c^2,Acute
    //a^2+b^2=c^2,Right
    //a^2+b^2<c^2,Obtuse
    //echo "0";
    $l1=$arr[0];
    $l2=$arr[1];
    $l=$l1*$l1+$l2*$l2;
    $str= $l1.'.'.$l2.'.'.$max;
    if($l>$max*$max) {
        return $str."Acute";
    } else if($l==$max*$max) {
        return $str."Right";
    } else if($l<$max*$max) {
        return $str."Obtuse";
    }
    
}

function checkShapeAngle($a,$b,$c)
{
    if ($a+$b>$c && $a+$c>$b && $b+$c>$a){
        $max = $a;
        $angle = acos(($b*$b+$c*$c-$a*$a)/(2*$b*$c))*180.0/M_PI;
        if($max < $b){
            $max = $b;
            $angle = acos(($a*$a+$c*$c-$b*$b)/(2*$a*$c))*180.0/M_PI;
        }
        if($max < $c){
            $max = $c;
            $angle = acos(($a*$a+$b*$b-$c*$c)/(2*$a*$b))*180.0/M_PI;
        }
        echo $angle;
        if($angle > 90.0){
            return "Obtuse";
        } else if($angle < 90.0){
            return "Acute";
        } else {
            return "Right";
        }
    } else {
        return "false";
    }
}


function getCalcuLength($line)
{
    $begin=$line[0];
    $end=end($line);
    $size=count($line)-1;
    if($size<1) return 0;
    $a=(0-$size)*(0-$size);
    $b=($begin-$end)*($begin-$end);
    $lenth=sqrt($a+$b);
    return $lenth;
}

function getDistance($line1,$line2)
{
    $begin=$line1[0];
    $end=end($line2);
    $size=count($line1)+count($line2)-2;
    if($size<2) return 0;
    $lenth=sqrt((0-$size)*(0-$size)+($begin-$end)*($begin-$end));
    return $lenth;
}

?>

<html>
    <body align='center' >     
        <table align='center'><tr>
                <td>
                    <?php echo $str; ?>
                </td>    
                <td>
            <?php echo $image_file; ?>
        </td><td>
            <?php echo $image_file1; ?>
        </td></tr></table>
    </body>
</html>

<?php 
function getLineListSet($dataY)
{
    //线性分析
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
    return $line_list;
}

function getImageWidth($line_count)
{
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
    return [$chart_width,$chart_height];
}

function getLineArea($line_list,$chart_width, $chart_height,$filename)
{
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
    return $image_file;
}
?>