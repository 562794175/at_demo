//+------------------------------------------------------------------+
//|                                                   SKDZEngine.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict
#include <Zmq/Zmq.mqh>
#include <JAson.mqh>
#include "Order.mqh"
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
class SKDZEngine
  {
private:
   int m_action;
   int m_operate;
   string m_ordernum;
   double m_sl;
   Order m_order;
public:
                     SKDZEngine();
                    ~SKDZEngine();
   void getResponse(int targetid,int state);
   int Action() {return m_action;}
   int Operate() {return m_operate;}
   double StopLoss() {return m_sl;}
   
   void Run(int targetid,int state);
  };
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
SKDZEngine::SKDZEngine()
  {
   m_action=0;
   m_operate=0;
   m_sl=0;
   m_ordernum="";
  }
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
SKDZEngine::~SKDZEngine()
  {
  }
//+------------------------------------------------------------------+

void SKDZEngine::Run(int targetid,int state)
{
   getResponse(targetid,state);
   m_order.Deal(targetid,m_action,m_operate,m_sl,m_ordernum);
}

void SKDZEngine::getResponse(int targetid,int state )
{
   Context context(1);
   Socket socket(context,ZMQ_REQ);
   if(!socket.connect("tcp://localhost:5555")) {
      Print("Server connect failed!");
      return;
   }
   CJAVal js;
   js["Task"]="skdzengine";
   js["Peroid"]=Period();
   js["Symbol"]=Symbol();
   js["Account"]=AccountNumber();
   js["TargetID"]=targetid;
   js["State"]=state;
   
   string out="";
   js.Serialize(out);
   ZmqMsg request(out);
   socket.send(request);
   
   // Get the reply.
   ZmqMsg reply;
   socket.recv(reply);
   js.Clear();
   js.Deserialize(reply.getData());
   m_action=js["action"].ToInt();
   m_sl=js["sl"].ToDbl();
   m_operate=js["operate"].ToInt();
   m_ordernum=js["ordernum"].ToStr();
   
   socket.disconnect("tcp://localhost:5555");
}