<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"C:\wwwroot\AMS\public/../application/index\view\informationarticle\index2.html";i:1522464427;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>详情页</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
</head>
<body>
    <div id="wrap">
        <!-- <header class="header header-fixed"> -->
            <!-- <a href="javascript:;" onclick="history.go(-1)" class="back back1"></a> -->
            	<!-- 详情 -->
        <!-- </header> -->
        <div class="information-e" style="margin-top: 0rem;" >
        	<h1 style="line-height:0.4rem;font-size: 0.26rem;margin: 0.2rem 0;"><?php echo $info['title']; ?></h1>
			
        	<section >
	            <div class="fl server-head ">
	            	<img src="<?php echo $info['headerurl']; ?>" />
	            </div>
	            <div class="fl server-news">
	            	<p>
		            	<?php echo $info['nickname']; ?><br />
		            	<span><?php echo $info['release_time']; ?></span>
		            </p>
	            </div>
	            <div class="clear"></div>
	        </section>
	        <div class="ma-t20">
	        	<?php echo $info['content']; ?>
	        </div>
			<section>
       			<div class="arr-show">
       				<span class="arr-show-click arr-show-left">评论<span><?php echo $info['commentCount']; ?></span></span>
	        		
       			</div>
	        	<div class="arr-zan arr-zan-left">
	        		<ul>
	        			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
		        			<li>
		        				<img src="<?php echo $vo1['headerurl']; ?>"/>
		        				<i><?php echo $vo1['nickname']; ?></i>
		        				<span><?php echo $vo1['comment_time']; ?></span>
		        				<p><?php echo $vo1['content']; ?></p>
		        			</li>
	        			<?php endforeach; endif; else: echo "" ;endif; ?>

	        		</ul>
	        		
	        	</div>
	        	
	        </section>
       		
        </div>
        
        <!--<div class="information-fixed">
        	<input type="text" name="" id="" value="" placeholder="写评论..." readonly="value"/>
        	<a href="javascript:" class="click-zan fr">
        		
        	</a>
	        <a href="javascript:" class="return-news fr"></a>
        	
        </div>-->
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