<?php
function set($var) {if(isset($var) && $var !== "") return true; else return false;}
if(count($_POST) > 5)  {
	if(set($_POST['firstname']) && set($_POST['lastname']) && set($_POST['email']) && set($_POST['username']) && set($_POST['password']) && set($_POST['passwordRepeat'])) {
		$_POST['username'] = strtolower($_POST['username']);
		if(ctype_alpha($_POST['firstname'])) {
			if(ctype_alpha($_POST['lastname'])) {
				include "../assets/validemail.php";
				if(validEmail($_POST['email'])) {
					if(strlen($_POST['password']) > 4) {
						if(strtolower($_POST['password']) !== $_POST['password']) {
							if(strtoupper($_POST['password']) !== $_POST['password']) {
								if(preg_match("#[0-9]#",$_POST['password'])) {
									if($_POST['passwordRepeat'] === $_POST['password']) {
										include "../assets/connect.php";
										if($conerror == false) {
											include "../assets/encrypt.php";
											$sql_username = mysqli_real_escape_string($con, encrypt($_POST['username']));
											$sql_email = mysqli_real_escape_string($con, encrypt($_POST['email']));
											$query = mysqli_query($con, "SELECT * FROM users WHERE username='$sql_username'");
											if($query !== false) {
												if(!mysqli_num_rows($query) > 0)  {
													$query = mysqli_query($con, "SELECT * FROM users WHERE email='$sql_email'");
													if($query != false) {
														if(!mysqli_num_rows($query) > 0) {
															$sql_password = mysqli_real_escape_string($con, encrypt($_POST['password']));
															$sql_firstname = mysqli_real_escape_string($con, encrypt($_POST['firstname']));
															$sql_lastname = mysqli_real_escape_string($con, encrypt($_POST['lastname']));
															$sql_time = mysqli_real_escape_string($con, encrypt(time()));
															$query = mysqli_query($con, "INSERT INTO users (username,password,first,last,email,last_login) VALUES ('$sql_username','$sql_password','$sql_firstname','$sql_lastname','$sql_email','$sql_time');");
															$email_username = htmlspecialchars(urlencode(encrypt(encrypt($_POST['username']))));
															$email_firstname = htmlspecialchars($_POST['firstname']);
															$email_lastname = htmlspecialchars($_POST['lastname']);
															$to = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
															$subject = "Activate your San Drive Account";
															include "../assets/mailmessage.php";
															$message = mail_create_message("Dear $email_firstname $email_lastname,<br>Welcome to San Drive, and thanks thanks for creating an account!<br>Before you begin using San Drive, you must activate your account. Please keep in mind that you must activate your account within 24 hours, or it will be deleted. You can activate it here: <a href='http://sandrive.tk/signup/activate/?username=$email_username'>http://sandrive.tk/signup/activate/?username=$email_username</a>.<br><br>Best Regards,<br>San Drive");
															$headers = "From: San Drive <activate@sandrive.tk>\r\nMIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8";
															$mail = mail($to,$subject,$message,$headers);
															if($mail != false) {
																header("Location: created/?username=".urlencode(encrypt($_POST['username'])));
																exit();
															} else {
																$error = "The activation email was not sent. To try again, click <a href='http://sandrive.tk/activate/retry/?username=".urlencode(encrypt($_POST['username']))."'>here</a>.";
															}
														} else {
															$error = "An account with that email already exists";
														}
													} else {
														$error = "Could not create your account due to a technical problem";
													}
												} else {
													$error = "An account with that username already exists";
												}
											} else {
												$error = "Could not create your account due to a technical problem";
											}
										} else {
											$error = "Could not create your account due to a technical problem";
										}
									} else {
										$error = "Passwords do not match";
									}
								} else {
									$error = "Password must contain a number";
								}
							} else {
								$error = "Password must contain lowercase letters";
							}
						} else {
							$error = "Password must contain uppercase letters";
						}
					} else {
						$error = "Password must be five characters long";
					}
				} else {
					$error = "The provided email is invalid";
				}
			} else {
				$error = "Last Name must be alphabetical";
			}
		} else {
			$error = "First Name must be alphabetical";
		}
	} else {
		$error = "Please fill out all the fields";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>
Sign Up - San Drive
</title>
<style type="text/css">
body {margin: 0px; font-family: Ubuntu; height: 100%}
html {height: 100%}
.box {width: 500px; padding: 50px; margin-left: auto; margin-right: 90px; height: 100%}
#info {font-family: Open Sans; font-size: 19px; color: #3D3D3D; text-align: justify}
#signupForm {margin-top: 20px}
input[type="text"],input[type="password"] {width: 60%; margin-bottom: 5%;}
.button {margin-right: 15px}
.label {font-family: Open Sans; display: inline-block; margin-bottom: 4px; font-size: 18px; color: #1A1A1A}
#formContent {margin-left: 10px}
#error {height: <?php if(isset($error)) {echo "20px";} else {echo "0px";} ?>; display: inline-block}
</style>
<link rel="stylesheet" href="/assets/styles.css" type="text/css">
</head>
<body class="grey">
<div class="box" style="border-top: none; border-bottom: none;">
<a href="/"><img src="/assets/images/logo_main_212.png" height="50"></a><div id="formContent"><div id="info">Creating a San Drive account allows you to securely store and organize your files in the cloud. It's easy and free!</div>
<form id="signupForm" action="/sign-up/" method="post" onsubmit="return checkNull();">
<span class="label">First Name</span><br>
<input type="text" name="firstname" autofocus id="firstName"<?php if(set($_POST['firstname'])) echo " value=\"".htmlspecialchars($_POST['firstname'])."\""; ?>><br>
<span class="label">Last Name</span><br>
<input type="text" name="lastname" id="lastName"<?php if(set($_POST['lastname'])) echo " value=\"".htmlspecialchars($_POST['lastname'])."\""; ?>><br>
<span class="label">Email Address</span><br>
<input type="text" name="email" id="email"<?php if(set($_POST['email'])) echo " value=\"".htmlspecialchars($_POST['email'])."\""; ?>><br>
<span class="label">Username</span><br>
<input type="text" name="username" id="username"<?php if(set($_POST['username'])) echo " value=\"".htmlspecialchars($_POST['username'])."\""; ?>><br>
<span class="label">Password</span><br>
<input type="password" name="password" id="password"<?php if(set($_POST['password'])) echo " value=\"".htmlspecialchars($_POST['password'])."\""; ?>><br>
<span class="label">Repeat Password</span><br>
<input type="password" id="passwordRepeat" name="passwordRepeat"<?php if(set($_POST['passwordRepeat'])) echo " value=\"".htmlspecialchars($_POST['passwordRepeat'])."\""; ?>><br>
<input type="submit" class="button" value="Sign Up &#8594;"><span id="errorHolder" style="color: #D43100"><span id="no-js">Please enable JavaScript </span><script type="text/javascript">document.getElementById("no-js").innerHTML="";</script><span id="error"<?php if(isset($error)) echo " style=\"display: inline-block;\""; ?>><?php if(isset($error)) echo $error; ?></span></span>
</form>
</div>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(
	function() {
				if($("#error").html() !== "") {
					location.href = "#";
					location.href = "#error";
				}
	}
);
function errorIn(msg) {
	$(document).ready(
		function () {
			$("html, body").animate({scrollTop: $("#error").offset().top},750);
			if($("#error").html() !== msg) {
				var errorContent = $("#error").html();
				$("#error").html(msg);
				if(errorContent == "") {
					$("#error").animate({height: "20px"},200);
				}
			}
		}
	);
}

function checkNull() {
	$("input[type='text'], input[type='password']").css("border","");
	if(!!!document.getElementById("firstName").value) {
		errorIn("First Name is required");
		$("#firstName").css("border", "thin solid red");
		$("#firstName").focus();
		return false;
	}
	if(!/^[a-zA-Z]*$/.test(document.getElementById("firstName").value)) {
		errorIn("First Name must be alphabetical");
		$("#firstName").css("border", "thin solid red");
		$("#firstName").focus();
		return false;
	}
	if(!!!document.getElementById("lastName").value) {
		errorIn("Last Name is required");
		$("#lastName").css("border", "thin solid red");
		$("#lastName").focus();
		return false;
	}
	if(!/^[a-zA-Z]*$/.test(document.getElementById("lastName").value)) {
		errorIn("Last Name must be alphabetical");
		$("#lastName").css("border", "thin solid red");
		$("#lastName").focus();
		return false;
	}
	if(!!!document.getElementById("email").value) {
		errorIn("Email Address is required");
		$("#email").css("border", "thin solid red");
		$("#email").focus();
		return false;
	}
	if(!/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(document.getElementById("email").value)) {
		errorIn("The provided email is invalid");
		$("#email").css("border", "thin solid red");
		$("#email").focus();
		return false;
	}
	if(!!!document.getElementById("username").value) {
		errorIn("Username is required");
		$("#username").css("border", "thin solid red");
		$("#username").focus();
		return false;
	}
	if(!!!document.getElementById("password").value) {
		errorIn("Password is required");
		$("#password").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	if(document.getElementById("password").value.length < 5) {
		errorIn("Password must be at least five characters long");
		$("#password").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	if(document.getElementById("password").value.toUpperCase() == document.getElementById("password").value) {
		errorIn("Password must contain lowercase characters");
		$("#passwordRepeat").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	if(document.getElementById("password").value.toLowerCase() == document.getElementById("password").value) {
		errorIn("Password must contain uppercase characters");
		$("#password").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	if(/\d/.test(document.getElementById("password").value) !== true) {
		errorIn("Password must contain a number");
		$("#passwordRepeat").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	if(!!!document.getElementById("passwordRepeat").value) {
		errorIn("Repeat password");
		$("#passwordRepeat").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	if(document.getElementById("passwordRepeat").value !== document.getElementById("password").value) {
		errorIn("Passwords do not match");
		$("#passwordRepeat").css("border", "thin solid red");
		$("#password").focus();
		return false;
	}
	$("#error").remove();
	return true;
}
</script>
</body>
</html>