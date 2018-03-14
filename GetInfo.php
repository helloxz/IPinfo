<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	//接口地址
	$apiurl = array("default" => "http://freeapi.ipip.net/","ipip" => "http://freeapi.ipip.net/","taobao" => "http://ip.taobao.com/service/getIpInfo.php?ip=","sina" => "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=","geoip" => "https://api.ip.sb/geoip/");
	$type = $_GET['type'];		//获取接口类型
	$ip = $_GET['ip'];			//获取IP
	$ip = geturl($ip);			//对域名进行解析并判断IP
	//对IP进行判断
	//$isip = filter_var($ip, FILTER_VALIDATE_IP);
	//if((!isset($ip)) || !$isip) {
	//	echo 'IP格式有误';
	//	exit;
	//}
	$fullurl = $apiurl[$type].$ip;

	queryip($ip,$type,$fullurl);

	function queryip($ip,$type,$fullurl){
		$curl = curl_init($fullurl);

	    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36");
	    curl_setopt($curl, CURLOPT_FAILONERROR, true);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	    $reinfo = curl_exec($curl);
	    curl_close($curl);
	    //echo $reinfo;
	    switch ( $type )
	    {	
	    	//返回默认数据
	    	case 'default':
	    		$ipinfo = json_decode($reinfo);
	    		$ipinfo = array("status" => 1,"ip" => $ip,"country" => $ipinfo[0],"region" => $ipinfo[1],"city" => $ipinfo[2],"county" => $ipinfo[3],"isp" => $ipinfo[4]);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;	
	    	//返回淘宝数据
	    	//返回ipip.net数据
	    	case 'ipip':
	    		$ipinfo = json_decode($reinfo);
	    		$ipinfo = array("status" => 1,"ip" => $ip,"country" => $ipinfo[0],"region" => $ipinfo[1],"city" => $ipinfo[2],"county" => $ipinfo[3],"isp" => $ipinfo[4]);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;	
	    	//返回淘宝数据
	    	case 'taobao':
	    		$ipinfo = json_decode($reinfo);
	    		$ipinfo = $ipinfo->data;
	    		$ipinfo->county = str_replace("X","",$ipinfo->county);
	    		$ipinfo = array(
	    			"status" => 1,
	    			"ip" => $ip,
	    			"country" => $ipinfo->country,
	    			"region" => $ipinfo->region,
	    			"city" => $ipinfo->city,
	    			"county" => $ipinfo->county,
	    			"isp" => $ipinfo->isp
	    		);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;
	    	//返回新浪数据
	    	case 'sina':
	    		$ipinfo = json_decode($reinfo);
	    		//print_r($ipinfo);
	    		//exit;
	    		$ipinfo = array(
	    			"status" => 1,
	    			"ip"	=>	$ip,
	    			"country"	=>	$ipinfo->country,
	    			"region"	=>	$ipinfo->province,
	    			"city"		=>	$ipinfo->city,
	    			"county"	=>	$ipinfo->district,
	    			"isp"		=>	$ipinfo->isp
	    		);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;
	    	//返回GEOIP数据
	    	case 'geoip':
	    		$ipinfo = json_decode($reinfo);
	    		$ipinfo = array(
	    			"status" => 1,
	    			"ip"	=>	$ip,
	    			"country"	=>	$ipinfo->country,
	    			"region"	=>	$ipinfo->region,
	    			"city"		=>	$ipinfo->city,
	    			"county"	=>	"",
	    			"isp"		=>	$ipinfo->organization
	    		);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;
	    	default:
	    		$ipinfo = array(
					"status"	=>	0,
					"msg"		=> 	"未识别的接口！"
	    		);
	    		echo json_encode($ipinfo);
	    		break;
	    }
	}

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
?>