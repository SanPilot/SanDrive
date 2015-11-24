<?php
$errors = array(
404 => array("title" => "Error 404 - Not Found", "description" => "The page you were looking for was moved or doesn't exist."),
500 => array("title" => "Error 500 - Internal Server Error", "description" => "The server encountered an error. Sorry about that."),
403 => array("title" => "Error 403 - Forbidden", "description" => "The page you are attempting to access is forbidden."),
401 => array("title" => "Error 401 - Unauthorized", "description" => "The page you are attempting to access is restricted."),
400 => array("title" => "Error 400 - Bad Request", "description" => "The server is having difficulty understanding the requested URL."),
);
if(!isset($errors[$_GET['id']])) {
header("Location: /");
exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>
<?php echo $errors[$_GET['id']]['title']; ?>
</title>
<style type="text/css">
.top {display: table; width: 100%; height: 100%}
.middle {display: table-cell; vertical-align: middle;}
.bottom {vertical-align: middle;}
.box {padding: 40px; margin: 0px auto 100px auto; width: 350px}
body {padding: 0px; margin: 0%; width: 100%; height: 100%; font-family: Open Sans; font-size: 30px; color: #141414}
html {padding: 0px; margin: 0%; width: 100%; height: 100%}
#errorTitle {color: #171717; margin-left: 4px}
</style>
<link rel="stylesheet" href="/assets/styles.css" type="text/css">
</head>
<body class="grey">
<div class="top">
<div class="middle">
<div class="bottom box">
<a href="/"><img src="/assets/images/logo_main_212.png"></a><br>
<span id="errorTitle"><b>Error <?php echo $_GET['id']; ?>:</b></span><br><?php echo $errors[$_GET['id']]['description']; ?>
</div>
</div>
</div>
</body>
</html>