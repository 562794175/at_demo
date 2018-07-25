import zmq
import numpy as np

class SocketHandler:
    def __init__(self):
        # Sample Commands for ZeroMQ MT4 EA
        self.eurusd_buy_order = "TRADE|OPEN|0|EURUSD|0|50|50|Python-to-MT4"
        self.eurusd_sell_order = "TRADE|OPEN|1|EURUSD|0|50|50|Python-to-MT4"
        self.eurusd_closebuy_order = "TRADE|CLOSE|0|EURUSD|0|50|50|Python-to-MT4"
        self.get_rates = "RATES|XAUUSD"
        #self.get_data = "DATA|XAUUSD|PERIOD_M15|2017.07.23 00:00|2018.07.25 00:00"
        self.get_data_close = "DATA|XAUUSD|PERIOD_M15|1|10000|close"
        self.get_data_volume = "DATA|XAUUSD|PERIOD_M15|1|10000|volume"
        self.context=None
        self.reqSocket=None
        self.pullSocket = None
        self.bid=0.0
        self.ask=0.0
        self.t = []
        self.s_bid = []
        self.s_ask = []


    # Sample Function for Client
    def create_client(self):
        # Create ZMQ Context
        self.context = zmq.Context()

        # Create REQ Socket
        self.reqSocket = self.context.socket(zmq.REQ)
        self.reqSocket.connect("tcp://172.16.211.193:5555")

        # Create PULL Socket
        self.pullSocket = self.context.socket(zmq.PULL)
        self.pullSocket.connect("tcp://172.16.211.193:5556")

        #Init list
        range_start, range_end, range_step = 0, 1, 0.005
        self.t = np.arange(range_start, range_end, range_step)


        # Send RATES command to ZeroMQ MT4 EA
        self.remote_send(self.reqSocket, self.get_rates)

        # Send BUY EURUSD command to ZeroMQ MT4 EA
        # remote_send(reqSocket, eurusd_buy_order)

        # Send CLOSE EURUSD command to ZeroMQ MT4 EA. You'll need to append the
        # trade's ORDER ID to the end, as below for example:
        # remote_send(reqSocket, eurusd_closebuy_order + "|" + "12345678")

        # PULL from pullSocket
        self.remote_pull(self.pullSocket)

    def send_getdata_close(self):
        return self.remote_send(self.reqSocket, self.get_data_close)

    def send_getdata_volume(self):
        return self.remote_send(self.reqSocket, self.get_data_volume)

    def pull_getdata(self):
        data = self.remote_pull(self.pullSocket)
        if data != None:
            msg = data.split("|")
            del msg[0]
            msg.reverse()
            # 转成数值类型
            return list(map(float,msg))

    def send_getrates(self):
        return self.remote_send(self.reqSocket, self.get_rates)

    def pull_getrates(self):
        rate = self.remote_pull(self.pullSocket)
        if rate != None:
            msg = rate.split("|")
            self.bid = float(msg[0])
            self.ask = float(msg[1])
        if self.bid != 0:
            if len(self.s_bid)>=len(self.t):
                del self.s_bid[0]
                del self.s_ask[0]
            self.s_bid.append(self.bid)
            self.s_ask.append(self.ask)
        return rate


    # Function to send commands to ZeroMQ MT4 EA
    def remote_send(self,socket, data):
        try:
            socket.send_string(data)
            msg = socket.recv_string()
            #print(msg)
            return msg

        except zmq.Again as e:
            #print("Waiting for PUSH from MetaTrader 4..")
            pass


    # Function to retrieve data from ZeroMQ MT4 EA
    def remote_pull(self,socket):
        try:
            msg = socket.recv_string(flags=zmq.NOBLOCK)
            #print(msg)
            return msg

        except zmq.Again as e:
            #print("Waiting for PULL from MetaTrader 4..")
            pass

