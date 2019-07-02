<?php
ini_set('date.timezone','Asia/Shanghai');
  
 if(!function_exists("svmtrain")) {
    function svmtrain($kerneltype,$scalefile,$modelsave) 
    {
        $svm = new SVM();
        $svm->setOptions(array(
            SVM::OPT_TYPE => SVM::C_SVC,
            SVM::OPT_KERNEL_TYPE => $kerneltype,
            SVM::OPT_P => 0.1,  // epsilon 0.1
            SVM::OPT_PROBABILITY => 1,
            SVM::OPT_GAMMA => (1/4),
            SVM::OPT_EPS => 0.01, 
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

