<?php
session_start();
$ws = new swoole_websocket_server('0.0.0.0', 9996);




$ws->on('open', function ($ws, $request) {

	// 收到前台的websocket请求,打印请求信息	
	$fd = $request->fd;
	var_dump($request->fd, $request->get, $request->server);
	// $ws->push($request->fd,"hello websocket\n");
	echo '链接成功' . PHP_EOL;


	$name = ($request->get['name'] != 'null') ? $request->get['name'] : '游客';
// 链接成功的时候吧 占用的 fd号码和 name room_id联系起来
	$_SESSION['link'.$fd]=array();
	$_SESSION['link' . $fd]['name']=$name;
	$_SESSION['link' . $fd]['room_id']=1;

	if ($name) {
		
		include("PDOconfig.php");

		$sql = "insert into user (name , loadtime) values(:name,:loadtime)";

		$stmt = $pdo->prepare($sql);
		$loadtime = time();
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':loadtime', $loadtime);

		$res = $stmt->execute();

		if ($res) {
			echo '数据写入成功';
// 进入 1 号 个房间
			$room_id= $_SESSION['link' . $fd]['room_id'];

			$GLOBALS['room'][$room_id][]=$fd;
			
			// 发送同一个房间的广播
			foreach ($GLOBALS['room'][$room_id] as $key => $val) {
				$ws->push($val, '{"name":"' . $name . '","mark":"first"}');
			}

		} else {
			echo "数据写入失败" . $res;
		}

	}

});

$ws->on('message', function ($ws, $frame) {
/* 
$frame->data  传入的数据
$frame->fd  链接标记号
*/

	var_dump($frame);
	$data = json_decode($frame->data, true);
	echo '监听message传入的数据'.PHP_EOL;
	var_dump($data);
$fd=$frame->fd;
$name= $_SESSION['link' . $fd]['name'];
$room_id= $_SESSION['link' . $fd]['room_id'];

	switch ($data['mark']) {
		/* 
	$_SESSION['link'.$fd]=array();
	$_SESSION['link' . $fd]['name']=$name; 
	$_SESSION['link' . $fd]['room_id']=1;
		 */
		// 换房间
		case 'changeroom':
			// 遍历 老房间 广播谁退出了
			foreach ($GLOBALS['room'][$room_id] as $key => $value) {
				# code...
				if($value == $frame->fd){
					unset($GLOBALS['room'][$room_id][$key]); //老房间信息删除
					$GLOBALS['room'][$data['room_id']][]=$frame->fd;
					echo 'globals全局数组'.PHP_EOL;
					var_dump($GLOBALS['room']);

					// 数据库修改房间号
					include("PDOconfig.php");
					$sql = "update user set room_id = :room_id where name = :name ";


					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name);
					$stmt->bindParam(':room_id', $data['room_id']);

					$res = $stmt->execute();
					if($res){
						echo '换房间成功';
						$_SESSION['link' . $fd]['room_id']=$data['room_id'];
					}else{
						echo '换房间失败';
					}
				}else{
					// 告诉老房间谁退出了 老房间号在session
					$ws->push($value, '{"name":"' . $name . '","mark":"out"}');
					
				}
// 告诉新房间谁进来了 新房间号在 data[room_id]
				foreach ($GLOBALS['room'][$data['room_id']] as $key => $value) {
					# code...
					if ($value == $frame->fd) {
						$ws->push($value, '{"name":"' . $name . '","mark":"selfchange"}');
					}else{
						$ws->push($value, '{"name":"' . $name . '","mark":"changefirst"}');
					}
				}



			}	

		break;
// 互发消息
		case 'going':
			$msg = $data['msg'];

			foreach ($GLOBALS['room'][$room_id] as $key => $value) {

				if ($frame->fd == $value) {
					$ws->push($value, '{"name":"' . $name . '","msg":"' . $msg . '","mark":"self"}');
				} else {
					$ws->push($value, '{"name":"' . $name . '","msg":"' . $msg . '","mark":"other"}');
				}

			}

		break;
	}

});


$ws->on('close', function ($ws, $fd) {
	/* 
	$_SESSION['link'.$fd]=array();
	$_SESSION['link' . $fd]['name']=$name;
	$_SESSION['link' . $fd]['room_id']=1;
	 */
	echo "client-{$fd} is closed\n";

	$name = $_SESSION['link' . $fd]['name'];
	$room_id=$_SESSION['link' . $fd]['room_id'];

	foreach ($GLOBALS['room'][$room_id] as $key => $value) {

		if($value == $fd){
			unset($GLOBALS['room'][$room_id][$key]);
			echo '剩下的全局数组中 房间里面的人';
			var_dump($GLOBALS['room']);
		}else{
			$ws->push($value, '{"name":"' . $name . '","mark":"out"}');
		}
		
	}
	$_SESSION['link' . $fd] =null;
// 退出注销
	include("PDOconfig.php");
	$leavetime=time();
	$sql = "update user set leavetime = :leavetime,isleave = 1 where name = :name ";


	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':leavetime', $leavetime);
	$stmt->bindParam(':name', $name);

	$res = $stmt->execute();
	if($res){
		echo '注销成功';
	}else{
		echo '自己看着办吧';
	}
});

$ws->start();
