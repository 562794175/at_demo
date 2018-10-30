 <?php
    set_time_limit(0);
    require_once("phpchartdir.php");
    require_once("function.php");
    require_once("page.class.php");
    $db = new MySQLi("localhost","root","123456","test");
    $sqlall = "select count(*) from xau_sample";
    $resultall = $db->query($sqlall);
    $arr1 = $resultall->fetch_row();//获取一个数组  只有一个值的数组
    $c = $arr1[0];//用一个变量获取这个数组的值
    $page = new page($c,100);//一共多少条 每页显示多少条
    $sql = "select *  from xau_sample  order by id desc " .$page->limit;
    $result = $db->query($sql);
    

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
    <h1 align="center">lib svm train</h1>
    <div>
  <?php
    echo "<div align='center'>{$page->fpage()}</div>";//显示分页信息
?>
    <table align="center" width="60%" style="text-align:center;" border="1">
        <tr>
            <td>class</td>
            <td>image -1</td>
            <td>image -2</td>
            <td>image -3</td>
            <td>image -4</td>
            <td>image -5</td>
            <td>image -6</td>
            <td>image -7</td>
            <td>image -8</td>
            <td>image -9</td>
            <td>image -10</td>
        </tr>
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
                //echo show_svm_simple_png($line_arr[$pos-1],$i,$sample_arr[$i]);
            }
        }
        $train_data=array();
        //$class_arr=[''=>'-1','3f'=>'+1','3r'=>'-1','5r'=>'-1','5f'=>'-1','3p'=>'-1','3t'=>'-1','3rt'=>'-1','7r'=>'-1','7f'=>'-1','5t'=>'-1'];
        $class_arr=[''=>'0','3f'=>'1','3r'=>'2','5r'=>'3','5f'=>'4','3p'=>'5','3t'=>'6','3rt'=>'7','7r'=>'8','7f'=>'9','5t'=>'10','5p'=>'11','4t'=>'12'];
        $open=fopen(dirname(__FILE__) . '/train.scale',"w" );
        
        foreach ($sample_image as $key => $value) {
            echo"<tr>";
            echo"<td>".$key."</td>";
//            Libsvm中的svm-scale归一化范围 ： [lower,upper]
//            所使用的规则： y=lower+（upper-lower）*(x-MinValue)/(MaxValue-MinValue)
//            x、y为转换前、后的值，MaxValue、MinValue分别为样本每一列最大值和最小值
//            libsvm程序默认范围为：[-1,1]
            foreach ($value as $k => $v) {
                $attr=$sample_image_info[$key][$k];
                echo"<td title='".$attr."'>";
                echo show_svm_simple_png($v,$k,$key,explode("-",$attr)[0]);
                echo $attr;
                echo"</td>";
                if(($k+1)%10==0) echo "</tr><tr><td></td>";
                $strLine=$class_arr[$key];
                $td=array($class_arr[$key]);
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
            echo"</tr>";
        }
        
        fclose($open);
        
        //system("svmscale –l 0 –u 1 –s train.range train.txt > train.scale");
        $svm = new SVM();
       
        $model = $svm->train(dirname(__FILE__) . '/train.scale');
        $model->save(dirname(__FILE__) . '/model.svm');
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