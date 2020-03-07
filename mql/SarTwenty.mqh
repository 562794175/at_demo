//+------------------------------------------------------------------+
//|                                                    SarTwenty.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict
#include <BaseMsg.mqh>


class SarTwenty : public BaseMsg
{
   public:
      SarTwenty();
};
SarTwenty::SarTwenty() {
   SetCmdName("sar_20");
   string value;
   for(int i=1;i<=20;i++) {
      value+=DoubleToStr(iSAR(NULL,0,0.02,0.2,i),Digits())+",";
   }
   parameter["value"]=value;
}