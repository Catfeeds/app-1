<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\demo\PHPTutorial\WWW\test\public/../application/admin\view\pub\login.html";i:1526971081;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="__ROOT__/favicon.ico" >
    <link rel="Shortcut Icon" href="__ROOT__/favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__LIB__/html5.js"></script>
    <script type="text/javascript" src="__LIB__/respond.min.js"></script>
    <script type="text/javascript" src="__LIB__/PIE_IE678.js"></script>
    <![endif]-->
    <link href="__STATIC__/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="__STATIC__/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="__LIB__/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>派职网企业管理系统</title>
    <meta name="keywords" content="<?php echo \think\Config::get('site.keywords'); ?>">
    <meta name="description" content="<?php echo \think\Config::get('site.keywords'); ?>">
</head>
<body>
<!--<div class="header">
    <h1>校园管理系统</h1>
</div>-->
<div class="loginWraper">
    <div id="loginform" class="loginBox">
    	<div class="logo"><a href="http://127.0.0.1:8020/recruit/index.html"><img src="__STATIC__/images/logo.png"/></a></div>
    	<h1 class="login-company">企业登录</h1>
        <form class="form form-horizontal" action="<?php echo \think\Url::build('checkLogin'); ?>" method="post" id="form">
            <div class="row cl">
                <label class="form-label col-xs-3 col-ms-3" style="line-height: 36px;font-size: 20px;">帐号</label>
                <div class="formControls col-xs-6 col-ms-6">
                    <input name="account" id="account" type="text" placeholder="请输入手机号" class="input-text size-L" datatype="*" nullmsg="请填写登录帐号">
                </div>
                <div class="col-xs-3 col-ms-3"></div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-ms-3" style="line-height: 40px;font-size: 20px;">密码</label>
                <div class="formControls col-xs-6 col-ms-6">
                    <input name="password" type="password" placeholder="密码" class="input-text size-L" datatype="*" nullmsg="请填写密码">
                </div>
                <div class="col-xs-3 col-ms-3"></div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-6 col-ms-6 col-xs-offset-3 col-ms-offset-3">
                    <input name="captcha" class="input-text size-L" type="text" placeholder="验证码" style="width:100px;min-width: auto" datatype="*" nullmsg="请填写验证码">
                    <img id="captcha" src="<?php echo captcha_src(); ?>" alt="验证码" title="点击刷新验证码" style="cursor:pointer;width: 150px;height: 40px">
                </div>
                <div class="col-xs-3 col-ms-3"></div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-6 col-xs-offset-3">
                    <label for="online">
                        <input type="checkbox" name="online" id="online" value="1">
                    	使我保持登录状态
                    </label>
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-6 col-xs-offset-3">
                    <input name="" type="submit" class="btn btn-success radius size-L mr-20" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
                    <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;重&nbsp;&nbsp;&nbsp;&nbsp;置&nbsp;">
                </div>
            </div>
            <div class="row cl">
            	<div class="col-xs-9 col-ms-9">
            		<a href="<?php echo url('pub/register'); ?>" class="register-company">还没有企业账号，点击注册</a>
            	</div>
               <div class="col-xs-3 col-ms-3"></div>
            </div>
        </form>
    </div>
</div>
<!--<div class="footer">Copyright LXR by 校园管理系统</div>-->
<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<script>
    $(function () {
        $("#captcha").click(function () {
            $(this).attr("src","<?php echo captcha_src(); ?>?t="+new Date().getTime())
        });





        $("#form").Validform({
            tiptype:2,
            ajaxPost:true,
            showAllError:true,
            callback:function(ret){
                if (ret.code){
                    if (ret.msg == '验证码错误!'){
                        $("#captcha").click();
                        $("[name='captcha']").val('');
                        layer.msg(ret.msg);
                    } else if(ret.msg == '请先完善企业信息'){
                        layer.msg(ret.msg);
                        var mobile = $('#account').val();

                        window.location.href='<?php echo url("pub/evpi"); ?>?phone='+mobile+'';
                    }else{
                        layer.msg(ret.msg); //显示其它错误
                    }
                } else {
                    layer.msg("登录成功！");
                    location.href = '<?php echo \think\Request::instance()->get('callback')?: \think\Url::build("Index/index"); ?>';
                }
            }
        });
//        $("#form").Validform({
//            tiptype:2,
//            ajaxPost:true,
//            showAllError:true,
//            callback:function(ret){
//                if (ret.code){
//                    if (ret.msg == '验证码错误!'){
//                        $("#captcha").click();
//                        $("[name='captcha']").val('');
//                        layer.msg(ret.msg);
//                    } else {
//                        layer.alert(ret.msg);
//                    }
//                } else {
//                    layer.msg("登录成功！");
//                    location.href = '<?php echo \think\Request::instance()->get('callback')?: \think\Url::build("Index/index"); ?>';
//                }
//            }
//        });


    })
</script>
</body>
</html>