//+------------------------------------------------------------------+
//|                                                BollingTwenty.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict
#include <BaseMsg.mqh>


class BollingTwenty : public BaseMsg
{
   public:
      BollingTwenty();
};
BollingTwenty::BollingTwenty() {
   SetCmdName("bolling_20");
   string lower;
   string main;
   string upper;
   for(int i=1;i<=20;i++) {
      lower+=DoubleToStr(iBands(NULL,0,20,2,0,PRICE_CLOSE,MODE_LOWER,i),Digits())+",";
      main+=DoubleToStr(iBands(NULL,0,20,2,0,PRICE_CLOSE,MODE_MAIN,i),Digits())+",";
      upper+=DoubleToStr(iBands(NULL,0,20,2,0,PRICE_CLOSE,MODE_UPPER,i),Digits())+",";
   }
   parameter["lower"]=lower;
   parameter["main"]=main;
   parameter["upper"]=upper;
}