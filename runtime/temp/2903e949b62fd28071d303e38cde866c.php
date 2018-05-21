<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:81:"C:\inetpub\wwwroot\anhui_hefei\public/../application/index\view\index\detail.html";i:1524801586;s:79:"C:\inetpub\wwwroot\anhui_hefei\public/../application/index\view\tpl\footer.html";i:1524808746;s:79:"C:\inetpub\wwwroot\anhui_hefei\public/../application/index\view\tpl\right1.html";i:1523956394;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="keywords" content="派职网,高考">
		<title><?php echo $data['positionName']; ?></title>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/common.css"/>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<a href="index.html" ><img src="__STATIC__/img/logo1.png" class="logo"/></a>
			</div>
		</nav>
		<div class="container container-top">
			<div class="col-sm-8 col-xs-12" >
				<header class="header1">
					<a href="<?php echo url('index/index'); ?>">首页 </a><span class="glyphicon glyphicon-menu-right"></span> 职位详情
				</header>
				<h1 class="title-h1">职位详情</h1>
				<section class="job-details">
					<div>
						<h1><?php echo $data['positionName']; ?></h1>
						<div><span><?php echo $data['salary']; ?><?php echo $data['unit']; ?></div>
						<p>
							<span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $data['area']; ?>&nbsp;&nbsp;
							<span class="glyphicon glyphicon-time"></span>&nbsp;<?php echo $data['release_time']; ?>&nbsp;&nbsp;
							<span class="glyphicon glyphicon-eye-open"></span>&nbsp;<?php echo $data['click']; ?>&nbsp;&nbsp;
							<a href="#" class="f-r 	enroll">立即报名</a>
						</p>
					</div>
					<ul>
						<li>兼职类型：<span><?php echo $data['property']; ?></span></li>
						<li>招聘人数：<span><?php echo $data['count']; ?>人</span></li>
						<li>性别要求：<span><?php echo $data['sex']; ?></span></li>
					</ul>
					<ul>
						<li>结算方式：<span><?php echo $data['payroll']; ?></span></li>
						<li>基本工资：<span><?php echo $data['salary']; ?><?php echo $data['unit']; ?></span></li>
					</ul>
					<div>
						<h2>工作内容</h2>
						<p class="p">
							<?php echo $data['content']; ?>
						</p>
					</div>
					<ul>
						<li>工作种类：<span><?php echo $data['type']; ?></span></li>
						<li>工作时间：<span><?php echo $data['start_time']; ?>-<?php echo $data['end_time']; ?></span></li>
						<li>上班时段：<span><?php echo $data['gather_place']; ?></span></li>
					</ul>
					<ul>
						
						<li>详细地址：<span><?php echo $data['work_place']; ?></span></li>
						<li>联系人：<span><?php echo $data['contacts']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data['phone']; ?></span></li>
					</ul>
					<div>已报名<span class="f-r span"><?php echo $data['enroolCount']; ?>人</span></div>
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
			$('.js-select li a').click(function(){
				$(this).addClass('new-pag').siblings('a').removeClass('new-pag');
			})
		})
	</script>
</html>
