<?php
	include_once("../config.php");
	include_once("../class/qqwry.php");
	include_once("../class/Query.Class.php");

	@$ip = htmlspecialchars($_GET['ip']);
	@$source = htmlspecialchars($_GET['source']);

	$query->delcache($ip,$source);
?>