<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"C:\wwwroot\AMS\public/../application/index\view\starlist\index.html";i:1514959360;}*/ ?>
﻿<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <title>明星榜</title>
    <link rel="stylesheet" href="__STATIC__/css/reset.css" />
    <link rel="stylesheet" href="__STATIC__/css/common.css">
    <script type="text/javascript" src="__STATIC__/js/jquerys-1.7.2.min.js"></script>
    <script type="text/javascript" src="__STATIC__/js/rem.js"></script>
</head>

<body>
    <div id="wrap">
        <header class="header header-fixed">
            <a href="javascript:;" onclick="history.go(-1)" class="back"></a>明星榜
        </header>
         <div class="star_b">
             <h3><i>班级</i>明星列表</h3>
             <span class="fr"style="margin-top: -0.2rem;font-size: 0.14rem;">
             	<?php echo $date; ?>
             </span>
            <?php foreach($data as $k=>$val) {?>
            	<!--1-->
             <div class="star_l">
                 <div class="star_w clearfix">
                 	<div class="star-headp">
                 		<div class="fl server-head server-head1">
		            		<img src="<?php echo $val['image']; ?>">
		            	</div>
		            	<p class="fl"><?php echo $val['student']; ?></p>
                 	</div>
                     <ul class="fr">
                        <?php if($val['sjstar'] != ''): ?>
                         <li>
                             <img src="__STATIC__/img/s.png">
                             <span>守纪星</span>
                         </li>
                         <?php else: ?>
                         <li>
                             <img src="__STATIC__/img/s-g.png">
                             <span>守纪星</span>
                         </li>
                         <?php endif; if($val['tjstar'] != ''): ?>
                         <li>
                             <img src="__STATIC__/img/s.png">
                             <span>听讲星</span>
                         </li>
                         <?php else: ?>
                         <li>
                             <img src="__STATIC__/img/s-g.png">
                             <span>听讲星</span>
                         </li>
                         <?php endif; if($val['sxstar'] != ''): ?>
                         <li>
                             <img src="__STATIC__/img/s.png">
                             <span>书写星</span>
                         </li>
                         <?php else: ?>
                         <li>
                             <img src="__STATIC__/img/s-g.png">
                             <span>书写星</span>
                         </li>
                         <?php endif; if($val['cystar'] != ''): ?>
                         <li>
                             <img src="__STATIC__/img/s.png">
                             <span>才艺星</span>
                         </li>
                         <?php else: ?>
                         <li>
                             <img src="__STATIC__/img/s-g.png">
                             <span>才艺星</span>
                         </li>
                         <?php endif; if($val['gastar'] != ''): ?>
                         <li>
                             <img src="__STATIC__/img/s.png">
                             <span>关爱星</span>
                         </li>
                         <?php else: ?>
                         <li>
                             <img src="__STATIC__/img/s-g.png">
                             <span>关爱星</span>
                         </li>
                         <?php endif; if($val['wsstar'] != ''): ?>
                         <li>
                             <img src="__STATIC__/img/s.png">
                             <span>卫生星</span>
                         </li>
                         <?php else: ?>
                         <li>
                             <img src="__STATIC__/img/s-g.png">
                             <span>卫生星</span>
                         </li>
                         <?php endif; ?>


                          
                     </ul>
                 </div>
                 <div class="desc"><?php echo $val['content']; ?></div>
             </div>
             
             <?php } ?>
         </div>
        
      
         
    </div>
</body>

</html>