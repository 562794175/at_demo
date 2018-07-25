import zmq
import random
import sys
import time

port = "5556"
context = zmq.Context()

socket_server = context.socket(zmq.PAIR)
socket_server.bind("tcp://*:%s" % port)

socket = context.socket(zmq.PAIR)
socket.connect("tcp://localhost:%s" % port)

for i in range(10):
        socket.send_string("client message to server "+ str(i))

while True:
        msg = socket_server.recv()
        print(msg)
        time.sleep(20)