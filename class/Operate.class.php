<?php
	/*
	1.使用此类之前必须先载入config.php
	2.该类提供各种操作
	*/
	class Operate{
		var $database;

		function __construct($database){
			$this->database = $database;
		}

		//清除缓存方法
		function dcache(){
			//获取7天前的时间
			$btime = date("Ymd",strtotime("-7 day"));

			//清理数据库
			$data = $this->database->delete("cache",[
				"date[<]" 	=>	$btime
			]);

			echo "已清理 ".$btime." 之前的缓存数据，影响".$data->rowCount()."行！";
		}
		//获取访客真实IP
		function getip(){
			if (getenv('HTTP_CLIENT_IP')) { 
		    $ip = getenv('HTTP_CLIENT_IP'); 
		    } 
		    elseif (getenv('HTTP_X_FORWARDED_FOR')) { 
		    $ip = getenv('HTTP_X_FORWARDED_FOR'); 
		    } 
		    elseif (getenv('HTTP_X_FORWARDED')) { 
		    $ip = getenv('HTTP_X_FORWARDED'); 
		    } 
		    elseif (getenv('HTTP_FORWARDED_FOR')) { 
		    $ip = getenv('HTTP_FORWARDED_FOR'); 

		    } 
		    elseif (getenv('HTTP_FORWARDED')) { 
		    $ip = getenv('HTTP_FORWARDED'); 
		    } 
		    else { 
		    $ip = $_SERVER['REMOTE_ADDR']; 
		    } 
		    return $ip; 
		}
	}

	$handle = new Operate($database);
?>