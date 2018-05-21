<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\school_management\finish.html";i:1525746707;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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


<div class="page-container">
	<form class="form" id="form" method="post" action="">
    <div class="row cl">
    	<div class="col-xs-3 col-sm-3"></div>
    	<div class="col-xs-6 col-sm-6">
			<ul class="pay-step row cl">
				<li class="col-xs-4 col-sm-4">1. 填写预招人数信息</li>
				<li class="col-xs-4 col-sm-4">2. 确认并支付</li>
				<li class="col-xs-4 col-sm-4">3. 完成</li>
			</ul>
			<div class="row cl pay-content">
				<p class="f-16 c-success"><strong>支付成功！</strong></p>
			</div>
			<a href="<?php echo url('AccountManagement/index'); ?>" class="btn btn-primary radius f-r mt-20">查看账户余额</a>
    	</div>
    	<div class="col-xs-3 col-sm-3"></div>
    </div>
    </form>
</div>
<style>
	.pay-step li{background:#fea128;color: #fff;line-height:40px;text-align: center;}
	.pay-step li:after{ content: '';display: inline-block;border:20px solid transparent;border-left: 20px solid #fea128;position:absolute;top:0px;right:-40px;z-index:2;}
	.pay-step li:before{content:'';display: inline-block;border: 23px solid transparent;border-left: 23px solid #fff;position:absolute;top:-3px;right:-46px;z-index:1;}
	.pay-step li:last-child:after{content:none ;}
	.pay-content{border: 1px solid #ddd;padding: 15px;}
	.pay-list{border:1px solid #ddd;padding: 15px;}
</style>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

<script src="__LIB__/laypage/1.3/laypage.js"></script>
<script src="__LIB__/lightbox2/js/lightbox.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('.pay-list input').iCheck({
			checkboxClass: 'icheckbox-blue',
			radioClass: 'iradio-blue',
			increaseArea: '20%'
		})
	});

	$('.pay-list div').click(function(){
		$('.pay-list div').find('.iradio-blue').removeClass('checked');
		$(this).find('.iradio-blue').addClass('checked');
		var val = $(this).find('input').val()
		console.log(val)
	})
	
	/*$(function(){
		$('.remove-post').click(function(){
			layer.confirm('是否要删除该职位？', {
	            btn : [ '确定', '取消' ]//按钮
	        }, function(index) {
	            layer.close(index);
	            //此处请求后台程序，下方是成功后的前台处理……
	        }); 
		})
	})*/
</script>

</body>
</html>