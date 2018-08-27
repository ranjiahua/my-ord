<?php

//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server('0.0.0.0', 9503);
$ws->user = [];
//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    // var_dump($request->fd, $request->get, $request->server);
    // var_dump($ws->connections);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    $ws->user[$frame->fd] = $frame->fd;
    var_dump($ws->user);

    foreach ($ws->connections as $fd) {
        $ws->push($fd, "{$frame->data}");
    }
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();
