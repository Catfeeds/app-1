<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\wwwroot\AMS\public/../application/index\view\video\index.html";i:1512108879;}*/ ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title></title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
</head>
<body>
    <div id="wrap">
    <header class="header">
        <a href="javascript:;" onclick="history.go(-1)" class="back"></a>视频安全
    </header>
    <div class="star_b zb_b jy">
	
        <div class="zb_ls">
            <h3>天鹅湖园区</h3>
            <ul class="zb_ul clearfix">
			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li>
                    <?php echo (stripslashes(htmlspecialchars_decode($vo['content']))) ? stripslashes(htmlspecialchars_decode($vo['content'])) :  ''; ?>
                    <span><?php echo isset($vo['title']) ? $vo['title'] :  ''; ?>-<?php echo isset($vo['release_time']) ? $vo['release_time'] :  ''; ?></span>
                </li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
		
    </div>
</div>
<div class="foot_nav">
  <a href="<?php echo \think\Url::build('Index/index'); ?>" class="fl">
    <i class="home"></i>
    <span style="color: #aeaeac;">幼儿园</span>
</a>
  <a href="#" class="active active_nav">
   <i class="photo"></i>
   <span>活动相册</span>
 </a> 
  <a href="<?php echo \think\Url::build('Personal/index'); ?>" class="fr"> 
    <i class="center"></i>
    <span style="color: #aeaeac;">我的</span>
  </a>
</div>

</body>
</html>
