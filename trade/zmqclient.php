<?php
/*

 * * Hello World client

 * * Connects REQ socket to tcp://localhost:5555

 * * Sends "Hello" to server, expects "World" back

 * * @author Ian Barber <ian(dot)barber(at)gmail(dot)com>

 * */



$context = new ZMQContext();



// Socket to talk to server

echo "Connecting to hello world server…\n";

$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);

$requester->connect("tcp://localhost:5555");

$date = json_encode(['Peroid'=>60,'Symbol'=>1,'GMTTime'=>1]);

if($requester->send($date) !== false){

    echo "send success\n";

}

$reply = $requester->recv();

printf ("Received:[%s]\n",$reply);