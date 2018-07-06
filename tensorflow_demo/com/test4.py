import gym
import gym.spaces

env = gym.make('CartPole-v0')
env.reset()

for _ in range(1000):
    env.render()