<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\company_release_position\fsedit.html";i:1526882821;s:76:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\base.html";i:1488874432;s:87:"C:\inetpub\wwwroot\anhui\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
	<div class="row cl">
		<div class="col-xs-1 col-sm-1 col-md-2"></div>
		<div class="col-xs-10 col-sm-10 col-md-7" style="border:3px dashed #fca128;margin:0 -15px;padding: 15px;">为了更好的解决快速招聘困难，在没有建立信用保障的基础上，兼职短工工资结算无法保障，派职网新增工资担保机制，即根据企业用工人数，用工时长将工资一次存入派职网工资发放帐户，根据企业上传实际用工考勤结算表，由派职网进行工资发放及余额结算，派职网不收取招聘费用。</div>	
	</div>
    <form class="form form-horizontal" id="form" method="post" action="<?php echo \think\Request::instance()->baseUrl(); ?>">
        <input type="hidden" name="id" value="<?php echo isset($vo['id']) ? $vo['id'] :  ''; ?>">
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>招聘公司：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="招聘公司" id="companyName" name="companyName" value="<?php echo isset($vo['companyName']) ? $vo['companyName'] :  $schoolName['schoolName']; ?>" readonly="readonly">
            </div>
            <div class="col-xs-3 col-sm-3 "></div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>用工企业：</label>
			<a  class="btn btn-success radius" id="addwork">添加</a>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="work_unit" class="select"   nullmsg="请选择用工企业">
                        <option value="<?php echo isset($vo['work_unit']) ? $vo['work_unit'] :  ''; ?>"><?php echo isset($vo['work_unit']) ? $vo['work_unit'] :  '请选择用工企业'; ?></option>
                        <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $i = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?>
                           <option value="<?php echo $v1['work_unit']; ?>"><?php echo $v1['work_unit']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 "></div>
        </div>
        <div class="row cl" >
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>工作区域：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <div class="select-box f-l mr-10 lh-25" style="width:25%;">
                    <select  class="select" id="s_province" name="province" datatype="*" nullmsg="请选择省"></select>
                </div>
                <div class="select-box f-l mr-10 lh-25"  style="width: 25%">
                    <select class="select" id="s_city" name="city" datatype="*" nullmsg="请选择市"></select>
                </div>
                <div class="select-box f-l mr-10 lh-25"  style="width: 25%">
                    <select class="select" id="s_county" name="area"  datatype="*" nullmsg="请选择区"></select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        
        <div class="row cl">
        	<div class="col-xs-1 col-sm-1 col-md-2"></div>
        	<div class="col-xs-10 col-sm-10 col-md-7"  id="count" style="border: 1px solid #fda128;margin:0 -15px;padding:10px 20px;">
        		<div class="row cl pb-10">
        			<div class="col-xs-3 col-sm-3">
        				<label><span class="c-red">*</span>薪资（元）：</label>
        				<div>
        					<input type="text" class="input-text f-l" placeholder="薪资" name="salary" datatype="*" value="<?php echo isset($vo['salary']) ? $vo['salary'] :  ''; ?>" nullmsg="请填写薪资" id="salarymoney" style="width: 50%;">
			            	<div class="select-box" style="width: 50%;">
			            		<select name="unit" class="select f-l"  datatype="*" nullmsg="请选择工作时长" id="salaryunit">
								    <option value="<?php echo isset($vo['unit']) ? $vo['unit'] :  ''; ?>"><?php echo isset($vo['unit']) ? $vo['unit'] :  '请选择薪资单位'; ?></option>
									<option value="0">单位</option>
			                        <option value="元/时">元/时</option>
			                        <option value="元/天">元/天</option>
			                        <option value="元/月">元/月</option>
			                    </select>
			            	</div>
	        			</div>
        			</div>
        			<div class="col-xs-3 col-sm-3">
        				<label><span class="c-red">*</span>工作时长（时）：</label>
			            <div class="select-box">
		                    <select name="work_hours" class="select"  datatype="*" nullmsg="请选择工作时长" id="salaryh">
							    <option value="<?php echo isset($vo['work_hours']) ? $vo['work_hours'] :  ''; ?>"><?php echo isset($vo['work_hours']) ? $vo['work_hours'] :  '请选择工作时长'; ?></option>
								<option value="">请选择工作时长</option>
		                        <option value="0.5">0.5小时</option>
		                        <option value="1">1小时</option>
		                        <option value="1.5">1.5小时</option>
		                        <option value="2">2小时</option> 
		                        <option value="2.5">2.5小时</option>
		                        <option value="3">3小时</option>
		                        <option value="3.5">3.5小时</option>
		                        <option value="4">4小时</option>
		                        <option value="4.5">4.5小时</option>
		                        <option value="5">5小时</option>
		                        <option value="5.5">5.5小时</option>
		                        <option value="6">6小时</option>
		                        <option value="6.5">6.5小时</option>
		                        <option value="7">7小时</option>
		                        <option value="7.5">7.5小时</option>
		                        <option value="8">8小时</option>
		                        <option value="8.5">8.5小时</option>
		                        <option value="9">9小时</option>
		                        <option value="9.5">9.5小时</option>
		                        <option value="10">10小时</option>
		                        <option value="10.5">10.5小时</option>
		                        <option value="11">11小时</option>
		                        <option value="11.5">11.5小时</option>
		                        <option value="12">12小时</option>
		                    </select>
		                </div>
        			</div>
        			<div class="col-xs-3 col-sm-3">
        				<label><span class="c-red">*</span>招聘人数（人）：</label>
			            <input type="text" class="input-text" placeholder="招聘人数" name="count" datatype="*" nullmsg="请填写招聘人数" id="salaryman" value="<?php echo isset($vo['count']) ? $vo['count'] :  ''; ?>">
        			</div>
        			<div class="col-xs-3 col-sm-3">
        				<label><span class="c-red">*</span><span id="jswork">工作天数（天）：</span></label>
			            <input type="text" class="input-text" placeholder="工作天数" name="work_days" datatype="*" nullmsg="请填写工作天数" id="salaryday" value="<?php echo isset($vo['work_days']) ? $vo['work_days'] :  ''; ?>">
        			</div>
		        </div>
		        <div class="row cl pb-10">
		        	<div class="col-xs-12 col-sm-12">
                        工资合计：<span class="c-warning f-18" id="salary">0</span> 元&nbsp;&nbsp;&nbsp;
                        保险金额: <span class="c-warning f-18" id="secure">0</span> 元&nbsp;&nbsp;&nbsp;
                        账户余额：<span class="c-warning f-18" id="salaryaccount"><?php echo isset($money) ? $money :  0; ?></span> 元&nbsp;&nbsp;&nbsp;
                        需充值：<span class="c-warning f-18" id="needpay">0</span> 元
                        <!-- <form method="post" action="<?php echo url('SchoolManagement/pay'); ?>">
                            <input type="hidden" name="needpay" id="needpay1" value="">
                            <input type="hidden" name="companyName" id="companyName1" value="">
                            <input type="hidden" name="jianzhitype" id="jianzhitype1" value="">
                            <input type="hidden" name="salaryUnit" id="salaryUnit1" value="">
                            <input type="hidden" name="salaryMoney" id="salaryMoney1" value="">
                            <input type="hidden" name="salaryH" id="salaryH1" value="">
                            <input type="hidden" name="salaryMan" id="salaryMan1" value="">
                            <input type="hidden" name="salaryDay" id="salaryDay1" value="">
                        </form> -->
		        		<a href="#" class="btn btn-success radius f-r" name="" id="salary_storage" value="" >工资预存</a>
		        	</div>
                    <input type="hidden" name="all_salary" id="all_salary" value="">
		        </div>
        	</div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>职位标题名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="职位标题名称(5~30字简介)" id="positionName" name="positionName"  datatype="/^[\u4e00-\u9fa5A-Za-z0-9]{1,30}$/" nullmsg="请填写职位标题名称"  value="<?php echo isset($vo['positionName']) ? $vo['positionName'] :  ''; ?>" >
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        
		<div class="row cl">
        	<div class="col-xs-1 col-sm-1 col-md-2"></div>
        	<div class="col-xs-10 col-sm-10 col-md-7"  id="count" style="border: 1px solid #ddd;margin:0 -15px;padding:10px 20px;">
        		<div class="row cl pb-10">
        			<div class="col-xs-4 col-sm-4">
        				<label ><span class="c-red">*</span>兼职属性：</label>
			            <div class="select-box">
		                    <select name="property" class="select"  datatype="*" nullmsg="请选择兼职属性">
								<option value="<?php echo isset($vo['property']) ? $vo['property'] :  ''; ?>"><?php echo isset($property_name) ? $property_name :  '请选择兼职属性'; ?></option>
		                        <option value="1">日结兼职</option>
		                        <option value="2">长期兼职</option>
		                        <option value="3">实习兼职</option>
		                        <option value="4">旅行兼职</option>
		                    </select>
		                </div>
        			</div>
        			<div class="col-xs-4 col-sm-4">
        				<label><span class="c-red">*</span>兼职类别：</label>
        				<div class="select-box">
		                    <select name="type" id="jianzhitype" class="select"  datatype="*" nullmsg="请选择兼职类别">
								 <option value="<?php echo isset($vo['type']) ? $vo['type'] :  ''; ?>"><?php echo isset($type_name) ? $type_name :  '请选择兼职类别'; ?></option>
		                        <?php if(is_array($all_type) || $all_type instanceof \think\Collection || $all_type instanceof \think\Paginator): $i = 0; $__LIST__ = $all_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($i % 2 );++$i;?>
		                         <option value="<?php echo $v2['id']; ?>"><?php echo $v2['name']; ?></option>
		                        <?php endforeach; endif; else: echo "" ;endif; ?>
		                    </select>
		                </div>
        			</div>
        			<div class="col-xs-4 col-sm-4">
        				<label><span class="c-red">*</span>结薪方式：</label>
        				<div class="select-box">
		                    <select name="payroll" class="select"  datatype="*" nullmsg="请选择结薪方式">
								<option value="<?php echo isset($vo['payroll']) ? $vo['payroll'] :  ''; ?>"><?php echo isset($payroll_name) ? $payroll_name :  '请选择结薪方式'; ?></option>
		                        <option value="0">日结</option>
		                        <option value="1">周结</option>
		                        <option value="2">月结</option>
		                    </select>
		                </div>
        			</div>
        			<div class="col-xs-4 col-sm-4 mt-20">
        				<label><span class="c-red">*</span>兼职时段：</label>
        				<div class="select-box">
		                    <select name="period" class="select"  datatype="*" nullmsg="请选择兼职时段">
								<option value="<?php echo isset($vo['period']) ? $vo['period'] :  ''; ?>"><?php echo isset($period_name) ? $period_name :  '请选择兼职时段'; ?></option>
		                        <option value="1">每天</option>
		                        <option value="2">工作日</option>
		                        <option value="3">双休日</option>
		                    </select>
		                </div>
        			</div>
        			<div class="col-xs-4 col-sm-4 mt-20">
        				<label><span class="c-red">*</span>性别要求：</label>
        				<div class="select-box">
		                    <select name="sex" class="select"  datatype="*" nullmsg="请选择性别要求">
		                        <option value="<?php echo isset($vo['sex']) ? $vo['sex'] :  ''; ?>"><?php echo isset($sex_name) ? $sex_name :  '请选择性别要求'; ?></option>
		                        <option value="1">男</option>
		                        <option value="2">女</option>
		                        <option value="3">男女不限</option>
		                    </select>
		                </div>
        			</div>
        			<div class="col-xs-4 col-sm-4 mt-20">
        				<label><span class="c-red">*</span>工作地点：</label>
        				<input type="text" class="input-text jsmap" placeholder="工作地点" readonly="readonly" id="work_place" name="work_place" value="<?php echo isset($vo['work_place']) ? $vo['work_place'] :  ''; ?>"  datatype="*" nullmsg="请填写工作地点">
        			</div>
        			
        		</div>
        	</div>
        	<div class="col-xs-1 col-sm-1 col-md-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>兼职开始日期：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="兼职开始日期" name="start_time" value="<?php echo isset($vo['start_time']) ? $vo['start_time'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'%y-%M-{%d+1}'})"   datatype="*" nullmsg="请填写兼职起始时间">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>兼职结束日期：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="兼职结束日期" name="end_time" value="<?php echo isset($vo['end_time']) ? $vo['end_time'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'%y-%M-{%d+1}'})"   datatype="*" nullmsg="请填写兼职结束日期">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>上班时间：</label>
            <div class="formControls col-xs-3 col-sm-3">
            	<div class="f-l" style="width: 45%;">
            		<input type="text" class="input-text jstime" placeholder="上班时间" name="up_work" value="<?php echo isset($vo['up_work']) ? $vo['up_work'] :  ''; ?>"  datatype="*" nullmsg="请填写上班时间">
            	</div>
            	<div class="f-l text-c lh-30" style="width:10%;">
            		~
            	</div>
                <div class="f-l" style="width: 45%;">
            		<input type="text" class="input-text jstime" placeholder="下班时间" name="off_work" value="<?php echo isset($vo['off_work']) ? $vo['off_work'] :  ''; ?>"  datatype="*" nullmsg="请填写上班时间">
            	</div>
            </div>
            <div class="col-xs-5 col-sm-5 skin-minimal col-md-3" style="line-height:30px;">
            	<div class="check-box">
				    <input type="checkbox" id="checkbox-1">
				    <label for="checkbox-1">上班时间不限，完成任务即可</label>
				</div>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-3"></div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>集合时间：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text jstime" placeholder="集合时间" name="gather_time" value="<?php echo isset($vo['gather_time']) ? $vo['gather_time'] :  ''; ?>"  datatype="*" nullmsg="请填写集合时间">
            </div>
            <div class="col-xs-5 col-sm-5 skin-minimal col-md-3" style="line-height:30px;">
            	<div class="check-box">
				    <input type="checkbox" id="checkbox-1">
				    <label for="checkbox-1">不需要集合时间，完成任务即可</label>
				</div>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-3"></div>
        </div>
		
		<div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>集合日期：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="集合日期" name="gather_date" value="<?php echo isset($vo['gather_date']) ? $vo['gather_date'] :  ''; ?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'%y-%M-{%d+1}'})"   datatype="*" nullmsg="请填写兼职结束日期">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>集合地点：</label>
            <div class="formControls col-xs-3 col-sm-3">
            	<input type="text" class="input-text jsmap" placeholder="集合地点" readonly="readonly" name="gaode" id="gaode" datatype="*" nullmsg="请填写集合地点" value="<?php echo isset($vo['gather_place']) ? $vo['gather_place'] :  ''; ?>"/>
            </div>
            <div class="col-xs-5 col-sm-5 skin-minimal col-md-3" style="line-height:30px;">
            	<div class="check-box">
				    <input type="checkbox" id="checkbox-1">
				    <label for="checkbox-1">不需要集合地点，完成任务即可</label>
				</div>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-3"></div>
        </div>
        <input type="hidden" id="gather_place_lng" name="gather_place_lng" value="<?php echo isset($vo['gather_place_lng']) ? $vo['gather_place_lng'] :  ''; ?>">
        <input type="hidden" id="gather_place_lat" name="gather_place_lat" value="<?php echo isset($vo['gather_place_lat']) ? $vo['gather_place_lat'] :  ''; ?>">
		<input type="hidden" id="work_place_lng" name="work_place_lng" value="<?php echo isset($vo['work_place_lng']) ? $vo['work_place_lng'] :  ''; ?>">
        <input type="hidden" id="work_place_lat" name="work_place_lat" value="<?php echo isset($vo['work_place_lat']) ? $vo['work_place_lat'] :  ''; ?>">
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
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <button type="submit" class="btn btn-primary radius">&nbsp;&nbsp;提交&nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default radius ml-20" onClick="layer_close();">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
    <div  id="gaodemap" class="hide" style="position: absolute;top:0;left:0;background:rgba(0,0,0,.5);width: 100%;height: 100%;overflow: hidden;">
    	<div style="position: absolute;top:0;left:0;right: 0;bottom:0;margin:auto;width:70%;height:600px;background: #fff;">
    		<div id="mapContainer"></div>
			<div id="tip">
			    <input type="text" id="keyword" name="keyword" value="请输入关键字：(选定后搜索)" onfocus='this.value=""'/>
			</div>
			<div id="tip">
			    <button id="button_click" class="Hui-iconfont f-20" value="搜索" style="border: none;background: #fff;">&#xe709;</button>
			</div>
    		<div style="position: absolute;bottom:15px;">
    			<label class="f-l lh-30 pr-10 pl-10" >地址:</label>
    			<div class="f-l">
    				<input type="text" id="address_info" class="input-text" placeholder="地址" value="" readonly="readonly" id="inputmap">
    			</div>
    			<input type="button" name="" id="submitmap1" value="提交地址" class="btn btn-success"/>
    			<input type="button" name="" id="submitmap2" value="提交地址" class="btn btn-success" style="display: none;"/>
    			<input type="button" name="" id="cancelmap" value="取消" class="btn btn-success"/>
    		</div>
    	</div>
    </div>
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
<script type="text/javascript" src="__STATIC__/js/area.js"></script>
<script type="text/javascript" src="__STATIC__/js/map.js"></script>
<script type="text/javascript" src="__LIB__/jquery-clockpicker/jquery-clock-timepicker.min.js"></script>
<style type="text/css">
    #tip {background-color: #ddf;color: #333;border: 1px solid silver;box-shadow: 3px 4px 3px 0px silver;position: absolute;top: 10px;right: 10px;border-radius: 5px;overflow: hidden;line-height: 20px;}
    #tip input[type="text"] {height: 25px;border: 0;padding-left: 5px;width: 280px;border-radius: 3px;outline: none;}
	#mapContainer {position: absolute;top: 0;left: 0;right: 0;bottom: 0;width:100%;height:90%;}
	#tip {background-color: #fff;padding-left: 10px;padding-right: 10px;position: absolute;right: 10px;top: 20px;border-radius: 3px;border: 1px solid #ccc;line-height: 30px;}
