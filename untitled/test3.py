# coding=UTF-8
#https://www.quantinsti.com/blog/gold-price-prediction-using-machine-learning-python/
from sklearn.linear_model import LinearRegression

import pandas as pd

import numpy as np

import matplotlib.pyplot as plt

import seaborn

import fix_yahoo_finance as yf

Df = yf.download('GLD','2008-01-01','2017-12-31')

Df=Df[['Close']]

Df= Df.dropna()

fig,axes=plt.subplots(2,1)

ax1=axes[0]

ax2=axes[1]

ax1.plot(Df)

#ax2.set_xticks(ax1.get_xticks())

#ax2.set_yticks(ax2.get_yticks())

#Df.Close.plot(figsize=(10,5),subplots=(211))

Df['S_3'] = Df['Close'].shift(1).rolling(window=3).mean()
Df['S_9']= Df['Close'].shift(1).rolling(window=9).mean()

#1
#2

#3
#4

Df= Df.dropna()
X = Df[['S_3','S_9']]

X.head()

#X = pd.DataFrame(X).fillna(0)

y = Df['Close']

y.head()

#y = pd.DataFrame(y).fillna(0)

t=.8

t = int(t*len(Df))

# Train dataset

X_train = X[:t]

y_train = y[:t]

# Test dataset

X_test = X[t:]

y_test = y[t:]



linear = LinearRegression().fit(X_train,y_train)



print("Gold ETF Price =", np.round(linear.coef_[0],2), \
"* 3 Days Moving Average", np.round(linear.coef_[1],2), \
"* 9 Days Moving Average +", np.round(linear.intercept_,2))


predicted_price = linear.predict(X_test)

predicted_price = pd.DataFrame(predicted_price,index=y_test.index,columns = ['price'])

ax2.plot(y_test)

ax2.plot(predicted_price)

plt.legend(['actual_price','predicted_price'])

'''
predicted_price.plot(figsize=(10,5),subplots=(212))

#y_test.plot(subplots=(212))

r2_score = linear.score(X[t:],y[t:])*100

print(float("{0:.2f}".format(r2_score)))



plt.legend(['predicted_price','actual_price'])

plt.ylabel("Gold ETF Price")
'''


plt.show()




