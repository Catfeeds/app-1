<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\admin_role\user.html";i:1517537064;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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


<div class="page-container">
    <form action="<?php echo \think\Request::instance()->baseUrl(); ?>" method="post" id="form" style="padding-top: 50px">
        <input type="hidden" name="id" VALUE="<?php echo \think\Request::instance()->param('id'); ?>" />
        <div class="cl pd-5 bg-1 bk-gray pos-f" style="left: 20px;right: 20px;top: 20px;">
            <span class="l">
                <button type="button" class="btn btn-primary radius" onclick="$('#checkAll').click()">&nbsp;&nbsp;全选&nbsp;&nbsp;</button>
            </span>

            <!--所有部门-->
            <div class="row cl" style="float: left;margin-left: 25px">
                <div class="formControls col-xs-6 col-sm-6">
                    <div class="select-box" style="width: 220px">
                        <select name="division" id="division" class="select">
                            <option value="">---请选择部门---</option>
                            <?php if(is_array($all_division) || $all_division instanceof \think\Collection || $all_division instanceof \think\Paginator): $i = 0; $__LIST__ = $all_division;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vd): $mod = ($i % 2 );++$i;?>
                                  <option value="<?php echo $vd['divisionName']; ?>"><?php echo $vd['divisionName']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3"></div>
            </div>

            <!--所有班级-->
            <div class="row cl" style="float: left;position: relative;left: -15px">
                <div class="formControls col-xs-6 col-sm-6">
                    <div class="select-box" style="width: 220px">
                        <select name="className" id="className" class="select">
                            <option value="">----请选择班级---</option>
                            <?php if(is_array($all_class) || $all_class instanceof \think\Collection || $all_class instanceof \think\Paginator): $i = 0; $__LIST__ = $all_class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vc): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $vc['class']; ?><?php echo $vc['className']; ?>"><?php echo $vc['class']; ?><?php echo $vc['className']; ?>班</option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3"></div>
            </div>

            <!--保存tp_admin_role表中当前角色id-->
            <input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id; ?>">
            <!--/保存tp_admin_role表中当前角色id-->

            <span class="l" style="float: left;">
                <button type="button" id="search" name="search" class="btn btn-primary radius" style="position: relative;left: -35px">&nbsp;&nbsp;查找&nbsp;&nbsp;</button>
            </span>



            <span class="r pt-5 pr-5">
                <button type="submit" class="btn btn-primary radius">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>
                <button type="button" class="ml-20 btn btn-default radius" onclick="layer_close()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </span>
        </div>



        <table class="table table-border table-bordered table-hover table-bg mt-20">
            <thead>
            <tr class="text-c">
                <th width="80">
                    <div class="check-box">
                        <input type="checkbox" id="checkAll">
                    </div>
                </th>
                <th width="150">账号</th>
                <th width="150">姓名</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr class="text-c">
                    <td>
                        <div class="check-box">
                            <input type="checkbox" name="user_id[]" value="<?php echo $vo['id']; ?>"/>
                        </div>
                    </td>
                    <td><?php echo $vo['account']; ?></td>
                    <td><?php echo $vo['realname']; ?></td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
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
        var checks = '<?php echo $checks; ?>'.split(",");
        if(checks.length > 0){
            for (var i in checks){
                $("[name='user_id[]'][value='"+checks[i]+"']").attr("checked",true);
            }
        }

        $("#form").Validform({
            tiptype:2,
            ajaxPost:true,
            showAllError:true,
            callback:function(ret){
                ajax_progress(ret);
            }
        });


        //点击查找按钮--按条件进行查找
        $('#search').click(function(){
            var division = $('#division').val();  //所要查的部门
            var className = $("#className").val(); //所要查的班级
            var role_id = $('#role_id').val();  //tp_admin_role表序号
            window.location.href="<?php echo url('AdminRole/user'); ?>?division="+division+"&className="+className+"&id="+role_id+"";
        });



    })
</script>

</body>
</html>