</style>
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
        
        $('#button_click').click(function(){
        	$('#inputmap').val($('#keyword').val())
        })
        
        $('#work_place').focus(function(){
        	$('#submitmap1').show();
        	$('#submitmap2').hide();
        	$('#gaodemap').show();
        	$('#cancelmap').click(function(){
        		$('#gaodemap').hide();
        	})
        	$('#submitmap1').click(function(){
        		$('#gaodemap').hide();
        		//layer.msg('提交成功！')
        		var val = $('#inputmap').val()
        		$('#work_place').val(val)
        		$('#work_place').blur()
        	})
        })
        
        $('#gaode').focus(function(){
        	$('#submitmap2').show();
        	$('#submitmap1').hide();
        	$('#gaodemap').show();
        	$('#cancelmap').click(function(){
        		$('#gaodemap').hide();
        	})
        	$('#submitmap2').click(function(){
        		$('#gaodemap').hide();
        		//layer.msg('提交成功！')
        		var val = $('#inputmap').val()
        		$('#gaode').val(val)
        		$('#gaode').blur()
        	})
        	
        })
            var salaryUnit = $('#salaryunit').val();
            var salaryMoney = $('#salarymoney').val();
            var salaryH = $('#salaryh').val();
            var salaryMan = $('#salaryman').val();
            var salaryDay = $('#salaryday').val();
            var salaryAccount = "<?php echo $money; ?>";

            var secure = $('#secure').text(salaryMan*salaryDay*2);//保险金额

            if(salaryUnit=="元/时"){
                $('#salary').text(salaryMoney*salaryH*salaryMan*salaryDay);
                //console.log($('#salary').text());
                $('#needpay').text(($('#salary').text() > salaryAccount - $('#secure').text() )? (parseInt($('#salary').text()) + parseInt($('#secure').text()) - salaryAccount ) : 0);
                $('#jswork').text('工作天数（天）：')
                $('#salaryday').attr('placeholder','工作天数')
            }else if(salaryUnit=="元/天"){
                $('#salary').text(salaryMoney*salaryMan*salaryDay);
                //console.log($('#salary').text());
                $('#needpay').text(($('#salary').text() > salaryAccount - $('#secure').text() )? (parseInt($('#salary').text()) + parseInt($('#secure').text()) - salaryAccount ) : 0);
                $('#jswork').text('工作天数（天）：')
                $('#salaryday').attr('placeholder','工作天数')
            }else if(salaryUnit=="元/月"){
                $('#salary').text(salaryMoney*salaryMan*salaryDay);
                //console.log($('#salary').text());
                $('#needpay').text(($('#salary').text() > salaryAccount - $('#secure').text() )? (parseInt($('#salary').text()) + parseInt($('#secure').text()) - salaryAccount ) : 0);
                $('#jswork').text('工作月数（月）：');
                $('#salaryday').attr('placeholder','工作月数')
            }

            $('#all_salary').val($('#needpay').text());

        $('#count').on('change keyup',function(){
        	 salaryUnit = $('#salaryunit').val();//薪资单位
        	 salaryMoney = $('#salarymoney').val();//薪资
        	 salaryH = $('#salaryh').val();//时长
	         salaryMan = $('#salaryman').val();//招聘人数
	         salaryDay = $('#salaryday').val();//招聘天数
             salaryAccount = "<?php echo $money; ?>";

             secure = $('#secure').text(salaryMan*salaryDay*2);//保险金额
            
        	if(salaryUnit=="元/时"){
	        	$('#salary').text(salaryMoney*salaryH*salaryMan*salaryDay);
                //console.log($('#salary').text());
                $('#needpay').text(($('#salary').text() > (salaryAccount - $('#secure').text()))? (parseInt($('#salary').text()) + parseInt($('#secure').text()) - salaryAccount ) : 0);
	        	$('#jswork').text('工作天数（天）：')
	        	$('#salaryday').attr('placeholder','工作天数')
        	}else if(salaryUnit=="元/天"){
        		$('#salary').text(salaryMoney*salaryMan*salaryDay);
                //console.log($('#salary').text());
                $('#needpay').text(($('#salary').text() > (salaryAccount - $('#secure').text()))? (parseInt($('#salary').text()) + parseInt($('#secure').text()) - salaryAccount ) : 0);
        		$('#jswork').text('工作天数（天）：')
	        	$('#salaryday').attr('placeholder','工作天数')
        	}else if(salaryUnit=="元/月"){
        		$('#salary').text(salaryMoney*salaryMan*salaryDay);
                //console.log($('#salary').text());
                $('#needpay').text(($('#salary').text() > (salaryAccount - $('#secure').text()))? (parseInt($('#salary').text()) + parseInt($('#secure').text()) - salaryAccount ) : 0);
        		$('#jswork').text('工作月数（月）：');
        		$('#salaryday').attr('placeholder','工作月数')
        	}


            $('#all_salary').val($('#needpay').text());
        })
        
        
		
		$('#salary_storage').click(function(){
            var companyName = $('#companyName').val();
            var jianzhitype = $('#jianzhitype').val();
             salaryUnit = $('#salaryunit').val();//薪资单位
             salaryMoney = $('#salarymoney').val();//薪资
             salaryH = $('#salaryh').val();//时长
             salaryMan = $('#salaryman').val();//招聘人数
             salaryDay = $('#salaryday').val();//招聘天数
            if(companyName == false){
                layer.msg('请选择公司');
                return false;
            }
            if(jianzhitype == false){
                layer.msg('请选择兼职类别');
                return false;
            }
            var needpay = $('#needpay').text();
            var secure = $('#secure').text();
            /*$('#needpay1').val() = needpay;
            $('#companyName1').val() = companyName;
            $('#jianzhitype1').val() = jianzhitype;
            $('#salaryUnit1').val() = salaryUnit;
            $('#salaryMoney1').val() = salaryMoney;
            $('#salaryH1').val() = salaryH;
            $('#salaryMan1').val() = salaryMan;
            $('#salaryDay1').val() = salaryDay;*/
            console.log(needpay);
            window.location.href = "<?php echo url('SchoolManagement/pay'); ?>?needpay="+needpay+"&companyName="+companyName+"&jianzhitype="+jianzhitype+"&salaryUnit="+salaryUnit+"&salaryMoney="+salaryMoney+"&salaryH="+salaryH+"&salaryMan="+salaryMan+"&salaryDay="+salaryDay+"&secure="+secure;
        });
		        
    var lng_addr = '';
    var lat_addr = '';  
    var windowsArr = [];
    var marker = [];
    
    var map = new AMap.Map("mapContainer", {
            resizeEnable: true,
            expandZoomRange:true,
            zoom: 20,//地图显示的缩放级别
            keyboardEnable: false
    });
    AMap.plugin(['AMap.Autocomplete','AMap.Geocoder'],function(){

      var autoOptions = {
        city: "", //城市，默认全国
        input: "keyword"//使用联想输入的input的id
      };
      autocomplete= new AMap.Autocomplete(autoOptions);
        
      document.getElementById('button_click').onclick=function(){
        var keyword = document.getElementById('keyword').value;
        var geocoder = new AMap.Geocoder({
            city: ""//城市，默认：“全国”
        });
        
      var marker = new AMap.Marker({
            map:map,
            bubble:true
        });
      if(keyword != ""){
        geocoder.getLocation(keyword,function(status,result){
              if(status=='complete'&&result.geocodes.length){
                marker.setPosition(result.geocodes[0].location);
                lng_addr = result.geocodes[0].location.lng;
                lat_addr = result.geocodes[0].location.lat;
                console.log(lng_addr);
                console.log(lat_addr);
                map.setCenter(marker.getPosition())
              }else{
                layer.msg('获取地址信息失败');
              }
            });
      }
        document.getElementById('address_info').value = keyword;
    }
      
    });
     AMap.service(["AMap.PlaceSearch"], function() {
        var placeSearch = new AMap.PlaceSearch({ //构造地点查询类
            pageSize: 5,
            pageIndex: 1,
            city: "", //城市
            map: map//,
            //panel: "panel"
        });
        //关键字查询
        placeSearch.search(keyword, function(status, result) {
        });
    });
    

       
       
       
    if (typeof map !== 'undefined') {
		map.on('complete', function() {
			if (location.href.indexOf('guide=1') !== -1) {
				map.setStatus({
					scrollWheel: false
				});
				if (location.href.indexOf('litebar=0') === -1) {
					map.plugin(["AMap.ToolBar"], function() {
						var options = {
							liteStyle: true
						}
						if (location.href.indexOf('litebar=1') !== -1) {
							options.position = 'LT';
							options.offset = new AMap.Pixel(10, 40);
						} else if (location.href.indexOf('litebar=2') !== -1) {
							options.position = 'RT';
							options.offset = new AMap.Pixel(20, 40);
						} else if (location.href.indexOf('litebar=3') !== -1) {
							options.position = 'LB';
						} else if (location.href.indexOf('litebar=4') !== -1) {
							options.position = 'RB';
						}
						map.addControl(new AMap.ToolBar(options));
					});
				}
			}
		});
	} 
       
      $('#work_place').focus(function(){
            $('#submitmap1').show();
            $('#submitmap2').hide();
            $('#gaodemap').show();
            $('#cancelmap').click(function(){
                $('#gaodemap').hide();
            })
            $('#submitmap1').click(function(){
                $('#gaodemap').hide();
                layer.msg('提交成功！')
                var val = $('#address_info').val()
                $('#work_place').val(val)
                $('#work_place_lng').val(lng_addr);
                $('#work_place_lat').val(lat_addr);
            })
        })
        
        $('#gaode').focus(function(){
            $('#submitmap2').show();
            $('#submitmap1').hide();
            $('#gaodemap').show();
            $('#cancelmap').click(function(){
                $('#gaodemap').hide();
            })
            $('#submitmap2').click(function(){
                $('#gaodemap').hide();
                layer.msg('提交成功！')
                var val = $('#address_info').val()
                $('#gaode').val(val)
                $('#gather_place_lng').val(lng_addr);
                $('#gather_place_lat').val(lat_addr);
            })
            
        })
        
    
       
       
       
      
        
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
    
    
	$('.jstime').clockTimePicker({});
	
	
	$('#addwork').click(function(){
        	layer.prompt({title:'请输入需要添加的企业名称'},function(val,index){
			    $.ajax({
				    url:'<?php echo url("CompanyReleasePosition/wadd"); ?>',
					type:'post',
					data:{work_unit:val},
					success:function(data){
					     
						if(data==1){
							layer.msg('添加成功');
							layer.close(index);
							location.reload();
						}else{
						    layer.msg('添加失败');
				       
						}
					    
					
					}
				

				
				})
			
        		
        	})
        })
	


</script>


</body>
</html>