<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:70:"C:\wwwroot\AMS\public/../application/admin\view\art_tutoring\edit.html";i:1522206767;s:66:"C:\wwwroot\AMS\public/../application/admin\view\template\base.html";i:1488874432;s:77:"C:\wwwroot\AMS\public/../application/admin\view\template\javascript_vars.html";i:1488874432;}*/ ?>
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
            <style type="text/css">
                #province select{margin-left:0px; width:326px;height: 25px}
            </style>
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>省市：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <div id="province"></div>
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>校区等级：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="校区等级" name="educationLevel" value="<?php echo isset($vo['educationLevel']) ? $vo['educationLevel'] :  ''; ?>"  datatype="*" nullmsg="请填写校区等级">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
		
		<div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>校区性质：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="校区性质" name="schoolNature" value="<?php echo isset($vo['schoolNature']) ? $vo['schoolNature'] :  ''; ?>"  datatype="*" nullmsg="请填写校区等级">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>校区名称：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="校区名称" name="schoolName" value="<?php echo isset($vo['schoolName']) ? $vo['schoolName'] :  ''; ?>"  datatype="*" nullmsg="请填写校区名称">
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">经办人</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="经办人" name="agent" value="<?php echo isset($vo['agent']) ? $vo['agent'] :  ''; ?>" >
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">联系电话：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="联系电话" name="phone" value="<?php echo isset($vo['phone']) ? $vo['phone'] :  ''; ?>" >
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red"></span>学校官网：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="学校官网" name="website" value="<?php echo isset($vo['website']) ? $vo['website'] :  ''; ?>" >
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>
		<div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red"></span>学校账号：</label>
            <div class="formControls col-xs-6 col-sm-6">
                <input type="text" class="input-text" placeholder="学校账号" name="schoolAccount" value="<?php echo isset($vo['schoolAccount']) ? $vo['schoolAccount'] :  ''; ?>" >
            </div>
            <div class="col-xs-3 col-sm-3"></div>
        </div>

        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <button type="submit" id="btn" class="btn btn-primary radius">&nbsp;&nbsp;提交&nbsp;&nbsp;</button>
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
<script type="text/javascript" src="__STATIC__/js/provincesData.js"></script>
<script type="text/javascript">
    /*调用插件*/
    $(function(){
        $("#province").ProvinceCity();
        $('#province select').attr({'datatype':'*','nullmsg':'请选择区域','class':'select'});

        $('#province select').eq(0).attr('name','province');//第一个区域下拉框
        $('#province select').eq(1).attr('name','city');//第二个区域下拉框


    });

    $.fn.ProvinceCity = function(){
        var _self = this;
        //定义2个默认值

        if("<?php echo isset($vo['province']) ? $vo['province'] :  ''; ?>"){
            _self.data("province",["<?php echo isset($vo['province']) ? $vo['province'] :  ''; ?>", "<?php echo isset($vo['province']) ? $vo['province'] :  ''; ?>"]);
            _self.data("city1",["<?php echo isset($vo['city']) ? $vo['city'] :  ''; ?>", "<?php echo isset($vo['city']) ? $vo['city'] :  ''; ?>"]);
        }else{
            _self.data("province",["请选择",""]);
            _self.data("city1",["请选择",""]);
        }

        //插入2个空的下拉框
        _self.append("<select></select>");
        _self.append("<select></select>");
        //分别获取2个下拉框
        var $sel1 = _self.find("select").eq(0);
        var $sel2 = _self.find("select").eq(1);
        //默认省级下拉
        if(_self.data("province")){
            $sel1.append("<option value='"+_self.data("province")[1]+"'>"+_self.data("province")[0]+"</option>");
        }
        $.each( GP , function(index,data){
            $sel1.append("<option value='"+data+"'>"+data+"</option>");
        });
        //默认的1级城市下拉
        if(_self.data("city1")){
            $sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
        }
        //省级联动 控制
        var index1 = "" ;
        $sel1.change(function(){
            //清空其它1个下拉框
            $sel2[0].options.length=0;
            index1 = this.selectedIndex;
            if(index1==0){  //当选择的为 “请选择” 时
                if(_self.data("city1")){
                    $sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
                }
            }else{
                $.each( GT[index1-1] , function(index,data){
                    $sel2.append("<option value='"+data+"'>"+data+"</option>");
                });
            }
        }).change();
        //1级城市联动 控制
        var index2 = "" ;
        return _self;
    };
</script>

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


        //生成编号
        $('#auto').click(function(){
//      var ID="<?php echo $ID; ?>";

            var type = $('#type').val();
            if(type == ''){
                return false;
            }else{
                rand_num = new String(Math.random()).substr(2,4);//随机生成4位数

                schoolID = type+ rand_num;
                $('#schoolID').val(schoolID);
            }

//      document.getElementById("schoolID").value=ID;
            //$("#schoolID").attr("value",ID);//填充内容
            //alert("生成学区编号");
        });


        //生成账号
        $('#auto1').click(function(){
//      var ID="<?php echo $ID; ?>";

            var schoolID = $('#schoolID').val();

            if(!schoolID){
                return false;
            }else{
                schoolAccount = schoolID+GetID();
                $('#schoolAccount').val(schoolAccount);
            }


            //document.getElementById("schoolAccount").value=ID+GetID();
            //$("#schoolAccount").attr("value",ID+GetID());//填充内容
            //alert("生成学区账号"+ID+GetID());
        });


    });


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
        var Num=uuid(4,10);
        return Num;
    }


</script>

</body>
</html>