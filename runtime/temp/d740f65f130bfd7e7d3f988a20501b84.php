<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:86:"C:\wwwroot\AMS\public/../application/admin\view\employee_access_management\access.html";i:1517563498;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <title><?php echo \think\Config::get('site.title'); ?></title>
    <link rel="Bookmark" href="__ROOT__/favicon.ico" >
    <link rel="Shortcut Icon" href="__ROOT__/favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__LIB__/html5.js"></script>
    <script type="text/javascript" src="__LIB__/respond.min.js"></script>
    <script type="text/javascript" src="__LIB__/PIE_IE678.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui/css/H-ui.min.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui.admin/css/H-ui.admin.css"/>
    <link rel="stylesheet" type="text/css" href="__LIB__/Hui-iconfont/1.0.7/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="__LIB__/icheck/icheck.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui.admin/skin/default/skin.css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/h-ui.admin/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/app.css"/>
    <link rel="stylesheet" type="text/css" href="__LIB__/icheck/icheck.css"/>
    
    <!--[if IE 6]>
    <script type="text/javascript" src="__LIB__/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <!--定义JavaScript常量-->
<script>
    window.THINK_ROOT = '<?php echo \think\Request::instance()->root(); ?>';
    window.THINK_MODULE = '<?php echo \think\Url::build("/" . \think\Request::instance()->module(), "", false); ?>';
    window.THINK_CONTROLLER = '<?php echo \think\Url::build("___", "", false); ?>'.replace('/___', '');
</script>
</head>
<body>

<nav class="breadcrumb">
    <div id="nav-title"></div>
    <a class="btn btn-success radius r btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:;" title="刷新"><i class="Hui-iconfont"></i></a>
</nav>


<div class="page-container">
<style>
   #fileupload {
   position:absolute;
   width: 60px;
   height: 60px;
   left: 15px;
   top: 10px;
   opacity:0
   }
</style>
    <form class="form form-horizontal" id="form" method="post" action="<?php echo \think\Request::instance()->baseUrl(); ?>">
        <input type="hidden" name="id" value="<?php echo isset($vo['id']) ? $vo['id'] :  ''; ?>">
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">员工账号：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="员工账号" readonly name="account" value="<?php echo isset($vo1['account']) ? $vo1['account'] :  ''; ?>">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">绑定手机：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="绑定手机" readonly name="number" value="<?php echo isset($vo1['iphone']) ? $vo1['iphone'] :  ''; ?>">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        
        
        <div class="row cl">
        	<div class="col-xs-3 col-sm-3 text-r" style="line-height: 100px;"><?php echo isset($vo1['name']) ? $vo1['name'] :  ''; ?>：</div>
        	<div class="col-xs-6 col-sm-6">
        		<div style="width: 15px;"></div>
        		<div style="border: 1px solid #ddd;height: 100px;">
        			<div class="col-xs-3 col-sm-3" style="padding: 10px;">
        				<div class="col-xs-6 col-sm-6">
        					<div>
					            <img src="<?php echo isset($vo1['image']) ? $vo1['image'] :  ''; ?>" width="60px" height="60px" style="border-radius:10px;margin-top:10px;" onerror="this.src='__STATIC__/images/defaulthead.jpg'"/>
        					</div>
        				</div>
        				<div class="col-xs-6 col-sm-6">
        					<a class="btn btn-default" href="javascript:;" style="width:60px;height:60px;margin-top:10px;border-radius:10px;">
        						<span class="Hui-iconfont Hui-iconfont-add" style="font-size: 24px;color: #999;line-height: 50px;"></span>
        					</a>
        					<input id="fileupload" type="file" name="file[]" accept="image/gif,image/jpeg,image/png,image/jpg" multiple="true" data-url="<?php echo \think\Url::build('upload'); ?>?type=<?php echo \think\Request::instance()->param('type'); ?>&funId=<?php echo \think\Request::instance()->param('funId'); ?>&personId=<?php echo \think\Request::instance()->param('personId'); ?>&self=<?php echo \think\Request::instance()->param('self'); ?>&pid=<?php echo \think\Request::instance()->param('pid'); ?>">
        				</div>
        			</div>
        			<div class="col-xs-5 col-sm-5" style="height: 100px;line-height:100px;">
        				<label class="form-label col-xs-5 col-sm-5">车牌号：</label>
        				<div class="formControls col-xs-7 col-sm-7  text-l">
			                <input type="text" class="input-text" placeholder="请输入车牌号"  name="carCard" value="<?php echo isset($vo1['carCard']) ? $vo1['carCard'] :  ''; ?>">
			            </div>
        			</div>
        			<div class="col-xs-4 col-sm-4 skin-minimal" style="height: 100px;padding: 40px 0;padding: 40px 0;text-align: center">
        				<div class="radio-box">
		                    <input type="radio" name="access" id="acess-1" value="1" <?php if($vo1['access'] == 1): ?> checked <?php endif; ?> class="btn1">
                            <input type="hidden" class="access" value="<?php echo $vo1['id']; ?>" >
		                    <label for="acess-1">启用</label>
		                </div>
		                <div class="radio-box">
		                    <input type="radio" name="access" id="acess-0" value="0" <?php if($vo1['access'] == 0): ?> checked <?php endif; ?> class="btn0">
                            <input type="hidden" class="access" value="<?php echo $vo1['id']; ?>" >
		                    <label for="acess-0">禁用</label>
		                </div>
                        <!-- <div class="radio-box">
                            <input type="radio" name="access" id="acess-1" value="1">
                            <label for="acess-1">启用</label>
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="access" id="acess-0" value="0">
                            <label for="acess-0">禁用</label>
                        </div> -->
        			</div>
        		</div>
	           	<div style="width: 15px;"></div>
        	</div>
        	<div class="col-xs-3 col-sm-3"></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">备注：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <textarea class="textarea" placeholder="" name="remark" onKeyUp="textarealength(this,100)"><?php echo isset($vo['remark']) ? $vo['remark'] :  ''; ?></textarea>
                <p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <button type="submit" class="btn btn-primary radius">&nbsp;&nbsp;提交&nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default radius ml-20" onClick="layer_close();">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<script>
