<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\wwwroot\AMS\public/../application/index\view\teacher\index.html";i:1522143369;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>教师简介</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script type="text/javascript" src="__STATIC__/js/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script>
	<script src="__STATIC__/js/jsbridge.js"></script>
</head>

<body>
    <div id="wrap">
        <header class="header  header-fixed">
            <a href="javascript:;" onclick="history.go(-1)" class="back"></a>教师简介
        </header>
		<ul class="teacher">
		<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<li>
				<div class="teacher_div">
					<img src="<?php echo $vo['image']; ?>"/>
				</div>
				<div class="teacher_div_r">
					<p><?php echo $vo['title']; ?></p>
					<p>学历：<?php echo $vo['xueli']; ?></p>
					<p style="font-size: 0.14rem"><?php echo $vo['content']; ?></p>
				</div>
				<div class="clear"></div>
			</li>
		<?php endforeach; endif; else: echo "" ;endif; ?>
			
		</ul>
    </div>
   <script>
       $(function () {
        // 首先调用JSBridge初始化代码，完成后再设置其他
        initJsBridge(function () {
			$(".back").click(function(){
				window.WebViewJavascriptBridge.callHandler('back','1', function (response) {
				
					 //showResponse(response);
				});
			
			})
		
	    })
	
	})
	
   </script>
</body>
</html>