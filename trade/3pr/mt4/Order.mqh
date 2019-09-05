//+------------------------------------------------------------------+
//|                                                        Order.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict
#include <Zmq/Zmq.mqh>
#include <JAson.mqh>
#include "Log.mqh"
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
class Order
  {
private:
   int m_objectflag;
   int m_targetid;
   Log m_log;
public:
                     Order();
                    ~Order();
   void Deal(int targetid,int action,int operate,double sl,string ordernum);
   void AutoOpen(int action,int operate,double sl);
   void ActiveClose(int action,int operate,double sl,string ordernum);
   void PassiveClose(int action,int operate,double sl,string ordernum);
   void StopLoss(int action,int operate,double sl,string ordernum);
   
   void Save(int action,int operate,double sl,string odernum);
  };
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
Order::Order()
  {
  MathSrand(GetTickCount());
  m_objectflag=0;
  }
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
Order::~Order()
  {
  }
//+------------------------------------------------------------------+

void Order::Deal(int targetid,int action,int operate,double sl,string ordernum)
{
   string event=IntegerToString(action)+IntegerToString(operate);
   m_targetid=targetid;
   if(event=="11" || event=="21" ) {
      AutoOpen(action,operate,sl);
   } else if(event=="12" || event=="22" ) {
      ActiveClose(action,operate,sl,ordernum);
   } else if(event=="13" || event=="23" ) {
      StopLoss(action,operate,sl,ordernum);
   }
}

void Order::AutoOpen(int action,int operate,double sl)
{
   string event=IntegerToString(action)+IntegerToString(operate);
   string objName="";
   int ordernum=MathRand();
   if(event=="11" && m_objectflag!=1) {
      objName="BUY-OPEN-"+TimeCurrent()+"-SL-"+DoubleToStr(sl)+"-NUMBER-"+ordernum;
      if(ObjectCreate(ChartID(),objName,OBJ_ARROW_BUY,0,Time[0],Ask)) {
         m_objectflag=1;
         Save(action,operate,sl,IntegerToString(ordernum));
         m_log.Save(m_targetid,objName);
      } else {
         Print("Error: can't create label! code #",GetLastError());
      }
      
   }else if(event=="21" && m_objectflag!=2) {
      objName="SELL-OPEN-"+TimeCurrent()+"-"+"SL"+"-"+DoubleToStr(sl)+"-NUMBER-"+ordernum;
      if(ObjectCreate(ChartID(),objName,OBJ_ARROW_SELL,0,Time[0],Bid)) {
         m_objectflag=2;
         Save(action,operate,sl,IntegerToString(ordernum));
         m_log.Save(m_targetid,objName);
      } else {
         Print("Error: can't create label! code #",GetLastError());
      }
   }//end if
}

void Order::ActiveClose(int action,int operate,double sl,string ordernum)
{
   m_objectflag=0;
   string objName="CLOSE-"+TimeCurrent()+"-ORDERNUM-"+ordernum;
   if(action==1) {
      ObjectCreate(ChartID(),objName,OBJ_ARROW_LEFT_PRICE,0,Time[0],Ask);
      Save(action,operate,sl,ordernum);
   } else if(action==2) {
      ObjectCreate(ChartID(),objName,OBJ_ARROW_RIGHT_PRICE,0,Time[0],Bid);
      Save(action,operate,sl,ordernum);
   }
   
   m_log.Save(m_targetid,objName);
}

void Order::StopLoss(int action,int operate,double sl,string ordernum)
{
   string objName="STOPLOSS-"+TimeCurrent()+"-ORDERID-"+ordernum;
   if(action==1) {
      ObjectCreate(ChartID(),objName,OBJ_ARROW_STOP,0,Time[0],Ask);
      Save(action,operate,sl,ordernum);
   } else if(action==2) {
      ObjectCreate(ChartID(),objName,OBJ_ARROW_STOP,0,Time[0],Bid);
      Save(action,operate,sl,ordernum);
   }
   
   m_log.Save(m_targetid,objName);
}

void Order::PassiveClose(int action,int operate,double sl,string ordernum)
{
   //m_objectflag=0;
}

void Order::Save(int action,int operate,double sl,string odernum)
{
   Context context(1);
   Socket socket(context,ZMQ_REQ);
   if(!socket.connect("tcp://localhost:5555")) {
      Print("Server connect failed!");
      return;
   }
   CJAVal js;
   js["Task"]="order";
   js["Peroid"]=Period();
   js["Symbol"]=Symbol();
   js["Account"]=AccountNumber();
   js["TargetID"]=m_targetid;
   js["SL"]=sl;
   js["OrderNum"]=odernum;
   js["Action"]=action;
   js["Operate"]=operate;
   string out="";
   js.Serialize(out);
   ZmqMsg request(out);
   socket.send(request);
   // Get the reply.
   ZmqMsg reply;
   socket.recv(reply);
   socket.disconnect("tcp://localhost:5555");
}
