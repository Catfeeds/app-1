<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\index\welcome.html";i:1515743789;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <title>我的桌面</title>
    <link rel="Bookmark" href="__ROOT__/favicon.ico" >
    <link rel="Shortcut Icon" href="__ROOT__/favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__LIB__/html5.js"></script>
    <script type="text/javascript" src="__LIB__/respond.min.js"></script>
    <script type="text/javascript" src="__LIB__/PIE_IE678.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui/css/H-ui.min.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui.admin/css/H-ui.admin.css"/>
    <link rel="stylesheet" type="text/css" href="__LIB__/Hui-iconfont/1.0.7/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="__LIB__/icheck/icheck.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui.admin/skin/default/skin.css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui.admin/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/app.css"/>
    <link rel="stylesheet" type="text/css" href="__LIB__/icheck/icheck.css"/>
    
    <!--[if IE 6]>
    <script type="text/javascript" src="__LIB__/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <!--定义JavaScript常量-->
<script>
    window.THINK_ROOT = '<?php echo \think\Request::instance()->root(); ?>';
    window.THINK_MODULE = '<?php echo \think\Url::build("/" . \think\Request::instance()->module(), "", false); ?>';
    window.THINK_CONTROLLER = '<?php echo \think\Url::build("___", "", false); ?>'.replace('/___', '');
</script>
</head>
<body>

<nav class="breadcrumb">
    <div id="nav-title"></div>
    <a class="btn btn-success radius r btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:;" title="刷新"><i class="Hui-iconfont"></i></a>
</nav>


<div class="page-container">
    <p class="f-20 text-success">欢迎使用 <?php echo \think\Config::get('site.name'); ?> <span class="f-14"><?php echo \think\Config::get('site.version'); ?></span> 后台管理系统！</p>
    <p>登录次数：<?php echo $info['login_count']; ?> </p>
    <?php if(session('last_login_time')): ?>
    <p>上次登录IP：<?php echo $last_login_ip; ?> &nbsp;&nbsp;&nbsp; 上次登录时间：<?php echo date('Y-m-d H:i:s',\think\Session::get('last_login_time')); ?> &nbsp;&nbsp;&nbsp; 上次登录地点：<?php echo $last_login_loc; ?></p>
    <?php endif; ?>
    <p>本次登录IP：<?php echo $current_login_ip; ?> &nbsp;&nbsp;&nbsp; 本次登录时间：<?php echo date('Y-m-d H:i:s',$info['last_login_time']); ?> &nbsp;&nbsp;&nbsp; 本次登录地点：<?php echo $current_login_loc; ?></p>
    

    <p class="c-red">以下为静态展示内容</p>
    <table class="table table-border table-bordered table-bg">
        <thead>
        <tr>
            <th colspan="7" scope="col">信息统计</th>
        </tr>
        <tr class="text-c">
            <th>统计</th>
            <th>资讯库</th>
            <th>图片库</th>
            <th>产品库</th>
            <th>用户</th>
            <th>管理员</th>
        </tr>
        </thead>
        <tbody>
        <tr class="text-c">
            <td>总数</td>
            <td>92</td>
            <td>9</td>
            <td>0</td>
            <td>8</td>
            <td>20</td>
        </tr>
        <tr class="text-c">
            <td>今日</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
        </tr>
        <tr class="text-c">
            <td>昨日</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
        </tr>
        <tr class="text-c">
            <td>本周</td>
            <td>2</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
        </tr>
        <tr class="text-c">
            <td>本月</td>
            <td>2</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
        </tr>
        </tbody>
    </table>
	<!-- 服务器信息 -->
    <!-- <table class="table table-border table-bordered table-bg mt-20">
        <thead>
        <tr>
            <th colspan="2" scope="col">服务器信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th width="30%">服务器计算机名</th>
            <td><span id="lbServerName">http://127.0.0.1/</span></td>
        </tr>
        <tr>
            <td>服务器IP地址</td>
            <td>192.168.1.1</td>
        </tr>
        <tr>
            <td>服务器域名</td>
            <td>www.h-ui.net</td>
        </tr>
        <tr>
            <td>服务器端口</td>
            <td>80</td>
        </tr>
        <tr>
            <td>服务器IIS版本</td>
            <td>Microsoft-IIS/6.0</td>
        </tr>
        <tr>
            <td>本文件所在文件夹</td>
            <td>D:\WebSite\HanXiPuTai.com\XinYiCMS.Web\</td>
        </tr>
        <tr>
            <td>服务器操作系统</td>
            <td>Microsoft Windows NT 5.2.3790 Service Pack 2</td>
        </tr>
        <tr>
            <td>系统所在文件夹</td>
            <td>C:\WINDOWS\system32</td>
        </tr>
        <tr>
            <td>服务器脚本超时时间</td>
            <td>30000秒</td>
        </tr>
        <tr>
            <td>服务器的语言种类</td>
            <td>Chinese (People's Republic of China)</td>
        </tr>
        <tr>
            <td>.NET Framework 版本</td>
            <td>2.050727.3655</td>
        </tr>
        <tr>
            <td>服务器当前时间</td>
            <td>2014-6-14 12:06:23</td>
        </tr>
        <tr>
            <td>服务器IE版本</td>
            <td>6.0000</td>
        </tr>
        <tr>
            <td>服务器上次启动到现在已运行</td>
            <td>7210分钟</td>
        </tr>
        <tr>
            <td>逻辑驱动器</td>
            <td>C:\D:\</td>
        </tr>
        <tr>
            <td>CPU 总数</td>
            <td>4</td>
        </tr>
        <tr>
            <td>CPU 类型</td>
            <td>x86 Family 6 Model 42 Stepping 1, GenuineIntel</td>
        </tr>
        <tr>
            <td>虚拟内存</td>
            <td>52480M</td>
        </tr>
        <tr>
            <td>当前程序占用内存</td>
            <td>3.29M</td>
        </tr>
        <tr>
            <td>Asp.net所占内存</td>
            <td>51.46M</td>
        </tr>
        <tr>
            <td>当前Session数量</td>
            <td>8</td>
        </tr>
        <tr>
            <td>当前SessionID</td>
            <td>gznhpwmp34004345jz2q3l45</td>
        </tr>
        <tr>
            <td>当前系统用户名</td>
            <td>NETWORK SERVICE</td>
        </tr>
        </tbody>
    </table> -->
</div>
<footer class="footer mt-20">
    
</footer>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

</body>
</html>