//+------------------------------------------------------------------+
//|                                                        Quote.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict
#include <BaseMsg.mqh>


class Quote : public BaseMsg
{
   public:
      Quote();
      void SetAutoStopLossState(bool value);
};
Quote::Quote() {
   SetCmdName("quote");
   parameter["ask"]=DoubleToStr(Ask,Digits());
   parameter["bid"]=DoubleToStr(Bid,Digits());
}

void Quote::SetAutoStopLossState(bool value) {
   parameter["autoStopLoss"]=value;
}