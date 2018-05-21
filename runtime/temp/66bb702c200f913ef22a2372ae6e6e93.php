<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"C:\wwwroot\AMS\public/../application/index\view\modifyinformation\index.html";i:1513914720;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>修改信息</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
</head>
<body>
    <div id="wrap">
        <header class="header header-fixed">
            <a href="javascript:;" onclick="history.go(-1)" class="back back1"></a>
            	详情
        </header>
        <div class="information">
        	<div>
	            <div class="fl server-head ">
	            	<img src="__STATIC__/img/dada.jpg"/>
	            </div>
	            <div class="fl server-news">
	            	<p>
		            	用户名<br />
		            	<span>时间</span>
		            </p>
	            </div>
	            <div class="clear"></div>
	        </div>
	        <p>幼儿依次取沙袋在场地自由分散进行一物多玩活动，例如：自抛接沙袋，并向其他幼儿推广。</p>
       		<img src="__STATIC__/img/timg(18).jpg"/>
       		<img src="__STATIC__/img/timg19.jpg"/>
       		<img src="__STATIC__/img/timg(17).jpg"/>
       		<div>
       			<div class="arr-show">
       				<span class="arr-show-click arr-show-left">评论<i>x</i></span>
	        		<span class="arr-show-right">赞<i>y</i></span>
       			</div>
	        	<div class="arr-zan arr-zan-left">
	        		<ul>
	        			<li>
	        				<img src="__STATIC__/img/dada.jpg"/>
	        				<i>用户名</i>
	        				<span>3小时前</span>
	        				<p>幼儿依次取沙袋在场地自由分散进行一物多玩活动，例如：自抛接沙袋，并向其他幼儿推广。</p>
	        			</li>
	        			<li>
	        				<img src="__STATIC__/img/dada.jpg"/><i>用户名</i>
	        				<span>3小时前</span>
	        				<p>幼儿依次取沙袋在场地自由分散进行一物多玩活动，例如：自抛接沙袋，并向其他幼儿推广。</p>
	        			</li>
	        		</ul>
	        		
	        	</div>
	        	<div class="arr-zan arr-zan-right">
	        		<ul>
	        			<li><img src="__STATIC__/img/dada.jpg"/><i>用户名</i></li>
	        			<li><img src="__STATIC__/img/dada.jpg"/><i>用户名</i></li>
	        			<li><img src="__STATIC__/img/dada.jpg"/><i>用户名</i></li>
	        			<li><img src="__STATIC__/img/dada.jpg"/><i>用户名</i></li>
	        		</ul>
	        	</div>
	        </div>
        </div>
        
        <div class="information-fixed">
        	<input type="text" name="" id="" value="" placeholder="写评论..." readonly="value"/>
        	<a href="javascript:" class="click-zan fr">
        		
        	</a>
	        
        	
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
			
			
			
		})
	</script>
</html>