<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\wwwroot\AMS\public/../application/index\view\album\index.html";i:1522302685;}*/ ?>
                                 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>活动相册</title>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/layui.css"/>
    <link rel="stylesheet" href="__STATIC__/css/baguetteBox.min.css">
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
	<script src="__STATIC__/js/jsbridge.js"></script>
</head>
<body>
    <div id="wrap">
	    <header class="header header-fixed">
	        <a  class="back"></a>活动相册
	    </header>
	    <ul class="album_ul">
	    	
			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	    	<li>
	    		<h1><?php echo $vo['release_time']; ?></h1>
	    		<div class="main-class">
					<div class="server-img row main-class-ul">
					<?php if($vo['thumbs'] != ''): if(is_array($vo['thumbs']) || $vo['thumbs'] instanceof \think\Collection || $vo['thumbs'] instanceof \think\Paginator): $k = 0; $__LIST__ = $vo['thumbs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k % 2 );++$k;if($k < 9): ?>
						<div class="imgbox imgbox3 col-xs-3">
						    <a>
							    <img src="<?php echo $vo1; ?>" class="js-img"/>
								<input type="hidden" class="js-image" value="<?php echo $vo['images'][$k-1]; ?>">
						    </a>
						</div>
					<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
					</div>
					<h2><span><?php echo $vo['className']; ?>班</span>&nbsp;&nbsp;<span><?php echo $vo['title']; ?></span></h2>
				</div>
	    	</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
	    	
	    	
	    </ul>
	</div>
</body>
<!-- 放大图片 -->

<script type="text/javascript">

	<!-- //图片九宫格 -->
		$(".imgbox img").each(function(i){
			var img = $(this);
			var realWidth;//真实的宽度
			var realHeight;//真实的高度
			$("<img/>").attr("src", $(img).attr("src")).load(function() {
				realWidth = this.width;
				realHeight = this.height;
				if(realWidth>realHeight){
				$(img).addClass('imgbox_img_2');
				}
				else{
				$(img).addClass('imgbox_img_1');
				}
			});
		});
		
		
		
		
	/**
     * jQuery
     */
    $(function () {
        // 首先调用JSBridge初始化代码，完成后再设置其他
        initJsBridge(function () {
		//放大图片
		        $(document).on('click','.js-img',function(){
			 
				var index = $(this).parents('.col-xs-3').index();
				var length = $(this).parents('.main-class-ul').find('.col-xs-3').length;
			//alert(length);
			    var str = '';
				
				for(var i=0;i<length;i++){
					//images[i]=$(this).parents('.main-class').find('.js-image').eq(i).val();
				str +=$(this).parents('.main-class-ul').find('.js-image').eq(i).val()+",";
				
				}
			    str += index;
			 //  console.log(str);
			    window.WebViewJavascriptBridge.callHandler('get',str, function (response) { 
					    // showResponse(response); 
				}); 

		
		    })
			//返回
			$(".back").click(function(){
				window.WebViewJavascriptBridge.callHandler('back','1', function (response) {
				
					 //showResponse(response);
				});
			
			})
		
	    })
	
	})
	
	
</script>
</html>
