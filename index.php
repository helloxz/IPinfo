<!DOCTYPE html>
<html lang="zh-cmn-Hans" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title>IP地址查询 - IPInfo</title>
	<meta name="generator" content="EverEdit" />
	<meta name="author" content="" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./layui/css/layui.css">
	<link rel="stylesheet" href="./static/style.css">
</head>
<body>
	<!--导航菜单-->
	<div class="menu">
		<div class="layui-container">
			<div class="layui-row">
				<div class="layui-col-lg12">
					<ul class="layui-nav" lay-filter="">
					  <li class="layui-nav-item"><a href=""><h1>最新活动</h1></a></li>
					  <li class="layui-nav-item layui-this"><a href="">产品</a></li>
					  <li class="layui-nav-item"><a href="">大数据</a></li>
					  <li class="layui-nav-item">
					    <a href="javascript:;">解决方案</a>
					    <dl class="layui-nav-child"> <!-- 二级菜单 -->
					      <dd><a href="">移动模块</a></dd>
					      <dd><a href="">后台模版</a></dd>
					      <dd><a href="">电商平台</a></dd>
					    </dl>
					  </li>
					  <li class="layui-nav-item"><a href="">社区</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--导航菜单END-->
	<!--内容部分-->
	<div class="layui-container content">
		<div class="layui-row">
			<div class="layui-col-lg10 layui-col-md-offset1">
				<table class="layui-table layui-form" lay-even="" lay-skin="nob">
				  <tbody>
				    <tr>
				      	<td width="75%">
						   	<input id="url" type="text" required="" lay-verify="required" placeholder="请输入URL地址" autocomplete="off" class="layui-input" data-cip-id="url">
					    </td>
					    <td width="15%">
						    
					      <select name="city" lay-verify="required" id="ua">
					        <option value="default"></option>
					        <option value="ipip">IPIP.NET</option>
					        <option value="taobao">淘宝</option>
					        <option value="sina">新浪</option>
					        <option value="geoip">GeoIP</option>
					      </select>
						    
					    </td>
				      	<td width="10%"><button type="submit" class="layui-btn layui-btn" id="btn">查 询</button></td>
				    </tr>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
	<!--内容部分END-->
	<!--底部-->
	<div class="footer">
		<div class="layui-container">
			<div class="layui-row">
				<div class="layui-col-lg12">
					Copyright Ⓒ 2017-2018 Powered by xiaoz.me
				</div>
			</div>
		</div>
	</div>
	<!--底部END-->
	<script src="./layui/layui.js"></script>
	<script>
	layui.use('form', function(){
	  var form = layui.form;
	  
	  //各种基于事件的操作，下面会有进一步介绍
	});
	</script>

</body>
</html>