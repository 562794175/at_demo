 <?php
    header("Content-Type: text/html;charset=utf-8");
    //set_time_limit(0);
    //分类训练
    require_once("phpchartdir.php");
    require_once("function.php");
    require_once("page.class.php");
    $db = new MySQLi("localhost","root","123456","test");
    $sql = "select *  from xau_sample  ";
    $result = $db->query($sql);
    
    $output = shell_exec('python darknet.py');
    echo $output;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
</head>
<style>
    td { font-size:4px}
</style>
<body>
    <h1 align="center">lib svm - YOLO3 train</h1>
    <div>


<?php

    if($result){
        $arr = $result->fetch_all();
        $sample_image=[];
        $sample_image_info=[];
        foreach($arr as $v){
            $id=$v[0];
            $orgin_id=$v[1];
            $sample=$v[2];
            $detail=$v[3];
            $content=$v[4];
            $sample_pos=$v[5];
            
            $sample_arr=explode('|',$sample);
            $line_arr= json_decode($content);
            $sample_pos_arr=explode('|',$sample_pos);
            
            for($i=0;$i<count($sample_pos_arr);$i++) {
                $pos_map=$sample_pos_arr[$i];
                
                $sample=explode(':',$pos_map)[0];
                $pos=explode(':',$pos_map)[1];
                
                $stype_arr = explode('+',$sample);
                $type = end($stype_arr);
                $sample_image[$type][]=$line_arr[$pos-1];
                $sample_image_info[$type][]=$orgin_id.'-'.$sample;
            }
        }
        $train_data=array();
        $class_arr=getClassArray();
        $open=fopen(dirname(__FILE__) . '/train.scale',"w" );
        
        foreach ($sample_image as $key => $value) {
//            Libsvm中的svm-scale归一化范围 ： [lower,upper]
//            所使用的规则： y=lower+（upper-lower）*(x-MinValue)/(MaxValue-MinValue)
//            x、y为转换前、后的值，MaxValue、MinValue分别为样本每一列最大值和最小值
//            libsvm程序默认范围为：[-1,1]
            foreach ($value as $k => $v) {
                $attr=$sample_image_info[$key][$k];
                $class=array_key_exists($key,$class_arr)?$class_arr[$key]:0;
                $strLine=$class;
                $td=array($class);
                if(!is_array($v)) {
                    echo $k." - ".$key." - ".$attr;
                    die();
                }
                
                $MinValue=min($v);
                $MaxValue=max($v);
                $lower=-1;
                $upper=1;
                foreach ($v as $kl => $vl) {
                    $one = $lower+($upper-$lower)*($vl-$MinValue)/($MaxValue-$MinValue);
                    array_push($td,$one);
                    $strLine.=" ".($kl+1).":".$one;
                }
                $train_data[]=$td;
                $strLine.="\r\n";
                fwrite($open,$strLine);
            }
        }
        
        fclose($open);
        $svm = new SVM();
        $model = $svm->train(dirname(__FILE__) . '/train.scale');
        $model->save(dirname(__FILE__) . '/model.svm');
    }
    
    echo "over!";
?>

    </div>
</body>
</html>