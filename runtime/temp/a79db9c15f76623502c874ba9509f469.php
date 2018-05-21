<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:97:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\company_release_fullposition\index.html";i:1524124118;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;s:96:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\company_release_fullposition\form.html";i:1523587112;s:94:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\company_release_fullposition\th.html";i:1523844483;s:94:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\company_release_fullposition\td.html";i:1523858283;}*/ ?>
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

    <!--<input type="text" class="input-text Wdate" style="width:250px" placeholder="发布时间" name="release_time" value="<?php echo \think\Request::instance()->param('release_time'); ?>"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"  >-->


    <div class="select-box" style="width:250px">
        <select name="companyName" class="select" datatype="*" nullmsg="请选择公司名称" >
            <option value ="">请选择公司名称</option>
            <?php if(is_array($companyName) || $companyName instanceof \think\Collection || $companyName instanceof \think\Paginator): $i = 0; $__LIST__ = $companyName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <option value ="<?php echo $vo['companyName']; ?>"><?php echo $vo['companyName']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
    </div>


    <div class="select-box" style="width:250px">
        <select name="city_area" class="select" datatype="*" nullmsg="请选择工作区域" >
            <option value ="">请选择工作区域</option>
            <?php if(is_array($city_area) || $city_area instanceof \think\Collection || $city_area instanceof \think\Paginator): $i = 0; $__LIST__ = $city_area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?>
            <option value ="<?php echo $v1['city']; ?>-<?php echo $v1['area']; ?>"><?php echo $v1['city']; ?><?php echo $v1['area']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
    </div>

    <div class="select-box" style="width:250px">
        <select name="isdown" class="select" datatype="*" nullmsg="请选择职位发布状态">
            <option value="">请选择职位状态</option>
            <option value ="0">正在上架</option>
            <option value ="1">已下架</option>
        </select>
    </div>




    <!--<div class="select-box" style="width:250px">-->
        <!--<select name="property" class="select" datatype="*" nullmsg="请选择职位属性" >-->
            <!--<option value ="">请选择职位属性</option>-->
            <!--<option value ="兼职">兼职</option>-->
        <!--</select>-->
    <!--</div>-->


    <!--<div class="select-box" style="width:250px">-->
        <!--<select name="period" class="select" datatype="*" nullmsg="请选择时段" >-->
            <!--<option value ="">请选择时段</option>-->
            <!--<option value ="工作日">工作日</option>-->
            <!--<option value ="双休日">双休日</option>-->
            <!--<option value ="每天">每天</option>-->
        <!--</select>-->
    <!--</div>-->

    <!--<div class="select-box" style="width:250px">-->
        <!--<select name="payroll" class="select" datatype="*" nullmsg="请选择结薪方式" >-->
            <!--<option value ="">请选择结薪方式</option>-->
            <!--<option value ="日结">日结</option>-->
            <!--<option value ="周结">周结</option>-->
            <!--<option value ="月结">月结</option>-->
        <!--</select>-->
    <!--</div>-->


    <button type="submit" class="btn btn-success"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
