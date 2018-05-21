<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"C:\wwwroot\AMS\public/../application/admin\view\student_management\edit.html";i:1519803799;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>学校名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="schoolName" id="schoolName" class="select" datatype="*" nullmsg="请选择学校名称">
                        <option value="<?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  ''; ?>" selected><?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  '请选择学区名称'; ?></option>
						<?php if(is_array($data_s) || $data_s instanceof \think\Collection || $data_s instanceof \think\Paginator): $i = 0; $__LIST__ = $data_s;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
							<option value="<?php echo $vo1['schoolName']; ?>"><?php echo $vo1['schoolName']; ?></option>
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
                    <select name="className" class="select" datatype="*" nullmsg="请选择班级名称">
						<option value ="<?php echo isset($vo['className']) ? $vo['className'] :  ''; ?>" selected><?php echo isset($vo['className']) ? $vo['className'] :  '请选择班级名称'; ?></option>
                        <?php if(is_array($data_c) || $data_c instanceof \think\Collection || $data_c instanceof \think\Paginator): $i = 0; $__LIST__ = $data_c;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
							<option value="<?php echo $vo1['class']; ?><?php echo $vo1['className']; ?>"><?php echo $vo1['class']; ?><?php echo $vo1['className']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
		<div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>学员账号：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="学员账号" id="account" name="account" value="<?php echo isset($vo['account']) ? $vo['account'] :  ''; ?>" readonly  datatype="*" nullmsg="请填写学员账号">
            </div>
            <?php if(!isset($vo['account'])): ?><div class="col-xs-3 col-sm-3"><button type="button" id="auto" class="btn btn-primary radius">&nbsp;&nbsp;自动生成&nbsp;&nbsp;</button></div><?php endif; if(isset($vo['account'])): ?><div class="col-xs-3 col-sm-3"><span class="label label-warning radius">不可更改</span></div><?php endif; ?>
        </div>
		<?php if(!isset($vo['passWord'])): ?>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>密码：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="password" class="input-text" placeholder="密码" name="passWord" value="<?php echo isset($vo['passWord']) ? $vo['passWord'] :  ''; ?>"  datatype="/^[\w\W]{6,32}$/" nullmsg="请填写密码">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
		<?php endif; ?>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>姓名：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="姓名" name="name" value="<?php echo isset($vo['name']) ? $vo['name'] :  ''; ?>"  datatype="*" nullmsg="请填写姓名">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>性别：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div class="select-box">
                    <select name="sex" class="select" datatype="*" nullmsg="请选择性别">
                        <option value="男">男</option>
                        <option value="女">女</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

		<div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>出生日期：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text Wdate" placeholder="出生日期" name="birthDate" value="<?php echo isset($vo['birthDate']) ? $vo['birthDate'] :  ''; ?>"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"   datatype="*" nullmsg="请选择出生日期">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">身高：</label>
			<div class="formControls col-xs-6 col-sm-6">
				<input type="text" class="input-text" placeholder="身高" name="height" value="<?php echo isset($vo['height']) ? $vo['height'] :  ''; ?>">
			</div>
			<div class="col-xs-3 col-sm-3"></div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">体重：</label>
			<div class="formControls col-xs-6 col-sm-6">
				<input type="text" class="input-text" placeholder="体重" name="weight" value="<?php echo isset($vo['weight']) ? $vo['weight'] :  ''; ?>">
			</div>
			<div class="col-xs-3 col-sm-3"></div>
		</div>
        
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系电话：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="联系电话" name="contact" value="<?php echo isset($vo['contact']) ? $vo['contact'] :  ''; ?>"  datatype="/(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}/" nullmsg="请填写联系电话">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

		
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
			<div class="formControls col-xs-6 col-sm-6">
				<table class="table-form table table-border table-bordered table-hover table-bg mt-20 skin-minimal">
					<thead>
					<tr class="text-c">
						<th width="50" title="删除后不可恢复，谨慎操作">
							操作<br>
							<a href="javascript:;" class="label label-success radius op-add" data-type="form" data-header="1">增加联系人</a>
						</th>
						<th width="50" title="关系">关系</th>
						<th width="50"  title="姓名">姓名</th>
						<th width="50" title="联系方式">联系电话</th>

					</tr>
					</thead>
					<tbody id="tbody-form">
					<?php if($count == 0): ?>
						<tr>
							<td title="删除后不可恢复，谨慎操作">
								<a href="javascript:;" class="label label-success radius mr-10 op-add" data-type="form">增加联系人</a>
								<a href="javascript:;" class="label label-danger radius op-delete">删除</a>
							</td>
							<input type="hidden" name="msg[0][id]" value="<?php echo isset($linkman1['id']) ? $linkman1['id'] :  ''; ?>">
							<input type="hidden" name="" value="<?php echo isset($linkman1['id']) ? $linkman1['id'] :  ''; ?>" class="del">
							<td title="关系">
								<select name="msg[0][relation]" class="select" datatype="*" nullmsg="请选择关系">
									<option value="爷爷">爷爷</option>
									<option value="奶奶">奶奶</option>
									<option value="外公">外公</option>
									<option value="外婆">外婆</option>
									<option value="爸爸">爸爸</option>
									<option value="妈妈">妈妈</option>
									<option value="舅舅">舅舅</option>
									<option value="姨">姨</option>
									<option value="哥哥">哥哥</option>
									<option value="姐姐">姐姐</option>
									<option value="学生本人">学生本人</option>
									<option value="其他">其他</option>
								</select>
							</td>
							<td title="姓名">
								<input type="text" class="input-text form-name" placeholder="姓名" name="msg[0][name]"  datatype="*" nullmsg="请填写姓名">
							</td>
							<td title="联系电话">
								<input type="text" class="input-text form-name" placeholder="联系电话" name="msg[0][number]"  datatype="*" nullmsg="请填写联系电话">
							</td>
							
						</tr>
					<?php else: if(is_array($linkman) || $linkman instanceof \think\Collection || $linkman instanceof \think\Paginator): $i = 0; $__LIST__ = $linkman;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$linkman1): $mod = ($i % 2 );++$i;?>
							<tr>
								<td title="删除后不可恢复，谨慎操作">
									<a href="javascript:;" class="label label-success radius mr-10 op-add" data-type="form">增加联系人</a>
									<a href="javascript:;" class="label label-danger radius op-delete">删除</a>
								</td>
								<input type="hidden" name="msg[<?php echo $linkman1['id']; ?>][id]" value="<?php echo isset($linkman1['id']) ? $linkman1['id'] :  ''; ?>">
								<input type="hidden" name="" value="<?php echo isset($linkman1['id']) ? $linkman1['id'] :  ''; ?>" class="del">
								<td title="关系">
									<select name="msg[<?php echo $linkman1['id']; ?>][relation]" class="select" datatype="*" nullmsg="请选择关系">
										<option value ="<?php echo $linkman1['relation']; ?>"><?php echo $linkman1['relation']; ?></option>
										<option value="爷爷">爷爷</option>
										<option value="奶奶">奶奶</option>
										<option value="外公">外公</option>
										<option value="外婆">外婆</option>
										<option value="爸爸">爸爸</option>
										<option value="妈妈">妈妈</option>
										<option value="舅舅">舅舅</option>
										<option value="姨">姨</option>
										<option value="哥哥">哥哥</option>
										<option value="姐姐">姐姐</option>
										<option value="学生本人">学生本人</option>
										<option value="其他">其他</option>
									</select>
								</td>
								<td title="姓名">
									<input type="text" class="input-text form-name" placeholder="姓名" name="msg[<?php echo $linkman1['id']; ?>][name]" value="<?php echo isset($linkman1['name']) ? $linkman1['name'] :  ''; ?>" datatype="*" nullmsg="请填写姓名">
								</td>
								<td title="联系电话">
									<input type="text" class="input-text form-name" placeholder="联系电话" name="msg[<?php echo $linkman1['id']; ?>][number]" value="<?php echo isset($linkman1['number']) ? $linkman1['number'] :  ''; ?>" datatype="*" nullmsg="请填写联系电话">
								</td>
								
							</tr>
						<?php endforeach; endif; else: echo "" ;endif; endif; ?>
					

					</tbody>
				</table>
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

