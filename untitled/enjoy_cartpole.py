import gym
import numpy as np
from baselines import deepq


def main():
    env = gym.make("CartPole-v0")
    #act = deepq.load("cartpole_model.pkl")

    while True:
        obs, done = env.reset(), False
        episode_rew = 0
        while not done:
            env.render()
            #obs, rew, done, _ = env.step(act(obs[None])[0])


            action = np.random.choice([0, 1])  # 随机决定小车运动的方向
            obs, rew, done, _ = env.step(action)
            episode_rew += rew
        print("Episode reward", episode_rew)


if __name__ == '__main__':
    main()