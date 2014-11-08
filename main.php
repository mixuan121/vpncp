<?php
	session_start();
	//判断是否登录
	if (isset($_SESSION['username'])) {
		$user = $_SESSION['username'];
	} else {
		header('location:login.php');
	}

	include 'config.php';

	/*已经使用*/
	$use = mysql_fetch_array(mysql_query("SELECT SUM(acctinputoctets + acctoutputoctets) FROM radacct WHERE username='".$user."'"));
	/*获取用户总流量*/
	$sum = mysql_fetch_array(mysql_query("SELECT * FROM radcheck WHERE attribute='Monthly-Traffic' AND username='".$user."'"));
	
	$resuse = $use[0]/1048576;
	$usage = (int)$resuse;
	$free =$sum[4] - $resuse;
	$freeres=(int)$free;
	/*计算使用流量的百分比*/
	$used = $usage / $sum[4] * 100;
	$freesum = $freeres / $sum[4] * 100;

	/*读取当前用户密码*/
	$nowpassword =  mysql_fetch_array(mysql_query("SELECT * FROM radcheck WHERE attribute='Cleartext-Password' AND username='".$user."'"));
	//检查用户输入
	$checkinput = '^([a-zA-Z0-9]+$)';
	//检查用户名是否存在
	$check = "SELECT username='".$_POST['newusername']."' FROM radcheck";
	$rows = is_array(mysql_fetch_array($check));


	/*更改用户名*/
	if (isset($_POST['postnewusername'])) { //判断是否点击
		if (empty($_POST['password']) || $_POST['password'] != $nowpassword[4]) { //判断是否为空，和输入的旧密码是否正确
			echo '<script>alert("请让我们知道正确的旧密码")</script>';
		} else if(!preg_match($checkinput, $_POST['newuser'])) {
			echo '<script>alert("用户名存在非法字符！")</script>';
		} else if($rows) {
			echo '<script>alert("用户名已经存在！")</script>';
		} else {
			$setusername = mysql_query("UPDATE radcheck set username='".$_POST['newusername']."' WHERE username='".$user."'"); //重置用户密码
			echo '<script>alert("用户名重置成功！请使用新用户名重新登录。");</script>';
		}
	}

	/*更改密码*/

	if (isset($_POST['postnewpassword'])) { //判断是否点击更改密码按钮
		if (empty($_POST['oldpassword'])) {	//判断旧密码是否为空
			echo '<script>alert("密码不能为空！")</script>';
		}else if($_POST['oldpassword'] != $nowpassword[4]){ //判断旧密码是否和登录密码向符合
			echo '<script>alert("旧密码不符合！")</script>';
		}else if(!preg_match($checkinput, $_POST['newpassword'])) {
			echo '<script>alert("密码存在非法字符！")</script>';
		}else if ($_POST['oldpassword'] == $nowpassword[4] && $_POST['newpassword'] == $_POST['renewpassword'] ) { //判断判断旧密码是否相等和新密码是否相等
			$setpassword = mysql_query("UPDATE radcheck set value='".$_POST['newpassword']."' WHERE attribute='Cleartext-Password' AND username='".$user."'"); //设置新密码
			// var_dump($setpassword);
			echo '<script>alert("密码重置成功！请使用新密码重新登录。");location.href="logout.php"</script>';
		} else {
			echo '<script>alert("请确定你的两次输入的密码是一致的！")</script>';
			}
		}


	/*显示状态*/
	$status = mysql_fetch_array(mysql_query("SELECT * FROM radacct WHERE acctstoptime IS NULL AND username='".$user."'")); //读取用户是否在线
	$online = $status['callingstationid']; //赋值，便于输出

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>VPN Control Panel</title>
	<link rel="stylesheet" href="include/css/style.css">
	<script src="https://lib.sinaapp.com/js/jquery/2.0.3/jquery-2.0.3.min.js"></script>
	<script src="include/js/main.js"></script>
