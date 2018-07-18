<?php
/*
	使用此类之前务必载入config.php	class/qqwry.php 否则会报错
*/	
	class IPinfo{
		var $database;
		var $apiurl;
		//构造函数
		function __construct($database,$qqwry) {
			$this->database = $database;
			$this->qqwry = $qqwry;
			#$this->apiurl = $apiurl;
		}
		//对IP进行判断
		function checkip($ip){
			if(!filter_var($ip, FILTER_VALIDATE_IP)) {
				$info = array(
					"code"		=>	0,
					"msg"		=>	"不是有效IP！"
				);
				$info = json_encode($info);
				echo $info;
				exit;
			}
		}
		//生成缓存，2个参数，一个是IP，一个是查询接口
		function caches($ip,$interface){
			//对IP进行判断，如果不是有效IP终止执行
			$this->checkip($ip);
			//通过IP验证，继续执行

			//获取当前时间
			(int)$thetime = date("Ymd",time());
			//7天以前
			(int)$before7 = date('Ymd',strtotime('-7 day'));

			//查询数据库
			$reinfo = $this->database->get("cache","*",[
				"ip"		=>	$ip,
				"source"	=>	$interface,
				"date[>=]"	=>	$before7		
			]);

			//如果数据不为空
			if($reinfo) {
				$reinfo['code']	= 1;
				$reinfo = json_encode($reinfo);
				return $reinfo;
			}

			//如果数据为空，继续执行
			//判断接口
			else{
				switch ( $interface )
				{
					case 'ipip':
						return $this->ipip($ip);
						break;		
					case 'taobao':
						return $this->taobao($ip);
						break;
					case 'geoip':
						return $this->geoip($ip);
						break;
					case 'qqwry':
						return $this->qqwry($ip);
						break;
					case 'lbsqq':
						return $this->lbsqq($ip);
						break;
					default:
						return $this->qqwry($ip);
						break;
				}
			}
		}
		//curl抓取数据
		function curl($url){
			$curl = curl_init($url);

		    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");
		    curl_setopt($curl, CURLOPT_FAILONERROR, true);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		    #设置超时时间，最小为1s（可选）
		    curl_setopt($curl , CURLOPT_TIMEOUT, 10);

		    $html = curl_exec($curl);
		    curl_close($curl);
		    return $html;
		}
		//通用写入数据，三个参数，一个IP，一个地址，一个数据来源
		function writed($ip,$address,$source){
			//写入数据库
			//获取当前时间
			(int)$thetime = date("Ymd",time());
			$insert = $this->database->insert("cache",[
				"ip"		=>	$ip,
				"address"	=>	$address,
				"date"		=>	$thetime,
				"source"	=>	$source
			]);
			
			//如果写入成功
			if($insert){
				$re = array(
					"code"		=>	1,
					"ip"		=>	$ip,
					"address"	=>	$address,
					"date"		=>	$thetime,
					"source"	=>	$source
				);
				$re = json_encode($re);
				return $re;
			}
			//其它情况
			else{
				$re = array(
					"code"		=>	1,
					"msg"		=>	"数据库写入失败！"
				);
				$re = json_encode($re);
				echo $re;
			}
		}
		//缓存ipip.net数据
		function ipip($ip){
			//验证IP
			$this->checkip($ip);
			//接口地址
			$apiurl = "http://freeapi.ipip.net/".$ip;
			$info = $this->curl($apiurl);
			$info = json_decode($info);
			
			$address = $info[0].' '.$info[1].' '.$info[2].$info[3].' '.$info[4];

			//写入并返回数据
			$re = $this->writed($ip,$address,'ipip');
			return $re;
		}
		//缓存淘宝数据
		function taobao($ip){
			//验证IP
			$this->checkip($ip);
			//接口地址
			$apiurl = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
			$info = $this->curl($apiurl);
			$info = json_decode($info);
			

			$taobao = $info->data;
			$taobao = $taobao->country." ".$taobao->region." ".$taobao->city." ".$taobao->county." ".$taobao->isp;
			$taobao = str_replace("XX","",$taobao);

			$address = $taobao;
			//写入并返回数据
			$re = $this->writed($ip,$address,'taobao');
			return $re;
		}
		//缓存GeoIP数据
		function geoip($ip){
			//验证IP
			$this->checkip($ip);
			//接口地址
			$apiurl = "https://api.ip.sb/geoip/".$ip;
			$info = $this->curl($apiurl);
			#var_dump($apiurl);

			$geoip = json_decode($info);
			$geoip = $geoip->country." ".$geoip->region." ".$geoip->city." ".$geoip->organization;

			$address = $geoip;
			//写入并返回数据
			$re = $this->writed($ip,$address,'geoip');

			return $re;
		}
		//缓存纯真ipip
		function qqwry($ip){
			//验证IP
			$this->checkip($ip);
			$addr = $this->qqwry -> ip2addr($ip);
			$addr = array_slice($addr,2,4);
			$addr = implode(" ",$addr);

			$address = $addr;
			//写入并返回数据
			$re = $this->writed($ip,$address,'qqwry');
			return $re;
		}
		//缓存腾讯数据
		function lbsqq($ip){
			//验证IP
			$this->checkip($ip);
			$apiurl = "https://apis.map.qq.com/ws/location/v1/ip?key=".LBSQQ."&ip=".$ip;
			$info = $this->curl($apiurl);

			$lbsqq = json_decode($info);

			$address = $lbsqq->result->ad_info;
			$address = $address->nation.' '.$address->province.' '.$address->city.' '.$address->district;
			
			//写入并返回数据
			$re = $this->writed($ip,$address,'lbsqq');
			return $re;
		}
		//清除缓存
		function delcache($ip,$source){
			//验证IP
			$this->checkip($ip);
			$del = $this->database->delete("cache",[
				"AND" => [
					"ip" 		=> $ip,
					"source"	=> $source
				]
			]);
			if($del){
				$re = array(
					"code"		=>	1,
					"msg"		=>	'缓存已清除，请重新查询！'
				);
				$re = json_encode($re);
				echo $re;
				exit;
			}
			else{
				$re = array(
					"code"		=>	0,
					"msg"		=>	'未知错误！'
				);
				$re = json_encode($re);
				echo $re;
				exit;
			}
		}
	}

	$query = new IPinfo($database,$qqwry);
?>