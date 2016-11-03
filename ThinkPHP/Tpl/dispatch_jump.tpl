<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message {
    width: 1198px;
    height: 300px;
    border: 1px solid #e5e5e5;
    margin-top: 15px;
}
.system-message h1{     
	font-size: 20px;
    font-weight: normal;
    line-height: 50px;
    text-align: center;
    margin-top: 80px;   
    color: #ff4a00;
}
.system-message .jump{ padding-top: 10px;text-align: center;}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em;font-size: 15px;text-align: center;}
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none;}
</style>

</head>
<body>
<include file="Public:s0_head" />	
<include file="Public:toolbar" />

<!--大框-->
<div class="yershop_wrapper">

<include file="Public:s0_logo" />

<!--导航条-->
<div class="menu-wrapper" >
    <div class="nav-all">
       <div class="all_class" id="all-class">
        <h2><span class="grid"><img src="__IMG__/grid.png"></span><span>商品分类</span><b></b></h2>
      </div>
      </div>
    <ul class="menu">
      <think:nav name="nav">
                    	<eq name="nav.pid" value="0">
                       <li>
                            <a href="{$nav.url|get_nav_url}" target="<eq name='nav.target' value='1'>_blank<else/>_self</eq>">{$nav.title}</a>
                      </li>
                        </eq>
                    </think:nav>
    </ul>
  </div>
<!--导航条结束-->


<div class="system-message">
<?php if(isset($message)) {?>
<h1><?php echo($message); ?></h1>
<p class="success">自动为您跳转至首页<!--<?php echo($message); ?>--></p>
<?php }else{?>
<h1><?php echo($error); ?></h1>
<p class="error">自动为您跳转至登陆页面<!--<?php echo($error); ?>--></p>
<?php }?>
<p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>

<include file="Public:footer" />
</div>
<!--大框结束-->
</body>
</html>
