<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:74:"C:\inetpub\wwwroot\anhui\public/../application/index\view\index\index.html";i:1525399159;s:73:"C:\inetpub\wwwroot\anhui\public/../application/index\view\tpl\footer.html";i:1524808746;s:72:"C:\inetpub\wwwroot\anhui\public/../application/index\view\tpl\right.html";i:1525657934;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">	
		<title><?php echo $title; ?>招聘找工作兼职，<?php echo $title; ?>大学生兼职，<?php echo $title; ?>校园兼职，<?php echo $title; ?>招聘 - 派职网</title>
		<meta name="viewport"
			content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="keywords"  content="<?php echo $title; ?>招聘网，<?php echo $title; ?>兼职,<?php echo $title; ?>大学兼职，<?php echo $title; ?>大学生兼职，<?php echo $title; ?>校园兼职，<?php echo $title; ?>招聘网，<?php echo $title; ?>工作派职网">
		<meta name="description"
			content="<?php echo $title; ?>找工作兼职全职随时随地派职给你，为求职者和企业建立双向保障双向实名认证，双向信用体系。" />
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="__STATIC__/css/common.css"/>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<a href="<?php echo url('index/index'); ?>" ><img src="__STATIC__/img/logo1.png" class="logo"/></a>
				<a href="<?php echo url('index/city'); ?>" class="city"><?php echo $title; ?><span class="glyphicon glyphicon-triangle-bottom"></span></a>
				<a href="/admin" class="f-r companyrecruit visible-md visible-lg" target="_blank">企业招聘管理</a>
			</div>
		</nav>
		<div class="container container-top">
			<h1 class="visible-xs title-h1">职位管理</h1>
			<div class="col-sm-8 col-xs-12" >
				<header class="m-t15 hidden-xs banner">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
						<?php if(is_array($banner) || $banner instanceof \think\Collection || $banner instanceof \think\Paginator): $k = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo4): $mod = ($k % 2 );++$k;?>
							<li data-target="#carousel-example-generic" data-slide-to="<?php echo $k-1; ?>"  <?php if($k == 1): ?> class="active" <?php endif; ?>></li>
						
						<?php endforeach; endif; else: echo "" ;endif; ?>
						</ol>
						<div class="carousel-inner" role="listbox" >
						
						<?php if(is_array($banner) || $banner instanceof \think\Collection || $banner instanceof \think\Paginator): $k = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo4): $mod = ($k % 2 );++$k;?>
							<div class="item <?php if($k == 1): ?> active<?php endif; ?>" >
								<img src="<?php echo $vo4['banner_img']; ?>" alt="派职网" width="100%">
							</div>
							
						<?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
				</header>
				<section class="m-t15">
					<ul class="nav-son o-h">
						<li>
							<a href="<?php echo url('part/daily'); ?>">
								<img src="__STATIC__/img/item1.png" width="100%"/>
								<p>日结兼职</p>
							</a>
						</li>
						<li>
							<a href="<?php echo url('part/long'); ?>">
								<img src="__STATIC__/img/item2.png" width="100%"/>
								<p>长期兼职</p>
							</a>
						</li>
						<li>
							<a href="<?php echo url('part/internship'); ?>">
								<img src="__STATIC__/img/item3.png" width="100%"/>
								<p>实习兼职</p>
							</a>
						</li>
						<li>
							<a href="<?php echo url('part/travel'); ?>">
								<img src="__STATIC__/img/item4.png" width="100%"/>
								<p>旅行兼职</p>
							</a>
						</li>
					</ul>
				</section>
				<section class="key-select hidden-xs">
					<h3>最新职位:</h3>
					<ul class="js-select">
						<li>
							<span>类型：</span>
							<div>
								<a href="<?php echo url('index/index'); ?>?property=0&type=<?php echo $type; ?>&area=<?php echo $area; ?>" <?php if($property == 0): ?>class="new-pag" <?php endif; ?>>不限</a>
								<a href="<?php echo url('index/index'); ?>?property=1&type=<?php echo $type; ?>&area=<?php echo $area; ?>" <?php if($property == 1): ?>class="new-pag" <?php endif; ?>>日结兼职</a>
								<a href="<?php echo url('index/index'); ?>?property=2&type=<?php echo $type; ?>&area=<?php echo $area; ?>" <?php if($property == 2): ?>class="new-pag" <?php endif; ?>>长期兼职</a>
								<a href="<?php echo url('index/index'); ?>?property=3&type=<?php echo $type; ?>&area=<?php echo $area; ?>" <?php if($property == 3): ?>class="new-pag" <?php endif; ?>>实习兼职</a>
								<a href="<?php echo url('index/index'); ?>?property=4&type=<?php echo $type; ?>&area=<?php echo $area; ?>" <?php if($property == 4): ?>class="new-pag" <?php endif; ?>>旅行兼职</a>
							</div>
						</li>
						<li>
							<span>种类：</span>
							<div>
								<a href="<?php echo url('index/index'); ?>?property=<?php echo $property; ?>&type=0&area=<?php echo $area; ?>" <?php if(empty($type)){?>class="new-pag" <?php } ?> >不限</a>
								<?php if(is_array($type1) || $type1 instanceof \think\Collection || $type1 instanceof \think\Paginator): $i = 0; $__LIST__ = $type1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
								<a href="<?php echo url('index/index'); ?>?property=<?php echo $property; ?>&type=<?php echo $vo['id']; ?>&area=<?php echo $area; ?>" <?php if($vo['id'] == $type): ?>class="new-pag" <?php endif; ?>><?php echo $vo['name']; ?></a>
								
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</li>
						<li>
							<span>区域：</span>
							<div>
								<a href="<?php echo url('index/index'); ?>?property=<?php echo $property; ?>&type=<?php echo $type; ?>&area=0" <?php if(empty($area)){?>class="new-pag" <?php } ?>>不限</a>
								<?php if(is_array($area1) || $area1 instanceof \think\Collection || $area1 instanceof \think\Paginator): $i = 0; $__LIST__ = $area1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
								<a href="<?php echo url('index/index'); ?>?property=<?php echo $property; ?>&type=<?php echo $type; ?>&area=<?php echo $vo1['id']; ?>" <?php if($vo1['id'] == $area): ?>class="new-pag" <?php endif; ?>><?php echo $vo1['name']; ?></a>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</li>
					</ul>
				</section>
				<section class="m-t15 list-job">
					<div style="overflow: hidden;">
						<label for="search-text" style="font-size: 16px;display: block;">搜索职位:</label>
					  	<input type="text" id="search-text" placeholder="请输入职称/地理位置进行筛选" class="form-control" style="width:80%;float: left;" name="name">
					  	<button class="glyphicon glyphicon-search btn btn-default"  type="button" style="width:12%;float: left;margin-left:5%;margin-top: -2px;" id="ck"></button>
					</div>
				  	<span class="list-count">共<?php echo $count; ?>条</span>
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
				<!-- <?php echo $page; ?> -->
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
	$("#ck").click(function(){
		var name = $(" input[ name='name' ] ").val();
		
		location.href="<?php echo url('index/index'); ?>?name="+name+"&m=1";
			
	})
	</script>
</html>
