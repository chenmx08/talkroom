<?php if (!defined('THINK_PATH')) exit();?><!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>websocket测试代码</title>
</head>
<script>



    var wsServer = 'ws://192.168.145.128:9996';
    var websocket = new WebSocket(wsServer);

    websocket.onopen = function (evt) {
        console.log('connect to websocket server.');
        websocket.send('我连上了');

    }

    websocket.onclose = function (evt) {
        console.log('disconnected');
    }

    websocket.onmessage = function (evt) {
        console.log('retrieved data from server:' + evt.data);
    }

    websocket.onerror = function (evt, e) {
        console.log('error occured: ' + evt.data);
    }

    function bbb() {
        websocket.send('我发了一个消息');
    }



</script>
<body>
    <form action=""></form>

    <a href="" onclick="bbb()">开启</a>
</body>

</html> -->





    <!DOCTYPE html>
    <html lang="cn">
    
    <head>
        <title>WebSocket chart application</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/4.0.0-alpha.2/css/bootstrap.css">
        <link rel="stylesheet" href="http://cdn.bootcss.com/tether/1.3.2/css/tether.css" />
        <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.js"></script>
        <script src="http://cdn.bootcss.com/tether/1.3.2/js/tether.js"></script>
        <script src="http://cdn.bootcss.com/bootstrap/4.0.0-alpha.2/js/bootstrap.js"></script>
        <script>  
            var ws = new WebSocket('ws://192.168.145.128:9996');
            var nickname;
            ws.onopen = function (e) {
                console.log('Connection to server opened');
            }
            function appendLog(type, nickname, message, clientcount) {
                var messages = document.getElementById('messages');
                var messageElem = document.createElement("li");
                var preface_label;
                if (type === 'notification') {
                    preface_label = "<span class=\"label label-info\">*</span>";
                } else if (type === 'nick_update') {
                    preface_label = "<span class=\"label label-warning\">*</span>";
                } else {
                    preface_label = "<span class=\"label label-success\">" + nickname + "</span>";
                }
                var message_text = "<h2>" + preface_label + "  " + message + "</h2>";
                messageElem.innerHTML = message_text;
                messages.appendChild(messageElem);
                var count_people = document.getElementById("count_people");
                count_people.innerHTML = clientcount;

            }
            ws.onmessage = function (e) {
                // var data = JSON.parse(e.data);
                var data= eval('('+e.data+')');
                // var data=e.data;
                nickname = data.nickname;
                appendLog(data.type, data.nickname, data.message, data.clientcount);
                console.log("ID: [%s] = %s", data.id, data.message);


            }
            function sendMessage() {
                var message = $('#message').val().trim();
                if (message.length < 1) {
                    alert("不能发送空内容！");
                    return;
                }
                ws.send($('#message').val());
                $('#message').val("");
                $('#message').focus();
                console.log(ws.bufferedAmount);
            }  
        </script>
    </head>
    
    <body lang="cn">
        <div class="vertical-center">
            <div class="container">
                <h2>多人在线聊天DEMO</h2>
                <hr />
                <p>当前在线人数：
                    <span id="count_people">0</span>
                </p>
                <ul id="messages" class="list-unstyled">
    
                </ul>
                <hr />
                <form role="form" id="chat_form" onsubmit="sendMessage(); return false;">
                    <div class="form-group">
                        <input class="form-control" type="text" name="message" id="message" placeholder="输入聊天内容" value="" autofocus/>
                    </div>
                    <button type="button" id="send" class="btn btn-primary" onclick="sendMessage();">发送!</button>
                </form>
            </div>
        </div>
    </body>
    
    </html>