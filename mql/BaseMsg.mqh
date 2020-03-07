//+------------------------------------------------------------------+
//|                                                      BaseMsg.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property strict

#include <JAson.mqh>

class BaseMsg
{  
   protected:
      CJAVal parameter;
      CJAVal command;
      
   public:
      BaseMsg();
      void SetCmdName(string name);
      string Serialize();


};

BaseMsg::BaseMsg() 
{
   parameter["symbol"]=Symbol();
   parameter["duration"]=Period();
   parameter["firstAxis"]=TimeToStr(iTime(Symbol(),PERIOD_M15,1),TIME_DATE|TIME_SECONDS);
   parameter["secondAxis"]=TimeToStr(iTime(Symbol(),PERIOD_H1,1),TIME_DATE|TIME_SECONDS);
}

void BaseMsg::SetCmdName(string name) {
   command["cmdName"]=name;
}



string BaseMsg::Serialize() {
   string strParam="";
   string strCmd="";
   parameter.Serialize(strParam);
   StringReplace(strParam,"{","");
   StringReplace(strParam,"}","");
   StringReplace(strParam,"\"","\'");
   command["parameter"]=strParam;
   command.Serialize(strCmd);
   return strCmd;
}