<?php
class CStoch {
    private $data;
    private $main;
    private $signal;
    
    public function __construct($dt=array()) {
        
        $this->data=$dt;
        
        $this->main= explode('|', $this->data['main']);
        $this->signal= explode('|', $this->data['signal']);
        
        if(empty(end($this->main))) {
            array_pop($this->main);
        }
        
        if(empty(end($this->signal))) {
            array_pop($this->signal);
        }
    }
    
    public function riseup() {
        if(end($this->main)>end($this->signal)) {
            return true;
        }
        //when state is skz
        if(end($this->main)< 20 ) {
            return true;
        }
        return false;
    }
    
    public function fulldown() {
        if(end($this->main)<end($this->signal) ) {
            return true;
        }
        //when state is skz
        if(end($this->main)> 80 ) {
            return true;
        }
        return false;
    }
    
    
}
