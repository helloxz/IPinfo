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
			$("#myip").append("<h3><i class='layui-icon'>&#xe715;</i> " + myip.country + myip.region + myip.city + myip.county + myip.isp + "</h3>");
		}
	});
	$("#btn").click(function(){
		var ip = $("#ip").val();
		ip = ip.replace(/ /g,"");	//替换空字符
		var type = $("#type").val();
		//判断IP是否为真
		var reg = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/
		if(!reg.test(ip)) {
			layer.open({
			  title: '错误提示'
			  ,content: 'IP格式有误！',
			  time:2000
			});    
			return false;
		}
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
		//获取数据
		$.get("./GetInfo.php?type="+type+"&ip="+ip,function(data,status){
			if(status == 'success') {
				var ipinfo = eval('(' + data + ')');
				$("#reip").text(ipinfo.ip);
				$("#country").text(ipinfo.country);
				$("#region").text(ipinfo.region);
				$("#city").text(ipinfo.city);
				$("#isp").text(ipinfo.isp);
				$("#county").text(ipinfo.county);
				$("#myip").hide();
				$("#ipinfo").show();
			}
		});
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