import numpy as np
import matplotlib.pyplot as plt
fig,axes=plt.subplots(2,1)
ax1=axes[0]
ax2=axes[1]
plt.subplots_adjust(bottom=0.2)
p_x = np.random.rand(1)
p_y = np.random.rand(1)
ax1.scatter(p_x,p_y)
plt.show()