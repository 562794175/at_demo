<?php
require_once 'import.php';
require_once 'predict.php';
require_once 'skdzdeal.php';
require_once 'order.php';
require_once 'log.php';
$context = new ZMQContext(1);
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind("tcp://*:5555");
while (true) {
    $request = $responder->recv();
    $data = json_decode($request,true);
    if(empty($data)) {
        $responder->send("wrong data!");
    }
    $task=$data['Task'];
    if($task=="import") {
        $targetid=taskImport($data);
        $state=taskPredict($data);
        $aResult=[
            "targetid"=>$targetid,
            "state"=>$state,
        ];
        $result=json_encode($aResult);
        $responder->send($result);
    } else if($task=="skdzengine") {
        $deal=taskSKDZDeal($data);
        $result=json_encode($deal);
        $responder->send($result);
    } else if($task=="order") {
        $orderid= taskOrder($data);
        $responder->send($orderid);
    } else if($task=="log") {
        taskLog($data);
        $responder->send(0);
    }
    usleep (1);
}


