<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	//载入纯真查询接口
	include_once( 'qqwry.php' );
	//接口地址
	$apiurl = array("ipip" => "http://freeapi.ipip.net/","taobao" => "http://ip.taobao.com/service/getIpInfo.php?ip=","sina" => "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=","geoip" => "https://api.ip.sb/geoip/");
	
	$ip = $_GET['ip'];			//获取IP
	$ip = geturl($ip);			//对域名进行解析并判断IP
	//组合后得到完整的API地址
	//$fullurl = $apiurl[$type].$ip;

	//遍历一次API得到所有json数据
	foreach( $apiurl as $key => $apitype )
	{
		$curl = curl_init($apitype.$ip);

	    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36");
	    curl_setopt($curl, CURLOPT_FAILONERROR, true);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	    $reinfo[$key] = curl_exec($curl);
	    curl_close($curl);
	    //echo $reinfo['ipip'];
	    
	}
	$ipip = ipip($reinfo['ipip']);
	//echo $reinfo['taobao'];
	$taobao = taobao($reinfo['taobao']);
	$sina = sina($reinfo['sina']);
	$geoip =  geoip($reinfo['geoip']);
	$qqwry = qqwry($ip);

	$alldata = array(
		"status"	=>	1,
		"ip"		=>	$ip,
		"ipip"		=>	$ipip,
		"taobao"	=>	$taobao,
		"sina"		=>	$sina,
		"geoip"		=>	$geoip,
		"qqwry"		=> 	$qqwry
	);
	$alldata = json_encode($alldata);
	echo $alldata;
	exit;

	//域名解析为ip
	function geturl($url){
		$domain = parse_url($url);
		//主机名不存在，可能是IP
		if(!$domain['host']) {
			//如果是IP
			if(filter_var($url, FILTER_VALIDATE_IP)) {
				$ip = $url;
			}//如果不是IP，可能是一个域名
			else{
				$ip = gethostbyname($url);
			}
		}//是一个URL
		else{
			$domain = $domain['host'];
			$ip = gethostbyname($domain);
		}
		//echo $domain;
		//最后返回解析后的IP
		if(filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		}
		else{
			$ipinfo = array(
				"status"	=>	0,
				"msg"		=> 	"不是一个有效的IP或域名！"
    		);
    		echo json_encode($ipinfo);
			exit;
		}
	}
	//获取ipip.net返回数据
	function ipip($apijson){
		//echo $apijson;
		$ipip = json_decode($apijson);
		$ipip = $ipip[0]." ".$ipip[1]." ".$ipip[2]." ".$ipip[3]." ".$ipip[4];
		return $ipip;
	}
	//获取淘宝返回数据
	function taobao($apijson) {
		$taobao = json_decode($apijson);
		$taobao = $taobao->data;
		//var_dump($taobao);
		$taobao = $taobao->country." ".$taobao->region." ".$taobao->city." ".$taobao->county." ".$taobao->isp;
		return $taobao;
	}
	//获取新浪数据
	function sina($apijson) {
		$sina = json_decode($apijson);
		$sina = $sina->country." ".$sina->province." ".$sina->city." ".$sina->district." ".$sina->isp;
		return $sina;
	}
	//获取geoip
	function geoip($apijson){
		$geoip = json_decode($apijson);
		$geoip = $geoip->country." ".$geoip->region." ".$geoip->city." ".$geoip->organization;
		return $geoip;
	}
	//获取纯真数据
	function qqwry($ip) {
		$qqwry = new ip();
		$ip_address = $_GET['ip'];
		$addr = $qqwry -> ip2addr("$ip_address");
		$addr = array_slice($addr,2,4);
		$addr = implode(" ",$addr);
		return $addr;
	}
?>