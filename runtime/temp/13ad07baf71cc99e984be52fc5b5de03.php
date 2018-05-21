<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"C:\wwwroot\AMS\public/../application/index\view\userinfo\index.html";i:1514339188;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>资料填写</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <link href="__STATIC__/css/data.css" rel="stylesheet" type="text/css" />
    <script src="__STATIC__/js/jquery.js"></script>
    <script src="__STATIC__/js/rem.js"></script>
	<script type="text/javascript" src="__STATIC__/js/date-roll.js" ></script>
	<script type="text/javascript" src="__STATIC__/js/iscroll.js" ></script>
</head>

<body style="overflow: hidden;">
    <div id="wrap">
    	<header class="header header1">
            <a href="javascript:;" onclick="history.go(-1)" class="back back1"></a>
            <p style="margin-left:28%;" id="Myzl" class="headerPclick">我的资料</p>
            <p style="margin-left:4%;" id="Babyzl">宝宝资料</p>
            <div class="clear"></div>
        </header>
        <div >
        	<!--左右切换-->
	        <div class="data-all">
	        	<div class="data-left">
	        		 <div class="data-header">
			        	<div class="data-header-img">
			        		<img src="__STATIC__/img/dada.jpg"/>
			        	</div>
			        </div>
	        		<div class="cu_info">
				        <div class="nc_edit clearfix">
				            <span class="fl">登陆账号:</span>
				            <input type="text" name="" value="<?php echo isset($data1['account']) ? $data1['account'] :  ''; ?>" class="fl" readonly>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix  duoselect">
				            <span class="fl">家长姓名:</span>
				            <input type="text" name="" value="<?php echo isset($data1['name']) ? $data1['name'] :  ''; ?>" class="fl" readonly>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix  duoselect">
				            <span class="fl">地址:</span>
				            <input type="text" name="" value="<?php echo isset($data1['className']) ? $data1['className'] :  ''; ?>" class="fl" readonly>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix">
				            <span class="fl" style="margin-right: 0.1rem;line-height: 0.3rem;">与宝宝关系:</span>
				            <select name="" id="select-relation" class="data-select-relation">
				            	<option value="请选择">请选择</option>
				            	<option value="爸爸">爸爸</option>
				            	<option value="妈妈">妈妈</option>
				            	<option value="爷爷">爷爷</option>
				            	<option value="奶奶">奶奶</option>
				            	<option value="其他">其他</option>
				            </select>
				            <!--<input type="text" name="" value="<?php echo isset($data2['relation']) ? $data2['relation'] :  ''; ?>" class="fl" readonly>-->
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix duoselect">
				            <span class="fl ">联系电话:</span>
				            <input type="text" name="" value="<?php echo isset($data2['number']) ? $data2['number'] :  ''; ?>" class="fl" readonly>
				        </div>
				        <div class="line_bgw" ></div>
			        </div>
	        	</div>
	        	<div class="data-right">
	        		 <div class="data-header">
			        	<div class="data-header-img">
			        		<img src="__STATIC__/img/dada.jpg"/>
			        	</div>
			        </div>
	        		<div class="cu_info">
				         <div class="nc_edit clearfix">
				            <span class="fl">宝宝姓名:</span>
				            <input type="text" name="" value="<?php echo isset($data1['name']) ? $data1['name'] :  ''; ?>" class="fl" readonly>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix">
				            <span class="fl">宝宝班级:</span>
				            <input type="text" name="" value="<?php echo isset($data1['name']) ? $data1['name'] :  ''; ?>" class="fl" readonly>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix">
				            <span class="fl">宝宝性别:</span>
				            <div class="fl data-sex">
				            	<button id="buttonboy" class="data-select"></button>
					            <label>男孩</label>
					            <button id="buttongirl"></button>
					            <label>女孩</label>
				            </div>
				            <!--<input type="text" name="" value="<?php echo isset($data1['className']) ? $data1['className'] :  ''; ?>" class="fl" readonly>-->
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix">
				            <span class="fl" style="margin-right: 0.1rem;line-height: 0.3rem;">出生日期:</span>
				            <input  id="beginTime" class="kbtn" value="2017-01-1" style="border:1px solid #ccc;padding:0.05rem 0.1rem;width: 40%;background: url(__STATIC__/img/data.png) no-repeat 95% center;background-size:18%;"/>
							<div id="datePlugin"></div>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix  duoselect">
				            <span class="fl ">宝宝身高:</span>
				            <input type="text" name="" value="<?php echo isset($data2['relation']) ? $data2['relation'] :  ''; ?>" class="fl" readonly>
				        </div>
				         <div class="line_bgw"></div>
				         <div class="nc_edit clearfix  duoselect">
				            <span class="fl">宝宝体重:</span>
				            <input type="text" name="" value="<?php echo isset($data2['name']) ? $data2['name'] :  ''; ?>" class="fl" readonly>
				        </div>
				        <div class="line_bgw"></div>
			        </div>
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
		 <a href="<?php echo \think\Url::build('News/index'); ?>">
           <i class="fexed-new"></i>
           <span style="color: #aeaeac; font-size: 10px;">消息</span>
         </a>
          <a href="<?php echo \think\Url::build('Entrance/index'); ?>" class="fr"> 
            <i class="fexed-room"></i>
            <span style="color: #aeaeac;font-size: 10px;">门禁</span>
          </a>
          <a href="<?php echo \think\Url::build('Personal/index'); ?>" class="active fr"> 
            <i class="fexed-our"></i>
            <span style="color: #aeaeac;font-size: 10px;">我的</span>
          </a>
        </div>
    </div>
</body>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#Myzl').click(function(){
				$(this).addClass('headerPclick')
				$('#Babyzl').removeClass('headerPclick')
				$('.data-left').show()
				$('.data-right').hide()
			})
			$('#Babyzl').click(function(){
				$(this).addClass('headerPclick')
				$('#Myzl').removeClass('headerPclick')
				$('.data-left').hide()
				$('.data-right').show()
			})
			/*男女孩切换*/
			$('#buttonboy').click(function(){
				$(this).addClass('data-select')
				$('#buttongirl').removeClass('data-select')
			})
			$('#buttongirl').click(function(){
				$(this).addClass('data-select')
				$('#buttonboy').removeClass('data-select')
			})
			//上下箭头切换
			/*var selectRelation =1;
			$('#select-relation').click(function(){
				if(selectRelation==1){
					$(this).css('background-image','url(__STATIC__/img/set-up.png)');
					selectRelation =0;
				}else{
					$(this).css('background-image','url(__STATIC__/img/set-down.png)');
					selectRelation =1;
				}
			})*/
			/*日历*/
			$('#beginTime').date();
		})
	</script>
</html>