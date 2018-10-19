<?php
require_once("phpchartdir.php");
require_once("function.php");
require_once("page.class.php");
$db = new MySQLi("localhost","root","123456","test");

$sqlall = "select count(*) from xau_15";
$resultall = $db->query($sqlall);
$arr1 = $resultall->fetch_row();
$c = $arr1[0];
$page = new page($c,1);
$sql = "select * from xau_15 ".$page->limit;
$result = $db->query($sql);
$arr = $result->fetch_all();
$dataY0=[];
$dataY1=[];
$labels=[];
$times=[];
$i=1;
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
echo "<div align='center'>".$page->fpage()."</div>";//显示分页信息
# The data for the line chart

$dataY = $closeData;

# The XY data of the first data series
//$dataX = array(50, 55, 37, 24, 42, 49, 63, 72, 83, 59);
//$dataY = array(3.6, 2.8, 2.5, 2.3, 3.8, 3.0, 3.8, 5.0, 6.0, 3.3);

# Create a XYChart object of size 450 x 420 pixels
$c = new XYChart(450, 420, 0xFF000000);

# Set the plotarea at (55, 65) and of size 350 x 300 pixels, with white background and a light grey
# border (0xc0c0c0). Turn on both horizontal and vertical grid lines with light grey color
# (0xc0c0c0)
$c->setPlotArea(55, 65, 350, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);

# Add a scatter layer using (dataX, dataY)
$c->addLineLayer($dataY);
# Add a trend line layer for (dataX, dataY)
$trendLayer = $c->addTrendLayer($dataY, 0x008000);
$trendLayer->setRegressionType(ExponentialRegression);
$trendLayer->addConfidenceBand(0.95, 0x806666ff);
$trendLayer->addPredictionBand(0.95, 0x8066ff66);

echo sprintf("Slope:%.4f\t",$trendLayer->getSlope());
echo sprintf("Intercept:%.4f\t",$trendLayer->getIntercept());
echo sprintf("Correlation:%.4f\t",$trendLayer->getCorrelation());
echo sprintf("Std Error:%.4f",$trendLayer->getStdError());


show_png($c,"test.png");


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
            $trendLayer->addConfidenceBand(0.95,0x806666ff);
            $trendLayer->addPredictionBand(0.95,0x8066ff66);
            $correlation = abs(round($trendLayer->getCorrelation(),2));
            unset($c);
            

            if($correlation>=0.95) {
                //$start_pos=$i;
                $start_pos= $i==$end_pos-1?$end_pos:$i;
                if($start_pos==$end_pos) {
                    $line_list[] = $line;
//                    echo implode(",", $line)."\t";
//                    echo count($line_list)."<br>";
                }
            }else if(count($line)>2 ) {
                //echo $correlation."[".count($line)."]\t";
                $start_pos= $i-1;
                array_pop( $line );
                $line_list[] = $line;
//                echo implode(",", $line)."\t";
//                echo count($line_list)."<br>";
                //show_png($c,"test".$start_pos.".png");
                break;
            }
           
        }//end if(count($line)>=2)

    }//end for($i=$start_pos;$i<$end_pos;$i++)
}
echo "\t - ".count($line_list);

$offset=0;
$c = new XYChart(450, 420, 0xFF000000);
$c->setPlotArea(55, 65, 350, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);
$line=[];
for($i=0;$i<count($line_list);$i++) {

    $size=count($line_list[$i]);
    for($n=0;$n<$size;$n++) {
        $line[$offset]=$line_list[$i][$n];
        $offset++;
    }
    $offset--;
    $c->addLineLayer($line);
}

show_png($c,"test2.png");

$train_data=$line_list;
//manually sample
$svm = new SVM();
$model = $svm->train($train_data);
$data = array(1 => 0.43, 3 => 0.12, 9284 => 0.2);
$result = $model->predict($data);
echo $result;
//auto sample
?>