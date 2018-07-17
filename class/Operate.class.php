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
	}

	$handle = new Operate($database);
?>