<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"C:\wwwroot\AMS\public/../application/index\view\informationarticle\cindex.html";i:1519781116;}*/ ?>
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
        <!--<header class="header header-fixed">
            <a href="javascript:;" onclick="history.go(-1)" class="back back1"></a>
            	详情
        </header>-->
        <div class="information-e" style="margin-top: 0;">
        	<h1 style="line-height:0.4rem;font-size: 0.26rem;margin: 0.2rem 0;"><?php echo $data['title']; ?></h1>
			<span class="add_follow_3 fr" onClick="ck(<?php echo $data['id']; ?>,'<?php echo $data['corpus_name']; ?>')" ><?php echo $data['corpus_name']; ?></span>
			<!-- <?php if($data['isme'] != 1): ?> -->
			<!-- <?php if($data['isconcern'] == 0): ?> -->
        	<!-- <span class="add_follow fr" onClick="btn1('<?php echo $data['bephone_account']; ?>')" id="guanzhu">关注</span> -->
        	<!-- <?php else: ?> -->
        	<!-- <span class="add_follow_2 fr" onClick="btn1('<?php echo $data['bephone_account']; ?>')" id="guanzhu">已关注</span> -->
        	<!-- <?php endif; ?> -->
			<!-- <?php endif; ?> -->
		
        	<input type="hidden" name="phone_account" value="<?php echo $data['phone_account']; ?>" id="phone_account"> 
        	<section >
	            <div class="fl server-head "  onClick="btn('<?php echo $data['bephone_account']; ?>')">
	            	<img src="<?php echo $data['headerurl']; ?>" />
	            </div>
	            <div class="fl server-news">
	            	<p>
		            	<?php if($data['nickname'] != ''): ?><?php echo $data['nickname']; else: ?><?php echo $data['grade']; ?>家长<?php endif; ?><br />
		            	<span><?php echo $data['release_time']; ?></span>
		            </p>
	            </div>
	            <div class="clear"></div>
	        </section>
	        <div class="ma-t20">
	        	<?php echo $data['content']; ?>
	        </div>
       		<section>
       			<div class="arr-show">
       				<span class="arr-show-click arr-show-left">评论<span><?php echo $data['comment_count']; ?></span></span>
	        		<span class="arr-show-right">喜欢<span><?php echo $data['collect_count']; ?></span></span>
       			</div>
	        	<div class="arr-zan arr-zan-left">
	        		<ul>
	        			<?php if(is_array($commentData) || $commentData instanceof \think\Collection || $commentData instanceof \think\Paginator): $i = 0; $__LIST__ = $commentData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
		        			<li>
		        				<img src="<?php echo $vo1['headerurl']; ?>" onClick="btn('<?php echo $vo1['phone_account']; ?>')"/>
		        				<i ><?php echo $vo1['nickname']; ?></i>
		        				<span><?php echo $vo1['comment_time']; ?></span>
		        				<p><?php echo $vo1['content']; ?></p>
		        			</li>
	        			<?php endforeach; endif; else: echo "" ;endif; ?>

	        		</ul>
	        		
	        	</div>
	        	<div class="arr-zan arr-zan-right">
	        		<ul>
	        			<?php if(is_array($collectData) || $collectData instanceof \think\Collection || $collectData instanceof \think\Paginator): $i = 0; $__LIST__ = $collectData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
	        			    <li><img src="<?php echo $vo2['headerurl']; ?>" onClick="btn('<?php echo $vo2['phone_account']; ?>')"/><i><?php echo $vo2['nickname']; ?></i></li>
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
			
		
			
		})

		function btn(phoneAccount){ 
		
		   android.info(phoneAccount); 
		}

        function ck(id,corpus_name){ 
		    android.jump(id,corpus_name); 
		   //android.jump(parseInt(id)); 
		} 		
		
		function btn1(bephone_account,phone_account){ 
		  var phone_account = $('#phone_account').val();
		    //alert(phone_account);
		     $.ajax({
                    type:'post',//选择get方式提交
                    url:'<?php echo url("informationarticle/corpusWorksConcern"); ?>',//将数据提交的页面
                    data:{phone_account:phone_account,bephone_account:bephone_account},//传值
                    dataType:'text',
                    success: function(data)
                    {
                        if(data ==0){
                            $('#guanzhu').removeClass('add_follow').addClass('add_follow_2');
                            $('#guanzhu').text('已关注');
                        }

                        if(data ==2){
                            $('#guanzhu').removeClass('add_follow_2').addClass('add_follow');
                            $('#guanzhu').text('关注');
                        }
                        //alert(data);
                    }
                })
		  
		}
	</script>
</html>