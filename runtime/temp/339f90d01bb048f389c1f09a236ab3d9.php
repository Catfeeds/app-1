<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:78:"C:\wwwroot\AMS\public/../application/admin\view\enrol_student_trace\index.html";i:1517540245;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\enrol_student_trace\form.html";i:1517550214;s:75:"C:\wwwroot\AMS\public/../application/admin\view\enrol_student_trace\th.html";i:1517371278;s:75:"C:\wwwroot\AMS\public/../application/admin\view\enrol_student_trace\td.html";i:1517377491;}*/ ?>
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
    <form class="mb-20" method="post" action="<?php echo \think\Url::build(\think\Request::instance()->action()); ?>">


    <!--<input type="text" class="input-text" style="width:250px" placeholder="标题" name="title" value="<?php echo \think\Request::instance()->param('title'); ?>" >-->
    <!--<input type="text" class="input-text" style="width:250px" placeholder="报名学段" name="class" value="<?php echo \think\Request::instance()->param('class'); ?>" >-->

    <!--<div class="select-box" style="width:250px">-->
        <!--<select class="select" name="sex" value="<?php echo \think\Request::instance()->param('sex'); ?>">-->
            <!--<option value="男" selected>男</option>-->
            <!--<option value="女">女</option>-->
        <!--</select>-->
    <!--</div>-->
    <input type="text" class="input-text" style="width:250px" placeholder="联系人" name="contacts" value="<?php echo \think\Request::instance()->param('contacts'); ?>" >

    <!--<input type="text" class="input-text Wdate" style="width:250px" placeholder="最近联系时间" name="contact_time" value="<?php echo \think\Request::instance()->param('contact_time'); ?>"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"  >-->


    <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>

</form>
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="l">
            <!--<?php if (\Rbac::AccessCheck('fadd')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="full_page('全屏添加','<?php echo \think\Url::build('fadd', []); ?>')"><i class="Hui-iconfont">&#xe600;</i> 全屏添加</a><?php endif; if (\Rbac::AccessCheck('forbid')) : ?><a href="javascript:;" onclick="forbid_all('<?php echo \think\Url::build('forbid', []); ?>')" class="btn btn-warning radius mr-5"><i class="Hui-iconfont">&#xe631;</i> 禁用</a><?php endif; if (\Rbac::AccessCheck('resume')) : ?><a href="javascript:;" onclick="resume_all('<?php echo \think\Url::build('resume', []); ?>')" class="btn btn-success radius mr-5"><i class="Hui-iconfont">&#xe615;</i> 恢复</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?><a href="javascript:;" onclick="del_all('<?php echo \think\Url::build('delete', []); ?>')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a><?php endif; if (\Rbac::AccessCheck('recyclebin')) : ?><a href="javascript:;" onclick="open_window('回收站','<?php echo \think\Url::build('recyclebin', []); ?>')" class="btn btn-secondary radius mr-5"><i class="Hui-iconfont">&#xe6b9;</i> 回收站</a><?php endif; if (\Rbac::AccessCheck('echarts')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open('图形展示','<?php echo \think\Url::build('echarts', []); ?>')"><i class="Hui-iconfont">&#xe61e;</i> 图形展示</a><?php endif; ?>-->
            <?php if (\Rbac::AccessCheck('forbid')) : ?><a href="javascript:;" onclick="forbid_all('<?php echo \think\Url::build('forbid', []); ?>')" class="btn btn-warning radius mr-5"><i class="Hui-iconfont">&#xe631;</i> 禁用</a><?php endif; if (\Rbac::AccessCheck('resume')) : ?><a href="javascript:;" onclick="resume_all('<?php echo \think\Url::build('resume', []); ?>')" class="btn btn-success radius mr-5"><i class="Hui-iconfont">&#xe615;</i> 恢复</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?><a href="javascript:;" onclick="del_all('<?php echo \think\Url::build('delete', []); ?>')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a><?php endif; if (\Rbac::AccessCheck('recyclebin')) : ?><a href="javascript:;" onclick="open_window('回收站','<?php echo \think\Url::build('recyclebin', []); ?>')" class="btn btn-secondary radius mr-5"><i class="Hui-iconfont">&#xe6b9;</i> 回收站</a><?php endif; if (\Rbac::AccessCheck('echarts')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open('图形展示','<?php echo \think\Url::build('echarts', []); ?>')"><i class="Hui-iconfont">&#xe61e;</i> 图形展示</a><?php endif; ?>
        </span>
        <span class="r pt-5 pr-5">

            共有数据 ：<strong><?php echo isset($count) ? $count :  '0'; ?></strong> 条

        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg mt-20">
        <thead>
        <tr class="text-c">
            <th width="25"><input type="checkbox"></th>
<th width="">序号</th>
<th width="">学生姓名</th>
<th width="">目前在读学校</th>
<th width="">报名学段</th>
<th width="">性别</th>

<th width="">联系人</th>
<th width="">备注</th>
<th width="">最近联系时间</th>
<th width="">是否成交</th>






            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($all_new_trace_info) || $all_new_trace_info instanceof \think\Collection || $all_new_trace_info instanceof \think\Paginator): $i = 0; $__LIST__ = $all_new_trace_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr class="text-c">
            <td><input type="checkbox" name="id[]" value="<?php echo $vo['id']; ?>"></td>



