//+------------------------------------------------------------------+
//|                                                   at20190703.mq4 |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict
#include <Zmq/Zmq.mqh>
#include <JAson.mqh>
#include "Market.mqh"
#include "SKDZEngine.mqh"

int bars_count=0;
int targetid=0;
int state=0;
SKDZEngine engine;

//+------------------------------------------------------------------+
//| Expert initialization function                                   |
//+------------------------------------------------------------------+
int OnInit()
{   
//--- create timer
   //bars_count=Bars;
   EventSetTimer(1);
   
//---
   return(INIT_SUCCEEDED);
}
//+------------------------------------------------------------------+
//| Expert deinitialization function                                 |
//+------------------------------------------------------------------+
void OnDeinit(const int reason)
{
//--- destroy timer
   EventKillTimer();
}
//+------------------------------------------------------------------+
//| Expert tick function                                             |
//+------------------------------------------------------------------+
void OnTick()
{
//---
}
//+------------------------------------------------------------------+
//| Timer function                                                   |
//+------------------------------------------------------------------+
void OnTimer()
{
//---     
   uint start=GetTickCount(); 
   if(bars_count!=Bars) {
      
      Market mkt;
      mkt.sendData();
      targetid=mkt.GetTargetId();
      state=mkt.GetState();
      
      bars_count=Bars;
      
      
   }
   
   engine.Run(targetid,state);
   
   //string fisher=iCustom(NULL,0,"RAVI FX Fisher",4,49,0.07,500,0,0);
   
   uint time=GetTickCount()-start; 
   
   string msg="use:"+time+" ms!";
   
   //msg+="t:"+targetid+",s:"+state;
  
   Print(msg); 
  
   
   

}