$(function () {
    $("[name='access'][value='<?php echo isset($vo['access']) ? $vo['access'] :  '0'; ?>']").prop("checked", true);
    var vo2_js = <?php echo json_encode($vo2js); ?>;
    
    var aarr = eval(vo2_js);
    
    //var ss = '<?php echo $vo['lAccess']; ?>'.split(",");
    for (i = 0; i < aarr.length; i++) {
        var str = 'lAccess_' + aarr[i]['id'];
        //alert(aarr[i]['access']);
        if (aarr[i]['access'] == '1') {
            $("[name='" + str + "'][value='1']").prop("checked", true);
        } else {
            $("[name='" + str + "'][value='0']").prop("checked", true);
        }
    }
    
    $('.skin-minimal input').iCheck({
        checkboxClass: 'icheckbox-blue',
        radioClass: 'iradio-blue',
        increaseArea: '20%'
    });

    $("#form").Validform({
        tiptype: 2,
        ajaxPost: true,
        showAllError: true,
        callback: function (ret) {
            ajax_progress(ret);
        }
    });

    $('.btn1').click(function(){
            var id = $('.access').val();
            $.ajax({
                    type: 'post',//选择get方式提交
                    url: '<?php echo url("EmployeeAccessManagement/start"); ?>',//将数据提交的页面
                    data: {id: id},//传值
                    success:function(data)
                    {
                        // if(data == 'ok'){
                        //    alert(data)
                        // }
                    }
            });
        })

        $('.btn0').click(function(){
            var id = $('.access').val();
          // alert(id)
            $.ajax({
                    type: 'post',//选择get方式提交
                    url: '<?php echo url("EmployeeAccessManagement/forbidden"); ?>',//将数据提交的页面
                    data: {id: id},//传值
                    success:function(data)
                    {
                        // if(data == 'ok'){
                        //    alert(data)
                        // }
                    }
            });
        })
    
    


    
})
</script>
</body>
</html>