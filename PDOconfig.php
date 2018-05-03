<?php

    $dsn="mysql:host=localhost;dbname=websocket;charset=utf8";

    $pdo=new PDO($dsn,'root','123456');
    $sql='set names utf8';
    $pdo->exec($sql);


    $pdo->setAttribute(3, 1);

    