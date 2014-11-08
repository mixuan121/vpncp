$(document).ready(function() {
	
	// 控制用户界面的模块显示
	$(".changeusername,.changepassword,.traffic").hide();
	$("#tab1").click(function() {
		$(".content,.changepassword,.traffic").hide();
		$(".changeusername").show();
	});	
	$("#tab2").click(function() {
		$(".content,.changeusername,.traffic").hide();
		$(".changepassword").show();
	});
	$("#tab3").click(function() {
		$(".content,.changeusername,.changepassword").hide();
		$(".traffic").show();
	});
	$("#value").click(function() {
		$(".traffic,.changeusername,.changepassword").hide();
		$(".content").show();
	});
});