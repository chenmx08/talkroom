<?php

$http = new swoole_http_server('0.0.0.0', 80, SWOOLE_BASE);
$http->on('request', function (swoole_http_request $req, swoole_http_response $res) use ($http) {
    $res->write("hello world");
    $res->end();
});




    $serv = new swoole_server ('127.0.0.1', 9999);

    $serv->on('connect', function (swoole_server $serv, $fd) {
        echo 'Client:Connect.' . PHP_EOL;
    });

    $serv->on('receive', function (swoole_server $serv, $fd, $fromId, $data) {
        $serv->send($fd, 'Server:' . $data);
    });

    $serv->on('close', function (swoole_server $serv, $fd) {
        echo 'Client:Close' . PHP_EOL;
    });

    $serv->start();

$http = new swoole_http_server('0.0.0.0', 9997);

$http->on('rquest', function ($request, $response) {
    var_dump($request->get(), $request->post());
    $response->header("Content-Type", "text/html;charset=utf-8");
    $response->end("<h1>Hello Swoole.#" . rand(1000, 9999) . "</h1>");
});

$http->start();
