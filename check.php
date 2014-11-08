<?php
session_start();
//判断用户
if (isset($_SESSION['username'])) {
	header("location:main.php");
}else {
	header("location:login.php");
}
?>