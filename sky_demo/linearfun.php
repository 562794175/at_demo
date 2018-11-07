<?php
require_once("function.php");

function GetLinearTrendChart($dataY,$filename) {
    # Create a XYChart object of size 450 x 420 pixels
    $c = new XYChart(301, 311, 0xFF000000);
    $c->setPlotArea(0, 0, 300, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);
    $c->yAxis()->setWidth(0);
    $c->addLineLayer($dataY);
    $trendLayer = $c->addTrendLayer($dataY, 0x008000);
    $trendLayer->addConfidenceBand(0.95, 0x806666ff);
    $trendLayer->addPredictionBand(0.95, 0x8066ff66);

    //echo sprintf("Slope:%.4f\t",$trendLayer->getSlope());
    //echo sprintf("Intercept:%.4f\t",$trendLayer->getIntercept());
    //echo sprintf("Correlation:%.4f\t",$trendLayer->getCorrelation());
    //echo sprintf("Std Error:%.4f",$trendLayer->getStdError());
    return get_cut_png($c,$filename);
}

function GetLineList($dataY,$filename) {
    $line_list=[];
    $start_pos=0;
    $end_pos=count($dataY);
    while($start_pos<$end_pos) {
        $line=[];
        for($i=$start_pos;$i<$end_pos;$i++) {
            $correlation=0;
            $line[]=$dataY[$i];
            if(count($line)>=2) {
                $c = new XYChart(301, 311, 0xFF000000);
                $c->setPlotArea(0, 0, 300, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);
                $c->addLineLayer($line);
                $c->yAxis()->setWidth(0);
                $trendLayer = $c->addTrendLayer($line);
                $trendLayer->addConfidenceBand(0.95,0x806666ff);
                $trendLayer->addPredictionBand(0.95,0x8066ff66);
                $correlation = abs(round($trendLayer->getCorrelation(),2));
                unset($c);
                if($correlation>=0.95) {
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

    $offset=0;
    $c = new XYChart(301, 311, 0xFF000000);
    $c->setPlotArea(0, 0, 300, 300, 0xffffff, -1, 0xc0c0c0, 0xc0c0c0, -1);
    $c->yAxis()->setWidth(0);
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
    return get_cut_png($c,$filename);
}