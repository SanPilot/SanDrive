<?php
function login($username, $password) {
	$username = strtolower($username);
	include_once "/home/u519317105/public_html/assets/connect.php";
	if($conerror) {
		$error = "Could not sign in - Please try again";
		return $error;
	} else {
		include_once "/home/u519317105/public_html/assets/encrypt.php";
		$username = mysqli_real_escape_string($con,encrypt($username));
		$query = mysqli_query($con,"SELECT password,active FROM users WHERE username='$username'");
		if($query != false) {
			if(mysqli_num_rows($query) > 0) {
				$result = mysqli_fetch_array($query);
				if(encrypt($password) == $result['password']) {
					if($result['active'] == 1) {
						return true;
					} else {
						$error = "That account has not been activated";
						return $error;
					}
				} else {
					$error = "The provided username or password is incorrect";
					return $error;
				}
			} else {
				$error = "The provided username or password is incorrect";
				return $error;
			}
		} else {
			$error = "Could not sign in - Please try again";
			return $error;
		}
	}
}
function getInfo($username,$password) {
	$username = strtolower($username);
	include_once "/home/u519317105/public_html/assets/connect.php";
	if(!$conerror) {
		include_once "/home/u519317105/public_html/assets/encrypt.php";
		$username = mysqli_real_escape_string($con,encrypt($username));
		$query = mysqli_query($con,"SELECT password,active,first,last FROM users WHERE username='$username'");
		if($query != false) {
			if(mysqli_num_rows($query) > 0) {
				$result = mysqli_fetch_array($query);
				if(encrypt($password) == $result['password']) {
					if($result['active'] == 1) {
						return array("firstname" => decrypt($result['first']), "lastname" => decrypt($result['last']), "username" => decrypt($username));
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}
?>