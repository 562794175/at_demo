//+------------------------------------------------------------------+
//|                                                 AutoStopLoss.mq4 |
//|                        Copyright 2019, MetaQuotes Software Corp. |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2019, MetaQuotes Software Corp."
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict

class AutoStopLoss
{
   public:
      AutoStopLoss();
      ~AutoStopLoss();
      void Executor();
      void SetEnabled(bool flag);
      bool GetEnabled();
      
   private:
      double Calculate(int ticket,int order_type,double order_open);
      
      bool enabled;
};

AutoStopLoss::AutoStopLoss() {
   enabled=true;
}

AutoStopLoss::~AutoStopLoss() {}

void AutoStopLoss::SetEnabled(bool flag) {
   enabled=flag;
}

bool AutoStopLoss::GetEnabled() {
   return enabled;
}

void AutoStopLoss::Executor() 
{
//---
   if(!enabled) {
      Print("自动止损没有启动！");
      return;
   }
   //1.判断时间周期
   if(ChartPeriod()!=PERIOD_M15) {
      Print("不是15分钟周期图表！");
      return;
   }
   //2.判断是否有订单
   int total=OrdersTotal();
   if(total<=0) {return;}
   for(int pos=0;pos<total;pos++) {
      if(OrderSelect(pos,SELECT_BY_POS)==false) {continue;}
      //3.获取订单类型，订单止损值A
      int order_type=OrderType(); 
      double stop_loss_a=OrderStopLoss();
      double order_open=OrderOpenPrice();
      //4.计算当前止损值B
      double stop_loss_b=Calculate(OrderTicket(),order_type,order_open);
      //5.比较A,B两个值，不同则设置止损为B
      //Print("Compare A B"+DoubleToStr(stop_loss_a,Digits())+DoubleToStr(stop_loss_b,Digits()));
      //Print("Result:"+DoubleToStr(stop_loss_a,Digits())!=DoubleToStr(stop_loss_b,Digits()));
      if(stop_loss_b!=0 && DoubleToStr(stop_loss_a,Digits())!=DoubleToStr(stop_loss_b,Digits())) {
         Print(OrderTicket()+"StopLoss:"+stop_loss_b);
         bool res=OrderModify(OrderTicket(),OrderOpenPrice(),stop_loss_b,OrderTakeProfit(),0,Blue); 
         if(!res) {
            Print("Error in OrderModify. Error code=",GetLastError()); 
         } else {
            Print("Order modified successfully."); }
         
      }
   }
}

double AutoStopLoss::Calculate(int ticket,int order_type,double order_open) 
{
//---
   //1.获取最近第二个SAR值
   double SAR=NormalizeDouble(iSAR(NULL,0,0.02,0.2,2),Digits());
   double SAR_OPEN=NormalizeDouble(Open[2],Digits());
   double OPEN=NormalizeDouble(order_open,Digits());
   double ASK=NormalizeDouble(Ask,Digits());
   double BID=NormalizeDouble(Bid,Digits());
   
   
   //2.获取最近20个K线最大值MAX_K，20个K线最小值MIN_K
   double MAX_K,MIN_K; 
   int val_index=iHighest(NULL,0,MODE_HIGH,20,1);
   if(val_index!=-1) {MAX_K=High[val_index];}
   else { PrintFormat("Error in iHighest. Error code=%d",GetLastError());}
   val_index=iLowest(NULL,0,MODE_LOW,20,1); 
   if(val_index!=-1) {MIN_K=Low[val_index];}
   else {PrintFormat("Error in iLowest. Error code=%d",GetLastError());}
   
   //Print(ticket+"MAX_K"+MAX_K+"MIN_K"+MIN_K+"SAR"+SAR);
   //3.买单SL：
      //1.1.SAR 大于 当前买价 SL = MIN_K
      //1.2.MIN_K 大于 SAR ,SL = MIN_K,否则 SL = SAR
   int SAR_INT=(int)(SAR*(10^Digits()));
   int SAR_OPEN_INT=(int)(SAR_OPEN*(10^Digits()));
   int OPEN_INT=(int)(OPEN*(10^Digits()));
   int ASK_INT=(int)(ASK*(10^Digits()));
   int BID_INT=(int)(BID*(10^Digits()));
   int MIN_K_INT=(int)(MIN_K*(10^Digits()));
   int MAX_K_INT=(int)(MAX_K*(10^Digits()));
   if(order_type==OP_BUY) {
      if(OPEN_INT>=ASK_INT) return MIN_K;
      if(OPEN_INT<ASK_INT && SAR_INT<SAR_OPEN_INT) return SAR;
   }
      
   //4.卖单SL:
      //1.1.SAR 小于 当前卖价 SL = MAX_K
      //1.2.MAX_K 小于 SAR ,SL = MAX_K,否则 SL = SAR
   if(order_type==OP_SELL) {
      if(OPEN_INT<=BID_INT) return MAX_K;
      if(OPEN_INT>BID_INT && SAR_INT>SAR_OPEN_INT) return SAR;
   }
       
   return 0;
}