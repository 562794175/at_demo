//+------------------------------------------------------------------+
//|                                                     AcTwenty.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict

#include <BaseMsg.mqh>


class AcTwenty : public BaseMsg
{
   public:
      AcTwenty();
      void SetValue(string value);
      void DefaultValue();
};
AcTwenty::AcTwenty() {
   SetCmdName("ac_20");
   string value;
   for(int i=1;i<=20;i++) {
      double result=iAC(NULL,0,i);
      value+=DoubleToStr(result,Digits())+",";
   }
   parameter["value"]=value;
}
void AcTwenty::SetValue(string value) {
   parameter["value"]=value;
}




