<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:75:"C:\wwwroot\AMS\public/../application/admin\view\all_school_post\select.html";i:1519632146;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
    .h1{font-size: 22px;border-bottom: 1px dashed #ddd;padding:15px}
    .content{color: #555;line-height:2;font-size: 16px;background: #fafafa}
    .content hr{border: none;border-bottom: 1px solid #666;}
    .content img{width: 40%;margin: 0 30%}
    .img-title{text-align: center;line-height: 3;font-size: 14px;color:#888}
    .img-hr{width: 30%;margin: 0 auto;border:none !important;border-top: 1px solid #aaa !important;}
    blockquote{background: #eee;border-left:5px solid #ccc;line-height: 3;}
    button{margin:20px auto;}
    h1{font-size: 22px;font-weight:800}
    h2{font-size: 20px;font-weight:800}
    h3{font-size: 18px;font-weight:800}
    h4{font-size: 16px;font-weight:800}
    .content a{color:#00f}
    form{border-top: 1px dashed #ddd;margin-top: 20px}
    form .iradio-blue{margin-top: 3px;}
    .reason{display: none;}
    
    .modal{color:#f00; position:fixed; left:0; top:0; right:0; bottom:0; z-index:1040; display:none; overflow:hidden;-webkit-overflow-scrolling:touch; outline:0}
	.modal-alert{position:fixed; right:auto; bottom:auto; width:300px; left:50%;margin-left:-150px; top:50%;margin-top:-30px; z-index:9999;background-color: #fff;border: 1px solid #999;border: 1px solid rgba(0,0,0,.2);outline: 0;
	-webkit-background-clip: padding-box;
	background-clip: padding-box;
	-webkit-box-shadow: 0 3px 9px rgba(0,0,0,.5);
	box-shadow: 0 3px 9px rgba(0,0,0,.5)}
	.modal-alert-info{padding:30px; text-align:center; font-size:14px; background-color:#fff}
    
	</style>
	<div class="col-md-3"></div>
	<div class="col-md-6 content">
		<h1 class="h1 bg"><?php echo htmlspecialchars_decode($current_info['title']); ?></h1>
		<?php echo htmlspecialchars_decode($current_info['content']); ?>
		<br><br><span style="margin-left: 550px">发布时间：<?php echo $current_info['release_time']; ?></span>
	    <!--<div class="bg">
	     	<p>图图乐就看看<b>来啦</b>空啦啦<i>咯额</i>龙凤<strike>看看呢咯嘛啦啊</strike></p>
	       	<p><strike>家具体健康快乐</strike></p>
	       	<blockquote>来啦来啦</blockquote>
	       	<div>
	       		<hr>
	        	<div>
	        		空我哦哦呢<a href="https://www.jianshu.com/p/512dc7ba58d5" class="editor-link" >违章</a>
	        	</div>
	        </div>
	        <div>咯我默默摩拜</div>
	     </div>-->
		

		<form method="post" action="<?php echo url('AllSchoolPost/check'); ?>">

			<!--保留当前记录的id-->
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<!--保留当前记录的id-->

			<div class="mt-20 skin-minimal">
				<span style="color: #f00;">对该文章的审核结果：</span>
			    <div class="radio-box">
				  <input type="radio" id="radio-1" name="demo-radio1" value="1">
				  <label for="radio-1">合格</label>
			    </div>
			    <div class="radio-box">
				  <input type="radio" id="radio-2" name="demo-radio1" value="2" >
				  <label for="radio-2">不合格</label>
			    </div>
			</div>

			<!--<div class="reason js-reason">-->
				<!--<label>不合格原因 :</label>-->
				<!--<textarea name="reason" id="reason" class="textarea radius" placeholder="请输入原因..."></textarea>-->
			<!--</div>-->

			<button class="btn btn-success radius jsBtn">提交</button>
			<div id="modal-alert" class="modal  modal-alert radius" >
				<div class="modal-alert-info">
					备注不能为空
				</div>
			</div>

		</form>
	</div> 
	<div class="col-md-3"></div>
</div>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.min.js"></script>
<script>
    $(function () {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
        
        $('.iCheck-helper').eq(1).click(function(){
        	$('.js-reason').slideDown();
        });
        
        $('.iCheck-helper').eq(0).click(function(){
        	$('.js-reason').slideUp();
        });
        
        //对审核结果进行判断
		$('.jsBtn').click(function(){
			var raDio = $(".iradio-blue");
			//判断是否选择了单选按钮
			if(raDio.hasClass('checked')){
//				var _val = $('input:radio:checked').val();
//				if(_val == 1){
					return true;
//				}else if(_val == 2){
//					if($('#reason').val().replace(/(^\s+)|(\s+$)/g,"") == ''){
//						$.Huimodalalert('请输入原因！',2000);
//						return false;
//					}
//				}
			}else{
				//弹出层
				$.Huimodalalert('请选择您对这篇文章的看法！',2000);
				return false;
			}
		})
        
    })
</script>

</body>
</html>