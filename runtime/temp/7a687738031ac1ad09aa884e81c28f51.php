<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:88:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\employee_management\index.html";i:1507940226;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\employee_management\form.html";i:1516761940;s:85:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\employee_management\th.html";i:1521621086;s:85:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\employee_management\td.html";i:1521777771;}*/ ?>
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
    <form class="mb-20" method="get" action="<?php echo \think\Url::build(\think\Request::instance()->action()); ?>">
	
    <div class="select-box" style="width:250px">
	<select name="schoolName" class="select" datatype="*" nullmsg="请选择学区名称" >
		<option value ="" selected>请选择学区名称</option>
		<?php if(is_array($schoolName) || $schoolName instanceof \think\Collection || $schoolName instanceof \think\Paginator): $i = 0; $__LIST__ = $schoolName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option value ="<?php echo $vo['schoolName']; ?>"><?php echo $vo['schoolName']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	</div>
	<div class="select-box" style="width:250px">
	<select name="divisionName" class="select" datatype="*" nullmsg="请选择部门名称" >
		<option value ="" selected>请选择部门名称</option>
		<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option value ="<?php echo $vo['divisionName']; ?>"><?php echo $vo['divisionName']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	</div>
	<div class="select-box" style="width:250px">
	<select name="className" class="select" datatype="*" nullmsg="请选择班级名称" >
		<option value ="" selected>请选择班级名称</option>
		<?php if(is_array($className) || $className instanceof \think\Collection || $className instanceof \think\Paginator): $i = 0; $__LIST__ = $className;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option value ="<?php echo $vo['className']; ?>"><?php echo $vo['className']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	</div>

	<input type="text" class="input-text" style="width:250px" placeholder="员工账号" name="account" value="<?php echo \think\Request::instance()->param('account'); ?>" >
    <input type="text" class="input-text" style="width:250px" placeholder="姓名" name="name" value="<?php echo \think\Request::instance()->param('name'); ?>" >
    <div class="select-box" style="width:250px">
        <select name="sex" class="select">
            <option value="男">男</option>
            <option value="女">女</option>
        </select>
    </div>
    <input type="text" class="input-text" style="width:250px" placeholder="身份证号码" name="cardID" value="<?php echo \think\Request::instance()->param('cardID'); ?>" >
    <input type="text" class="input-text" style="width:250px" placeholder="岗位" name="postName" value="<?php echo \think\Request::instance()->param('postName'); ?>" >
    <input type="text" class="input-text" style="width:250px" placeholder="电话" name="iphone" value="<?php echo \think\Request::instance()->param('iphone'); ?>" >

    <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
</form>

<form  method="post" action="<?php echo \think\Url::build('admin/EmployeeManagement/upload'); ?>" enctype="multipart/form-data">
    <div style="position:relative;">
        <input type="file" class="input-text" style="width:250px;opacity: 0" name="file" value="" >
        <p style="position: absolute;width:250px;top:0;line-height: 30px;text-align: center;border: 1px solid #DDD;background: #fff;z-index: -1;">上传excel</p>
        <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i>导入</button>
   </div>
</form>
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="l">
            <?php if (\Rbac::AccessCheck('add')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open('添加','<?php echo \think\Url::build('add', []); ?>')"><i class="Hui-iconfont">&#xe600;</i> 添加</a><?php endif; if (\Rbac::AccessCheck('forbid')) : ?><a href="javascript:;" onclick="forbid_all('<?php echo \think\Url::build('forbid', []); ?>')" class="btn btn-warning radius mr-5"><i class="Hui-iconfont">&#xe631;</i> 禁用</a><?php endif; if (\Rbac::AccessCheck('resume')) : ?><a href="javascript:;" onclick="resume_all('<?php echo \think\Url::build('resume', []); ?>')" class="btn btn-success radius mr-5"><i class="Hui-iconfont">&#xe615;</i> 恢复</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?><a href="javascript:;" onclick="del_all('<?php echo \think\Url::build('delete', []); ?>')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a><?php endif; if (\Rbac::AccessCheck('recyclebin')) : ?><a href="javascript:;" onclick="open_window('回收站','<?php echo \think\Url::build('recyclebin', []); ?>')" class="btn btn-secondary radius mr-5"><i class="Hui-iconfont">&#xe6b9;</i> 回收站</a><?php endif; if (\Rbac::AccessCheck('echarts')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open('图形展示','<?php echo \think\Url::build('echarts', []); ?>')"><i class="Hui-iconfont">&#xe61e;</i> 图形展示</a><?php endif; ?>
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
<th width=""><?php echo sort_by('学校名称','schoolName'); ?></th>
<th width=""><?php echo sort_by('部门名称','divisionName'); ?></th>
<th width=""><?php echo sort_by('班级名称','className'); ?></th>
<th width="">身份</th>
<th width=""><?php echo sort_by('员工账号','account'); ?></th>
<th width=""><?php echo sort_by('姓名','name'); ?></th>
<th width="">性别</th>
<th width="">身高</th>
<th width="">体重</th>
<th width="">头像</th>
<th width=""><?php echo sort_by('岗位','postName'); ?></th>
<th width="">电话</th>

            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr class="text-c">
            <td><input type="checkbox" name="id[]" value="<?php echo $vo['id']; ?>"></td>
<td><?php echo $vo['id']; ?></td>
<td><?php echo $vo['schoolName']; ?></td>
<td><?php echo $vo['divisionName']; ?></td>
<td><?php echo $vo['className']; ?></td>

<td>
    <?php if($vo['identity'] == 1): ?>
            园长
    <?php endif; if($vo['identity'] == 2): ?>
           教师
    <?php endif; ?>
</td>

<td><?php echo high_light($vo['account'],\think\Request::instance()->param('account')); ?></td>
<td><?php echo high_light($vo['name'],\think\Request::instance()->param('name')); ?></td>
<td><?php echo $vo['sex']; ?></td>
<td><?php echo $vo['height']; ?></td>
<td><?php echo $vo['weight']; ?></td>
<td><img src="__ROOT__<?php echo $vo['employee_headurl']; ?>" height="40"></td>
<td><?php echo high_light($vo['postName'],\think\Request::instance()->param('postName')); ?></td>
<td><?php echo high_light($vo['iphone'],\think\Request::instance()->param('iphone')); ?></td>

            <td class="f-14">
                <?php echo show_status($vo['status'],$vo['id']); if (\Rbac::AccessCheck('edit')) : ?> <a title="编辑" href="javascript:;" onclick="layer_open('编辑','<?php echo \think\Url::build('edit', ['id' => $vo["id"], ]); ?>')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe6df;</i></a><?php endif; if (\Rbac::AccessCheck('delete')) : ?> <a title="删除" href="javascript:;" onclick="del(this,'<?php echo $vo['id']; ?>','<?php echo \think\Url::build('delete', []); ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>
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

<script>
    $(function () {
        $("[name='schoolName']").find("[value='<?php echo \think\Request::instance()->param('schoolName'); ?>']").attr("selected", true);
        $("[name='divisionName']").find("[value='<?php echo \think\Request::instance()->param('divisionName'); ?>']").attr("selected", true);
        $("[name='className']").find("[value='<?php echo \think\Request::instance()->param('className'); ?>']").attr("selected", true);
        $("[name='sex']").find("[value='<?php echo \think\Request::instance()->param('sex'); ?>']").attr("selected", true);
        $("[name='relation']").find("[value='<?php echo \think\Request::instance()->param('relation'); ?>']").attr("selected", true);
    })
</script>

</body>
</html>