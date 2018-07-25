from time import sleep
from threading import Thread
import numpy as np
import matplotlib.pyplot as plt
import SocketHandler as sh


#自定义类，用来封装两个按钮的单击事件处理函数
class ButtonHandler:
    def __init__(self,l_bid,l_ask,socket,ax1):
        self.flag =True
        self.l_bid = l_bid
        self.l_ask = l_ask
        self.sct=socket
        self.bid=0.0
        self.ask=0.0
        self.ax1=ax1

    #线程函数，用来更新数据并重新绘制图形
    def threadStart(self):
        while self.flag:
            sleep(0.02)
            self.sct.send_getrates()
            self.sct.pull_getrates()

            #更新数据
            self.l_bid.set_xdata(self.sct.t-self.sct.t[0])
            self.l_bid.set_ydata(self.sct.s_bid)
            self.l_ask.set_ydata(self.sct.s_ask)

            #plt.ylim(min(self.sct.s)*1.1,max(self.sct.s)*1.1)
            self.ax1.margins(1,1)
            #重新绘制图形
            plt.draw()


    def Start(self, event):
        self.flag =True
        #创建并启动新线程
        t =Thread(target=self.threadStart)
        t.start()

    def Stop(self, event):
        self.flag =False