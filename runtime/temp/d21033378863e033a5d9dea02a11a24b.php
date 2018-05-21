<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"C:\wwwroot\AMS\public/../application/index\view\notice\details.html";i:1522304424;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title><?php echo $notice['title']; ?></title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
	<script src="__STATIC__/js/jsbridge.js"></script>
</head>
<body>
    <div id="wrap">
        <header class="header header-fixed">
            <a href="<?php echo url('notice/index'); ?>?phone_account=<?php echo $phone_account; ?>&account=<?php echo $account; ?>&type=<?php echo $type; ?>" class="back back1"></a>
            
        </header>
		<section class="read">
			<img src="__STATIC__/img/read.png" width="100%"/>
		</section>
        <div class="information">
        	<section>
	            <div class="fl server-head ">
	            	<img src="<?php echo $notice['headerurl']; ?>"/>
	            </div>
	            <div class="fl server-news">
	            	<p>
		            	<?php echo $notice['nickname']; ?><br />
		            	<span><?php echo $notice['release_time']; ?></span>
		            </p>
	            </div>
	            <div class="clear"></div>
	        </section>
			<h1 class="h1"><?php echo $notice['title']; ?></h1>
	        <p><?php echo $notice['content']; ?></p>
	        <?php if($notice['images'] != ''): if(is_array($notice['images']) || $notice['images'] instanceof \think\Collection || $notice['images'] instanceof \think\Paginator): $k = 0; $__LIST__ = $notice['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k % 2 );++$k;?>
				<div >
				   <img src="<?php echo $vo1; ?>" class="js-img"/>
				   <input type="hidden" class="js-image" value="<?php echo $vo1; ?>"/>
				</div>
				<?php endforeach; endif; else: echo "" ;endif; endif; ?>
       		<section>
       			<div class="arr-show">
       				<span class="arr-show-click arr-show-left">评论<i><?php echo $commentCount; ?></i></span>
	        		<span class="arr-show-right">赞<i class="zanCount"><?php echo $zanCount; ?></i></span>
       			</div>
	        	<div class="arr-zan arr-zan-left">
	        		<ul>
	        			<?php if(is_array($comment) || $comment instanceof \think\Collection || $comment instanceof \think\Paginator): $i = 0; $__LIST__ = $comment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        			<li>
	        				<img src="<?php echo $vo['headerurl']; ?>"/>
	        				<i><?php echo $vo['nickname']; ?></i>
	        				<span><?php echo $vo['comment_time']; ?></span>
	        				<p><?php echo $vo['content']; ?></p>
	        			</li>
	        			<?php endforeach; endif; else: echo "" ;endif; ?>
	        		</ul>
	        		
	        	</div>
	        	<div class="arr-zan arr-zan-right">
	        		<ul><?php if(is_array($zan) || $zan instanceof \think\Collection || $zan instanceof \think\Paginator): $i = 0; $__LIST__ = $zan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
	        			<li><img src="<?php echo $vo2['headerurl']; ?>"/><i><?php echo $vo2['nickname']; ?></i></li>
	        			<?php endforeach; endif; else: echo "" ;endif; ?>
	        		</ul>
	        	</div>
	        </section>
        </div>
        
		<input type="hidden" name="nid" value="<?php echo $nid; ?>">
        <input type="hidden" name="phone_account" value="<?php echo $phone_account; ?>">
		
		
        <div class="information-fixed">
        	<input type="text" name="" id="" value="" placeholder="写评论..." readonly="value" class="comment"/>
        	<a href="javascript:" class="<?php if($iszan == ''): ?>click-zan <?php else: ?>click-zan click-zan-red<?php endif; ?> fr"  >
        	</a>
	        
        	
        </div>
    </div>
</body>
	<script type="text/javascript">
	
	
	/**
     * 显示响应信息
     * @param response 响应信心
     */
    function showResponse(response) {
        alert(response);
    }
	  /**
     * jQuery
     */
    $(function () {
        // 首先调用JSBridge初始化代码，完成后再设置其他
        initJsBridge(function () {
		    var jsImg = $('img.js-img');
			var images = new Array();
			var str = '';
			var str2= '';
			var str1= '';
			for (var i = 0; i < jsImg.length; i++) {
			    var a = jsImg[i];
			    a.index = i;
				
				str +=a.src+",";
			    a.onclick = function () {

					str1 += this.index;
					
					str2=str+str1;
					window.WebViewJavascriptBridge.callHandler('get', str2, null);
						 
					str1 = '';

			    }

			}
			$(".comment").click(function(){
			    var nid = $(" input[ name='nid' ] ").val();
			    window.WebViewJavascriptBridge.callHandler('comment1',nid, function (response) {
                    //showResponse(response);
                });
			
			
			})

         });
			
			
          
    })
		
			<!-- var jsImg = $('img.js-img'); -->
			<!-- var images = new Array(); -->
			<!-- var str = ''; -->
			<!-- var str2= ''; -->
			<!-- var str1= ''; -->
			<!-- for (var i = 0; i < jsImg.length; i++) { -->
			    <!-- var a = jsImg[i]; -->
			    <!-- a.index = i; -->
				
				<!-- str +=a.src+","; -->
			    <!-- a.onclick = function () { -->

					<!-- str1 += this.index; -->
					
					<!-- str2=str+str1;str1 = ''; -->
					<!-- console.log(str); -->
					<!-- //window.WebViewJavascriptBridge.callHandler('get', str, null); -->
						
				

					
			    <!-- } -->
				
				
			<!-- } -->
    

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
		
		function Back(){
			android.toFinish();
			
		}
	</script>
	
	    <script type="text/javascript">
        $(document).ready(function(){
           $(document).on('click','.click-zan',function(){
                var nid = $(" input[ name='nid' ] ").val();
				var phone_account = $(" input[ name='phone_account' ] ").val();
				var account = $(" input[ name='account' ] ").val();
                <!-- if($(this).hasClass('click-zan-red')){ -->
                    <!-- $(this).removeClass('click-zan-red') -->
                <!-- }else{ -->
                    <!-- $(this).addClass('click-zan-red') -->
                <!-- } -->
				
                $.ajax({
                url:'<?php echo url("notice/noticezan"); ?>',
                type:'post',
                data:{id:nid,phone_account:phone_account},
                dataType:'json',
                success:function(data){
			
					if(data ==1){
					     location.reload();
					    <!-- $('.fr').addClass('click-zan-red'); -->
						<!-- zanCount = parseInt($('.zanCount').text())+1; -->
						<!-- $('.zanCount').text(zanCount) -->
					}
					if(data ==2){
					    location.reload();
					    <!-- $('.fr').removeClass('click-zan-red');// -->
						<!-- zanCount = parseInt($('.zanCount').text())-1; -->
						
						<!-- $('.zanCount').text(zanCount) -->
					}
					
                 
                
                }            
            }) 
        })
    })
    </script>
</html>