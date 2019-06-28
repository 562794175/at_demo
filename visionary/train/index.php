<?php 
require_once 'function.php';
$db = getDBConn();
$sqlcount = "select count(*) from xau_bolling where human_type<>0";
$resultall = $db->query($sqlcount);
$arr1 = $resultall->fetch_row();
$c = $arr1[0];//用一个变量获取这个数组的值
echo "标记总数：".$c."<br>";

$sql = "select * from xau_bolling where human_type<>0";
$open=fopen(dirname(__FILE__) . '/train.scale',"w" );
$result = $db->query($sql);
if($result){
    $arr = $result->fetch_all();
    foreach ($arr as $k => $v) {
    //Libsvm中的svm-scale归一化范围 ： [lower,upper]
    //所使用的规则： y=lower+（upper-lower）*(x-MinValue)/(MaxValue-MinValue)
    //x、y为转换前、后的值，MaxValue、MinValue分别为样本每一列最大值和最小值
    //libsvm程序默认范围为：[-1,1]
        $id=$v[0];
        $alower=explode(',',$v[1]);
        $amain=explode(',',$v[2]);
        $aupper=explode(',',$v[3]);
        
        //只取最后5个数据
//        $alower=array_slice($alower, -10);
//        $amain=array_slice($amain, -10);
//        $aupper=array_slice($aupper, -10);
        
        $learntype=$v[4];
        $humantype=$v[5];
        $strLine=$humantype;
        $atmp= array_merge($alower,$amain);
        $atmp= array_merge($atmp,$aupper);
        $MinValue=min($atmp);
        $MaxValue=max($atmp);
        $lower=-1;
        $upper=1;
        foreach ($atmp as $kl => $vl) {
            $one = $lower+($upper-$lower)*($vl-$MinValue)/($MaxValue-$MinValue);
            $strLine.=" ".($kl+1).":".$one;
        }
       
        $strLine.="\r\n";
        fwrite($open,$strLine);
    }

    fclose($open);
    
    svmtrain(0,'/train.scale','/model.linear.svm');
    svmtrain(1,'/train.scale','/model.ploy.svm');
    svmtrain(2,'/train.scale','/model.rbf.svm');
    svmtrain(3,'/train.scale','/model.sigmoid.svm');
    //svmtrain(4,'/train.scale','/model.precomputed.svm');
    
//    $svm = new SVM();
//    $svm->setOptions(array(
//        SVM::OPT_TYPE => SVM::C_SVC,
//        SVM::OPT_KERNEL_TYPE => SVM::KERNEL_LINEAR,
//        SVM::OPT_P => 0.1,  // epsilon 0.1
//        SVM::OPT_PROBABILITY => 1
//    ));
//    $model = $svm->train(dirname(__FILE__) . '/train.scale');
//    $model->save(dirname(__FILE__) . '/model.svm');
    echo "训练结束！";
}