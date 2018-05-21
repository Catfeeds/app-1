<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"C:\wwwroot\AMS\public/../application/index\view\part\long.html";i:1524019392;s:63:"C:\wwwroot\AMS\public/../application/index\view\tpl\footer.html";i:1523583851;s:63:"C:\wwwroot\AMS\public/../application/index\view\tpl\right1.html";i:1523956394;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="keywords" content="派职网,高考">
		<title>派职网-长期兼职</title>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/common.css"/>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<a href="<?php echo url('index/index'); ?>" ><img src="__STATIC__/img/logo1.png" class="logo"/></a>
			</div>
		</nav>
		<div class="container container-top">
			<div class="col-sm-8 col-xs-12" >
				<header class="header1">
					<a href="<?php echo url('index/index'); ?>">首页 </a><span class="glyphicon glyphicon-menu-right"></span> 长期兼职
				</header>
				<div class="m-t15">
					<img src="__STATIC__/img/cqjz.png" width="100%"/>
				</div>
				<section class="m-t15 list-job">
					<ul id="list">
						<?php if(!empty($part)): if(is_array($part) || $part instanceof \think\Collection || $part instanceof \think\Paginator): $i = 0; $__LIST__ = $part;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
								<a href="<?php echo url('index/detail'); ?>?id=<?php echo $vo2['id']; ?>">
									<li class="in">
										<span class="new-pag-green"><?php echo $vo2['wageGuarantee']; ?></span>&nbsp;<span class="title"><?php echo $vo2['positionName']; ?></span>
										<p class="new">
											<span class="title"><?php echo $vo2['area']; ?></span><span>&nbsp;|&nbsp;已报名<?php echo $vo2['enroolCount']; ?>&nbsp;|&nbsp;需求<?php echo $vo2['count']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $vo2['period']; ?></span>
										</p>
										<p class="new"><span class="new-pag-bor font-12"><?php echo $vo2['type']; ?></span><span class="new-pag-bor font-12"><?php echo $vo2['payroll']; ?></span><span class="f-r"><?php echo $vo2['release_time']; ?></span></p>
									</li>
								</a>
						    <?php endforeach; endif; else: echo "" ;endif; else: ?>	
							<span >无数据</span>
						<?php endif; ?>
					</ul>
				</section>
				<?php echo $page; ?>
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
						©2001-2018 安徽派职网 | 派职网 
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
