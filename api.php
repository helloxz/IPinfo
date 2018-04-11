<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	//载入纯真IP
	include_once( 'qqwry.php' );

	$ip = $_GET['ip'];		//获取IP
	$type = $_GET['type'];	//获取数据类型
	$data = $_GET['data'];	//要返回的数据

	//如果IP不存在或者为空
	if((!isset($ip) || ($ip == ''))) {
		//获取访客IP
		$ip = $_SERVER["REMOTE_ADDR"];
	}
	//如果IP格式不对
	if(!filter_var($ip, FILTER_VALIDATE_IP)) {
		$reinfo = array(
			"status" => 0,
			"msg" => 'IP格式不对'
		);
		echo json_encode($reinfo);
		exit;
	}
	//查询IP
	$qqwry = new ip();
	$addr = $qqwry -> ip2addr("$ip");
	$addr = array_slice($addr,2,4);
	$addr = implode(" ",$addr);

	//返回不同的数据
	switch ( $data )
	{
		case 'ip':
			$addr = '';
			break;
		case 'addr':	
			$ip = '';
			break;	
		default:
			$ip = $ip;
			$addr = $addr;
			break;
	}

	//根据类型返回不同结果
	switch ( $type )
	{
		case 'text':
			echo $ip." ".$addr;
			break;	
		case 'json':
			$info = array(
				"status" => 1,
				"ip" => $ip,
				"addr" => $addr
			);
			echo json_encode($info);
			exit;	
		default:
			echo $ip." ".$addr;
			break;
	}
?>