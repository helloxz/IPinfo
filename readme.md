# IPinfo
IPinfo是一个开箱即用的IP查询工具，整合了淘宝、IPIP、GeoIP2、纯真、腾讯等IP查询接口。

### 主要功能
* 自动获取内网IP、公网IP
* 支持多个查询接口
* 支持一键查询所有IP接口，方便对比数据
* IP数据缓存

### 更新记录
#### 2018-07-18
* 去除新浪查询接口
* 新增SQLite 3进行数据缓存
* API支持
* 增加腾讯IP查询接口

### 使用说明
#### 环境要求
* PHP >= 5.6
* PDO组件
* SQLite 3

#### 安装说明
1. 访问`check.php`获取项目绝对路径
2. 修改`config.php`填写项目绝对路径
3. 确保`data`目录可读可写，否则缓存无法写入，新手易犯权限问题
4. 如果需要支持腾讯查询接口，需要在 [http://lbs.qq.com/](http://lbs.qq.com/) 申请API，并在`config.php`中填写自己的key
```
//设置腾讯API
	define("LBSQQ","");
```

#### 安全设置
避免您的数据库被别人下载，请在Nginx配置中加入以下规则：
```
location ~ \.(db3|dat)$ {
	deny all;
}
```

#### API接口
请参考文档：[https://doc.xiaoz.me/#/api/ipinfo](https://doc.xiaoz.me/#/api/ipinfo)

### Demo
* [https://ip.awk.sh/](https://ip.awk.sh/)

![](https://imgurl.org/upload/1803/cb30735507513797.png)

![](https://imgurl.org/upload/1803/7a747d7002a6097e.png)

![](https://imgurl.org/upload/1803/02f8ffeb41418ef6.png)


### 联系我
* Blog:[https://www.xiaoz.me/](https://www.xiaoz.me/)
* Q Q:337003006
* E-mail:xiaoz93#outlook.com
* 捐赠：[https://dwz.ovh/ipinfo](https://dwz.ovh/ipinfo)