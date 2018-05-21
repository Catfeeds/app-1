<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"C:\wwwroot\AMS\public/../application/index\view\note\selectnote.html";i:1522295835;}*/ ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>查看日记</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
	<script src="__STATIC__/js/jsbridge.js"></script>
</head>
<body>
<div id="wrap">
    <header class="header">
        <a href="javascript:;" onclick="history.go(-1)" class="back"></a>所有日记
    </header>
    <div class="star_b zb_b jy" style="padding:2vw">
    	<ul class="zb_ul">
		<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<a href="<?php echo url('Note/index2'); ?>?account=<?php echo $vo['account']; ?>&phone_account=<?php echo $vo['phone_account']; ?>">
				<li>
					<img src="<?php echo $data2[$key]['student_headurl']; ?>" title="成长日记" />
					<div>
						<p> <?php echo $data2[$key]['name']; ?></p>
						<p> <?php echo $data2[$key]['className']; ?>班<span class="fr"><?php echo $vo['release_time']; ?></span></p>
					</div>
				</li>
			</a>
		<?php endforeach; endif; else: echo "" ;endif; ?>
			
		</ul>
		
    </div>
</div>
</body>
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

			//点击返回
			$(".back").click(function(){
				window.WebViewJavascriptBridge.callHandler('back','1', function (response) {
				
					 //showResponse(response);
				});
			
			})

	    })
		
	
	})
</script>
</html>
