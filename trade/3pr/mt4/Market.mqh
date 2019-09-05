//+------------------------------------------------------------------+
//|                                                       Market.mqh |
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
class Market
  {
private:
   CJAVal m_data;
   int m_targetid;
   int m_state;
public:
                     Market();
                    ~Market();
   void getData();
   void sendData();
   int GetTargetId();
   int GetState();
  };
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
Market::Market()
  {
   m_targetid=0;
   m_state=0;
  }
//+------------------------------------------------------------------+
//|                                                                  |
//+------------------------------------------------------------------+
Market::~Market()
  {
  
  }
//+------------------------------------------------------------------+
void Market::getData(void)
{
   m_data["Task"]="import";
   m_data["Peroid"]=Period();
   m_data["Symbol"]=Symbol();
   m_data["Account"]=AccountNumber();
   m_data["ChartTime"]=TimeToString(Time[1],TIME_DATE|TIME_SECONDS);
   m_data["LocalTime"]=TimeToString(TimeLocal(),TIME_DATE|TIME_SECONDS);
   string sHigh,sLow,sOpen,sClose,sBandsLower,sBandsMain,sBandsUpper;
   string sObv,sAC,sStochMain,sStochSIGNAL,sSAR,sFisher,sOsMA;
   string sTenKanSen,sKiJunSen,sSenKouSpanA,sSenKouSpanB,sChikouSpan;
   for(int i=20;i>=1;i--){
      sHigh+=DoubleToString(High[i],Digits)+"|";
      sLow+=DoubleToString(Low[i],Digits)+"|";
      sOpen+=DoubleToString(Open[i],Digits)+"|";
      sClose+=DoubleToString(Close[i],Digits)+"|";
      sBandsLower+=DoubleToString(iBands(NULL,0,20,2,0,PRICE_CLOSE,MODE_LOWER,i),Digits)+"|";
      sBandsMain+=DoubleToString(iBands(NULL,0,20,2,0,PRICE_CLOSE,MODE_MAIN,i),Digits)+"|";
      sBandsUpper+=DoubleToString(iBands(NULL,0,20,2,0,PRICE_CLOSE,MODE_UPPER,i),Digits)+"|";
      sSAR+=DoubleToString(iSAR(NULL,0,0.02,0.2,i),Digits)+"|";
      
      sTenKanSen+=DoubleToString(iIchimoku(NULL,0,9,26,52,MODE_TENKANSEN,i),Digits)+"|";
      sKiJunSen+=DoubleToString(iIchimoku(NULL,0,9,26,52,MODE_KIJUNSEN,i),Digits)+"|";
      sSenKouSpanA+=DoubleToString(iIchimoku(NULL,0,9,26,52,MODE_SENKOUSPANA,i),Digits)+"|";
      sSenKouSpanB+=DoubleToString(iIchimoku(NULL,0,9,26,52,MODE_SENKOUSPANB,i),Digits)+"|";
      sChikouSpan+=DoubleToString(iIchimoku(NULL,0,9,26,52,MODE_CHIKOUSPAN,i+26),Digits)+"|";
      
      //sObv+=DoubleToString(iOBV(NULL,0,PRICE_CLOSE,i),Digits)+"|";
      sObv+=(string)iOBV(NULL,0,PRICE_CLOSE,i)+"|";
      sAC+=(string)iAC(NULL,0,i)+"|";
      
      sStochMain+=(string)iStochastic(NULL,0,5,3,3,MODE_SMMA,0,MODE_MAIN,i)+"|";
      sStochSIGNAL+=(string)iStochastic(NULL,0,5,3,3,MODE_SMMA,0,MODE_SIGNAL,i)+"|";
      
      sOsMA+=(string)iOsMA(NULL,0,12,26,9,PRICE_CLOSE,i)+"|";
      
      sFisher+=(string)iCustom(NULL,0,"RAVI FX Fisher",4,49,0.07,500,0,i)+"|";
   }
   m_data["High"]=sHigh;
   m_data["Low"]=sLow;
   m_data["Open"]=sOpen;
   m_data["Close"]=sClose;
   m_data["BandsLower"]=sBandsLower;
   m_data["BandsMain"]=sBandsMain;
   m_data["BandsUpper"]=sBandsUpper;
   m_data["OBV"]=sObv;
   m_data["AC"]=sAC;
   m_data["StochMain"]=sStochMain;
   m_data["StochSIGNAL"]=sStochSIGNAL;
   m_data["SAR"]=sSAR;
   m_data["Fisher"]=sFisher;
   m_data["OsMA"]=sOsMA;
   m_data["TenKanSen"]=sTenKanSen;
   m_data["KiJunSen"]=sKiJunSen;
   m_data["SenKouSpanA"]=sSenKouSpanA;
   m_data["SenKouSpanB"]=sSenKouSpanB;
   m_data["ChikouSpan"]=sChikouSpan;
}

void Market::sendData(void)
{
   Context context(1);
   Socket socket(context,ZMQ_REQ);
   if(!socket.connect("tcp://localhost:5555")) {
      Print("Server connect failed!");
      return;
   }
   
   string out="";
   getData();
   m_data.Serialize(out);
   ZmqMsg request(out);
   socket.send(request);
   
   // Get the reply.
   ZmqMsg reply;
   socket.recv(reply);
   //Print(reply.getData());
   m_data.Clear();
   m_data.Deserialize(reply.getData());
   m_targetid=m_data["targetid"].ToInt();
   m_state=m_data["state"].ToInt();
   //Print("t:"+targetid+",s:"+state);
   socket.disconnect("tcp://localhost:5555");
   return;
}

int Market::GetTargetId()
{
   return m_targetid;
}

int Market::GetState(void)
{
   return m_state;
}