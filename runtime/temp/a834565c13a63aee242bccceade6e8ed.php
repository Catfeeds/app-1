<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\wwwroot\AMS\public/../application/index\view\shuttle\index.html";i:1519787901;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>接送管理</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
	<link rel="stylesheet" href="__STATIC__/css/popup.css">
    <script type="text/javascript" src="__STATIC__/js/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script>
	<script type="text/javascript" src="__STATIC__/js/popup.js"></script>

	<script type="text/javascript">
		//点击禁用
		//id是联系人的id
		function forbidden(id)
		{
			var liEq = $(this).parent().index();
			var json = {
				title:"温馨提示",
				msg:"确定要禁用此联系人吗?",
				buttons:[
					{ title:"是",color:"red",click:function(){

						$.ajax({
							type: 'get',//选择get方式提交
							url: '<?php echo url("shuttle/forbidden"); ?>',//将数据提交的页面
							data: {id: id},//传值
							success:function(data)
							{
								if(data == 'ok'){
									$('#jin'+id).siblings('.shuttle-btg').removeClass('shuttle-bt-bg-g');
									$('#jin'+id).addClass('shuttle-bt-bg-r');
								}
							}
						});
					} },
					{ title:"否",click:function(){} }
				]
			};
			$.alertView(json);

		}


		//点击启用
		//id是联系人的id
		function start(id)
		{
			var liEq = $(this).parent().index();
			var json = {
				title:"温馨提示",
				msg:"确定要启用此联系人吗?",
				buttons:[
					{ title:"是",color:"red",click:function(){
						$.ajax({
							type:'get',//选择get方式提交
							url:'<?php echo url("shuttle/start"); ?>',//将数据提交的页面
							data:{id:id},//传值
							success: function(data)
							{
								if(data == 'ok'){
									$('#qi'+id).siblings('.shuttle-btr').removeClass('shuttle-bt-bg-r');
									$('#qi'+id).addClass('shuttle-bt-bg-g');
								}
							}

						});
					} },
					{ title:"否",click:function(){} }
				]
			};
			$.alertView(json);
		}



		//删除记录
		function del(id) {
			var liEq = $(this).parent().index()
			var json = {
				title: "温馨提示",
				msg: "确定要删除此联系人吗?",
				buttons: [
					{
						title:"删除",color:"red",click:function(){
						  $.ajax({
							 type:'get',//选择get方式提交
							 url:'<?php echo url("shuttle/delete"); ?>',//将数据提交的页面
							 data:{id:id},//传值
							 success: function(data)
							 {
								if(data == 'ok'){
									$("#del"+id).eq(liEq).parent().remove();
								}
							 }

						  });

					    }
					},
					{
						title: "取消", click: function () {
					}
					}
				]
			};

			$.alertView(json);
		}
		
		function Back(){
			android.toFinish();
			
		}
		

	</script>
</head>
<body>
    <div id="wrap">
        <header class="header  header-fixed">
            <a href="ios://gotoback" onclick="Back()" class="back"></a>接送管理
		</header>
        <div class="bb_w_l_jy jy shuttle-content" >
			<a href="<?php echo \think\Url::build('Shuttle/index2'); ?>?student_id=<?php echo $student_id; ?>&phone_account=<?php echo $phone_account; ?>&account=<?php echo $account; ?>"><h1>添加接送人<span class="fr">+</span></h1></a>
			<ul>
				<?php if(is_array($linkman_info) || $linkman_info instanceof \think\Collection || $linkman_info instanceof \think\Paginator): $i = 0; $__LIST__ = $linkman_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<li>
						<span class="fl"><?php echo $vo['relation']; ?></span>
						<div><img src="<?php echo $linkman_img[$key]; ?>" /></div>

						<button id="del<?php echo $vo['id']; ?>" class="fr shuttle-bt shuttle-del" onclick="del('<?php echo $vo['id']; ?>')">删除</button>

						<button  id="jin<?php echo $vo['id']; ?>" class="fr shuttle-bt shuttle-btr" onclick="forbidden('<?php echo $vo['id']; ?>')">
							 禁用
							<?php if($linkman_access[$key] == 0): ?>
							    <script type="text/javascript">
									$('#jin<?php echo $vo['id']; ?>').siblings('.shuttle-btg').removeClass('shuttle-bt-bg-g');
									$("#jin<?php echo $vo['id']; ?>").addClass('shuttle-bt-bg-r');
								</script>
							<?php endif; ?>
						</button>

						<button  id="qi<?php echo $vo['id']; ?>" class="fr shuttle-bt  shuttle-btg" onclick="start('<?php echo $vo['id']; ?>')">
							  启用
							<?php if($linkman_access[$key] == 1): ?>
								<script type="text/javascript">
									$('#qi<?php echo $vo['id']; ?>').siblings('.shuttle-btr').removeClass('shuttle-bt-bg-r');
									$('#qi<?php echo $vo['id']; ?>').addClass('shuttle-bt-bg-g');
								</script>
							<?php endif; ?>
						</button>
					</li>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
        </div>
    </div>


</body>
</html>