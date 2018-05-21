<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"C:\wwwroot\AMS\public/../application/admin\view\album\fsedit.html";i:1521093644;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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


<style type="text/css">
    #picture {
        position:absolute;
        width: 60px;
        height: 60px;
        left: 25px;
        top: 20px;
        opacity:0;
        overflow: hidden;
    }
</style>
<div class="page-container" style="margin-top: 50px">
    <form class="form form-horizontal" id="form" method="post" action="<?php echo \think\Request::instance()->baseUrl(); ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($vo['id']) ? $vo['id'] :  ''; ?>">
        
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标题：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="标题" name="title" value="<?php echo isset($vo['title']) ? $vo['title'] :  ''; ?>"  datatype="*" nullmsg="请填写标题">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
		<div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>发布时间：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="发布时间" name="release_time" value="<?php echo isset($vo['release_time']) ? $vo['release_time'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"   datatype="*" nullmsg="请填写发布时间">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

         <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>学校名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="schoolName" class="select"  datatype="*" nullmsg="请选择学校名称" id="schoolName">
                        <option value ="<?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  ''; ?>" selected><?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  '请选择学校名称'; ?></option>
                        <?php if(is_array($schoolName) || $schoolName instanceof \think\Collection || $schoolName instanceof \think\Paginator): $i = 0; $__LIST__ = $schoolName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                            <option value ="<?php echo $vos['schoolName']; ?>" ><?php echo $vos['schoolName']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>班级名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="className" class="select"  datatype="*" nullmsg="请选择班级名称" id="className">
                        <option value ="<?php echo isset($vo['className']) ? $vo['className'] :  ''; ?>" selected><?php echo isset($vo['className']) ? $vo['className'] :  '请选择班级名称'; ?></option>
                        <?php if(is_array($className2) || $className2 instanceof \think\Collection || $className2 instanceof \think\Paginator): $i = 0; $__LIST__ = $className2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
                            <option value ="<?php echo $vo1['class']; ?><?php echo $vo1['className']; ?>" ><?php echo $vo1['class']; ?><?php echo $vo1['className']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3" style="position: relative;top: 25px">上传图片：</label>
            <div class="formControls col-xs-6 col-sm-6">

                <!--<button type="button" class="btn btn-primary radius" onclick="layer_open('文件上传','<?php echo \think\Url::build('Album/upindex'); ?>')">上传</button>-->


                <div style="width: 15px;"></div>
                <div  style="border: 1px solid #ddd;overflow: hidden;padding: 10px;">
                    <div>
                        <a class="btn btn-default" href="javascript:;" style="width:60px;height:60px;margin-top:10px;border-radius:10px;">
                            <span class="Hui-iconfont Hui-iconfont-add" style="font-size: 24px;color: #999;line-height:50px;"></span>
                        </a>
                        <input id="picture" type="file" name="picture[]" accept="image/gif,image/jpeg,image/png,image/jpg" multiple="true" >
						<span id="show">

                            <?php

                                if(!empty($vo['images']))
                                {

                                        $arrImg = json_decode($vo['images']);
                                        foreach($arrImg as $value)
                                        {
                            ?>
                                            <img src="__ROOT__<?php echo $value; ?>" height="60" style="margin-top: 11px">

                            <?php

                                         }

                                }
                            ?>


                        </span>
                    </div>
                </div>
                <div style="width: 15px;"></div>
                <!--用来存放所有上传原图片的路径-->
                <input type="hidden" name="path" id="path">
                <!--用来存放所有上传原图片的路径-->

                <!--用来存放所有上传图片的缩略图路径-->
                <input type="hidden" name="thumbpath" id="thumbpath">
                <!--用来存放所有上传图片的缩略图路径-->





            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
<!-- 
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>封面照片：</label>
            <div class="formControls col-xs-6 col-sm-6" >
               
                     <input type="file" class="input-text" placeholder="" name="image" value="" >
                     <?php if(!empty($vo['image'])) {?>
                       <img src="__STATIC__/../../uploads/file/<?php echo $vo['image']; ?>" width="80" height="50"/>
                     <?php } ?>
                   </if>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

-->
        <!--<div class="row cl">-->
            <!--<label class="form-label col-xs-3 col-sm-3">内容：</label>-->
            <!--<div class="formControls col-xs-6 col-sm-6">-->
				<!--&lt;!&ndash; 加载编辑器的容器 &ndash;&gt;-->
				<!--<script id="container" name="content" type="text/plain" style="height:400px"></script>-->

            <!--</div>-->
            <!--&lt;!&ndash;<div class="col-xs-3 col-sm-3"></div>&ndash;&gt;-->
        <!--</div>-->

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">标签：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="标签" name="label" value="<?php echo isset($vo['label']) ? $vo['label'] :  ''; ?>" >
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

<script type="text/javascript" src="__LIB__/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<!--<script type="text/javascript" src="__LIB__/showdown/1.4.2/showdown.min.js"></script>-->
<!--<script>window.UEDITOR_HOME_URL = '__LIB__/ueditor/1.4.3/'</script>-->
<!--<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.config.js"></script>-->
<!--<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.all.min.js"> </script>-->
<!--<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>-->


<script>
    $(function () {


        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
		$("#form").Validform({
            tiptype: 2,
            ajaxPost: true,
            showAllError: true,
            callback: function (ret){
                ajax_progress(ret);
            }
        });


        //当选择图片后，利用ajax实现多上传
        $('#picture').change(function(){
            file =  $('#picture')[0].files;
            var formData = new FormData();

            //多图片上传---一张也可
            for(var i=0; i<file.length;i++){
                formData.append('myfile[]', file[i]);
            }

            //formData.append("myfile",file[0]); //上传一张图片

            $.ajax({
                type:'post',//选择post方式提交
                url:'<?php echo url("Album/upload"); ?>',//将数据提交的页面
                contentType: false,
                processData: false,
                data:formData, //传值
                success: function(data)
                {

                    //alert(data);return false; //传回来的data表示图片路径，以逗号分隔
                    var arr = data.split('|');
                    $('#show').html(''); //清除所有内容
                    arr2 = arr[0].split(',');
                    for(x in arr2){
                        //开始显示上传的图片
                        $('#show').append('<img src=__ROOT__"'+arr2[x]+'" height="60px" style="border-radius:2px;margin-top:10px;margin-right:5px">');
                    }

                    $('#path').val(arr[0]); //保存所有上传图片的路径

                    $('#thumbpath').val(arr[1]); //保存所有上传图片缩略图的路径


                }
            })
        });



    });




</script>

</body>
</html>