<?php
	//定义项目绝对路径，如果不清楚请先访问check.php获取
	define("APP","D:/wwwroot/IPinfo/");
	include_once(APP."class/Medoo.php");

	//初始化Medoo
    use Medoo\Medoo;
    $database = new medoo([
        'database_type' => 'sqlite',
        'database_file' => APP."data/ipinfo.db3"
    ]);
?>