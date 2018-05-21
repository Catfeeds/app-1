<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"C:\inetpub\wwwroot\anhui\public/../application/index\view\index\guarantee.html";i:1525849918;s:76:"C:\inetpub\wwwroot\anhui\public/../application/index\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
	<form class="form" id="form" method="post" action="<?php echo url('Index/pay'); ?>">
    <div class="row cl">
    	<div class="col-xs-3 col-sm-3"></div>
    	<div class="col-xs-6 col-sm-6">
			<ul class="pay-step row cl">
				<li class="col-xs-4 col-sm-4">1. 填写预招人数信息</li>
				<li class="col-xs-4 col-sm-4">2. 确认并支付</li>
				<li class="col-xs-4 col-sm-4">3. 完成</li>
			</ul>
    		<div style="border: 1px solid #fea128;padding:10px;">为了更好的解决个体小微企业招聘困难，在没有建立信用机制的基础上，兼职员工工资结算无法保障，派职网新增工资担保机制，即根据企业用工人数，用工时长将工资一次存入派职网工资发放帐户，根据企业上传实际用工考勤结算表，由派职进行工资发放及企业余额结算。</div>
    		<h2 class="h2">
    			填写预招人数信息
    			<span class="add-post">添加新的岗位</span>
    		</h2>
    		<div class="mt-20 post">
    			<div class="row cl">
    				<div class="col-xs-6 col-sm-6">
			            <lable class="col-xs-5 col-sm-5 text-r"><span class="c-red">*</span>岗位名称:</lable>
			            <div class="col-xs-7 col-sm-7">
			            	<input type="text" class="input-text " name="station_name">
			            </div>
			        </div>
			        <div class="col-xs-6 col-sm-6">
			            <lable class="col-xs-5 col-sm-5 text-r"><span class="c-red">*</span>岗位薪水:</lable>
			            <div class="col-xs-7 col-sm-7">
			            	<input type="text" class="input-text " name="station_salary">
			            </div>
			        </div>
    			</div>
    			<div class="row cl">
    				<div class="col-xs-6 col-sm-6">
			            <lable class="col-xs-5 col-sm-5 text-r"><span class="c-red">*</span>招聘人数:</lable>
			            <div class="col-xs-7 col-sm-7">
			            	<input type="text" class="input-text " name="station_number">
			            </div>
			        </div>
			        <div class="col-xs-6 col-sm-6">
			            <lable class="col-xs-5 col-sm-5 text-r"><span class="c-red">*</span>用工时长:</lable>
			            <div class="col-xs-7 col-sm-7">
			            	<input type="text" class="input-text " name="station_time">
			            </div>
			        </div>
    			</div>
    			<div class="row cl">
    				<div class="col-xs-6 col-sm-6">
			            <lable class="col-xs-5 col-sm-5 text-r f-16"><strong>合计:</strong></lable>
			            <div class="col-xs-7 col-sm-7">
			            	<input type="text" class="input-text " name="station_all">
			            </div>
			        </div>
			        <div class="col-xs-6 col-sm-6">
			        	<span class="btn btn-secondary radius remove-post"><i class="Hui-iconfont" style="font-size: 16px;">&#xe609;&nbsp;</i>删除岗位</span>
			        </div>
			    </div>
    		</div>
    		
    		<div>
    			<div class="row cl">
		        	<div class="col-xs-6 col-sm-6">
			            <lable class="col-xs-5 col-sm-5 text-r f-18"><strong>总合计:</strong></lable>
			            <div class="col-xs-7 col-sm-7">
			            	<input type="text" class="input-text " name="number">
			            </div>
			        </div>
			        <div class="col-xs-6 col-sm-6">
			            <input type="submit" class="btn btn-primary radius" name="" id="" value="存入工资"/>
			        </div>
		        </div>
    		</div>
    	</div>
    	<div class="col-xs-3 col-sm-3"></div>
    </div>
    </form>
</div>
<style>
	.pay-step{margin:0 0 30px;}
	.pay-step li{background:#ccc;color: #fff;line-height:40px;text-align: center;}
	.pay-step li:after{ content: '';display: inline-block;border:20px solid transparent;border-left: 20px solid #fea128;position:absolute;top:0px;right:-40px;z-index:2;}
	.pay-step li:nth-child(2):after{ content: '';display: inline-block;border:20px solid transparent;border-left: 20px solid #ccc;position:absolute;top:0px;right:-40px;z-index:2;}
	.pay-step li:before{content:'';display: inline-block;border: 23px solid transparent;border-left: 23px solid #fff;position:absolute;top:-3px;right:-46px;z-index:1;}
	.pay-step li:last-child:after{content:none ;}
	.pay-step li:first-child{background: #fea128;}
	.h2{border-left: 4px solid #fea128;line-height:20px;font-size: 20px;height: 20px;padding:5px 10px;margin: 30px 0;}
	.post{border:1px solid #ddd;padding-bottom: 20px;background: #f2f2f2;}
	.add-post{float: right;background: #fea128;font-size: 16px;color: #fff;padding:5px;border-radius: 4px;cursor:pointer}
	.remove-post{float: right;margin-right:20px;}
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
		$('.remove-post').click(function(){
			var sub = $(this).index(); 
			layer.confirm('是否要删除该职位？', {
	            btn : [ '确定', '取消' ]
	        }, function(index) {
	            layer.close(index);
	            $('.post').eq(sub).remove();
	        }); 
		})
	})
</script>

</body>
</html>