<td><?php echo $vo['id']; ?></td>

<td><?php echo $vo['name']; ?></td>
<td><?php echo $vo['current_school']; ?></td>
<td><?php echo $vo['class']; ?></td>
<td><?php echo $vo['sex']; ?></td>

<td><?php echo $vo['contacts']; ?></td>
<td>
    <?php if($vo['remark'] != ''): ?>
         <?php echo $vo['remark']; else: ?>
          查看过
    <?php endif; ?>
</td>
<td><?php echo $vo['contact_time']; ?></td>
<td>
    <!--判断该联系人是否成交-->
    <?php if($vo['isdone'] == 1): ?>
           <a href="#?id=<?php echo $vo['id']; ?>" style="text-decoration: underline">成交</a>
    <?php endif; if($vo['isdone'] == 0): ?>
           <a href="#?id=<?php echo $vo['id']; ?>" style="text-decoration: underline">未成交</a>
    <?php endif; ?>
</td>


<!--if($new_trace_info[$vo['id']]['remark']){-->
<!--echo $new_trace_info[$vo['id']]['remark'].'&nbsp;&nbsp;&nbsp;&nbsp;';-->
<!--}else{-->
<!--echo '查看过'.'&nbsp;&nbsp;&nbsp;&nbsp;';-->
<!--}-->










            <td class="f-14">
                <!--<?php echo show_status($vo['status'],$vo['id']); ?>-->
                <!--<?php if (\Rbac::AccessCheck('fsedit')) : ?> <a title="全屏编辑" href="javascript:;" onclick="full_page('全屏编辑','<?php echo \think\Url::build('fsedit', ['id' => $vo["id"], ]); ?>')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe6df;</i></a><?php endif; ?>-->

                <!--<?php if (\Rbac::AccessCheck('select')) : ?> <a title="查看" href="javascript:;" onclick="layer_open('查看','<?php echo \think\Url::build('select', ['id' => $vo['id'], ]); ?>')" class="label radius ml-5 label-primary">查看</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?> <a title="删除" href="javascript:;" onclick="del(this,'<?php echo $vo['id']; ?>','<?php echo \think\Url::build('delete', []); ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>-->

                <!--<a href="<?php echo url('Enrolstudent/select'); ?>?id=<?php echo $vo['id']; ?>">查看</a>-->
                <?php if (\Rbac::AccessCheck('delete')) : ?> <a title="删除" href="javascript:;" onclick="del(this,'<?php echo $vo['id']; ?>','<?php echo \think\Url::build('delete', []); ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page-bootstrap"><?php echo isset($page) ? $page :  ''; ?></div>
</div>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

</body>
</html>