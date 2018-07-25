# coding=UTF-8
import numpy as np
import matplotlib.pyplot as plt
from matplotlib.widgets import Button
import SocketHandler as sh
import ButtonHandler as bh
from sklearn.linear_model import LinearRegression
import pandas as pd
#创建上下两个图表
fig,axes=plt.subplots(2,1)
ax1=axes[0]
ax2=axes[1]

#设置图形显示位置
plt.subplots_adjust(bottom=0.2)

socket = sh.SocketHandler()
socket.create_client()

#显示数据
socket.send_getdata_close()
dt = socket.pull_getdata()
while dt==None:
    socket.send_getdata_close()
    dt = socket.pull_getdata()

#ax2.scatter(0, min(dt) - 2)
#ax2.scatter(20, max(dt) + 2)
l2,=ax2.plot(range(0,len(dt)),dt)

#多加一个数据用来预测
print('dt len:',len(dt))
dt.append(0)
print('dt len:',len(dt))

#获取其他特征
socket.send_getdata_volume()
vol = socket.pull_getdata()
while dt==None:
    socket.send_getdata_volume()
    vol = socket.pull_getdata()

vol.append(0)
vol=pd.DataFrame(vol)

#训练
dt=pd.DataFrame(dt)
dt['S_3'] = dt[0].shift(1).rolling(window=2).mean()
dt['S_9'] = dt[0].shift(1).rolling(window=5).mean()
dt['S_VOL'] = vol[0].shift(1).rolling(window=2).mean()
dt= dt.dropna()

t=.8
t = int(t*len(dt[0]))
X_ALL=dt[['S_3','S_VOL']]
Y_ALL=dt[0]
# Train dataset
X_train = X_ALL[:t]
Y_train = Y_ALL[:t]
# Test dataset
X_test = X_ALL[t:]
Y_test = Y_ALL[t:]

linear = LinearRegression().fit(X_train,Y_train)
print("Gold ETF Price =", np.round(linear.coef_[0],2), \
"* 3 Days Moving Average", np.round(linear.coef_[1],2), \
"* 9 Days Moving Average +", np.round(linear.intercept_,2))

#预测1
predicted_price = linear.predict(X_test)
predicted_price = pd.DataFrame(predicted_price,index=Y_test.index,columns = ['price'])

l2,=ax2.plot(predicted_price)
#tmp=predicted_price[len(predicted_price)-1]
#print(float("{0:.2f}",tmp))

#使用score()函数来计算模型的拟合优度
X_test=X_test[0:len(X_test)-1]
Y_test=Y_test[0:len(Y_test)-1]
r2_score = linear.score(X_test,Y_test)*100
print(float("{0:.2f}".format(r2_score)))

plt.legend(['actual','predicted'])

#实验数据
while len(socket.s_bid)<len(socket.t):
    socket.send_getrates()
    socket.pull_getrates()

l_bid,=ax1.plot(socket.t, socket.s_bid, lw=1)
l_ask,=ax1.plot(socket.t, socket.s_ask, lw=1)

#ax1.scatter(0.8,min(socket.s_bid)-0.5)
#ax1.scatter(0.2,max(socket.s_ask)+0.5)

#定义按钮点击事件回调处理
callback =bh.ButtonHandler(l_bid,l_ask,socket,ax1)

#创建按钮并设置单击事件处理函数
axprev = plt.axes([0.81,0.05,0.1,0.075])
bprev =Button(axprev,'Stop')
bprev.on_clicked(callback.Stop)
axnext = plt.axes([0.7,0.05,0.1,0.075])
bnext =Button(axnext,'Start')
bnext.on_clicked(callback.Start)

plt.show()






