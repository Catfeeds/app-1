<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"C:\wwwroot\AMS\public/../application/index\view\expression\index.html";i:1522133396;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>园区风采</title>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/layui.css"/>
    <link rel="stylesheet" href="__STATIC__/css/baguetteBox.min.css">
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <link rel="stylesheet" type="text/css" href="__STATIC__/js/layui/css/layui.css"/>
    <script type="text/javascript" src="__STATIC__/js/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script> 
	<script src="__STATIC__/js/jsbridge.js"></script>
	
</head>

<body>
	<div id="wrap">
		<header class="header  header-fixed">
			<a class="back"></a>园区风采
		</header>
		<div style="margin-top:0.7rem ;"></div>
		<!--1-->
		 <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		 <a href="<?php echo url('expression/details'); ?>?id=<?php echo $vo['id']; ?>&phone_account=<?php echo $phone_account; ?>&account=<?php echo $account; ?>&type=<?php echo $type; ?>">
		<div class="server-public">
			
				<div>
					<div class="fl server-head">
						<img src="<?php echo $vo['headerurl']; ?>" />
					</div>
					<div class="fl server-news">
						<p>
							<?php echo $vo['nickname']; ?><br />
							<span><?php echo $vo['release_time']; ?></span
						</p>
					</div>
					<div class="clear"></div>
				</div>
				<h1 class="h1"><?php echo $vo['title']; ?></h1>
				<p>
					<?php echo $vo['content']; ?>
				</p>
			
			

			<div class="main-class">
				<div class="server-img row main-class-ul">
					<?php if($vo['images'] != ''): if(is_array($vo['images']) || $vo['images'] instanceof \think\Collection || $vo['images'] instanceof \think\Paginator): $k = 0; $__LIST__ = $vo['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k % 2 );++$k;if($k < 9): ?>
				
					<div class="imgbox imgbox1 col-xs-4">
						 
						    <img src="<?php echo $vo['thumbs'][$k-1]; ?>"/>
					    
					</div>
					<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
					
				</div>
			</div>
		
			<div class="server-click">
				
				<p class="fr server-click-int">
					<span><i class=" click-int"><?php echo $vo['comment_count']; ?></i>评论</span>
					<span><i class=" click-int"><?php echo $vo['zan_count']; ?></i>人赞</span>
				</p>
			</div>
			<!-- <div class="server-comment">
				<form>
					<input type="text" placeholder="发表你的评价" class="server-comment-inputs fl" style="color: #aaa;" />
					<button class="layui-icon send-button-bq" ">&#xe650;</button>
	            	<button class="send-button ">发送</button>
	            </form>
	        </div> -->
        </div>
		</a>
        <div class="line_bgw " style="background:#fff;"></div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
       

            </div>
        </div>
        
	</div>
</body>
<!-- 放大图片 -->
<script type="text/javascript" src="__STATIC__/js/baguetteBox.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		/*点赞*/
		$('.click-zan').click(function(){
			if($(this).hasClass('click-zan-red')){
				$(this).removeClass('click-zan-red')
			}else{
				$(this).addClass('click-zan-red')
			}
		})
		/*发送评论*/
		$('.return-news').click(function(){
			$(this).parent().next().toggle()
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
		baguetteBox.run('.main-class');
		
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
	})
</script>

</html>