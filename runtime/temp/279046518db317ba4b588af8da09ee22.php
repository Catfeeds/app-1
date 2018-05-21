<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:84:"C:\wwwroot\AMS\public/../application/admin\view\company_release_position\fsedit.html";i:1523862449;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>招聘公司：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="companyName" class="select"  datatype="*" nullmsg="请选择公司">
                        <option value="<?php echo isset($vo['companyName']) ? $vo['companyName'] :  ''; ?>"><?php echo isset($vo['companyName']) ? $vo['companyName'] :  '请选择公司'; ?></option>
                        <?php if(is_array($schoolName) || $schoolName instanceof \think\Collection || $schoolName instanceof \think\Paginator): $i = 0; $__LIST__ = $schoolName;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?>
                           <option value="<?php echo $v1['schoolName']; ?>"><?php echo $v1['schoolName']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl" >
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>工作区域：</label>
            <div class="formControls col-xs-1 col-sm-1">
                <div class="select-box">
                    <select class="select" id="s_province" name="province" datatype="*" nullmsg="请选择省">

                    </select>
                </div>
            </div>
            <div class="col-xs-1 col-sm-1"></div>
            <div class="formControls col-xs-1 col-sm-1">
                <div class="select-box">
                    <select class="select" id="s_city" name="city" datatype="*" nullmsg="请选择市">

                    </select>
                </div>
            </div>
            <div class="col-xs-1 col-sm-1"></div>
            <div class="formControls col-xs-1 col-sm-1">
                <div class="select-box">
                    <select class="select" id="s_county" name="area"  datatype="*" nullmsg="请选择区">

                    </select>
                </div>
            </div>
            <div class="col-xs-1 col-sm-1"></div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>工作地点：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="工作地点" id="work_place" name="work_place" value="<?php echo isset($vo['work_place']) ? $vo['work_place'] :  ''; ?>"  datatype="*" nullmsg="请填写工作地点">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>




        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>职位属性：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="property" class="select"  datatype="*" nullmsg="请选择职位属性">
						<option value="<?php echo isset($vo['property']) ? $vo['property'] :  ''; ?>"><?php echo isset($property_name) ? $property_name :  '请选择职位属性'; ?></option>
                        <option value="1">日结兼职</option>
                        <option value="2">长期兼职</option>
                        <option value="3">实习兼职</option>
                        <option value="4">旅行兼职</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>职位名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="职位名称(5-12个字)" id="positionName" name="positionName" value="<?php echo isset($vo['positionName']) ? $vo['positionName'] :  ''; ?>"  datatype="/^[\u4e00-\u9fa5A-Za-z0-9]{5,12}$/" nullmsg="请填写职位名称">

            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>工作类别：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="type" class="select"  datatype="*" nullmsg="请选择工作类别">
						 <option value="<?php echo isset($vo['type']) ? $vo['type'] :  ''; ?>"><?php echo isset($type_name) ? $type_name :  '请选择工作类别'; ?></option>
                        <?php if(is_array($all_type) || $all_type instanceof \think\Collection || $all_type instanceof \think\Paginator): $i = 0; $__LIST__ = $all_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($i % 2 );++$i;?>
                         <option value="<?php echo $v2['id']; ?>"><?php echo $v2['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>兼职起始时间：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="兼职起始时间" name="start_time" value="<?php echo isset($vo['start_time']) ? $vo['start_time'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'%y-%M-{%d+1}'})"   datatype="*" nullmsg="请填写兼职起始时间">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>兼职结束时间：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="兼职结束时间" name="end_time" value="<?php echo isset($vo['end_time']) ? $vo['end_time'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'%y-%M-{%d+1}'})"   datatype="*" nullmsg="请填写兼职结束时间">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>兼职时段：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="period" class="select"  datatype="*" nullmsg="请选择兼职时段">
						<option value="<?php echo isset($vo['period']) ? $vo['period'] :  ''; ?>"><?php echo isset($period_name) ? $period_name :  '请选择兼职时段'; ?></option>
                        <option value="0">工作日</option>
                        <option value="1">双休日</option>
                        <option value="2">每天</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>上班时间：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="上班时间" name="work_time" value="<?php echo isset($vo['work_time']) ? $vo['work_time'] :  ''; ?>"  datatype="*" nullmsg="请填写上班时间">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>集合地点：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <textarea cols="" rows="" class="textarea" name="gather_place" id="nr"  placeholder="集合地点"  datatype="*" nullmsg="请填写集合地点"><?php echo isset($vo['gather_place']) ? $vo['gather_place'] :  ''; ?></textarea>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>招聘人数：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="招聘人数" name="count" value="<?php echo isset($vo['count']) ? $vo['count'] :  ''; ?>"  datatype="*" nullmsg="请填写招聘人数">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>性别要求：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="sex" class="select"  datatype="*" nullmsg="请选择性别要求">
                        <option value="<?php echo isset($vo['sex']) ? $vo['sex'] :  ''; ?>"><?php echo isset($sex_name) ? $sex_name :  '请选择性别要求'; ?></option>
                        <option value="0">男</option>
                        <option value="1">女</option>
                        <option value="2">男女不限</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>薪资待遇：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="薪资待遇" name="salary" value="<?php echo isset($vo['salary']) ? $vo['salary'] :  ''; ?>"  datatype="*" nullmsg="请填写薪资待遇">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>结薪方式：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="payroll" class="select"  datatype="*" nullmsg="请选择结薪方式">
						<option value="<?php echo isset($vo['payroll']) ? $vo['payroll'] :  ''; ?>"><?php echo isset($payroll_name) ? $payroll_name :  '请选择结薪方式'; ?></option>
                        <option value="0">日结</option>
                        <option value="1">周结</option>
                        <option value="2">月结</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>招聘要求：</label>
            <div class="formControls col-xs-6 col-sm-6">
            	<textarea cols="" rows="" class="textarea" name="requirement" id="yq"  placeholder="招聘要求" datatype="*" nullmsg="请填写招聘要求"><?php echo isset($vo['requirement']) ? $vo['requirement'] :  ''; ?></textarea>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>工作内容：</label>
            <div class="formControls col-xs-6 col-sm-6">
            	<textarea cols="" rows="" class="textarea" name="content" id="nr"  placeholder="工作内容"  datatype="*" nullmsg="请填写工作内容"><?php echo isset($vo['content']) ? $vo['content'] :  ''; ?></textarea>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系人：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="联系人" name="contacts" value="<?php echo isset($vo['contacts']) ? $vo['contacts'] :  ''; ?>"  datatype="*" nullmsg="请填写联系人">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系电话：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="联系电话" name="phone" value="<?php echo isset($vo['phone']) ? $vo['phone'] :  ''; ?>"  datatype="/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/" nullmsg="请填写联系电话">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>



        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标签：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="label" class="select"  datatype="*" nullmsg="请选择标签">
						<option value="<?php echo isset($vo['label']) ? $vo['label'] :  ''; ?>"><?php echo isset($vo['label']) ? $vo['label'] :  '请选择标签'; ?></option>
                        <option value="最优推荐">最优推荐</option>
                        <option value="公司急招">公司急招</option>
                        <option value="工资担保">工资担保</option>
                    </select>
                </div>
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
<script type="text/javascript" src="__LIB__/showdown/1.4.2/showdown.min.js"></script>
<script>window.UEDITOR_HOME_URL = '__LIB__/ueditor/1.4.3/'</script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__LIB__/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="__STATIC__/js/area.js"></script>


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


    var s=["s_province","s_city","s_county"];//三个select的name
//    var opt0 = ["","",""];//初始值

    var province = "<?php echo isset($vo['province']) ? $vo['province'] :  ''; ?>";
    var city = "<?php echo isset($vo['city']) ? $vo['city'] :  ''; ?>";
    var area = "<?php echo isset($vo['area']) ? $vo['area'] :  ''; ?>";
    var opt0 = [""+province+"",""+city+"",""+area+""];//初始值

    function _init_area(){  //初始化函数
        for(i=0;i<s.length-1;i++){
            document.getElementById(s[i]).onchange=new Function("change("+(i+1)+")");
        }
        change(0);
    }
    _init_area();


</script>


</body>
</html>