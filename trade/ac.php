<?php

class CAC {
    private $data;
    
    private $count;
    
    public function __construct($dt=array()) {
        
        $this->data=$dt;
        if(empty(end($this->data))) {
            array_pop($this->data);
        }
        $this->count=count($this->data);
    }
    
    public function riseup() {
        if($this->data[$this->count-1]>$this->data[$this->count-2]) {
            return true;
        }
        return false;
    }
    
    public function fulldown() {
        if($this->data[$this->count-1]<$this->data[$this->count-2]) {
            return true;
        }
        return false;
    }
}
