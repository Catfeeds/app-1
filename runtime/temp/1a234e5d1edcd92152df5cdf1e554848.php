<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"C:\wwwroot\AMS\public/../application/index\view\classroom\index.html";i:1522291667;}*/ ?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>宝宝课程</title>
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
<div id="wrap" style="padding: 0;background: #f0f0f0;min-height:95vh">
    <header class="header  header-fixed">
        <a  class="back"></a>宝宝课程
    </header>
    <div id="rightPart2" class="box rightPart2" style="margin-top: 0.54rem;">
	<?php if($type == 1): ?>
	<div class="kindergarten-leader">
    		<div class="select">请选择查看班级<div class="select-img"><img src="__STATIC__/img/back.png"width="100%"/></div></div>
    		<ul class="option display">
			<?php if(is_array($classManagement) || $classManagement instanceof \think\Collection || $classManagement instanceof \think\Paginator): $i = 0; $__LIST__ = $classManagement;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
    			<li>
    				<?php echo $vo['class']; ?>
    				<ul class="optionCh display">
					<?php if(is_array($vo['className']) || $vo['className'] instanceof \think\Collection || $vo['className'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['className'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
    					<li><a href="<?php echo url('classroom/index'); ?>?className=<?php echo $vo['class']; ?><?php echo $vo1['className']; ?>&account=<?php echo $account; ?>&type=<?php echo $type; ?>"><?php echo $vo['class']; ?><?php echo $vo1['className']; ?>班</a></li>
    					
					<?php endforeach; endif; else: echo "" ;endif; ?>
    				</ul>
    			</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
    			
    		</ul>
    	</div>
        <?php endif; ?>
		<div class="schedule1">
			<div id="calendar" class="dateShow">
				<table id="dayshow" class="dates">
					<tr class="riqi">
						<th class="tl" colspan="2"><button id="last-week" class="lastW" title="上一周">上一周</button></th>
						<th id="curday" style="color:#333;font-weight: bold !important;" colspan="3"></th>
						<th class="tr" colspan="2"><button id="next-week" class="nextW" title="下一周">下一周</button></th>
					</tr>
					<tr id="monitor">
						<td><div class="<?php if(!empty($riqi['0'])){?>button-bg2 <?php } ?>"><button >日<br/><i></i></button></div></td>
						<td><div class="<?php if(!empty($riqi['1'])){?>button-bg2 <?php } ?>"><button >一<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['2'])){?>button-bg2 <?php } ?>"><button >二<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['3'])){?>button-bg2 <?php } ?>"><button >三<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['4'])){?>button-bg2 <?php } ?>"><button  >四<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['5'])){?>button-bg2 <?php } ?>"><button >五<br/><i></i></button></div></td>
                        <td><div class="<?php if(!empty($riqi['6'])){?>button-bg2 <?php } ?>"><button  >六<br/><i></i></button></div></td>
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
				        	<div><img src="__STATIC__/img/defult-class.png"/></div>
				        	<p>暂无课时</p>
				        </div>
					<?php else: ?>
				        <div class="main-food-family">
							<a href="/index/starlist/index?date=<?php echo $weekDate[$k-1]; ?>&account=<?php echo $account; ?>" class="fr" style="background: #33cc99;text-align:center;display: block;height: 0.4rem;color: #fff;line-height: 0.4rem;width: 25%;border-radius:5em ;">
						今日表现</a>
							<dl>
							   
								<dt class="dt-am">AM </dt> 
								<?php if(is_array($vo['morning']) || $vo['morning'] instanceof \think\Collection || $vo['morning'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['morning'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
								<dd><?php echo $vo1['name']; ?></dd>
								<?php endforeach; endif; else: echo "" ;endif; ?>
								
								<dt class="dt-pm">PM</dt>
								<?php if(is_array($vo['afternoon']) || $vo['afternoon'] instanceof \think\Collection || $vo['afternoon'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['afternoon'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
								<dd><?php echo $vo2['name']; ?></dd>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</dl>
							<div class="main-class">
						        <ul class="row main-class-ul">
								<?php if(is_array($vo['classroomImages']) || $vo['classroomImages'] instanceof \think\Collection || $vo['classroomImages'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['classroomImages'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
						            <li class="col-xs-4">
						                <a >
						                	<img src="<?php echo $vo3['1']; ?>" class="js-img"/>
											<input type="hidden" class="js-image" value="<?php echo $vo3['0']; ?>">
						                </a>
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
							   <input type="hidden" name="cid" value="<?php echo $vo['id']; ?>" class="cid">
							</a>
							<?php else: ?>
							<a href="javascript:" class="click-zan click-zan-red" style="margin:0.1rem 50%;">
							    <input type="hidden" name="cid" value="<?php echo $vo['id']; ?>" class="cid">
							</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
						
						

			        </div>
			      </div>
				
				<?php endforeach; endif; else: echo "" ;endif; ?>
			   <input type="hidden" name="schoolAccount" value="<?php echo $schoolAccount; ?>">
			   <input type="hidden" name="className" value="<?php echo $className; ?>">
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
		
		// 首先调用JSBridge初始化代码，完成后再设置其他
        initJsBridge(function () {
		
			
			 $(document).on('click','.js-img',function(){
			 
				var index = $(this).parents('.col-xs-4').index();
				var length = $(this).parents('.main-class-ul').find('li').length;
			//alert(index);
			    var str = '';
				//var images = new Array();
				for(var i=0;i<length;i++){
					//images[i]=$(this).parents('.main-class').find('.js-image').eq(i).val();
				str +=$(this).parents('.main-class').find('.js-image').eq(i).val()+",";
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

<script src="__STATIC__/js/date-class.js"></script> 
<script src="__STATIC__/js/yuanzhang.js"></script>
<script type="text/javascript">
$(function(){
		var jsButton = $("#monitor button")
		var ii = $("#monitor td button i")
		var yearMouth = $('#curday').html();
		var _year = parseInt(yearMouth.substring(0, 4));
		var _mouth = parseInt(yearMouth.substring(5));
		var mouth = yearMouth.substring(5,7);
		for(j = 0; j < 7; j++){
			var _day = parseInt(ii.eq(j).html());
			if (_day < 10) {
				day = '0' + _day;
			} else {
				day = _day;
			}
			for (i = 0; i < 7; i++) {
				var iiEq = parseInt(ii.eq(6).html())
				if (_day <= iiEq) {
					if (_mouth < 10) {
						var date = _year + "-0" + _mouth + "-" + day;
					} else {
						var date = _year + "-" + _mouth + "-" + day;
					}
				} else {
					var d = _mouth - 1;
					if (d != 0) {
						if (_mouth < 10) {
							var date = _year + "-0" + d + "-" + day;
						} else {
							var date = _year + "-" + d + "-" + day;
						}
					}else{
						var date = (_year - 1) + "-" + "12" + "-" + day;
					}
				}
			}
			//console.log(date)
			
		}
		
		
		
		$('.click-zan').click(function(){
                var cid = $(this).find('.cid').val();
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
                url:'<?php echo url("classroom/zan"); ?>',
                type:'post',
                data:{cid:cid,phone_account:phone_account,account:account,type:type},
                dataType:'json',
                success:function(d){
             
                    $(".zan-pass").html(d);
                }

                })
            })
	})	
</script>

</body>
</html>