# coding=UTF-8

import gym
import json

lname=['Hopper-v2','MsPacman-v0','MountainCar-v0','CartPole-v0']


env=gym.make(lname[0])

json.dump()

env.reset()

for _ in range(1000):
    env.render()
    env.step(env.action_space.sample())