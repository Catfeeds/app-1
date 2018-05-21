<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\school_management\fsedit.html";i:1526625093;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
    <form class="form form-horizontal" id="form" method="post" action="<?php echo \think\Request::instance()->baseUrl(); ?>">
        <input type="hidden" name="id" value="<?php echo isset($vo['id']) ? $vo['id'] :  ''; ?>">
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>注册电话：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" readonly="readonly" name="number" value="<?php echo $vo['number']; ?>">
            </div>
            <div class="col-xs-4 col-sm-4"></div>
            <div class="col-xs-3 col-sm-3"><span class="label label-warning radius">不可修改</span></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>注册号或统一信用码：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" readonly="readonly" name="regNumber" value="<?php echo $vo['regNumber']; ?>">
            </div>
            <div class="col-xs-4 col-sm-4"></div>
            <div class="col-xs-3 col-sm-3"><span class="label label-warning radius">不可修改</span></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>公司名称：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" readonly="readonly" name="schoolName" value="<?php echo $vo['schoolName']; ?>">
            </div>
            <div class="col-xs-4 col-sm-4"></div>
            <div class="col-xs-3 col-sm-3"><span class="label label-warning radius">不可修改</span></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>法人：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" readonly="readonly" name="corporation" value="<?php echo $vo['corporation']; ?>">
            </div>
            <div class="col-xs-4 col-sm-4"></div>
            <div class="col-xs-3 col-sm-3"><span class="label label-warning radius">不可修改</span></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4">营业执照：</label>
            <div class="formControls col-xs-4 col-sm-4">
            	<div class="col-xs-2 col-sm-2" style="padding:10px 0;height:135px">
            		<img src="__STATIC__/images/defaulthead.jpg" width="100%" height="100%"/>
            	</div>
				<div class="col-xs-10 col-sm-10" style="padding:10px 0 10px 20px;text-align: justify;">
					<p>上传:营业执照图片<a href="<?php echo url('SchoolManagement/guarantee'); ?>" class="btn btn-primary radius f-r">工资担保</a></p>
					<p class="c-999">照片信息需清晰可见，内容真实有效，不得做任何修改。照片支持.jpg.jepg.bmp.gif.png格式，大小不超过8M。</p>
					<input type="file" size="30" class="picture" name="picture[]" data="1"/>
				</div>
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>所在区域：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <div class="select-box" style="padding: 0;border: none">
                    <select class="select" id="s_province" name="province" datatype="*" nullmsg="请选择省" style="padding:4px;width:48%;float: left;border:1px solid #ddd;margin-right:4%"></select>
                    <select class="select" id="s_city" name="city" datatype="*" nullmsg="请选择市" style="padding:4px;width:48%;float: left;border:1px solid #ddd"></select>
                </div>
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>

		<div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>公司详细地址：</label>
            <div class="formControls col-xs-4 col-sm-4">
            	<textarea class="textarea" name="address" datatype="*" nullmsg="请输入公司详细地址"><?php echo $vo['address']; ?></textarea>
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4">认证授权书：</label>
            <div class="formControls col-xs-4 col-sm-4">
				<div class="col-xs-2 col-sm-2" style="padding:10px 0;height:135px">
            		<img src="__STATIC__/images/defaulthead.jpg" width="100%" height="100%"/>
            	</div>
				<div class="col-xs-10 col-sm-10" style="padding:10px 0 10px 20px;text-align: justify;">
					<p>请下载《认证授权书》<a href="#" class="btn btn-primary radius f-r">点击下载附件</a></p>
					<p><span class="c-333">按要求填写，并加盖企业公章(公司法人备案的芯片章)。<br /><span class="c-red">授权人和实名认证人需为同一人!</span></span><br /><span  class="c-999">照片信息需清晰可见，内容真实有效，不得做任何修改。照片支持.jpg.jepg.bmp.gif.png格式，大小不超过8M。</span></p>
					<input type="file" size="30" class="picture" data="2"/>
				</div>
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>

		<div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>联系电话：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" placeholder="请输入招聘联系电话" name="tel" value="<?php echo $vo['tel']; ?>" datatype="/(13|14|15|18|17)[0-9]{9}/" nullmsg="请输入联系电话">
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4">主营业务：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" placeholder="请输入公司主营业务" name="business" value="<?php echo $vo['business']; ?>">
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>
        
        
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4">公司简介：</label>
            <div class="formControls col-xs-4 col-sm-4">
            	<textarea class="textarea" name="introduce"><?php echo $vo['introduce']; ?></textarea>
            </div>
            <div class="col-xs-4 col-sm-4"></div>
        </div>
        
        <div class="row cl">
            <div class="col-xs-8 col-sm-8 col-xs-offset-4 col-sm-offset-4">
                <button type="submit" class="btn btn-primary radius">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>
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

<script type="text/javascript" src="__LIB__/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<script type="text/javascript" src="__LIB__/showdown/1.4.2/showdown.min.js"></script>
<script>window.UEDITOR_HOME_URL = '__LIB__/ueditor/1.4.3/'</script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="__STATIC__/js/areaevpi.js"></script>
<script>
    $(function(){
        $("#form").Validform({
            tiptype: 2,
            ajaxPost: true,
            showAllError: true,
            callback: function (ret){
                ajax_progress(ret);
            }
        });

           //当选择图片后，利用ajax实现多上传
        $('.picture').change(function(){
            file =  $(this)[0].files;
            num =  $(this).attr('data');
            id = $("input[name='id']").val();
            var formData = new FormData();

           //alert(file.length)
            <!-- //多图片上传---一张也可 -->
            for(var i=0; i<file.length;i++){
                formData.append('file', file[i]);
            }

        
            formData.append('num', num);
            formData.append('id', id);
            $.ajax({
                type:'post',//选择post方式提交
                url:'<?php echo url("SchoolManagement/upload"); ?>',//将数据提交的页面
                contentType: false,
                processData: false,
                data:formData, //传值
                //dataType:'json',
                success: function(d)
                {

                alert(d);
//

                }
            })
        });
    });
	
	var s=["s_province","s_city"]; //两个select的id
    var opt0 = ["<?php echo $vo['one_level']; ?>","<?php echo $vo['two_level']; ?>"]; //初始值

    function _init_area(){ //初始化函数
        for(i=0;i<s.length-1;i++){
            document.getElementById(s[i]).onchange=new   Function("change("+(i+1)+")");
        }
        change(0);
    }
    _init_area();
</script>

</body>
</html>