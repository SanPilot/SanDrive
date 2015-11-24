<?php
session_start();
if(isset($_GET['signout'])) {
	$session_destroy = session_destroy();
	$setcookie = setcookie("PHPSESSID","",time(),"/");
	$setcookie_2 = setcookie("remember","",time(),"/");
	if($session_destroy && $setcookie && $setcookie_2) {
		header("Location: /sign-in/?src=signout");
	} else {
		header("Location: /sign-in/?src=signout&signout_error");
	}
	exit();
}
if(isset($_COOKIE['remember'])) {
	$remember_decode_value = explode("|", $_COOKIE['remember']);
	include "/home/u519317105/public_html/assets/encrypt.php";
	$remember_decode_username = decrypt($remember_decode_value[0]);
	$remember_decode_password = decrypt($remember_decode_value[1]);
	$_SESSION['username'] = $remember_decode_username;
	$_SESSION['password'] = $remember_decode_password;
}
if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
	include "assets/login.php";
	$login = login($_SESSION['username'],$_SESSION['password']);
	if($login === true) {
		if(isset($_GET['cont'])) {
				header("Location: ".$_GET['cont']);
			} else {
				header("Location: /drive/");
			}
			exit();
	}
}
if(count($_POST) > 1) {
	if(isset($_POST['password']) && isset($_POST['username']) && $_POST['password'] != "" && $_POST['username'] != "") {
		include "../assets/login.php";
		$error = null;
		$login = login($_POST['username'],$_POST['password']);
		if($login === true) {
			if(isset($_POST['remember']) && $_POST['remember'] == "remember") {
				$remember_value = encrypt($_POST['username'])."|".encrypt($_POST['password']);
				setcookie("remember", $remember_value, time()+60*60*24*25, "/");
			}
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['password'] = $_POST['password'];
			if(isset($_GET['cont'])) {
				header("Location: ".$_GET['cont']);
			} else {
				header("Location: /drive/");
			}
			exit();
		} else {
			$error = $login;
		}
	} else {
		$error = "Enter a username and a password";
	}
}
if(isset($_GET['src']) && $_GET['src'] == "signout") {
	if(!isset($_GET['signout_error'])) {
		$confirm_text = true;
		$error = "You were signed out successfully";
	} else {
		$error = "There was an error signing you out";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>
Sign In - San Drive
</title>
<link href='/assets/styles.css' rel='stylesheet' type='text/css'>
<style type="text/css">
.top {display: table; width: 100%; height: 100%}
.middle {display: table-cell; vertical-align: middle;}
.bottom {vertical-align: middle; width: 252px; margin: 0px auto 0px auto; padding: 45px 30px;}
html, body {overflow: hidden; height: 100%; margin: 0px; padding: 0px; font-family: Ubuntu;}
input[type="text"],input[type="password"] {width: 240px; margin-bottom: 10px;}
.input {color: #3D3D3D; font-weight: 400; margin-left: 1px; margin-bottom: 4px; display: inline-block}
input[type="submit"] {margin-left: auto}
#error {height: <?php if(isset($error)) {echo "initial";} else {echo "0px";} ?>;<?php if($confirm_text) {echo " color: #3498db;";} ?>}
.button {margin-top: 4%;}
#logo {display: block; margin: 0px auto 20px auto}
</style>
</head>
<body class="grey">
<div class="top">
<div class="middle">
<div class="bottom box">
<img src="/assets/images/logo_main_212.png" height="50" id="logo">
<form name="sign-in" action="/sign-in/<?php if(isset($_GET['cont'])) {echo "&cont=".urlencode($_GET['cont']);} ?>" method="post" onsubmit="return checkNull();">
<span class="input">Username</span><br>
<input type="text" onkeydown="tabOverride(event);" name="username" id="username" autofocus<?php if(isset($_POST['username'])) {echo " value=\"".htmlspecialchars($_POST['username'])."\"";} ?>><script type="text/javascript">function tabOverride(e) {if(e.keyCode == 9) {setTimeout(function(){document.getElementById("password").focus()},1);}}</script><br>
<span class="input">Password <a href="/forgot">(Forgot?)</a></span><br>
<input type="password" name="password" id="password"<?php if(isset($_POST['password'])) {echo " value=\"".htmlspecialchars($_POST['password'])."\"";} ?>>
<br><input type="checkbox" id="remember" name="remember" value="remember"<?php if(isset($_POST['remember'])) echo " checked";?>><label for="remember"></label><label for="remember" class="input">Remember Me</label>
<span id="no-js" style="color: #D43100">Please enable JavaScript<?php if(isset($error)) echo "<br>"; ?><script type="text/javascript">document.getElementById("no-js").innerHTML="";</script></span><div id="error"><?php if(isset($error)) echo $error; ?></div>
<input type="submit" value="Sign In &#8594;" class="button"><br>
</form>
</div>
<div id="sign-up" style="text-align: center; margin-top: 1%; margin-bottom: 5%; font-size: 16px">
<a href="/sign-up/">Don't have an account? Sign Up</a>
</div>
</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
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
function checkNull() {
<?php if(isset($_POST['username']) && $_POST['password']) {echo "\tif(document.getElementById(\"username\").value == ".json_encode($_POST['username'])." && document.getElementById(\"password\").value == ".json_encode($_POST['password']).") {return false;}\n";} ?>
<?php if($confirm_text) echo "\t$(\"#error\").css(\"color\",\"#D43100\");\n";?>
	if(!!document.getElementById("username").value && !!document.getElementById("password").value) {
		return true;
	}
	if(!!!document.getElementById("username").value && !!!document.getElementById("password").value) {
		errorIn("Enter a username and a password");
	} else if(!!!document.getElementById("username").value) {
		errorIn("Enter a username");
	} else if(!!!document.getElementById("password").value) {
		errorIn("Enter a password");
	}
	return false;
}
</script>
</body>
</html>