//+------------------------------------------------------------------+
//|                                                   OsmaTwenty.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict
#include <BaseMsg.mqh>


class OsmaTwenty : public BaseMsg
{
   public:
      OsmaTwenty();
};
OsmaTwenty::OsmaTwenty() {
   SetCmdName("osma_20");
   string value;
   for(int i=1;i<=20;i++) {
      value+=DoubleToStr(iOsMA(NULL,0,12,26,9,PRICE_CLOSE,i),Digits())+",";
   }
   parameter["value"]=value;
}