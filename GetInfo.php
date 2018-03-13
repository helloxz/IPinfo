<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	$apiurl = array("default" => "http://freeapi.ipip.net/","ipip" => "http://freeapi.ipip.net/","taobao" => "http://ip.taobao.com/service/getIpInfo.php?ip=","sina" => "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=","geoip" => "https://api.ip.sb/geoip/");
	$type = $_GET['type'];
	$ip = $_GET['ip'];
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
	    		$ipinfo = array("ip" => $ip,"country" => $ipinfo[0],"region" => $ipinfo[1],"city" => $ipinfo[2],"county" => $ipinfo[3],"isp" => $ipinfo[4]);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;	
	    	//返回淘宝数据
	    	//返回ipip.net数据
	    	case 'ipip':
	    		$ipinfo = json_decode($reinfo);
	    		$ipinfo = array("ip" => $ip,"country" => $ipinfo[0],"region" => $ipinfo[1],"city" => $ipinfo[2],"county" => $ipinfo[3],"isp" => $ipinfo[4]);
	    		$ipinfo = json_encode($ipinfo);
	    		echo $ipinfo;
	    		break;	
	    	//返回淘宝数据
	    	case 'taobao':
	    		$ipinfo = json_decode($reinfo);
	    		$ipinfo = $ipinfo->data;
	    		$ipinfo->county = str_replace("X","",$ipinfo->county);
	    		$ipinfo = array(
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
	    		echo '类型有误!';
	    		break;
	    }
	}
?>