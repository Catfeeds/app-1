<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"C:\wwwroot\AMS\public/../application/index\view\shuttle\index2.html";i:1519787863;}*/ ?>
﻿<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>接送人信息录入</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script type="text/javascript" src="__STATIC__/js/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script>

    <script type="text/javascript">
        function check()
        {
            if(form1.relation.value == ''){
                alert('请输入关系！');
                return false;
            }
			if(form1.relation.value == '学生本人'){
                if(form1.re.value == 1){
                    alert('已有学生本人！');
                    return false;
                }
            }
            if(form1.name.value == ''){
                alert('请输入您的姓名！');
                return false;
            }
            if(form1.number.value == ''){
                alert('请输入您的联系方式！');
                return false;
            }
        }
    </script>

</head>

<body>
    <div id="wrap">
        <header class="header  header-fixed">
            <a href="javascript:;" onclick="history.go(-1)" class="back"></a>接送人信息录入
		</header>
        <div class="bb_w_l_jy jy shuttle2-content" >
        	<form name="form1" action="<?php echo \think\Url::build('Shuttle/addLinkman'); ?>" method="post">
        		<div>
        			<label>关系：</label>
					<select name="relation" lay-filter="school" lay-verify="required" style="line-height:0.5rem;border-bottom:1px solid #3c9;width:80%;color:#555">
				   		<option value="" selected="">请选择关系</option>
				        <option value="爷爷">爷爷</option>
						<option value="奶奶">奶奶</option>
						<option value="外公">外公</option>
						<option value="外婆">外婆</option>
						<option value="爸爸">爸爸</option>
						<option value="妈妈">妈妈</option>
						<option value="哥哥">哥哥</option>
						<option value="姐姐">姐姐</option>
						<option value="学生本人">学生本人</option>
						<option value="其他">其他</option>
			     	</select>
        			<!--<input type="text" id="relation" name="relation" placeholder="请输入您与孩子的关系"/>-->
        		</div>
        		<div>
        			<label>姓名：</label>
        			<input type="text" id="name" name="name" placeholder="请输入您的姓名"/>
        		</div>
        		<div>
        			<label>电话：</label>
        			<input type="text" id="number" name="number" placeholder="请输入您的联系方式"/>
        		</div>
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>"/>
                <input type="hidden" name="re" value="<?php echo $re; ?>"/>
                <input type="hidden" name="phone_account" value="<?php echo $phone_account; ?>"/>
				<input type="hidden" name="account" value="<?php echo $account; ?>"/>
        		<button class="shuttle2-bt" onclick="return check()">提交</button>

        	</form>
        </div>
    </div>
</body>
</html>