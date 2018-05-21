<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"C:\wwwroot\AMS\public/../application/index\view\notice\index.html";i:1522134259;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>园内通知</title>
	<link rel="stylesheet" type="text/css" href="__STATIC__/css/layui.css"/>
    <link rel="stylesheet" href="__STATIC__/css/baguetteBox.min.css">
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <link rel="stylesheet" type="text/css" href="__STATIC__/js/layui/css/layui.css"/>
    <script type="text/javascript" src="__STATIC__/js/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script> 
	<script src="__STATIC__/js/jsbridge.js"></script>
	<script src="__STATIC__/js/yuanzhang.js"></script>
</head>

<body>
	<div id="wrap">
		<header class="header  header-fixed">
			<a class="back" ></a>园内通知
		</header>
		<div style="margin-top:0.64rem ;"></div>
		<?php if($type == 1): ?>
	    <div class="kindergarten-leader">
    		<div class="select">请选择查看班级<div class="select-img"><img src="__STATIC__/img/back.png"width="100%"/></div></div>
    		<ul class="option display">
			<?php if(is_array($classManagement) || $classManagement instanceof \think\Collection || $classManagement instanceof \think\Paginator): $i = 0; $__LIST__ = $classManagement;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
    			<li>
    				<?php echo $vo['class']; ?>
    				<ul class="optionCh display">
					<?php if(is_array($vo['className']) || $vo['className'] instanceof \think\Collection || $vo['className'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['className'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
    					<li><a href="<?php echo url('notice/index'); ?>?className=<?php echo $vo['class']; ?><?php echo $vo1['className']; ?>&account=<?php echo $account; ?>&type=<?php echo $type; ?>"><?php echo $vo['class']; ?><?php echo $vo1['className']; ?>班</a></li>
    					
					<?php endforeach; endif; else: echo "" ;endif; ?>
    				</ul>
    			</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
    			
    		</ul>
    	</div>
        <?php endif; ?>
		<!--1-->
		 <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<a href="<?php echo url('notice/details'); ?>?id=<?php echo $vo['id']; ?>&phone_account=<?php echo $phone_account; ?>&account=<?php echo $account; ?>&type=<?php echo $type; ?>">
		    <div class="server-public <?php if($vo['isread'] != '1'): ?>server-public-1<?php endif; ?>">
				<div>
					<div class="fl server-head">
						<img src="<?php echo $vo['headerurl']; ?>" />
					</div>
					<div class="fl server-news">
						<p>
							<?php echo $vo['nickname']; ?><br />
							<span><?php echo $vo['release_time']; ?></span>
						</p>
					</div>
					<div class="clear"></div>
				</div>
				<h1 class="h1"><?php echo $vo['title']; ?></h1>
				
				<?php echo $vo['content']; ?>
			
			
				<div class="main-class">
					<div class="server-img row main-class-ul">
						<?php if($vo['img'] != ''): if(is_array($vo['img']) || $vo['img'] instanceof \think\Collection || $vo['img'] instanceof \think\Paginator): $k = 0; $__LIST__ = $vo['img'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k % 2 );++$k;if($k < 9): ?>
					
						<div class="imgbox imgbox1 col-xs-4">
							<span> 
								<img src="<?php echo $vo1['1']; ?>"/>
							</span>
						</div>
						<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
						
					</div>
				</div>
				<?php if($vo['isread'] == ''): ?>
				<div class="server-cfr">
					<span href="#">确认收到</span>
				</div>
				<?php endif; ?>
			</div>
		</a>
        <div class="line_bgw "  style="background:#fff;"></div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
       

            </div>
        </div>
        
	</div>
</body>
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
		
	})
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
</script>
</html>