<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"C:\wwwroot\AMS\public/../application/index\view\schoolnews\details.html";i:1517194635;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title><?php echo $info['title']; ?></title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
</head>
<body>
    <div id="wrap">
        <!-- <header class="header header-fixed"> -->
            <!-- <a href="javascript:;" onclick="history.go(-1)" class="back back1"></a> -->
            <!-- 校内新闻 -->
        <!-- </header> -->
        <div style="margin-top: 0"></div>
        <div class="information" style="margin-top: 0;">
        	<h1 style="line-height:0.4rem;font-size: 0.26rem;margin: 0.2rem 0;"><?php echo $info['title']; ?></h1>
        	
        	<div>
	            
	            <div class="fl server-news">
	            	<p style="margin-top: -0.2rem;margin-bottom: 0.2rem;">
		            	<span ><?php echo $info['release']; ?></span>&nbsp;&nbsp;
		            	<span ><?php echo $info['release_time']; ?></span>
		            	
		            </p>
	            </div>
	            <div class="clear"></div>
	        </div>
	        <?php echo $info['content']; ?>
       		
        </div>
   
    </div>
</body>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.arr-show-left').click(function(){
				$(this).addClass('arr-show-click')
				$(this).siblings().removeClass('arr-show-click')
				$('.arr-zan-left').show();
				$('.arr-zan-right').hide()
			})
			
			$('.arr-show-right').click(function(){
				$(this).addClass('arr-show-click')
				$(this).siblings().removeClass('arr-show-click')
				$('.arr-zan-left').hide();
				$('.arr-zan-right').show()
				
			})
			
			//图片九宫格
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
			
		})
	</script>
</html>