<?php 
session_start();
	include 'config.php';
function checkuser($username,$password) {
	
	$sql = mysql_query("SELECT * FROM radcheck WHERE username='".$username."' AND value='".$password."'");
	
	if (mysql_num_rows($sql) == 0) {
		return false;
	} else {
		return ture;
	}
}
if (isset($_POST['sub'])) {
	if (checkuser($_POST['username'],$_POST['password'])) {
		$_SESSION['username']=$_POST['username'];
		$username = $_POST['username'];
		echo '<script>location.href="main.php";</script>';
	}else{
		echo '<script>alert("用户名或密码不正确！");location.href="login.php";</script>';
	}
}
?> 

<!doctype html>
<html lang="zh_CN">
<head>
	<meta charset="UTF-8">
	<title>Login Page</title>
	<link rel="stylesheet" href="include/css/login.css">
</head>
<body>
	<div class="wrap">
		<h3>LOGIN</h3>
		<form action="login.php" name="login" id="login" method="POST">
			<input type="text" id="username" name="username" value="Username" onFocus="this.value=''">
			<input type="password" id="password" name="password" value="Password" onFocus="this.value=''">
			<input type="submit" id="sub" name="sub" value="Sign up">
		</form>
	</div>
</body>
</html>