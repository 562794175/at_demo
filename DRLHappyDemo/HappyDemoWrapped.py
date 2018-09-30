import numpy as np
import sys
import random

import HappyDemoUtils as happy_demo_utils
import matplotlib.pyplot as plt

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


class GameState:
    def __init__(self):
        self.score = self.playerIndex = self.loopIter = 0
        self.figure = plt.figure()
        self.axes = self.figure.add_subplot (111)
        #self.axes.axis('off')

        #
        self.range_start, self.range_end, self.range_step = 0, 1, 0.001
        t = np.arange(self.range_start, self.range_end, self.range_step)
        s = np.sin(4 * np.pi * t)
        self.l, = self.axes.plot(t, s, lw=2)

        #self.rand=0

    def show(self):
        plt.show()

    def actionNO(self):
        print("action:do nothing but check")

    def actionSO(self):
        self.axes.scatter(1, 0.1)
        print("action:SO")

    def actionBO(self):
        print("action:BO")

    def actionCA(self):
        print("action:CA")

    def actionSOBC(self):
        print("action:SO_BC")

    def actionBOSC(self):
        print("action:BO_SC")

    def actionBC(self):
        print("action:BC")

    def actionSC(self):
        print("action:SC")

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
        xdata = t - t[0]
        self.l.set_xdata(xdata)
        self.l.set_ydata(ydata)

        self.action(input_actions)

        #
        # self.rand+=1
        # filename="png/"+str(self.rand)+".png"
        # plt.imsave(filename,fig2data(self.figure))

        # ?
        image_data = fig2data(self.figure)

        plt.pause(0.01)  # pause a bit so that plots are updated

        return image_data, reward, terminal

