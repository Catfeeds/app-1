<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:82:"C:\wwwroot\AMS\public/../application/admin\view\training_coursechapter\fsedit.html";i:1521859239;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
    #videos{
        position:absolute;
        width: 60px;
        height: 60px;
        left: 15px;
        top: 10px;
        opacity:0;
        overflow: hidden;
    }
</style>
<div class="page-container" style="margin-top: 50px">
    <form class="form form-horizontal" id="form" method="post" action="<?php echo \think\Request::instance()->baseUrl(); ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($vo['id']) ? $vo['id'] :  ''; ?>">


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>学校名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="schoolName" class="select"  datatype="*" nullmsg="请选择学校名称" id="schoolName">
                        <option value ="<?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  ''; ?>" selected><?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  '请选择学校名称'; ?></option>
                        <?php if(is_array($schoolName) || $schoolName instanceof \think\Collection || $schoolName instanceof \think\Paginator): $i = 0; $__LIST__ = $schoolName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                        <option value ="<?php echo $vos['schoolName']; ?>"><?php echo $vos['schoolName']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属课程：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="courseid" class="select"  datatype="*" nullmsg="请选择课程名称">
                        <option value ="<?php echo isset($vo['courseid']) ? $vo['courseid'] :  ''; ?>" selected><?php echo isset($course_name) ? $course_name :  '请选择课程名称'; ?></option>

                        <?php if(is_array($allcourse) || $allcourse instanceof \think\Collection || $allcourse instanceof \think\Paginator): $i = 0; $__LIST__ = $allcourse;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vm): $mod = ($i % 2 );++$i;?>
                        <option value ="<?php echo $vm['id']; ?>"><?php echo $vm['courseName']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>章节标题：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="标题" name="title" value="<?php echo isset($vo['title']) ? $vo['title'] :  ''; ?>"  datatype="*" nullmsg="请填写标题">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3" style="position: relative;top: 25px">上传视/音频：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <!--<input type="text" class="input-text" id="upload" placeholder="请点击后面的上传按钮" datatype="*" nullmsg="请填写图片url" style="width: 70%">-->


                <div style="width: 15px;"></div>
                <div  style="border: 1px solid #ddd;overflow: hidden;padding: 10px;">
                    <div>
                        <a class="btn btn-default" href="javascript:;" style="width:60px;height:60px;margin-top:10px;border-radius:10px;">
                            <span class="Hui-iconfont Hui-iconfont-add" style="font-size: 24px;color: #999;line-height:50px;"></span>
                        </a>
                        <input type="file" id="videos" name="videos" accept="video/mp4,audio/mp3" >
						<span id="show">

                             <?php
                                  if(!empty($vo['videos']))
                                  {

                                       $suff = explode('.',$vo['videos']);
                                       if($suff[1] == 'mp3')
                                       {
                                            echo '缩略图省略';
                                       }
                                       if($suff[1] == 'mp4')
                                       {
                             ?>
                                           <video src="__ROOT__<?php echo $vo['videos']; ?>"  height="60" style="position:absolute;top:10px;height:60px;border-radius:2px;margin:10px 5px 0 10px;"></video>


                             <?php
                                        }
                                  }
                             ?>


                        </span>
                    </div>
                </div>
                <div style="width: 15px;"></div>
                <!--用来存放当前上传视频的路径-->
                <input type="hidden" name="path" id="path">
                <!--用来存放当前上传视频的路径-->


            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">章节介绍：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <!-- 加载编辑器的容器 -->
                <script id="container" name="content" type="text/plain" style="height:400px"></script>

            </div>
            <!--<div class="col-xs-3 col-sm-3"></div>-->
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>上传时间：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="上传时间" name="release_time" value="<?php echo isset($vo['release_time']) ? $vo['release_time'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"   datatype="*" nullmsg="请填写上传时间">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标签：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="label" class="select"  datatype="*" nullmsg="请选择课程标签">
                        <option value="<?php echo isset($vo['label']) ? $vo['label'] :  ''; ?>" selected><?php echo isset($vo['label']) ? $vo['label'] :  '请选择课程标签'; ?></option>

                        <option value="免费">免费</option>
                        <option value="无">无</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        <!--<div class="row cl">-->
            <!--<label class="form-label col-xs-3 col-sm-3">标签：</label>-->
            <!--<div class="formControls col-xs-6 col-sm-6">-->
                <!--<input type="text" class="input-text" placeholder="标签" name="label" value="<?php echo isset($vo['label']) ? $vo['label'] :  ''; ?>" >-->
            <!--</div>-->
            <!--<div class="col-xs-3 col-sm-3"></div>-->
        <!--</div>-->


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
<script type="text/javascript" src="__LIB__/showdown/1.4.2/showdown.min.js"></script>
<script>window.UEDITOR_HOME_URL = '__LIB__/ueditor/1.4.3/'</script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script>
    $(function () {
        var ue = UE.getEditor('container',{
            //serverUrl:'<?php echo \think\Url::build("Ueditor/index"); ?>',
            //autoClearinitialContent: true,
            //wordCount: false,
            //elementPathEnabled: false,
            //autoFloatEnabled: false,

            initialContent: '<?php echo htmlspecialchars_decode($vo['content']); ?>'
        });

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



        //当选择视频或音频后，利用ajax实现视音频单上传
        $('#videos').change(function(){
            file =  $('#videos')[0].files;
            var formData = new FormData();

            formData.append("myfile",file[0]); //上传一段视音频

            $.ajax({
                type:'post',//选择post方式提交
                url:'<?php echo url("TrainingCoursechapter/upload"); ?>',//将数据提交的页面
                contentType: false,
                processData: false,
                data:formData, //传值
                success: function(data)
                {

                    //alert(data);return false; //传回来的data表示当前上传视音频的路径

                    $('#show').html(''); //清除所有内容
                    //开始显示上传的视频
                    //$('#show2').append('<video src=__ROOT__"'+data+'" height="60px" style="position:absolute;top:10px;height:60px;border-radius:2px;margin:10px 5px 0 10px;"></video>');

                    //上传成功后，显示已成功上传！
                    $('#show').text('已成功上传！');


                    $('#path').val(data);  //保存该上传视音频的路径


                }
            })
        });



    });
    window.onresize = function(){
        var ue = UE.getEditor('container',{
            serverUrl:'<?php echo \think\Url::build("Ueditor/index"); ?>',
            //autoClearinitialContent: true,
            //wordCount: false,
            //elementPathEnabled: false,
            //autoFloatEnabled: false,

            initialContent: '<?php echo htmlspecialchars_decode($vo['content']); ?>'
        });
    }

</script>


</body>
</html>