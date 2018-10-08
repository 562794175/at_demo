import cv2
import sys
import numpy as np
import HappyDemoWrapped as game
from BrainDQN_Nature import BrainDQN

import threading
import time
import inspect
import ctypes


def _async_raise(tid, exctype):
	"""raises the exception, performs cleanup if needed"""
	tid = ctypes.c_long(tid)
	if not inspect.isclass(exctype):
		exctype = type(exctype)
	res = ctypes.pythonapi.PyThreadState_SetAsyncExc(tid, ctypes.py_object(exctype))
	if res == 0:
		raise ValueError("invalid thread id")
	elif res != 1:
		# """if it returns a number greater than one, you're in trouble,
		# and you should call it again with exc=NULL to revert the effect"""
		ctypes.pythonapi.PyThreadState_SetAsyncExc(tid, None)
		raise SystemError("PyThreadState_SetAsyncExc failed")


def stop_thread(thread):
	_async_raise(thread.ident, SystemExit)

def preprocess(observation):
	observation = cv2.cvtColor(cv2.resize(observation, (80, 80)), cv2.COLOR_BGR2GRAY)
	ret, observation = cv2.threshold(observation,1,255,cv2.THRESH_BINARY)
	return np.reshape(observation,(80,80,1))


def playHapplyDemo():
	# Step 1: init BrainDQN
	actions = 4
	brain = BrainDQN(actions)
	# Step 2: init Flappy Bird Game
	happyDemo = game.GameState()

	action0 = np.array([1,0,0,0])  # do nothing
	#action0 = np.array([1, 0])  # do nothing
	observation0, reward0, terminal = happyDemo.frame_step(action0)
	observation0 = cv2.cvtColor(cv2.resize(observation0, (80, 80)), cv2.COLOR_BGR2GRAY)
	ret, observation0 = cv2.threshold(observation0, 1, 255, cv2.THRESH_BINARY)
	brain.setInitState(observation0)

	t = threading.Thread(target=playStep,args=(happyDemo,brain,))
	t.start()
	happyDemo.show()
	stop_thread(t)

def playStep(happyDemo,brain):
	while True:

		# if happyDemo.getPointCount()==0:
		# 	action = np.array([0,0,0,0,0,0,0,0])
		# 	at = np.random.randint(0, 8)
		# 	action[at]=1
		#
		# elif happyDemo.getPointCount() <= 10:
		# 	action = brain.getAction()

		action = brain.getAction()

		nextObservation, reward, terminal = happyDemo.frame_step(action)
		nextObservation = preprocess(nextObservation)
		brain.setPerception(nextObservation, action, reward, terminal)

def main():
	playHapplyDemo()

if __name__ == '__main__':
	main()
