//+------------------------------------------------------------------+
//|                                                  KlineTwenty.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict
#include <BaseMsg.mqh>


class KlineTwenty : public BaseMsg
{
   public:
      KlineTwenty();
};
KlineTwenty::KlineTwenty() {
   SetCmdName("kline_20");
   string high;
   string open;
   string close;
   string low;
   for(int i=1;i<=20;i++) {
      high+=DoubleToStr(iHigh(NULL,0,i),Digits())+",";
      open+=DoubleToStr(iOpen(NULL,0,i),Digits())+",";
      close+=DoubleToStr(iClose(NULL,0,i),Digits())+",";
      low+=DoubleToStr(iLow(NULL,0,i),Digits())+",";
   }
   parameter["high"]=high;
   parameter["open"]=open;
   parameter["close"]=close;
   parameter["low"]=low;
}