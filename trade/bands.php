<?php
require_once("phpchartdir.php");
class CBands {
    private $data;
    
    private $lower;
    private $main;
    private $upper;
    
    public function __construct($dt=array(),$lower=array(),$main=array(),$upper=array()) {
        
        if(empty($dt)) {
            $this->data['lower']=$lower;
            $this->data['main']=$main;
            $this->data['upper']=$upper;
        } else {
            $this->data=$dt;
        }
        $this->initData();
    }

    protected function initData()
    {
        $this->lower= explode('|', $this->data['lower']);
        $this->main= explode('|', $this->data['main']);
        $this->upper= explode('|', $this->data['upper']);
        
        if(empty(end($this->lower))) {
            array_pop($this->lower);
        }
        if(empty(end($this->main))) {
            array_pop($this->main);
        }
        if(empty(end($this->upper))) {
            array_pop($this->upper);
        }
    }
    
    public function predictState()
    {
        //combime
        $atmp= array_merge($this->lower,$this->main);
        $atmp= array_merge($atmp,$this->upper);
        //svm predict 1-s,2-k,3-d,4-z
        $state=getSVMPredict($atmp,'/model/model.linear.svm');
        return $state;
    }
    
    public function getMaxValue()
    {
        return max($this->upper);
    }
    
    public function getMinValue()
    {
        return min($this->lower);
    }
}
