<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:80:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\salary_info\index.html";i:1526521659;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;s:79:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\salary_info\form.html";i:1526521270;s:77:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\salary_info\th.html";i:1526277403;s:77:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\salary_info\td.html";i:1526528898;}*/ ?>
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
        <select name="work_id" class="select" datatype="*" nullmsg="请选择用工单位" >
            <option value ="">请选择用工单位</option>
            <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $i = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <option value ="<?php echo $vo['id']; ?>"><?php echo $vo['work_unit']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
    </div>
   
	
 <!--    <input type="text" class="input-text" style="width:250px" placeholder="请输入市" name="city" value="<?php echo \think\Request::instance()->param('city'); ?>" >
    <input type="text" class="input-text" style="width:250px" placeholder="校区性质" name="schoolNature" value="<?php echo \think\Request::instance()->param('schoolNature'); ?>" >
	<input type="text" class="input-text" style="width:250px" placeholder="学校等级" name="educationLevel" value="<?php echo \think\Request::instance()->param('educationLevel'); ?>"  >
    <input type="text" class="input-text" style="width:250px" placeholder="校区名称" name="schoolName" value="<?php echo \think\Request::instance()->param('schoolName'); ?>"  > -->
    <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
</form>


<form   method="post" action="<?php echo \think\Url::build('admin/SalaryInfo/exportToExcel'); ?>" enctype="multipart/form-data">
    <div style="position:relative;">
        <div class="select-box" style="width:250px">
        <select name="work_id" class="select" datatype="*" nullmsg="请选择用工单位" >
            <option value ="">请选择用工单位</option>
            <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $i = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <option value ="<?php echo $vo['id']; ?>"><?php echo $vo['work_unit']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
    </div>
     
        <button type="submit" class="btn btn-success">下载</button>
   </div>
</form>

<form  id="form1" method="post" action="<?php echo \think\Url::build('admin/SalaryInfo/upload'); ?>" enctype="multipart/form-data">
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
<th width="">用户ID</th>
<th width="">姓名</th>
<th width="">岗位</th>
<th width="">薪资</th>
<th width="">出勤</th>
<th width="">合计</th>
<th width="">工资</th>
<th width="">是否结算</th>

            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr class="text-c">
                    <td><input type="checkbox" name="id[]" value="<?php echo $vo['id']; ?>"></td>
<td><?php echo $vo['id']; ?></td>
<td><?php echo $vo['user_id']; ?></td>
<td><?php echo $vo['name']; ?></td>
<td><?php echo $vo['positionName']; ?></td>
<td><?php echo $vo['salary']; ?></td>
<td><?php echo $vo['work_day']; ?></td>
<td><?php echo $vo['total']; ?></td>
<td><?php echo $vo['work_money']; ?></td>
<td><?php if($vo['isbalance'] == 1): ?>已结算<?php else: ?>未结算<?php endif; ?></td>
                    <td class="f-14">
                        <?php echo show_status($vo['status'],$vo['id']); if (\Rbac::AccessCheck('fsedit')) : ?> <a title="全屏编辑" href="javascript:;" onclick="full_page('全屏编辑','<?php echo \think\Url::build('fsedit', ['id' => $vo["id"], ]); ?>')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe6df;</i></a><?php endif; if (\Rbac::AccessCheck('delete')) : ?> <a title="删除" href="javascript:;" onclick="del(this,'<?php echo $vo['id']; ?>','<?php echo \think\Url::build('delete', []); ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>
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

<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<script type="text/javascript" src="__STATIC__/js/provincesData.js"></script>

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

    });




</script>

</body>
</html>