<?php
class CIchimoku {
    private $data;
    private $TenKanSen;
    private $KiJunSen;
    private $SenKouSpanA;
    private $SenKouSpanB;
    private $ChikouSpan;
    
    private $ohlc;
    private $open;
    private $high;
    private $low;
    private $close;
    
    public function __construct($dt=array(),$ohlc=array()) {
        
        $this->data=$dt;
        $this->ohlc=$ohlc;
        
        $this->initData();
        $this->initOHLC();
    }
    
    protected function initData()
    {
        $this->TenKanSen= explode('|', $this->data['TenKanSen']);
        $this->KiJunSen= explode('|', $this->data['KiJunSen']);
        $this->SenKouSpanA= explode('|', $this->data['SenKouSpanA']);
        $this->SenKouSpanB= explode('|', $this->data['SenKouSpanB']);
        $this->ChikouSpan= explode('|', $this->data['ChikouSpan']);
        
        if(empty(end($this->TenKanSen))) {
            array_pop($this->TenKanSen);
        }
        if(empty(end($this->KiJunSen))) {
            array_pop($this->KiJunSen);
        }
        if(empty(end($this->SenKouSpanA))) {
            array_pop($this->SenKouSpanA);
        }
        if(empty(end($this->SenKouSpanB))) {
            array_pop($this->SenKouSpanB);
        }
        if(empty(end($this->ChikouSpan))) {
            array_pop($this->ChikouSpan);
        }
    }
    
    protected function initOHLC()
    {
        $this->open= explode('|', $this->ohlc['open']);
        $this->high= explode('|', $this->ohlc['high']);
        $this->low= explode('|', $this->ohlc['low']);
        $this->close= explode('|', $this->ohlc['close']);
        
        if(empty(end($this->open))) {
            array_pop($this->open);
        }
        if(empty(end($this->high))) {
            array_pop($this->high);
        }
        if(empty(end($this->low))) {
            array_pop($this->low);
        }
        if(empty(end($this->close))) {
            array_pop($this->close);
        }
    }

    public function riseup_tks() {
        //when state is d，
        if($this->close[count($this->close)-1] > $this->TenKanSen[count($this->TenKanSen)-1] && 
                $this->close[count($this->close)-2] > $this->TenKanSen[count($this->TenKanSen)-2] ) {
            return true;
        }
        return false;
    }
    
    public function fulldown_tks() {
        //when state is d
        if($this->close[count($this->close)-1] < $this->TenKanSen[count($this->TenKanSen)-1] && 
                $this->close[count($this->close)-2] < $this->TenKanSen[count($this->TenKanSen)-2] ) {
            return true;
        }
        return false;
    }
}
