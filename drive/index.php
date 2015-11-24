<?php
session_start();
if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
	include "../assets/login.php";
	$userinfo = getInfo($_SESSION['username'],$_SESSION['password']);
	if($userinfo == false) {
		//header("Location: /?cont=".urlencode($_SERVER['REQUEST_URI']));
		//exit();
	}
} else {
	header("Location: /?cont=".urlencode($_SERVER['REQUEST_URI']));
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>
San Drive is Coming Soon!
</title>
<style type="text/css">
.top {display: table; width: 100%; height: 100%}
.middle {display: table-cell; vertical-align: middle;}
.bottom {vertical-align: middle; margin-bottom: 5%}
.box {width: 35%; padding: 30px 20px; margin: 0px auto 4% auto}
#title {margin-bottom: 7px;}
#dsc {font-family: Open Sans; font-size: 25px; padding-left: 10px; color: #242424}
html, body {overflow: hidden; height: 100%; margin: 0px; padding: 0px;}
</style>
<link rel="stylesheet" type="text/css" href="/assets/styles.css">
</head>
<body class="grey">
<div class="top">
<div class="middle">
<div class="bottom box">
<div id="title"><a href="/"><img src="/assets/images/logo_main_212.png" width="212"></a></div>
<div id="dsc">Dear <?php echo $userinfo['firstname']." ".$userinfo['lastname']; ?>,<br>San Drive is currently still in development, but very soon will be available. Stick around!</div>
</div>
</div>
</div>
</body>
</html>