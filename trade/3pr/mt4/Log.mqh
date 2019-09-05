//+------------------------------------------------------------------+
//|                                                          Log.mqh |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict
#include <Zmq/Zmq.mqh>
#include <JAson.mqh>
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
class Log
  {
private:

public:
                     Log();
                    ~Log();
   void Save(int targetid,const string msg);
  };
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
Log::Log()
  {
  }
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
Log::~Log()
  {
  }
//+------------------------------------------------------------------+

void Log::Save(int targetid,const string msg)
{
   Context context(1);
   Socket socket(context,ZMQ_REQ);
   if(!socket.connect("tcp://localhost:5555")) {
      Print("Server connect failed!");
      return;
   }
   CJAVal js;
   js["Task"]="log";
   js["Peroid"]=Period();
   js["Symbol"]=Symbol();
   js["Account"]=AccountNumber();
   js["TargetID"]=targetid;
   js["Msg"]=msg;
   string out="";
   js.Serialize(out);
   ZmqMsg request(out);
   socket.send(request);
   // Get the reply.
   ZmqMsg reply;
   socket.recv(reply);
   socket.disconnect("tcp://localhost:5555");
}