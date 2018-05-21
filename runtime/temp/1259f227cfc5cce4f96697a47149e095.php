<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"C:\wwwroot\AMS\public/../application/index\view\schoolnews\index.html";i:1517194616;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script type="text/javascript" src="__STATIC__/js/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script> 
</head>
	<style type="text/css">
		.school_news_list{overflow: hidden;border-top:1px dashed #3c9;border-bottom:1px dashed #3c9;margin-top: 0.2rem;padding: 0.05rem;}
		.school_news_list_img{border-radius:0.02rem;width: 30%;height:0.75rem;overflow: hidden;position: relative;margin: 0.05rem 0;}
		.school_news_list p{line-height:0.25rem;font-size: 0.16rem;color: #333;}
		.school_news_list_w{width: 70%; padding:0 2%;}
		.school_news_list_w .ppp2{height:0.50rem;}
		.color_888{color: #aaa !important;margin: 0.1rem;font-size: 0.14rem !important;}
	</style>
<body>
	<div id="wrap">
		<!-- <header class="header  header-fixed"> -->
			<!-- <a href="javascript:;" onclick="history.go(-1)" class="back"></a>校内新闻 -->
		<!-- </header> -->
		<div style="margin-top:0rem ;"></div>
         <!--5-->
        <div class="server-public" style="padding:0.01rem 0.1rem 0.54rem;">
        	<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		        <div class="school_news_list">
		        	<a href="<?php echo url('schoolnews/detail'); ?>?id=<?php echo $vo['id']; ?>">
			           	<div class="fl school_news_list_w">
			           		<p class="ppp2">
				            	【<i><?php echo $vo['title']; ?></i>】
				            	
				            	<?php  
				            	  echo  mb_substr(strip_tags($vo['content']),0,50,'utf-8');      
				            	?>
				            </p>
				            <p class="color_888"><span class="fl"><?php echo $vo['release']; ?></span><span class="fr"><?php echo $vo['release_time']; ?></span></p>
			           	</div>
		           	</a>
		           	<a href="<?php echo url('schoolnews/detail'); ?>?id=<?php echo $vo['id']; ?>">
			           	<div class="fr school_news_list_img">
				           	<img src="<?php echo $vo['image']; ?>" onerror="this.src='__STATIC__/img/zw-picture.jpg'"/>
				        </div>
			        </a>
		        </div>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
    </div>
</body>
<!-- 放大图片 -->
<script type="text/javascript">
	$(document).ready(function(){
		//图片九宫格
		$(".school_news_list_img img").each(function(i){
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