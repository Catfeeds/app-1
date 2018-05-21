<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"C:\wwwroot\AMS\public/../application/admin\view\all_school_post\selectall.html";i:1519633170;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
    <style type="text/css">
    .d-n{display: none;}
    .content{color: #555;line-height:2;font-size: 16px;background: #f5f5f5;margin:20px 0}
	.content>ul{padding:15px 15px 30px;text-indent:0}
    .content>ul>li{background: #fff;padding:0 10px 10px;margin-top: 15px}
    .content>ul>li>span{margin-top:15px;color:#999}
    .content>ul>li p{margin: 0;text-align: left;padding: 0 15px}
	.content>ul>li img{width:20%;margin:15px auto;display: block}
    .content>ul h1{font-size: 20px;border-bottom:1px dashed #ddd;}
    .content>ul .shen-he{overflow: hidden;color: #999}
    .title-w{border: 1px solid #3c9;font-size: 14px;color: #3c9;padding:1px 5px;margin-top: -10px;margin-left: 10px;border-radius:5px}
	</style>
	<div class="col-md-2"></div>
	<div class="col-md-8 content">
	    <ul>
	    	<?php if(is_array($all3) || $all3 instanceof \think\Collection || $all3 instanceof \think\Paginator): $i = 0; $__LIST__ = $all3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<li>
	    		<span class="f-r"><?php echo $vo['release_time']; ?></span>
	    		<h1><?php echo htmlspecialchars_decode($vo['title']); ?><span class="title-w"><?php echo $vo['plate_name']; ?></span></h1>
	    		<p class="cut_str"><?php echo htmlspecialchars_decode($vo['content']); ?></p>

				<!--若查询的是未审核-->
				<!--<?php if($isqualified == 0): ?>-->
				    <!--<div class="shen-he js-noresult">-->
						<!--未审核-->
					<!--</div>-->
				<!--<?php endif; ?>-->
                <?php
                    if($vo['isqualified'] == 0)
                    {
                ?>
					  <div class="shen-he js-noresult">
						  <a href="<?php echo url('AllSchoolPost/select',array('id'=>$vo['id'])); ?>" style="color: #148cf1">未审核</a>
					  </div>
				<?php

                    }

                ?>


				<!--若查询的是合格-->
				<?php if($isqualified == 1): ?>
				    <div class="shen-he js-result"><span class="f-r">审核人员：<?php echo $vo['assessor']; ?></span></div>
				<?php endif; ?>
				<!--若查询的是不合格-->
				<!--<?php if($isqualified == 2): ?>-->
				    <!--<div class="shen-he js-result"><span class="js-result-no">未通过理由：<?php echo $vo['reason']; ?></span><span class="f-r">审核人员：<?php echo $vo['assessor']; ?></span></div>-->
				<!--<?php endif; ?>-->

			</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>

	    	
	    </ul>
	</div> 
	<div class="col-md-2"></div>
</div>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		//限制字符个数
		$(".cut_str").each(function(){
			var maxwidth=100;
			if($(this).text().length>maxwidth){
				$(this).text($(this).text().substring(0,maxwidth));
				$(this).html($(this).html()+'...');
			}
		});

	});
</script>

</body>
</html>