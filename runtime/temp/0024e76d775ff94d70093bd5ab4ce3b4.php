<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\company_position_enroll\selectresume.html";i:1523605938;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <title><?php echo \think\Config::get('site.title'); ?></title>
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


<style>
html,body{height:100%;width:100%}
.container-fluid{padding-top:70px;height:100%;line-height:30px}
.information{border:1px solid #ddd;padding:30px;position:relative;margin-bottom:30px;}
.information .title{font-size: 16px;font-weight: 800;border-left: 4px solid #fc3;padding:5px 10px;margin-bottom:10px}
.mr-bt{margin-bottom: 20px}
.information>div>ul{padding:0 15px;overflow: hidden}
.head{width:100px;position:absolute;right: 30px;top:30px;border:1px solid #aaa}
.live li{border-left: 1px solid #fc3;padding:6px 20px;position: relative;line-height: 1;}
.dot{width:14px;height:14px;background: #fc3;border-radius:7px;position: absolute;left:-8px;top:5px;box-shadow: 0 0 6px #fc3;}
.color-a{color: #aaa;}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-3 col-sm-3"></div>
		<div class="col-xs-6 col-sm-6 information">
			<div class="mr-bt">
				<h1 class="title">基本信息</h1>
				<div class="head"><img src="__STATIC__/images/defaulthead.jpg" width="100%" /></div>
				<ul class="row">
					<li class="col-xs-6 col-sm-6">
						姓名：jigglypuff
					</li>
					<li class="col-xs-6 col-sm-6">
						性别：女
					</li>
					<li class="col-xs-6 col-sm-6">
						年龄：23
					</li>
					<li class="col-xs-6 col-sm-6">
						身高：166cm
					</li>
					<li class="col-xs-6 col-sm-6">
						联系电话：18755270011
					</li>

				</ul>
			</div>
			<div class="mr-bt live">
				<h1 class="title">教育经历</h1>
				<ul>
					<li>
						<div class="dot"></div>
						<p>2011.9—2014.6</p>
						<p>合肥一中&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;高中</p>
						<p class="color-a">主修专业：普高</p>
						<p class="color-a">在校经历：优秀学生</p>
					</li>
					<li>
						<div class="dot"></div>
						<p>2014.9—2018.6</p>
						<p>合肥工业大学&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;本科</p>
						<p class="color-a">主修专业：电器制动化专业</p>
						<p class="color-a">在校经历：优秀学生</p>
					</li>
				</ul>
			</div>
			<div class="mr-bt live">
				<h1 class="title">工作经历</h1>
				<ul>
					<li>
						<div class="dot"></div>
						<p>2018.04-2018.05</p>
						<p>安徽朗朗教育咨询有限公司&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;销售</p>
						<p class="color-a">所属部门：市场营销</p>
					</li>
				</ul>
			</div>
			<div class="live">
				<h1 class="title">个人擅长</h1>
				<ul>
					<li>
						<div class="dot"></div>
						<p>能熟练的运用各种办公软件、网络工具，打字速度较快</p>
						<p>英语六级，具有较强的听说读写能力</p>
						<p>具有丰富的工作经验</p>
						<!--<p class="color-a">所属部门：市场营销</p>-->
					</li>
				</ul>
			</div>
		</div>
		<div class="col-xs-3 col-sm-3"></div>
	</div>
</div>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

<script type="text/javascript" src="__LIB__/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<script type="text/javascript" src="__LIB__/showdown/1.4.2/showdown.min.js"></script>
<script>window.UEDITOR_HOME_URL = '__LIB__/ueditor/1.4.3/'</script>

<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>

<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.min.js"></script>
<script>

</script>

</body>
</html>