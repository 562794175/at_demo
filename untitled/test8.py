# coding=UTF-8
import numpy as np

import pandas as pd

lt = [1,3,2,5,6,4]

dt=pd.DataFrame(lt)

tt=dt.rolling(window=3).mean()

ft=dt.shift(1).rolling(window=3).mean()

lt.reverse()

print(lt)