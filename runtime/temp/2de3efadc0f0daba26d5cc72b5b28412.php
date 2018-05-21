<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:73:"C:\inetpub\wwwroot\anhui\public/../application/index\view\index\city.html";i:1525657163;s:73:"C:\inetpub\wwwroot\anhui\public/../application/index\view\tpl\footer.html";i:1524808746;s:72:"C:\inetpub\wwwroot\anhui\public/../application/index\view\tpl\right.html";i:1525657934;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="keywords" content="派职网,高考">
		<title>派职网-城市</title>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/common.css"/>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<a href="/" ><img src="__STATIC__/img/logo1.png" class="logo"/></a>
			</div>
		</nav>
		<div class="container container-top">
			<div class="col-sm-8 col-xs-12" >
				<header class="header1">
					<a href="/">首页</a><span class="glyphicon glyphicon-menu-right"></span> 城市选择
				</header>
				<h1 class="title-h1">热门城市</h1>
				<section class="new-pag-all m-t15 o-h">
					<a href="http://hefei.paizhiw.com" target="_blank" class="new-pag-b">合肥</a>
					<a href="http://beijing.paizhiw.com" target="_blank" class="new-pag-b">北京</a>
					<a href="http://shanghai.paizhiw.com" target="_blank"  class="new-pag-b">上海</a>
					<a href="http://tianjin.paizhiw.com" target="_blank" class="new-pag-b">天津</a>
					<a href="http://shenzhen.paizhiw.com" target="_blank" class="new-pag-b">深圳</a>
				</section>
				<section class="m-t15 list-city">
					<div style="overflow: hidden;">
						<label for="search-text" style="font-size: 16px;display: block;">搜索城市:</label>
					  	<input type="text" name="city" id="search-text" placeholder="请输入职称/地理位置进行筛选" class="form-control" style="width:80%;float: left;">
					  	<button class="glyphicon glyphicon-search btn btn-default"  type="button" style="width:12%;float: left;margin-left:5%;margin-top: -2px;"></button>
					</div>
				
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					 <div class="m-t15 city">
						<span><?php echo $key; ?></span>
						<?php if(is_array($vo) || $vo instanceof \think\Collection || $vo instanceof \think\Paginator): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
						<div class="province">
						
							<div class="title"><?php echo $vo1['name']; ?></div>
							
							<div class="area">
							<?php if(is_array($vo1['children']) || $vo1['children'] instanceof \think\Collection || $vo1['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo1['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
							<a  href="<?php echo $vo2['url']; ?>" target="_blank" ><?php echo $vo2['name']; ?></a>
							<?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</div>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						
					 </div>
					 <?php endforeach; endif; else: echo "" ;endif; ?>
					
				</section>
			<footer class="m-t15 hidden-xs">
					<div class="friend-link">
						<ul>
							<li>友情链接：</li>
							<li><a href="javascript:">派职网</a>|</li>
							<li><a href="javascript:">派职网</a>|</li>
							<li><a href="javascript:">派职网</a>|</li>
							<li><a href="javascript:">派职网</a></li>
						</ul>
					</div>
					<div>
						©2018 派职网 paizhiw.com 安徽朗懿互联科技集团有限公司 版权所有 皖ICP备17023487号-4
					</div>
				</footer>
				<footer class="footer visible-xs">
					<a href="javascript:">下载派职网，随时随地派职给你</a>
</footer>
			</div>
			
			<div class="col-sm-4 hidden-xs">
				<h4 class="m-t15">热门职位</h4>
				<div class="m-t15 right-ul">
				<?php if(is_array($hotType) || $hotType instanceof \think\Collection || $hotType instanceof \think\Paginator): $k = 0; $__LIST__ = $hotType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo5): $mod = ($k % 2 );++$k;if($k < 5): ?>
					<a href="<?php echo url('index/index'); ?>?type=<?php echo $vo5['id']; ?>"><?php echo $vo5['type']; ?></a>
				<?php endif; endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<h4 class="m-t15">热门城市</h4>
				<div class="m-t15 right-ul">
					<a href="http://hefei.paizhiw.com" target="_blank" >合肥</a>
					<a href="http://beijing.paizhiw.com" target="_blank" >北京</a>
					<a href="http://shanghai.paizhiw.com" target="_blank"  >上海</a>
					<a href="http://shenzhen.paizhiw.com" target="_blank" >深圳</a>
				</div>
				<div class="m-t15 app" id="app">
					<div class="col-sm-4">
						<img src="__STATIC__/img/ma.png" alt="派职网" title="派职网" width="100%"/>
					</div>
					<div class="col-sm-8">
						<p>下载派职网APP <span class="glyphicon glyphicon-menu-right"></span></p>
						<span>随时随地派职给你</span>
					</div>
					<div class="c-b"></div>
					<div class="app-show">
						<img src="__STATIC__/img/ma.png" width="100%"/>
						<img src="__STATIC__/img/jt.png" class="app-jt"/>
					</div>
				</div>
</div>
		</div>
		<div class="actGotop"><a href="javascript:;" title="返回顶部" class="glyphicon glyphicon-menu-up"></a><br/>TOP</div>
	</body>
	<script src="__STATIC__/js/jquery-2.1.4.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="__STATIC__/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="__STATIC__/js/gotop.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		$(function(){
			$('.btn').click(function(){
				var city =  $(" input[ name='city' ] ").val();
			
				$.ajax({
                    type:'post',//选择get方式提交
                    url:'/index/index/citySearch',//将数据提交的页面
                    data:{city:city},//传值
					dataType:'json',
                    success: function(data)
                    {   //alert(data)
					    var msg = data['msg'];
					    if(data['status'] =='0'){
						   // var msg = data['msg'];
						    alert(msg)
						}else{
						    location.href="+msg+";
						
						}
                        //status = data.status;
                     

                    
                    }
                })
			})
		})
	</script>
</html>
