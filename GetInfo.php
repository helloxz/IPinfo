<?php
	$apiurl = array("ipip" => "http://freeapi.ipip.net/","taobao" => "http://ip.taobao.com/service/getIpInfo.php?ip=","sina" => "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=","geoip" => "https://api.ip.sb/geoip/");
	$type = $_GET['type'];
	$ip = $_GET['ip'];
	$fullurl = $apiurl[$type].$ip;

	queryip($fullurl);

	function queryip($fullurl){
		$curl = curl_init($fullurl);

	    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36");
	    curl_setopt($curl, CURLOPT_FAILONERROR, true);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	    $reinfo = curl_exec($curl);
	    curl_close($curl);
	    echo $reinfo;
	}
?>