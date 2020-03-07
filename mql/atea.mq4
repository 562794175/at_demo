//+------------------------------------------------------------------+
//|                                                         atea.mq4 |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict

#include <SocketLib.mqh>
#include <AutoStopLoss.mqh>
#include <JAson.mqh>
#include <AcTwenty.mqh>
#include <KlineTwenty.mqh>
#include <BollingTwenty.mqh>
#include <OsmaTwenty.mqh>
#include <SarTwenty.mqh>
#include <Quote.mqh>

AutoStopLoss autoStoploss;
ClientSocket* glbClientSocket = NULL;

//string Hostname="47.97.108.10";
string Hostname="127.0.0.1";
int ServerPort=3460;
int barsCount;

//+------------------------------------------------------------------+
//| Expert initialization function                                   |
//+------------------------------------------------------------------+
int OnInit()
  {
//--- create timer
   EventSetTimer(1);
   
   
   
//---
   return(INIT_SUCCEEDED);
  }
//+------------------------------------------------------------------+
//| Expert deinitialization function                                 |
//+------------------------------------------------------------------+
void OnDeinit(const int reason)
  {
//--- destroy timer
   EventKillTimer();
   if (glbClientSocket) {
      delete glbClientSocket;
      glbClientSocket = NULL;
   }
   
  }
//+------------------------------------------------------------------+
//| Expert tick function                                             |
//+------------------------------------------------------------------+
void OnTick()
  {
//---
   
   
   autoStoploss.Executor();
   
   
  }
//+------------------------------------------------------------------+
//| Timer function                                                   |
//+------------------------------------------------------------------+
void OnTimer()
  {
//---
   if (!glbClientSocket) {
      glbClientSocket = new ClientSocket(Hostname, ServerPort);
      if (glbClientSocket.IsSocketConnected()) {
         Print("Client connection succeeded");
      } else {
         Print("Client connection failed");
         delete glbClientSocket;
         glbClientSocket = NULL;
         return;
      }
   }


   if (!glbClientSocket.IsSocketConnected()) return;
   
   uint start=GetTickCount(); 
   if(barsCount!=Bars) {
      
      AcTwenty acTwenty;
      glbClientSocket.Send(acTwenty.Serialize());
      
      KlineTwenty klineTwenty;
      glbClientSocket.Send(klineTwenty.Serialize());
      
      BollingTwenty bollingTwenty;
      glbClientSocket.Send(bollingTwenty.Serialize());
      
      OsmaTwenty osmaTwenty;
      glbClientSocket.Send(osmaTwenty.Serialize());
      
      SarTwenty sarTwenty;
      glbClientSocket.Send(sarTwenty.Serialize());
      
      barsCount=Bars;
   }
   Quote quote;
   quote.SetAutoStopLossState(autoStoploss.GetEnabled());
   glbClientSocket.Send(quote.Serialize());
   
   
   string strMessage;
   do {
      strMessage = glbClientSocket.Receive();
      if (strMessage != "") {
         CJAVal json;
         json.Deserialize(strMessage);
         if(json["param"].ToStr()=="AUTO_STOPLOSS") {
            autoStoploss.SetEnabled(json["value"].ToInt());
         }
         if(json["param"].ToStr()=="OPEN_ORDER") {
            Print("OPEN_ORDER");
         }
         if(json["param"].ToStr()=="STOP_PROFIT") {
            Print("STOP_PROFIT");
         }
      }
   } while (strMessage != "");
   
   
   // If the socket is closed, destroy it, and attempt a new connection
   // on the next call to OnTick()
   if (!glbClientSocket.IsSocketConnected()) {
      // Destroy the server socket. A new connection
      // will be attempted on the next tick
      Print("Client disconnected. Will retry.");
      delete glbClientSocket;
      glbClientSocket = NULL;
   }
   
  }
//+------------------------------------------------------------------+
