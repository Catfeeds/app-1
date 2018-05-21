<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"C:\wwwroot\AMS\public/../application/index\view\news\index.html";i:1513664050;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>消息</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
</head>
<body>
    <div id="wrap">
      <div class="center_box">
        <h2>消息</h2>
      </div>
      <div class="center_news">
        <div class="center_news_imp">
        	<a href="javascript:">
        		<div class="center_news_imp_l"style="background: #33CC99;">
        			<img src="__STATIC__/img/AddressList.png"/>
        		</div>
        		<div class="center_news_imp_r">
        			<span>通讯录</span>
        		</div>
        	</a>
        	<a href="javascript:">
        		<div  class="center_news_imp_l"style="background: #cc6666;">
        			<img src="__STATIC__/img/newTZ.png"/>
        		</div>
        		<div class="center_news_imp_r">
        		<span>评论提醒</span>
        		</div>
        	</a>
        	<a href="javascript:">
        		<div  class="center_news_imp_l" style="background: #ffcc33;">
        			<img src="__STATIC__/img/bast .png"/>
        		</div>
        		<div class="center_news_imp_r">
        		<span>点赞提醒</span>
        		</div>
        	</a>
        	
        </div>
        <div class="notice_news">
        	<a href="javascript:">
        		<div  class="center_news_imp_l">
        			<img src="__STATIC__/img/mommy.jpg"/>
        		</div>
        		<div class="center_news_imp_r">
        		<p><span>同户名</span><span class="fr" style="font-size: 0.14rem;">2017-12-12</span></p>
        		<p class="ppp1">幼儿园专题,汇集与幼儿园相关的最新资讯,幼儿园最新信息,内容全部来自中国国际教育网精心选择与幼儿园相关的信息</p>
        		</div>
        	</a>
        </div>
      </div>
    </div>
    <div class="foot_nav">
         <a href="<?php echo \think\Url::build('Circle/index'); ?>" class="fl">
            <i class="fexed-qzhi"></i>
            <span style=" color: #aeaeac;font-size: 10px;">圈子</span>
          </a>
           <a href="<?php echo \think\Url::build('Index/index'); ?>" class="active_nav">
           <i class="fexed-class"></i>
           <span style="font-size: 10px;">班级</span>
          </a>
		 <a href="<?php echo \think\Url::build('News/index'); ?>" class="active ">
           <i class="fexed-new"></i>
           <span style="color: #aeaeac; font-size: 10px;">消息</span>
         </a>
          <a href="<?php echo \think\Url::build('Entrance/index'); ?>" class="fr"> 
            <i class="fexed-room"></i>
            <span style="color: #aeaeac;font-size: 10px;">门禁</span>
          </a>
          <a href="<?php echo \think\Url::build('Personal/index'); ?>" class="fr"> 
            <i class="fexed-our"></i>
            <span style="color: #aeaeac;font-size: 10px;">我的</span>
          </a>
    </div>
</body>

</html>