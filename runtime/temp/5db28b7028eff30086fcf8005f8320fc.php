<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"C:\wwwroot\AMS\public/../application/index\view\recipe\index.html";i:1522290791;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>每周食谱</title>
    <link rel="stylesheet" href="__STATIC__/css/index.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/swiper-3.4.2.min.css">
    <script type="text/javascript" src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script>
    <script type="text/javascript" src="__STATIC__/js/swiper-3.4.2.jquery.min.js"></script>
	<script src="__STATIC__/js/jsbridge.js"></script>
</head>
<body>
	<div id="wrap" style="background: #f0f0f0;">
    <header class="header  header-fixed">
        <a class="back"></a>每周食谱
    </header>
    <div id="rightPart2" class="box rightPart2" style="margin-top: 0.54rem;">
		<div class="schedule1">
			<div id="calendar" class="dateShow">
				<table id="dayshow" class="dates">
					<tr class="riqi">
						<th class="tl" colspan="2"><button id="last-week" class="lastW" title="上一周">上一周</button></th>
						<th id="curday" style="color:#333;font-weight: bold !important;" colspan="3"></th>
						<th class="tr" colspan="2"><button id="next-week" class="nextW" title="下一周">下一周</button></th>
					</tr>
					<tr id="monitor">
						<td><div class="<?php if(!empty($riqi['0'])){?>button-bg <?php } ?>"><button >日<br/><i></i></button></div></td>
						<td><div class="<?php if(!empty($riqi['1'])){?>button-bg <?php } ?>"><button >一<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['2'])){?>button-bg <?php } ?>"><button >二<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['3'])){?>button-bg <?php } ?>"><button >三<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['4'])){?>button-bg <?php } ?>"><button  >四<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['5'])){?>button-bg <?php } ?>"><button >五<br/><i></i></button></div></td>
						<td><div class="<?php if(!empty($riqi['6'])){?>button-bg <?php } ?>"><button  >六<br/><i></i></button></div></td>
					</tr>
				</table>
			</div>
		</div>
		<div id="ranking-item-container1" class="swiper-container" >
		    <div class="swiper-wrapper">
			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
			    <div class="swiper-slide ">
			        <div class="content-slide">
					<?php if(empty($vo)): ?>
				        <div class="default-class">
				        	<div><img src="__STATIC__/img/defult-food.png"/></div>
				        	<p>暂无食谱</p>
				        </div>
			       
					<?php else: ?>
			   
				        <div class="main-food-family">
							<dl>
								<dt class="dt-breakfast">早餐</dt>
								<?php if(is_array($vo['breakfast']) || $vo['breakfast'] instanceof \think\Collection || $vo['breakfast'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['breakfast'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
								<dd><?php echo $vo1['name']; ?></dd>
								<?php endforeach; endif; else: echo "" ;endif; ?>
								
								<dt class="dt-lunch">中餐</dt>
								<?php if(is_array($vo['lunch']) || $vo['lunch'] instanceof \think\Collection || $vo['lunch'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['lunch'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
								<dd><?php echo $vo1['name']; ?></dd>
								<?php endforeach; endif; else: echo "" ;endif; ?>
								<dt class="dt-mug-up">午点</dt>
								<?php if(is_array($vo['snack']) || $vo['snack'] instanceof \think\Collection || $vo['snack'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['snack'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
								<dd><?php echo $vo1['name']; ?></dd>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</dl>
							<div class="main-food">
						        <ul class="row main-food-ul">
								<?php if(is_array($vo['recipeImage']) || $vo['recipeImage'] instanceof \think\Collection || $vo['recipeImage'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['recipeImage'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
						            <li>
						                <a >
						                	<img src="<?php echo $vo3['1']; ?>" class="js-img"/>
											<input type="hidden" class="js-image" value="<?php echo $vo3['0']; ?>">
						                </a>
						                <p class="ppp1"><?php echo $vo3['2']; ?></p>
						            </li>
								<?php endforeach; endif; else: echo "" ;endif; ?>
						            
						            
						        </ul>
						    </div>
						    
							<p class="zan-pass">
								<?php if(is_array($vo['zanname']) || $vo['zanname'] instanceof \think\Collection || $vo['zanname'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['zanname'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
									<span>
										<i><?php echo $vo3; ?></i>
									</span>
						     	<?php endforeach; endif; else: echo "" ;endif; ?>
							</p>
							<?php if($vo['iszan'] == ''): ?>
							<a href="javascript:" class="click-zan" style="margin:0.1rem 50%;">
							   <input type="hidden" name="rid" value="<?php echo $vo['id']; ?>" class="rid">
							</a>
							<?php else: ?>
							<a href="javascript:" class="click-zan click-zan-red" style="margin:0.1rem 50%;">
							    <input type="hidden" name="rid" value="<?php echo $vo['id']; ?>" class="rid">
							</a>
							<?php endif; ?>
						</div>
						<?php endif; ?>
			        </div>
			    </div>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			   <input type="hidden" name="schoolAccount" value="<?php echo $schoolAccount; ?>">
			 
               <input type="hidden" name="phone_account" value="<?php echo $phone_account; ?>">
		       <input type="hidden" name="account" value="<?php echo $account; ?>">
			   <input type="hidden" name="type" value="<?php echo $type; ?>">
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
	$(function() {
		var jsButton = $("#monitor button")
		var rankingSwiper = new Swiper('#ranking-item-container1', {
			speed: 500,
			on: {
				slideChangeTransitionStart: function() {
					$("#monitor .mark1").removeClass('mark1');
					jsButton.eq(this.activeIndex).addClass('mark1');
				},
			},
		})
		$("#monitor td").on('click', function(e) {
			e.preventDefault()
			$("#monitor .mark1").removeClass('mark1')
			$(this).find('button').addClass('mark1')
			rankingSwiper.slideTo($(this).index())
		})
	
	initJsBridge(function () {
		$(document).on('click','.js-img',function(){
			 
				var index = $(this).parents('li').index();
				var length = $(this).parents('.main-food-ul').find('li').length;
			//alert(length);
			    var str = '';
				//var images = new Array();
				for(var i=0;i<length;i++){
					str +=$(this).parents('.main-food').find('.js-image').eq(i).val()+',';
				
				}
			    str += index;
			  // console.log(images);
			    window.WebViewJavascriptBridge.callHandler('get',str, function (response) {
					   // showResponse(response);
						});

		
		})
			
		$(".back").click(function(){
				window.WebViewJavascriptBridge.callHandler('back','1', function (response) {
				
					 //showResponse(response);
		        });
	    })	
	})
})
</script>

<script type="text/javascript">
$(function(){
	    

		$('.click-zan').click(function(){
                var rid = $(this).find('.rid').val();
				//alert(cid);
                var type = $(" input[ name='type' ] ").val();
				var phone_account = $(" input[ name='phone_account' ] ").val();
				var account = $(" input[ name='account' ] ").val();
                if($(this).hasClass('click-zan-red')){
			
                    $(this).removeClass('click-zan-red');
                }else{
                    $(this).addClass('click-zan-red')
                }
                $.ajax({
                url:'<?php echo url("recipe/zan"); ?>',
                type:'post',
                data:{rid:rid,phone_account:phone_account,account:account,type:type},
                dataType:'json',
                success:function(d){
             
                    $(".zan-pass").html(d);
                }

                })
            })
	})	
</script>

<script src="__STATIC__/js/date.js"></script> 
</body>
</html>
