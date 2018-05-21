<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"C:\wwwroot\AMS\public/../application/index\view\note\index.html";i:1522302177;}*/ ?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />
		<title>成长日记</title>

		<link rel="stylesheet" type="text/css" href="__STATIC__/css/layui.css" />
		<link rel="stylesheet" href="__STATIC__/css/baguetteBox.min.css">

		<link rel="stylesheet" href="__STATIC__/css/reset.css" />
		<link rel="stylesheet" href="__STATIC__/css/common.css">
		<link rel="stylesheet" type="text/css" href="__STATIC__/js/layui/css/layui.css" />
		<script type="text/javascript" src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
		<script type="text/javascript" src="__STATIC__/js/rem.js"></script>
		<script src="__STATIC__/js/jsbridge.js"></script>
	</head>

	<body>
		<div id="wrap">
			<header class="header header-fixed">
				<a  class="back"></a>成长日记
			</header>
			<img src="__STATIC__/img/timg(27).jpg" width="100%" style="margin-top: 0.54rem;">
			<div class="b_info">
				<div class="b_info_img">
					<img src="<?php echo $info['student_headurl']; ?>">
				</div>
				<p class="sg_tz">
					身高：<span><?php echo $info['height']; ?>cm</span>&nbsp;&nbsp;&nbsp;体重：<span><?php echo $info['weight']; ?>kg</span>
				</p>
				<p class="name_p clearfix"><span class="name fl"><?php echo $info['name']; ?></span><span class="jl fr">记录你的点点滴滴</span></p>
			</div>

			<div class="py clearfix">

				<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<div class="py_l fl">
					<div class="py_img">
						<img src="<?php echo $vo['headerurl']; ?>">
					</div>
					<span><?php echo $vo['relation']; ?></span>
				</div>
				<?php endforeach; endif; else: echo "" ;endif; ?>

				<div class="rj_r fr clearfix" style="width:0.1rem;">
					<a href="<?php echo \think\Url::build('note/seeNote'); ?>?phone_account=<?php echo $phone_account; ?>&account=<?php echo $account; ?>" class="fr rj_r_alook">看日记</a>
				</div>
			</div>

			<div class="rj">
				<div class="rj_t clearfix">
					<div class="dw1"></div>
					<div class="rj_r fr clearfix">
						<span class="fl">今天我<?php echo $riqi; ?>了</span>
						<a  class="fr rj_r_a">
						
								 <span class="writeNote">写日记</span>
							
                        </a>
					</div>
				</div>
				<?php

                foreach ($data as $key =>$value){
                ?>
					<div class="mm clearfix">
						<div class="dw2"></div>
						<span class="mm_t fl"><?php echo $value['riqi']; ?></span>
						<span class="mm_j fr">记录日期  <?php echo $value['release_time']; ?></span>
					</div>
					<div class="zp clearfix">
						<div class="dw3"></div>
						<p><?php echo $value['content']; ?></p>
						<div class="server-img main-class">
						<?php if(is_array($value['thumbs']) || $value['thumbs'] instanceof \think\Collection || $value['thumbs'] instanceof \think\Paginator): $k = 0; $__LIST__ = $value['thumbs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
							<div class="row main-class-ul">
								<div class="imgbox imgbox1 col-xs-4">
									<a > 
									<img src="<?php echo $vo; ?>" class="js-img" />
									<input type="hidden" class="js-image" value="<?php echo $value['images'][$k-1]; ?>">
									</a>
								</div>
								
							</div>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
						
                	</div>
            	<?php } ?>
        	</div>
    	</div>
	    
</body>
<!-- 放大图片 -->
<script type="text/javascript " src="__STATIC__/js/baguetteBox.min.js "></script>
<script type="text/javascript ">
	$(document).ready(function() {
			
			//图片九宫格
		$(".imgbox img ").each(function(i) {
			var img = $(this);
			var realWidth; //真实的宽度
			var realHeight; //真实的高度
			$("<img/>").attr("src", $(img).attr("src")).load(function() {
				realWidth = this.width;
				realHeight = this.height;
				if (realWidth > realHeight) {
					$(img).addClass('imgbox_img_2');
				} else {
					$(img).addClass('imgbox_img_1');
				}
			});
		}); 
		
	
	});
	
	/**
     * jQuery
     */
    $(function () {
        // 首先调用JSBridge初始化代码，完成后再设置其他
        initJsBridge(function () {
		
		 //点击放大图片
		    $(document).on('click','.js-img',function(){
			 
				var index = $(this).parents('.main-class-ul').index();
				var length = $(this).parents('.main-class').find('.main-class-ul').length;
			    //alert(index);
			    var str = '';
				//var images = new Array();
				for(var i=0;i<length;i++){
					//images[i]=$(this).parents('.main-class').find('.js-image').eq(i).val();
				str +=$(this).parents('.main-class').find('.js-image').eq(i).val()+",";
				}
			    str += index;
			   //console.log(str);
			    window.WebViewJavascriptBridge.callHandler('get',str, function (response) {
					   // showResponse(response);
						});

		
		    })
		   
			//点击返回
			$(".back").click(function(){
				window.WebViewJavascriptBridge.callHandler('back','1', function (response) {
				
					 //showResponse(response);
				});
			
			})
			//点击写日记
			$(".writeNote").click(function(){
				window.WebViewJavascriptBridge.callHandler('writeNote','1', function (response) {
				
					 //showResponse(response);
				});
			
			})
			
			
			
		
	    })
		
		
	
	})
</script>
</html>