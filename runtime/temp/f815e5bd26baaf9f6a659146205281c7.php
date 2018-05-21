<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"C:\wwwroot\AMS\public/../application/index\view\circlemodifyinformation\index.html";i:1515132703;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>查看详情</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
</head>
<body>
    <div id="wrap">
        <header class="header header-fixed">
            <a onclick="myOnclick()" href='ios://gotoback'  value="back" class="back back1"></a>
            	详情
				
        </header>
        <div class="information">

			<div>
	            <div class="fl server-head ">
					<!--发布动态的老师头像-->
	            	<img src="__STATIC__/../../<?php echo $employee_headurl; ?>"/>
	            </div>
	            <div class="fl server-news">
	            	<p>
		            	<?php echo $data['release']; ?><br />
		            	<span><?php echo date('Y-n-j H:i',strtotime($data['release_time'])); ?></span>
		            </p>
	            </div>
	            <div class="clear"></div>
	        </div>
	        <p><?php echo htmlspecialchars_decode($data['content']); ?></p>
       		<!--<img src="__STATIC__/img/timg(18).jpg"/>-->
       		<!--<img src="__STATIC__/img/timg19.jpg"/>-->
       		<!--<img src="__STATIC__/img/timg(17).jpg"/>-->
			<div>
       			<div class="arr-show">
       				<span class="arr-show-click arr-show-left">评论<i><?php echo count($comment_array); ?></i></span>
	        		<span class="arr-show-right">赞<i><?php echo count($zan_array); ?></i></span>
       			</div>
	        	<div class="arr-zan arr-zan-left">
	        		<ul>
	        			<?php if(is_array($comment_array) || $comment_array instanceof \think\Collection || $comment_array instanceof \think\Paginator): $i = 0; $__LIST__ = $comment_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
							<li>
								<!--判断当前输出的是老师还是学生-->
								<?php
									 $array = $personal[$key];
									 if(array_key_exists('student_headurl',$array)){
								?>
										 <img src="__STATIC__/../../<?php echo $personal[$key]['student_headurl']; ?>" />
								<?php

									 }else{
								?>
										 <img src="__STATIC__/../../<?php echo $personal[$key]['employee_headurl']; ?>" />
								<?php
									 }
								?>
								<!--/判断当前输出的是老师还是学生-->
								<i><?php echo $personal[$key]['name']; ?></i>
								<span><?php echo date('Y-n-j H:i',strtotime($vo['comment_time'])); ?></span>
								<p><?php echo $vo['content']; ?></p>
							</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
	        	<div class="arr-zan arr-zan-right">
	        		<ul>
	        			<?php if(is_array($zan_user) || $zan_user instanceof \think\Collection || $zan_user instanceof \think\Paginator): $i = 0; $__LIST__ = $zan_user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?>
						     <li>
								 <!--判断当前输出的是老师还是学生-->
								 <?php

									 if(array_key_exists('student_headurl',$v1)){
								?>
								         <img src="__STATIC__/../../<?php echo $v1['student_headurl']; ?>" />
								 <?php

									 }else{
								?>
								         <img src="__STATIC__/../../<?php echo $v1['employee_headurl']; ?>" />
								 <?php
									 }
								?>
								 <!--/判断当前输出的是老师还是学生-->
								 <i><?php echo $v1['name']; ?></i>
							 </li>
	        			<?php endforeach; endif; else: echo "" ;endif; ?>

	        		</ul>
	        	</div>
	        </div>
        </div>
        
        <div class="information-fixed">
        	<input type="text" name="" id="" value="" placeholder="写评论..." readonly="value"/>
        	<a href="javascript:;" class="click-zan fr" style="position: relative;left: -45px"></a>
	        <!--<a href="javascript:;" class="return-news fr"></a>-->
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
		});

		function myOnclick(){
　　		android.toFinish();
		//alert('1')
		}
	</script>
</html>