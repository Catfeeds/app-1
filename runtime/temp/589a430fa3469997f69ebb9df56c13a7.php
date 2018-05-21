<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"C:\wwwroot\AMS\public/../application/index\view\introduce\index.html";i:1520922534;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>幼儿园介绍</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script type="text/javascript" src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script> 
	<script src="__STATIC__/js/jsbridge.js"></script>
	<style>
	.bb_p{font-size:0.16rem;}
	.bb_p img{width:100%;height:auto;}
	</style>
</head>

<body>
    <div id="wrap">
        <header class="header  header-fixed">
            <a    class="back"></a>幼儿园介绍
		</header>
        <div class="bb_w_l_jy jy" >
		<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <div class="bb_w_l">
				<div class="bb_p">
                <?php echo (stripslashes(htmlspecialchars_decode($vo['content']))) ? stripslashes(htmlspecialchars_decode($vo['content'])) :  ''; ?>
				</div>
            </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    
</body>

<script type="text/javascript">
	
	/**
     * jQuery
     */
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

</html>