import numpy as np
import sys
import random
import matplotlib
#matplotlib.use('Agg')
import HappyDemoUtils as happy_demo_utils
import matplotlib.pyplot as plt
import matplotlib.markers as mmarkers
from matplotlib.patches import Circle
import requests
import time
import datetime

def getTime():
    return time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())

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

class Point:
    def __init__(self):
        self.x=0
        self.y=0
        self.scatter_obj=None
        self.action='NO'
        self.open_time=getTime()


    def setAttr(self,x,y,action,scatter_obj):
        self.x=x
        self.y=y
        self.action=action
        self.scatter_obj=scatter_obj
        self.open_time = getTime()


class GameState:
    def __init__(self):
        self.figure = plt.figure()
        self.trainCount = 0
        #self.axes = self.figure.add_subplot(111)
        #self.axes.axis('off')
        self.init()



    def init(self):
        self.axes = self.figure.add_subplot(111)
        # self.axes.axis('off')
        self.range_start, self.range_end, self.range_step = 0, 1, 0.001
        t = np.arange(self.range_start, self.range_end, self.range_step)
        s = np.sin(4 * np.pi * t)
        self.l, = self.axes.plot(t, s, color='green')
        self.point_dict = {}
        # for x in dir(ts):
        #     print(x)
        self.rand = 0
        self.offset = 0
        self.reward_total = 0
        self.reduc = 0.000
        self.trainCount+=1

    def show(self):
        plt.show()

    def getPointCount(self):
        return len(self.point_dict)

    def requestStatic(self,act,act_exe,open_x,open_y,close_x,close_y,reward,close_time,open_time):
        payload = {'act': act,'act_exe':act_exe, 'open_x': open_x,'open_y':open_y,'close_x':close_x,'close_y':close_y,'reward':reward,'close_time':close_time,'open_time':open_time}
        r = requests.post("http://localhost/action_his.php", data=payload)
        if r.text.find('error')>=0:
            print('error:'+r.url)

    def actionNO(self,x,y):
        r=0.00
        for i in self.axes.collections:
            pt = self.point_dict[i]
            if getattr(pt,'action')=='BO' and getattr(pt,'y')>(y-self.reduc):
                tr=((y - self.reduc) - getattr(pt, 'y'))
                self.requestStatic('BO','NO',getattr(pt,'x'),getattr(pt,'y'),x,y,tr,getTime(),getattr(pt,'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]

            if getattr(pt,'action')=='SO' and getattr(pt,'y')<(y+self.reduc):
                tr = (getattr(pt, 'y') - (y + self.reduc))
                self.requestStatic('SO','NO', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr,getTime(),getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]
        return r

    def actionSO(self,x,y):
        ts = self.axes.scatter(x, y, s=30, marker='v',color='green')
        pt = Point()
        pt.setAttr(x, y,'SO',ts)
        self.point_dict[ts]=pt
        return 0.01



    def actionBO(self,x,y):
        ts = self.axes.scatter(x, y, s=30, marker='^',color='red')
        pt = Point()
        pt.setAttr(x, y, 'BO', ts)
        self.point_dict[ts] = pt
        return 0.01


    def actionCA(self,x,y):
        r = 0.0
        for i in self.axes.collections:
            pt = self.point_dict[i]
            if getattr(pt, 'action') == 'BO':
                tr = ((y - self.reduc) - getattr(pt, 'y'))
                self.requestStatic('BO','CA', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr, getTime(), getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]

            if getattr(pt, 'action') == 'SO':
                tr = (getattr(pt, 'y') - (y + self.reduc))
                self.requestStatic('SO','CA', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr, getTime(),getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]
        return r

    def actionSOBC(self,x,y):
        ts = self.axes.scatter(x, y, s=30, marker='v',color='green')
        pt = Point()
        pt.setAttr(x, y, 'SO', ts)
        self.point_dict[ts] = pt
        r = 0.01
        for i in self.axes.collections:
            pt = self.point_dict[i]
            if getattr(pt,'action')=='BO':
                tr = ((y - self.reduc) - getattr(pt, 'y'))
                self.requestStatic('BO','SOBC', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr,getTime(), getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]
        return r

    def actionBOSC(self,x,y):
        ts = self.axes.scatter(x, y, s=30, marker='^',color='red')
        pt = Point()
        pt.setAttr(x, y, 'BO', ts)
        self.point_dict[ts] = pt
        r = 0.01
        for i in self.axes.collections:
            pt = self.point_dict[i]
            if getattr(pt, 'action') == 'SO':
                tr = (getattr(pt, 'y') - (y + self.reduc))
                self.requestStatic('SO','BOSC', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr, getTime(), getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]
        return r

    def actionBC(self,x,y):
        r = 0.0
        for i in self.axes.collections:
            pt = self.point_dict[i]
            if getattr(pt, 'action') == 'BO':
                tr = ((y - self.reduc) - getattr(pt, 'y'))
                self.requestStatic('BO','BC', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr, getTime(), getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]
        return r

    def actionSC(self,x,y):
        r = 0.0
        for i in self.axes.collections:
            pt = self.point_dict[i]
            if getattr(pt, 'action') == 'SO':
                tr = (getattr(pt, 'y') - (y + self.reduc))
                self.requestStatic('SO','SC', getattr(pt, 'x'), getattr(pt, 'y'), x, y, tr, getTime(), getattr(pt, 'open_time'))
                #r += tr
                if tr<0:
                    r += -1
                else:
                    r += tr
                i.remove()
                del self.point_dict[i]
        return r

    def action(self,input_actions,x,y):
        # NO, SO, BO, CA, SO_BC, BO_SC, BC, SC
        # input_actions[0] == 1: NO do nothing but check
        if input_actions[0] == 1:
            return self.actionNO(x,y)
        # input_actions[1] == 1: SO
        elif input_actions[1] == 1:
            return self.actionSO(x,y)
        # input_actions[2] == 1: BO
        elif input_actions[2] == 1:
            return self.actionBO(x,y)
        # input_actions[3] == 1: CA
        elif input_actions[3] == 1:
            return self.actionCA(x,y)
        # input_actions[4] == 1: SO_BC
        elif input_actions[4] == 1:
            return self.actionSOBC(x,y)
        # input_actions[5] == 1: BO_SC
        elif input_actions[5] == 1:
            return self.actionBOSC(x,y)
        # input_actions[6] == 1: BC
        elif input_actions[6] == 1:
            return self.actionBC(x,y)
        # input_actions[7] == 1: SC
        elif input_actions[7] == 1:
            return self.actionSC(x,y)

    def frame_step(self, input_actions):
        reward = 0.001
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

        reward = self.action(input_actions,xdata[-1],ydata[-1])

        self.reward_total += reward

        #print('%f,%f' % (xdata[-1], ydata[-1]))
        print('pointcount:%d,total reward:%f,traincount:%d' %(len(self.point_dict),self.reward_total,self.trainCount))

        #
        # self.rand+=1
        # filename="png/"+str(self.rand)+".png"
        # plt.imsave(filename,fig2data(self.figure))

        # ?
        image_data = fig2data(self.figure)

        plt.pause(0.01)  # pause a bit so that plots are updated

        # check if crash here
        if self.reward_total < -1:
            terminal = True
            plt.gcf().clf()
            self.init()
            reward = -1

        return image_data, reward, terminal

