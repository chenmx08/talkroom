# talkroom
基于swoole的websocket初步搭建的多房间聊天室

套用的是thinkPHP3的框架，因为最近工作用的是它。
主要文件

/websocket.php  websocket服务器脚本，使用命令行 php websocket运行
/thinkPHP3/websocket/index.php  入口文件
/PDOconfig.php  websocket中数据库操作的配置文件
/thinkPHP3/websocket/Lib/Action/IndexAction.class.php  控制器文件

