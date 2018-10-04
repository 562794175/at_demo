import numpy as np
import sys
import random
import matplotlib
#matplotlib.use('Agg')
import HappyDemoUtils as happy_demo_utils
import matplotlib.pyplot as plt
import matplotlib.markers as mmarkers
from matplotlib.patches import Circle

def fig2data(fig):
    """
    @brief Convert a Matplotlib figure to a 4D numpy array with RGBA channels and return it
    @param fig a matplotlib figure
    @return a numpy 3D array of RGBA values
    """
    # draw the renderer
    fig.canvas.draw()
    data = np.fromstring(fig.canvas.tostring_rgb(), dtype=np.uint8, sep='')
    data = data.reshape(fig.canvas.get_width_height()[::-1] + (3,))
    return data

    # # Get the RGBA buffer from the figure
    # w, h = fig.canvas.get_width_height()
    # buf = np.fromstring(fig.canvas.tostring_argb(), dtype=np.uint8)
    # buf.shape = (w, h, 4)
    #
    # # canvas.tostring_argb give pixmap in ARGB mode. Roll the ALPHA channel to have it in RGBA mode
    # buf = np.roll(buf, 3, axis=2)
    # return bufh

class GameState:
    def __init__(self):
        self.score = self.playerIndex = self.loopIter = 0
        #plt.ion()  # 打开交互模式

        self.figure = plt.figure()
        self.axes = self.figure.add_subplot(111)
        #self.axes.axis('off')
        #
        self.range_start, self.range_end, self.range_step = 0, 1, 0.01
        t = np.arange(self.range_start, self.range_end, self.range_step)
        s = np.sin(4 * np.pi * t)

        self.l, = self.axes.plot(t, s,color='green',linestyle='dashed')

        ts = self.axes.scatter(0.5, 0.1, s=30, marker='^')

        tt1=ts.get_linestyle()

        #ts.LineWidth=0.6

        # for x in dir(ts):
        #     print(x)

        #self.rand=0
        self.offset=0

    def show(self):
        #plt.ioff()
        plt.show()

    def actionNO(self):
        print("action:do nothing but check")

    def actionSO(self):
        #self.axes.scatter(1-self.offset, 0.1,s=30,marker='v')
        print("action:SO")
        self.axes.scatter(1, 0.8, s=30, marker='v')

    def actionBO(self):
        #self.axes.scatter(1-self.offset, 0.2,s=30,marker='^')
        print("action:BO")
        self.axes.scatter(0.5, 0.1, s=30, marker='^')

    def actionCA(self):
        print("action:CA")
        # for x in [1, 2, 3]:
        # ct = self.axes.collections.count(self.ts1)
        # if ct>0:
        #     self.axes.collections.remove(self.ts1)
        for i in self.axes.collections:
            i.remove()

    def actionSOBC(self):
        #self.axes.scatter(1-self.offset, 0.4, s=30, marker='3')
        print("action:SO_BC")
        self.axes.scatter(1, 0.8, s=30, marker='v')
        for i in self.axes.collections:
            i.remove()

    def actionBOSC(self):
        #self.axes.scatter(1-self.offset, 0.5, s=30, marker='4')
        print("action:BO_SC")
        self.axes.scatter(0.5, 0.1, s=30, marker='^')
        for i in self.axes.collections:
            i.remove()

    def actionBC(self):
        #self.axes.scatter(1-self.offset, 0.6, s=30, marker='*')
        print("action:BC")
        for i in self.axes.collections:
            i.remove()

    def actionSC(self):
        #self.axes.scatter(1-self.offset, 0.7, s=30, marker='h')
        print("action:SC")
        for i in self.axes.collections:
            i.remove()

    def action(self,input_actions):
        # NO, SO, BO, CA, SO_BC, BO_SC, BC, SC
        # input_actions[0] == 1: NO do nothing but check
        if input_actions[0] == 1:
            self.actionNO()
        # input_actions[1] == 1: SO
        elif input_actions[1] == 1:
            self.actionSO()
        # input_actions[2] == 1: BO
        elif input_actions[2] == 1:
            self.actionBO()
        # input_actions[3] == 1: CA
        elif input_actions[3] == 1:
            self.actionCA()
        # input_actions[4] == 1: SO_BC
        elif input_actions[4] == 1:
            self.actionSOBC()
        # input_actions[5] == 1: BO_SC
        elif input_actions[5] == 1:
            self.actionBOSC()
        # input_actions[6] == 1: BC
        elif input_actions[6] == 1:
            self.actionBC()
        # input_actions[7] == 1: SC
        elif input_actions[7] == 1:
            self.actionSC()

    def frame_step(self, input_actions):
        reward = 0.1
        terminal = False

        if sum(input_actions) != 1:
            raise ValueError('Multiple input actions!')

        self.range_start += self.range_step
        self.range_end += self.range_step
        t = np.arange(self.range_start, self.range_end, self.range_step)
        ydata = np.sin(4 * np.pi * t)
        xdata = t-t[0]
        self.offset =t[0]

        self.l.set_xdata(xdata)
        self.l.set_ydata(ydata)

        for i in self.axes.collections:
            ioffset = i.get_offsets()
            ioffset[0][0]=ioffset[0][0]-self.range_step
            i.set_offsets(ioffset)


        self.action(input_actions)

        #
        # self.rand+=1
        # filename="png/"+str(self.rand)+".png"
        # plt.imsave(filename,fig2data(self.figure))

        # ?
        image_data = fig2data(self.figure)

        plt.pause(0.01)  # pause a bit so that plots are updated

        return image_data, reward, terminal

