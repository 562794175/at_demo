#!usr/bin/env python
# encoding:utf-8

import math


def eval_test():
    l = '[1,2,3,4,[5,6,7,8,9]]'
    d = "{'a':123,'b':456,'c':789}"
    t = '([1,3,5],[5,6,7,8,9],[123,456,789])'
    print('--------------------------转化开始--------------------------------')

    print(type(l), type(eval(l)))

    print(type(d), type(eval(d)))

    print(type(t), type(eval(t)))



if __name__ == "__main__":
    eval_test()