<script type="text/javascript" src="__LIB__/Validform/5.3.2/Validform.min.js"></script>
<script type="text/javascript" src="__LIB__/My97DatePicker/WdatePicker.js"></script>
<script>
	$(function () {
        //$("[name='schoolName']").find("[value='<?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  ''; ?>']").attr("selected", true);
        //$("[name='className']").find("[value='<?php echo isset($vo['className']) ? $vo['className'] :  ''; ?>']").attr("selected", true);
        $("[name='sex']").find("[value='<?php echo isset($vo['sex']) ? $vo['sex'] :  '男'; ?>']").attr("selected", true);
        $("[name='relation']").find("[value='<?php echo isset($vo['relation']) ? $vo['relation'] :  '爸爸'; ?>']").attr("selected", true);
		
		// 获取模板
        var template = {}, index = {};
        //template['form'] = $("#tbody-form").html();
		template['form'] = '\
<tr>\
							<td title="删除后不可恢复，谨慎操作">\
								<a href="javascript:;" class="label label-success radius mr-10 op-add" data-type="form">增加联系人</a>\
								<a href="javascript:;" class="label label-danger radius op-delete">删除</a>\
							</td>\
							<input type="hidden" name="msg[0][id]" value="0">\
							<td title="关系">\
								<select name="msg[0][relation]" class="select" datatype="*" nullmsg="请选择关系">\
									<option value="爷爷">爷爷</option>\
									<option value="奶奶">奶奶</option>\
									<option value="外公">外公</option>\
									<option value="外婆">外婆</option>\
									<option value="爸爸">爸爸</option>\
									<option value="妈妈">妈妈</option>\
									<option value="舅舅">舅舅</option>\
									<option value="姨">姨</option>\
									<option value="哥哥">哥哥</option>\
									<option value="姐姐">姐姐</option>\
									<option value="学生本人">学生本人</option>\
									<option value="其他">其他</option>\
								</select>\
							</td>\
							<td title="姓名">\
								<input type="text" class="input-text form-name" placeholder="姓名" name="msg[0][name]"  datatype="*" nullmsg="请填写姓名">\
							</td>\
							<td title="联系电话">\
								<input type="text" class="input-text form-name" placeholder="联系电话" name="msg[0][number]"  datatype="*" nullmsg="请填写联系电话">\
							</td>\
							\
						</tr>\
';
        template['field'] = $("#tbody-field").html();
        index['form'] = <?php echo isset($maxid) ? $maxid :  0; ?>;
        index['field'] = <?php echo isset($maxid) ? $maxid :  0; ?>;

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
		
		function form_init() {
            $('.skin-minimal input').iCheck('destroy');
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
        }

        // 一定要在获取模板后在初始化，否则会出现错误
        form_init();
        // 增加一栏
        $(document).on("click", ".op-add", function () {
            var type = $(this).attr("data-type");
            var html = template[type].replace(/(\[\d+\])/g, '[' + (++index[type]) + ']');
            // 表头菜单，追加到第一个
            if ($(this)[0].hasAttribute('data-header')) {
                $("#tbody-" + type).prepend(html);
            } else {
                $(this).closest('tr').after(html);
            }
            form_init();
        }).on("click", ".op-delete", function () {
            // 删除一栏
            $(this).closest("tr").fadeOut(undefined, undefined, function () {
                // 使用回调函数，强行移除该DOM
                var id = $(this).closest("tr").find(".del").val();
				if(id!=''){
					$.ajax({
						url:'<?php echo \think\Url::build("StudentManagement/dellinkman"); ?>',
						type:'post',
						data:{'id':id},
						success:function(d){
							if(d==1){ 
								$(this).remove();
							}  

						}
					})
				}else{
				    $(this).remove();
				}
                
            });
            form_init();
        });

        <?php if(isset($table_info)): ?>
            var tableInfo = <?php echo $table_info; ?>;
            var objForm = $("#tbody-form");
            objForm.find('tr').remove();
            for (var i = 0; i < tableInfo.fields.length; i++) {
                objForm.append(template['form'].replace(/(\[\d+\])/g, '[' + (++index['form']) + ']'));
                var objCurrent = objForm.find('tr:last');
                objCurrent.find('.form-name').val(tableInfo.fields[i]);
            }
        <?php endif; ?>
		
    })
	
    
	function uuid(len, radix) {
		var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
		var uuid = [], i;
		radix = radix || chars.length;
	 
		if (len) {
		  // Compact form
		  for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random()*radix];
		} else {
		  // rfc4122, version 4 form
		  var r;
	 
		  // rfc4122 requires these characters
		  uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
		  uuid[14] = '4';
	 
		  // Fill in random data.  At i==19 set the high bits of clock sequence as
		  // per rfc4122, sec. 4.1.5
		  for (i = 0; i < 36; i++) {
			if (!uuid[i]) {
			  r = 0 | Math.random()*16;
			  uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
			}
		  }
		}
	 
		return uuid.join('');
	}
	function GetID() 
	{ 
	/*
		var Num="";
		var ID = "<?php echo isset($id) ? $id :  ''; ?>";
		for(var i=7;i>ID.length;i--)
		{
			Num = Num + "0";
		}
		Num = Num + ID;
		return Num;
	*/
		var Num=uuid(5,10);
		return Num;
	} 
	$('#auto').click(function(){
		var obj = document.getElementById("schoolName");
		var index = obj.selectedIndex;
		if(index>0)
		{
			var data = <?php echo json_encode($data_s);?>;
			
			document.getElementById("account").value=data[index-1]['schoolID']+GetID();
			//alert("测试"+data[index-1]['schoolID']);
		}
		//document.getElementById("account").value=ID;
		//$("#schoolID").attr("value",ID);//填充内容
		//alert("生成学区编号");
	})
</script>

</body>
</html>