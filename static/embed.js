layui.use(['layer', 'form'], function(){
  	var form = layui.form;
  	var layer = layui.layer;
  //各种基于事件的操作，下面会有进一步介绍
});
$(document).ready(function(){
	//访问页面时加载
	var getip = $("#getip").text();
	$.get("./GetInfo.php?type=taobao"+"&ip="+getip,function(data,status){
		if(status == 'success') {
			var myip = eval('(' + data + ')');
			//$("#myip").append("<h3><i class='layui-icon'>&#xe715;</i> " + myip.country + myip.region + myip.city + myip.county + myip.isp + "</h3>");
			$("#mylocation").text(myip.country + ' ' + myip.region + ' ' + myip.city + ' ' + myip.county + ' ' + myip.isp);
		}
	});
	$("#btn").click(function(){
		$("#myip").hide();
		$("#ipinfo").hide();
		$("#allinfo").hide();
		$("#loading").show();
		
		var ip = $("#ip").val();
		ip = ip.replace(/ /g,"");	//替换空字符
		var type = $("#type").val();
		//判断IP是否为真
		//var reg = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/
		//if(!reg.test(ip)) {
		//	layer.open({
		//	  title: '错误提示'
		//	  ,content: 'IP格式有误！',
		//	  time:2000
		//	});    
		//	return false;
		//}
    	//判断接口类型
		switch(type) {
			case 'default':
				$("#api").text("IPIP.NET");
				break;
			case 'ipip':
				$("#api").text("IPIP.NET");
				break;
			case 'taobao':
				$("#api").text("淘宝");
				break;
			case 'sina':
				$("#sina").text("新浪");
				break;
			case 'geoip':
				$("#api").text("GeoIP");
				break;
		}
		//获取所有数据
		if(type == 'all') {
			$.get("./AllInfo.php?ip="+ip,function(data,status){
				var ipinfo = eval('(' + data + ')');
				if(status == 'success') {
					$("#allip").text(ipinfo.ip);
					if(ipinfo.status == 1) {
						//$("#myip").hide();
						//$("#ipinfo").hide();
						$("#ipip").text(ipinfo.ipip);
						$("#taobao").text(ipinfo.taobao);
						$("#sina").text(ipinfo.sina);
						$("#geoip").text(ipinfo.geoip);
						$("#qqwry").text(ipinfo.qqwry);
						$("#allinfo").show();
						$("#loading").hide();
					}
					else if(ipinfo.status == 0){
						layer.open({
						  title: '错误提示'
						  ,content: ipinfo.msg,
						}); 
						$("#loading").hide();  
					}
				}
			})
		}
		else{
			$.get("./GetInfo.php?type="+type+"&ip="+ip,function(data,status){
				if(status == 'success') {
					var ipinfo = eval('(' + data + ')');
					//如果IP查询成功
					if(ipinfo.status == 1) {
						$("#reip").text(ipinfo.ip);
						$("#country").text(ipinfo.country);
						$("#region").text(ipinfo.region);
						$("#city").text(ipinfo.city);
						$("#isp").text(ipinfo.isp);
						$("#county").text(ipinfo.county);
						//$("#myip").hide();
						//$("#allinfo").hide();
						$("#ipinfo").show();
						$("#loading").hide();
					}
					//IP查询失败
					else if(ipinfo.status == 0) {
						layer.open({
						  title: '错误提示'
						  ,content: ipinfo.msg,
						});   
						$("#loading").hide();
					}
				}
			});
		}
	});
});
function mobile(){
	var protocol = window.location.protocol;
	var host = window.location.host;
	var pathname = window.location.pathname;
	var url = protocol + '//' + host + pathname;
	var qrcode = "https://pan.baidu.com/share/qrcode?w=200&h=200&url=" + url;
	var qrimg = "<center><img src = '" + qrcode + "' /></center>";
	layer.open({
		type: 1,
	  title: '请用手机扫描访问'
	  ,content: qrimg
	});   
}
function api(){
	layer.open({
	  title: '温馨提示：'
	  ,content: 'API正在开发中...敬请期待。'
	});  
}
function about(){
	layer.open({
	  	type: 2, 
	  	title:'关于',
	  	area: ['800px', '600px'],
	  	content: './static/about.html' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
	}); 
}
//获取内网ip
function getIPs(callback){
    var ip_dups = {};
    //compatibility for firefox and chrome
    var RTCPeerConnection = window.RTCPeerConnection
        || window.mozRTCPeerConnection
        || window.webkitRTCPeerConnection;
    var useWebKit = !!window.webkitRTCPeerConnection;
    //bypass naive webrtc blocking using an iframe
    if(!RTCPeerConnection){
        //NOTE: you need to have an iframe in the page right above the script tag
        //
        //<iframe id="iframe" sandbox="allow-same-origin" style="display: none"></iframe>
        //<script>...getIPs called in here...
        //
        var win = iframe.contentWindow;
        RTCPeerConnection = win.RTCPeerConnection
            || win.mozRTCPeerConnection
            || win.webkitRTCPeerConnection;
        useWebKit = !!win.webkitRTCPeerConnection;
    }
    //minimal requirements for data connection
    var mediaConstraints = {
        optional: [{RtpDataChannels: true}]
    };
    var servers = {iceServers: [{urls: "stun:stun.services.mozilla.com"}]};
    //construct a new RTCPeerConnection
    var pc = new RTCPeerConnection(servers, mediaConstraints);
    function handleCandidate(candidate){
        //match just the IP address
        var ip_regex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/
        var ip_addr = ip_regex.exec(candidate)[1];
        //remove duplicates
        if(ip_dups[ip_addr] === undefined)
            callback(ip_addr);
        ip_dups[ip_addr] = true;
    }
    //listen for candidate events
    pc.onicecandidate = function(ice){
        //skip non-candidate events
        if(ice.candidate)
            handleCandidate(ice.candidate.candidate);
    };
    //create a bogus data channel
    pc.createDataChannel("");
    //create an offer sdp
    pc.createOffer(function(result){
        //trigger the stun server request
        pc.setLocalDescription(result, function(){}, function(){});
    }, function(){});
    //wait for a while to let everything done
    setTimeout(function(){
        //read candidate info from local description
        var lines = pc.localDescription.sdp.split('\n');
        lines.forEach(function(line){
            if(line.indexOf('a=candidate:') === 0)
                handleCandidate(line);
        });
    }, 1000);
}
//insert IP addresses into the page
getIPs(function(ip){
    //var li = document.createElement("li");
    //li.textContent = ip;
    //local IPs
    if (ip.match(/^(192\.168\.|169\.254\.|10\.|172\.(1[6-9]|2\d|3[01]))/))
        $("#localip").append(ip);
});