</form>
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="l">
            <!--<?php if (\Rbac::AccessCheck('fadd')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="full_page('全屏添加','<?php echo \think\Url::build('fadd', []); ?>')"><i class="Hui-iconfont">&#xe600;</i> 全屏添加</a><?php endif; if (\Rbac::AccessCheck('forbid')) : ?><a href="javascript:;" onclick="forbid_all('<?php echo \think\Url::build('forbid', []); ?>')" class="btn btn-warning radius mr-5"><i class="Hui-iconfont">&#xe631;</i> 禁用</a><?php endif; if (\Rbac::AccessCheck('resume')) : ?><a href="javascript:;" onclick="resume_all('<?php echo \think\Url::build('resume', []); ?>')" class="btn btn-success radius mr-5"><i class="Hui-iconfont">&#xe615;</i> 恢复</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?><a href="javascript:;" onclick="del_all('<?php echo \think\Url::build('delete', []); ?>')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a><?php endif; if (\Rbac::AccessCheck('recyclebin')) : ?><a href="javascript:;" onclick="open_window('回收站','<?php echo \think\Url::build('recyclebin', []); ?>')" class="btn btn-secondary radius mr-5"><i class="Hui-iconfont">&#xe6b9;</i> 回收站</a><?php endif; if (\Rbac::AccessCheck('echarts')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open('图形展示','<?php echo \think\Url::build('echarts', []); ?>')"><i class="Hui-iconfont">&#xe61e;</i> 图形展示</a><?php endif; ?>-->
             <?php if (\Rbac::AccessCheck('fadd')) : ?><a class="btn btn-primary radius mr-5" href="javascript:;" onclick="full_page('全屏添加','<?php echo \think\Url::build('fadd', []); ?>')"><i class="Hui-iconfont">&#xe600;</i> 全屏添加</a><?php endif; if (\Rbac::AccessCheck('delete')) : ?><a href="javascript:;" onclick="del_all('<?php echo \think\Url::build('delete', []); ?>')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a><?php endif; if (\Rbac::AccessCheck('recyclebin')) : ?><a href="javascript:;" onclick="open_window('回收站','<?php echo \think\Url::build('recyclebin', []); ?>')" class="btn btn-secondary radius mr-5"><i class="Hui-iconfont">&#xe6b9;</i> 回收站</a><?php endif; ?>
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
<th width="">发布者</th>
<th width=""><?php echo sort_by('公司名称','companyName'); ?></th>
<th width="">工作区域</th>
<th width="">职位名称</th>
<th width="">工作类别</th>
<th width="">发布时间</th>
<th width="">招聘人数</th>
<th width="">报名人数</th>
<th width=""><?php echo sort_by('标签','label'); ?></th>
<th width="">职位状态</th>



















            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr class="text-c">
            <td><input type="checkbox" name="id[]" value="<?php echo $vo['id']; ?>"></td>
<td><?php echo $vo['id']; ?></td>
<td><?php echo $vo['release']; ?></td>
<td><?php echo $vo['companyName']; ?></td>
<td><?php echo $vo['city']; ?><?php echo $vo['area']; ?></td>

<td><?php echo $vo['positionName']; ?></td>
<td>

    <?php
       if(!array_key_exists($vo['type'],$type_array)){
           echo '';
       }else{
           echo $type_array[$vo['type']];
       }
    ?>
</td>
<td>
    <?php echo $vo['release_time']; ?>
</td>
<td><?php echo $vo['count']; ?></td>

<td>
    <?php
       if(!array_key_exists($vo['id'],$enroll_array)){
           echo 0;
       }else{
           echo count($enroll_array[$vo['id']]);
       }
    ?>
</td>


<td><?php echo $vo['label']; ?></td>

<td>

    <?php if($vo['isdown'] == 0): ?>
         <a id="info<?php echo $vo['id']; ?>" href="javascript:;"  onclick="isdown(<?php echo $vo['id']; ?>)"  style="color: #148cf1">正在上架</a>
    <?php endif; if($vo['isdown'] == 1): ?>
         <a id="info<?php echo $vo['id']; ?>" href="javascript:;" onclick="isdown(<?php echo $vo['id']; ?>)"  style="color: #148cf1">已下架</a>
    <?php endif; ?>

</td>




            <td class="f-14">

                <!--只有正在上架的职位才让其刷新职位-->
                <?php if($vo['isdown'] == 0): ?>
                   <a href="<?php echo url('CompanyReleaseFullposition/refresh',array('id'=>$vo['id'])); ?>" style="color: #148cf1">刷新职位</a>
                <?php endif; ?>
                <!--只有正在上架的职位才让其刷新职位-->

                <!--<?php echo show_status($vo['status'],$vo['id']); ?>-->
                <?php if (\Rbac::AccessCheck('fsedit')) : ?> <a title="全屏编辑" href="javascript:;" onclick="full_page('全屏编辑','<?php echo \think\Url::build('fsedit', ['id' => $vo["id"], ]); ?>')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe6df;</i></a><?php endif; if (\Rbac::AccessCheck('delete')) : ?> <a title="删除" href="javascript:;" onclick="del(this,'<?php echo $vo['id']; ?>','<?php echo \think\Url::build('delete', []); ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a><?php endif; ?>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page-bootstrap"><?php echo isset($page) ? $page :  ''; ?></div>
</div>


<script type="text/javascript" src="__LIB__/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript">
    function isdown(id)
    {
        $(function(){
            $.ajax({
                type:'get',//选择get方式提交
                url:'<?php echo url("CompanyReleaseFullposition/isdown"); ?>',//将数据提交的页面
                data:{id:id},//传值
                success: function(data)
                {
                    if(data == 'down'){
                        $('#info'+id).text('已下架');
                    }
                    if(data == 'up'){
                        $('#info'+id).text('正在上架');
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