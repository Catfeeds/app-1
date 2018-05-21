<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:61:"C:\wwwroot\AMS\public/../application/admin\view\pub\info.html";i:1512494564;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;s:60:"C:\wwwroot\AMS\public/../application/admin\view\pub\th2.html";i:1512491650;s:60:"C:\wwwroot\AMS\public/../application/admin\view\pub\td2.html";i:1512493978;}*/ ?>
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
    <div class="cl pd-5 bg-1 bk-gray">
        <!--<span class="l">-->
        <!--<?php if (\Rbac::AccessCheck('forbid')) : ?><a href="javascript:;" onclick="forbid_all('<?php echo \think\Url::build('forbid', []); ?>')" class="btn btn-warning radius mr-5"><i class="Hui-iconfont">&#xe631;</i> 禁用</a><?php endif; if (\Rbac::AccessCheck('resume')) : ?><a href="javascript:;" onclick="resume_all('<?php echo \think\Url::build('resume', []); ?>')" class="btn btn-success radius mr-5"><i class="Hui-iconfont">&#xe615;</i> 恢复</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?><a href="javascript:;" onclick="del_all('<?php echo \think\Url::build('delete', []); ?>')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a><?php endif; if (\Rbac::AccessCheck('recyclebin')) : ?><a href="javascript:;" onclick="open_window('回收站','<?php echo \think\Url::build('recyclebin', []); ?>')" class="btn btn-secondary radius mr-5"><i class="Hui-iconfont">&#xe6b9;</i> 回收站</a><?php endif; if (\Rbac::AccessCheck('echarts')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open('图形展示','<?php echo \think\Url::build('echarts', []); ?>')"><i class="Hui-iconfont">&#xe61e;</i> 图形展示</a><?php endif; ?>-->
        <!--</span>-->
        <span class="l">全部消息：</span>
        <span class="r pt-5 pr-5">
            共有请假消息 ：<strong><?php echo isset($count) ? $count :  '0'; ?></strong> 条
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg mt-20">
        <thead>
        <tr class="text-c">
            <!--<th width="25"><input type="checkbox"></th>-->
<th width="">序号</th>
<th width="">请假人</th>
<th width="">提交时间</th>
<th width="">学校名称</th>
<th width="">班级名称</th>
<th width="">起止时间</th>
<th width="">截止时间</th>
<th width="">事由</th>
<th width="">状态</th>




        </tr>
        </thead>
        <tbody>
        <?php if(is_array($result) || $result instanceof \think\Collection || $result instanceof \think\Paginator): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr class="text-c">
            <!--<td><input type="checkbox" name="id[]" value="<?php echo $vo['id']; ?>"></td>-->
<td><?php echo $vo['id']; ?></td>
<td><?php echo $vo['release']; ?></td>
<td><?php echo $vo['release_time']; ?></td>
<td><?php echo $vo['schoolName']; ?></td>
<td><?php echo $vo['className']; ?>班</td>
<td><?php echo $vo['start_time']; ?></td>
<td><?php echo $vo['end_time']; ?></td>
<td><?php echo $vo['content']; ?></td>
<td>
    <?php if($vo['checkinfo'] == 0): ?>
          <a id="info<?php echo $vo['id']; ?>" href="javascript:;" style="color: brown;text-decoration: underline" onclick="isapprove(<?php echo $vo['id']; ?>)">未审批</a>
       <?php else: ?>
          <a id="info<?php echo $vo['id']; ?>" href="javascript:;" style="color: brown;text-decoration: underline" onclick="isapprove(<?php echo $vo['id']; ?>)">已审批</a>
    <?php endif; ?>
</td>






        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page-bootstrap"><?php echo isset($page) ? $page :  ''; ?></div>
</div>
<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript">
    function isapprove(id)
    {
         $(function(){
             $.ajax({
                 type:'get',//选择get方式提交
                 url:'<?php echo url("pub/getId"); ?>',//将数据提交的页面
                 data:{id:id},//传值
                 success: function(data)
                 {
                     if(data == 'approve'){
                         $('#info'+id).text('已审批');
                     }
                     if(data == 'disapprove'){
                         $('#info'+id).text('未审批');
                     }
                 }
             })
         })
    }
</script>

<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="__LIB__/layer/2.4/layer.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="__STATIC__/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="__STATIC__/js/app.js"></script>
<script type="text/javascript" src="__LIB__/icheck/jquery.icheck.min.js"></script>

</body>
</html>