</head>
<body>
	<div class="wrap">
		<div class="left">
			<ul>
				<li class="welcome">欢迎您，<?php echo $user ?></li>
				<li class="menu" id="value"><a href="#self"><i class="iconfont">&#xf007c;</i>我的帐号</a></li>
				<li class="menu" id="tab1"><a href="#username"><i class="iconfont">&#x343f;</i>更改帐号</a></li>
				<li class="menu" id="tab2"><a href="#password"><i class="iconfont">&#xe65e;</i>更改密码</a></li>
				<li class="menu" id="tab3"><a href="#traffic"><i class="iconfont">&#xf0012;</i>查看流量</a></li>
				<li class="menu"><a href="logout.php"><i class="iconfont">&#xe627;</i>退出登录</a></li>
			</ul>
		</div>
		<div class="right">
			<div class="header">
				VPN Control Panel
			</div>

			<!--INDEX CONTENT-->
			<div class="content" >
				<h3>Account Usage</h3>
				<ul class="main">
					<li>总流量：<span><?php echo $sum[4]."MB";?></span><div class="sum"><div class="sum1"></div></div></li>
					<li>已使用：<span><?php echo $usage."MB"; ?></span><div class="use"><div class="use1" style="width:<?php echo $used.'%'; ?>;"></div></div></li>
					<li>剩余量：<span><?php echo $freeres."MB"?></span><div class="free"><div class="free1" style="width:<?php echo $freesum.'%' ?>;"></div></div></li>
				</ul>
				<div style="clear:both;margin-top:30px;"></div>
				<h3>Account Info</h3>
				<ul class="main">
					<li><div class="info">Username : </div><span><?php echo $user ?></span></li>
					<li><div class="info">Password : </div><span><?php echo $nowpassword[4] ?></span></li>
					<li><div class="info">IPSec PSK :</div><span>psk</span> </li>
				</ul>
			</div>
			<!--Change Username-->
			<div class="changeusername">
				<div class="nowuser">
					<span>您当前的帐号为：</span><span><?php echo $user."/".$nowpassword[4]; ?></span>				</div>
				<form id="changeusername" name="changeusername" action="main.php" method="post">
					<p>密码 : </p><input type="password" id="password" name="password" value="请输入密码" onFocus="this.value=''"><br>
					<p>帐号 ：</p><input type="text" id="newusername" name="newusername" value="请输入新帐号" onFocus="this.value=''"><br>
					<input type="submit" id="postnewusername" name="postnewusername" value="提交">
				</form>			
			</div>
			<!-- Change Password -->
			<div class="changepassword">
				<div class="nowuser">
					<span>您当前的帐号为：</span><span><?php echo $user."/".$nowpassword[4]; ?></span>
				</div>	
				<form id="changepassword" name="changepassword" action="main.php" method="post">
					<p>旧密码 : </p><input type="password" id="oldpassword" name="oldpassword" value="请输入旧密码" onFocus="this.value=''"><br>
					<p>新密码 : </p><input type="password" id="newpassword" name="newpassword" value="请输入新密码" onFocus="this.value=''"><br>
					<p>新密码 : </p><input type="password" id="renewpassword" name="renewpassword" value="请确认新密码" onFocus="this.value=''"><br>
					<input type="submit" id="postnewpassword" name="postnewpassword" value="提交">
				</form>		
			</div>
			<!-- Traffic -->
			<div class="traffic">
			<div class="nowuser">
				<span>您当前的帐号状态：</span><span><?php if($online == "") {echo "尚无登录记录。";}else{echo "最后一次在线IP".$online;}?></span>
			</div>
				<div class="pic">
				<ul class="main">
					<li>总流量：<span><?php echo $sum[4]."MB";?></span><div class="sum"><div class="sum1"></div></div></li>
					<li>已使用：<span><?php echo $usage."MB"; ?></span><div class="use"><div class="use1" style="width:<?php echo $used.'%'; ?>;"></div></div></li>
					<li>剩余量：<span><?php echo $freeres."MB"?></span><div class="free"><div class="free1" style="width:<?php echo $freesum.'%' ?>;"></div></div></li>
				</ul>
				</div>		
			</div>
		</div>
	</div>
</body>
</html>