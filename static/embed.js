layui.use('form', function(){
  var form = layui.form;
  
  //各种基于事件的操作，下面会有进一步介绍
});
$(document).ready(function(){
	//访问页面时加载
	var getip = $("#getip").text();
	$.get("./GetInfo.php?type=ipip"+"&ip="+getip,function(data,status){
		if(status == 'success') {
			var myip = eval('(' + data + ')');
			$("#myip").append("<h3>" + myip.country + myip.region + myip.city + myip.county + myip.isp + "</h3>");
		}
	});
	$("#btn").click(function(){
		var ip = $("#ip").val();
		var type = $("#type").val();
		switch(type) {
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