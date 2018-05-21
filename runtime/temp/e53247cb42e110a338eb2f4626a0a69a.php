<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\account_management\index.html";i:1526867656;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;s:86:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\account_management\form.html";i:1525249605;}*/ ?>
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
    <!--<form class="mb-20" method="get" action="<?php echo \think\Url::build(\think\Request::instance()->action()); ?>">
    <div class="select-box" style="width:250px">
	<select name="schoolName" class="select" datatype="*" nullmsg="请选择公司名称" >
		<option value ="">请选择公司名称</option>
		<?php if(is_array($all_companyName) || $all_companyName instanceof \think\Collection || $all_companyName instanceof \think\Paginator): $i = 0; $__LIST__ = $all_companyName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option value ="<?php echo $vo['schoolName']; ?>"><?php echo $vo['schoolName']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	</div>

    <input type="text" class="input-text" style="width:250px" placeholder="公司账号" name="schoolAccount" value="<?php echo \think\Request::instance()->param('schoolAccount'); ?>" >




	<div class="select-box" style="width:250px">
		<select name="isperfect" class="select" datatype="*" nullmsg="请选择公司信息是否完善" >
			<option value ="">请选择公司信息是否完善</option>
			<option value ="1">已完善</option>
			<option value ="0">未完善</option>
		</select>
	</div>


    <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
</form>-->
    <div class="row cl">
    	<div class="col-xs-2 col-sm-2"></div>
    	<div class="col-xs-8 col-sm-8">
    		<div class="row cl title">
    			<h1 class="f-20"><span class="Hui-iconfont c-warning">&#xe643;&nbsp;</span><span id="companyName"><?php echo $schoolName; ?></span><span title="认证公司" class="Hui-iconfont c-success f-26" style="cursor: pointer;">&nbsp;&#xe70d;</span></h1>
    			<div>
    				<span class="Hui-iconfont f-16 c-warning">&#xe6b5;&nbsp;</span>余额：<span class="c-red f-20"><?php echo $money; ?></span>&nbsp;元
    				<a href="javascript:;" id="guarantee" class="f-r btn btn-success radius"><span class="Hui-iconfont f-16">&#xe6b7;&nbsp;</span>充值</a>
    			</div>
    		</div>
    		<div class="row cl record mt-30">
    			<div class="col-xs-6 col-sm-6 active">充值记录</div>
    			<div class="col-xs-6 col-sm-6">工资支付记录</div>
    		</div>
    		<dvi class="row cl record-list">
    			<div>
    				<table class="table table-border table-bordered  table-stripe">
    					<tr>
    						<th>充值日期</th>
    						<th>充值金额</th>
    						<th>预招人数</th>
    						<th>预招岗位</th>
    						<th>岗位工资</th>
    						<th>岗位时长</th>
    					</tr>
                        <?php if(is_array($all_companyName) || $all_companyName instanceof \think\Collection || $all_companyName instanceof \think\Paginator): $i = 0; $__LIST__ = $all_companyName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vh): $mod = ($i % 2 );++$i;?>
    					<tr>
    						<td><?php echo $vh['buytime']; ?></td>
    						<td><?php echo $vh['buynum']; ?></td>
    						<td><?php echo $vh['needpeople']; ?></td>
    						<td><?php echo $vh['needposition']; ?></td>
    						<td><?php echo $vh['salary']; ?></td>
    						<td><?php echo $vh['workday']; ?></td>
    					</tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
    				</table>
    			</div>
	    		<div class="hide">
    				<table class="table table-border table-bordered  table-stripe">
    					<tr>
    						<th>支出日期</th>
    						<th>支出金额</th>
    						<th>支出岗位</th>
    						<th>岗位人数</th>
    						<th>详细信息</th>
    					</tr>
    					<tr>
    						<td>2018-05-04</td>
    						<td>2000元</td>
    						<td>服务员</td>
    						<td>3</td>
    						<td><a href="javascript:;">点此查看详情</a></td>
    					</tr>
    				</table>
    			</div>
    		</dvi>
    	</div>
    	<div class="col-xs-2 col-sm-2"></div>
    </div>
    <div class="page-bootstrap"><?php echo isset($page) ? $page :  ''; ?></div>
</div>
<style type="text/css">
	.title{background: #f5f5f5;padding:10px 15px 20px;border: 1px solid #ddd;}
	.record{text-align: center;border: 1px solid #ddd;overflow: hidden;}
	.record div{cursor:pointer;background: #fff;font-size:16px;line-height: 50px;}
	.record div.active{background: #F37B1D;color: #fff;}
	.record-list div{margin: 0 -15px;}
</style>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

<script type="text/javascript">
	$(function(){
		$('.record div').click(function(){
			$(this).addClass('active').siblings('div').removeClass('active');
			var index = $(this).index();
			$('.record-list div').eq(index).removeClass('hide').siblings('div').addClass('hide')
		})
		var companyName = $('#companyName').text();
		//页面全屏显示
        $('#guarantee').click(function(){
        	var index = layer.open({
        	  type: 2,
			  title:'填写预招人数信息',
			  content: "<?php echo url('SchoolManagement/guarantee'); ?>?companyName="+companyName,
			  area: ['90%', '90%'],
			  maxmin: true
			});
			layer.setTop(index);
        })
        
	})
</script>

</body>
</html>