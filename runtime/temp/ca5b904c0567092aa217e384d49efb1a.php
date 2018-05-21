<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\wwwroot\AMS\public/../application/index\view\leave\index.html";i:1522200568;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>我要请假</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <link rel="stylesheet" href="__STATIC__/layui/css/layui.css" media="all">
    <script type="text/javascript" src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script>
	<script src="__STATIC__/js/jsbridge.js"></script>
</head>
<body>
<div id="wrap">
    <header class="header">
        <a href="javascript:;" onclick="history.go(-1)" class="back"></a>我要请假
    </header>
    <form class="form form-horizontal" id="form" method="post" >
        <div class="star_b zb_b jy" style="background: #fff;font-size: 0.16rem">
        	<div class="layui-form-item">
			    <label style="line-height:2">起始时间</label>
			    <input type="text" class="layui-input" id="test1" name="start_time" readonly>
			</div>
        	<div class="layui-form-item">
			    <label style="line-height:2">结束时间</label>
			    <input type="text" class="layui-input" id="test2" name="end_time" readonly>
			</div>
			<input type="hidden" name="account" value="<?php echo $account; ?>">
			<input type="hidden" name="type" value="<?php echo $type; ?>">
            <textarea name="" placeholder="请输入请假事由" class="layui-textarea" id="content"></textarea>
            <button  type="submit" class="layui-btn" style="background: #33cc99;margin-top: 15px;font-size: 0.16rem"  id="btn_1">提交</button>
        </div>
    </form>
</div>
</body>
<script src="__STATIC__/layui/layui.js"></script>
<script>
$(function(){
            $('#form').submit(function(){
                //起止时间
                var start_time = $(" input[ name='start_time' ] ").val();
                //截至时间
                var end_time = $(" input[ name='end_time' ] ").val();
				var content = $("#content").val();
				var account = $(" input[ name='account' ] ").val();
				var type = $(" input[ name='type' ] ").val();
                if(start_time ==""){
                    alert('请输入起止日期！');
                    return false;
                }
                if(end_time ==""){
                    alert('请输入截止日期！');
                    return false;
                }
                if(content == ''){
                    alert('请输入内容！');
                    return false;
                }
				
              //  var bool = false;
                $.ajax({
                    type:"POST", //post提交方式
                    url:"<?php echo url('Index/leave/getInfo'); ?>",
                    data:{start_time:start_time,end_time:end_time,content:content,type:type,account:account},
                    async:false,
                    success:function(data)
                    {  
					//alert(data);
                       if(data ==1){
                           alert('提交成功！');
                       //   bool = true;
                       }else{
                           alert('提交失败！');
                        //   bool = false;
                       }
                    }
                });
              // return bool; //若提交成功，则提交表单


            })
        });

layui.use('laydate', function(){
  var laydate = layui.laydate;
  laydate.render({
    elem: '#test1',
	min:'today'
  });
  var laydate2 = layui.laydate;
  laydate2.render({
    elem: '#test2',
	min:'today'
  });
});

  
       $(function () {
        // 首先调用JSBridge初始化代码，完成后再设置其他
        initJsBridge(function () {
			$(".back").click(function(){
				window.WebViewJavascriptBridge.callHandler('back','1', function (response) {
				
					 //showResponse(response);
				});
			
			})
		
	    })
	
	})

</script>
</html>