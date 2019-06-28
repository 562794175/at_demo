<?php
ini_set('date.timezone','Asia/Shanghai');
require_once("phpchartdir.php");
if(isOnWindows()) {
    define("PATHSEP", "\\");
} else {
    define("PATHSEP", "/");
 }
 
if(!function_exists("getRightRate")) {
    //计时函数
    function G($start,$end='',$dec=4)
    {
        static $_info = array();
        if (!empty($end))
        {
            if(!isset($_info[$end])) $_info[$end] = microtime(TRUE);
            $sconds = number_format(($_info[$end]-$_info[$start]), $dec) * 1000;
            echo "{$sconds}ms<br />";
        }
        else
        {
            $_info[$start] = microtime(TRUE);
        }
    }
}
 
 if(!function_exists("getRightRate")) {
     function getRightRate($i)
     {
        $db = getDBConn();
        $sql = "select *  from xau_bolling where human_type>0 limit " .($i*100).",100";
        $result = $db->query($sql);
        
        $linear_right_class=0;
        $linear_right_key=0;
         
        if($result){
            $arr = $result->fetch_all();
            foreach($arr as $k => $v){
                $id=$v[0];
                $alower= explode(',', $v[1]);
                $amain=explode(',', $v[2]);
                $aupper=explode(',', $v[3]);
                //只取最后5个数据
//                $alower=array_slice($alower, -10);
//                $amain=array_slice($amain, -10);
//                $aupper=array_slice($aupper, -10);
                $learn_type=$v[4];
                $human_type=$v[5];
                //预测
                $atmp= array_merge($alower,$amain);
                $atmp= array_merge($atmp,$aupper);
                $svm_type=predict($atmp,$human_type,'/model.linear.svm');
                //准确率
                if($svm_type[1]==1) $linear_right_class++;
                if($svm_type[2]==1) $linear_right_key++;
            }
        }//end if
        return [$linear_right_class,$linear_right_key];
     }
     
 }
 
 
if(!function_exists("getTypeCount")) {
    function getTypeCount($type)
    {
       $db = getDBConn();
       $sqlcount = "select count(*) from xau_bolling where human_type=".$type;
       $resultall = $db->query($sqlcount);
       $arr1 = $resultall->fetch_row();
       $c = $arr1[0];//用一个变量获取这个数组的值
       return $c;
    }
}
 
  if(!function_exists("getBollingPng")) {
      function getChecked($Orign,$Give) {
          if($Orign==$Give ) {
              return "checked";
          } 
          return "";
      }
  }
  if(!function_exists("predict")) {
     function predict($dt,$act=0,$model='/model.linear.svm')
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
        $class_type=svmload($data,$act,$model);

        $res.="linear:".$class_type[0]."<br>";       
        return [$res,$class_type[1],$class_type[2]];
     }
 }
 
   if(!function_exists("svmload")) {
      function svmload($data,$act,$modelname)
      {
        $res="";
        $right_class=0;
        $right_key=0;
        $model = new SVMModel();
        $model->load(dirname(__FILE__) . $modelname);
        $class = $model->predict($data);
        $return = array();
        $result = $model->predict_probability($data, $return);
        arsort($return);
        reset($return);
        $key = key($return);
        if($act==$class) {
            $res.= "<font color='green'>class:".$class."</font>";
            $right_class=1;
        } else {
            $res.= "<font color='red'>class:".$class."</font>";
            $right_class=0;
        }
        if($act==$key) { 
            $res.="<font color='green'>key:".$key."</font>";
            $right_key=1;
        } else {
            $res.="<font color='red'>key:".$key."</font>";
            $right_key=0;
        }
        return [$res,$right_class,$right_key];
      }
  }
 
 if(!function_exists("getBollingPng")) {
    function getBollingPng($id,$abolling,$width=100,$height=100)
    {
        $filename=$id."_".$width."_".$height.".png";
        $realpath=realpath('.')."".PATHSEP."png".PATHSEP.$filename;
        $sitepath="png".PATHSEP.$filename;
        if (file_exists($realpath)) {
            return $sitepath;
        }
        
        $c = new XYChart($width, $height+10);
        $plotAreaObj =$c->setPlotArea(0, 0, $width, $height);
        $plotAreaObj->setGridColor(Transparent, Transparent);
        $plotAreaObj->setBackground(Transparent, Transparent, Transparent);
        $c->yAxis()->setColors(Transparent, Transparent);
        //$layer = $c->addCandleStickLayer($data['h'],$data['l'], $data['o'], $data['c'], 0xFFFFFF, 0x000000);
        //$layer->setLineWidth(2);
        if(!empty($abolling)) {
            $c->addLineLayer($abolling['l']);
            $c->addLineLayer($abolling['m']);
            $c->addLineLayer($abolling['u']);
        }
        $c->makeChart($realpath);
        cutPng($realpath, 0, 0, $width, $height, $realpath);
        return $sitepath;
    }
}
 
 if(!function_exists("cutPng")) {
    function cutPng($background, $cut_x, $cut_y, $cut_width, $cut_height, $location){
        $back=imagecreatefrompng($background);
        imagesavealpha($back,true);
        $new=imagecreatetruecolor($cut_width, $cut_height);
        imagealphablending($new,false);
        imagesavealpha($new,true);
        imagecopyresampled($new, $back, 0, 0, $cut_x, $cut_y, $cut_width, $cut_height,$cut_width,$cut_height);
        imagepng($new, $location);
        imagedestroy($new);
        imagedestroy($back);
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