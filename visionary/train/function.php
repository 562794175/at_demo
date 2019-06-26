<?php
ini_set('date.timezone','Asia/Shanghai');

 if(!function_exists("predict")) {
     function predict($dt)
     {
        $res="";
        $MinValue=min($dt);
        $MaxValue=max($dt);
        $lower=-1;
        $upper=1;
        $data=[];
        foreach ($dt as $kl => $vl) {
            $one = $lower+($upper-$lower)*($vl-$MinValue)/($MaxValue-$MinValue);
            $data[$kl+1]=$one;
        }
        $res.="linear:".svmload('/model.linear.svm')."<br>";
        $res.="rbf:".svmload('/model.rbf.svm')."<br>";
        $res.="sigmoid:".svmload('/model.sigmoid.svm')."<br>";
        $res.="ploy:".svmload('/model.ploy.svm')."<br>";
        //$res.="precomputed:".svmload('/model.precomputed.svm');
        return $res;
     }
 }
 
 
  if(!function_exists("svmload")) {
      function svmload($modelname)
      {
        $model = new SVMModel();
        $model->load(dirname(__FILE__) . $modelname);
        $class = $model->predict($data);
        $res.= "class:".$class;
        $return = array();
        $result = $model->predict_probability($data, $return);
        arsort($return);
        reset($return);
        $key = key($return);
        $res.="key:".$key;
        return $res;
      }
  }
 
  
 if(!function_exists("svmtrain")) {
    function svmtrain($kerneltype,$scalefile,$modelsave) 
    {
        $svm = new SVM();
        $svm->setOptions(array(
            SVM::OPT_TYPE => SVM::C_SVC,
            SVM::OPT_KERNEL_TYPE => $kerneltype,
            SVM::OPT_P => 0.1,  // epsilon 0.1
            SVM::OPT_PROBABILITY => 1
        ));
        $model = $svm->train(dirname(__FILE__) . $scalefile);
        $model->save(dirname(__FILE__) . $modelsave);
    }
 }


 if(!function_exists("fetchArray")) {
    function fetchArray($dt)
    {
        $query=[];
        while ($row = mysqli_fetch_array($dt,MYSQLI_ASSOC)) {
            $query[]=$row;
        }
        return $query;
    }
}
if(!function_exists("getDBConn")) {
    function getDBConn()
    {
        return new MySQLi("localhost","root","123456","test");
    }
}

