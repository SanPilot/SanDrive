<?php
function sendForgotEmail($email) {
	include "../assets/connect.php";
	if(!$conerror) {
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$query = mysqli_query($con, "SELECT password FROM users WHERE email='$email'");
		if($query != false) {
			$result_set = mysqli_fetch_array($query);
			if(mysqli_num_rows($query) > 1) {
				$to = $result_set['password'];
				return "success";
			} else {
				return "There's a problem with your account";
			}
		} else {
			return "An error has occurred";
		}
		mysqli_close($con);
	} else {
		return "An error has occurred";
	}
}
if(isset($_POST['email']) && isset($_POST['src']) && $_POST['src'] == "ajax") {
	echo sendForgotMail($_POST['email']);
	exit();
}
	
if(isset($_GET['email'])) {
	$result = sendForgotMail($_POST['email']);
	if($result == success) {
		$confirm_text = true;
		$error = "If the email ".htmlspecialchars($_POST['email'])." exists, your password was sent there";
	} else {
		$error = $result;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>
Forgot Your Password?
</title>
<link rel="stylesheet" href="/assets/styles.css">
<style type="text/css">
body,html {width: 100%; margin: 0px}
.content {margin: 5px 0px 0px 13px;}
.box {width: 630px; margin: 10% auto;}
form {display: inline-block;}
#error {height: <?php if(isset($error)) {echo "initial";} else {echo "0px";} ?>;<?php if($confirm_text) {echo " color: #3498db;";} ?>;}
</style>
</head>
<body class="grey">
<div class="box" style="font-family: Open Sans; padding: 20px">
<img src="/assets/images/logo_main_212.png" alt="San Drive" width="212"><br>
<div class="content">Forgot Your Password?
Enter your email here: <form action="/forgot/" method="post" onsubmit="exec(); return false;"><input type="text" name="email" id="field" style="float: left; margin: 0px"><input type="submit" class="button" value="Next &#8594;" style="margin: 0px;" id="button"></form>
<div id="error"><?php if(isset($error)) {echo $error;} ?></div>
</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
var field = document.getElementById("field");
var button = document.getElementById("button");
var error = document.getElementById("error");
function errorIn(msg) {
	$(document).ready(
		function () {
			if($("#error").html() !== msg) {
				var errorContent = $("#error").html();
				$("#error").html(msg);
				if(errorContent == "") {
					$("#error").animate({height: "20px"},150);
				}
			}
		}
	);
}
function exec() {
	field.setAttribute("disabled","true");
	button.setAttribute("disabled","true");
	if(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(field.value)) {
		$("#error").animate({height: "0px"},150);
		$("#error").html("");
		var xmlhttp;
		if (window.XMLHttpRequest)
		{
			xmlhttp=new XMLHttpRequest();
		} else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var response = xmlhttp.reponseText;
				if(response == "success") {
					console.log("Success");
				} else {
					console.error("The response was: "+response);
				}
			}
		}
		xmlhttp.open("POST","/forgot/",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("email="+field.value+"&src=ajax");
	} else {
		errorIn("Please enter a valid email");
	}
	field.removeAttribute("disabled");
	button.removeAttribute("disabled");
}
</script>
</body>
</html>