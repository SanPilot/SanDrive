<?php
function encrypt($data){
	$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "dd56d7ba89135e54b2d43244138a1947", $data, MCRYPT_MODE_CBC, "aaac111aebc3a53a97dbab5237a3ddc8");
	return base64_encode($encrypted);
}
function decrypt($data){
	$data = base64_decode($data);
	return mcrypt_decrypt(MCRYPT_RIJNDAEL_256,"dd56d7ba89135e54b2d43244138a1947", $data, MCRYPT_MODE_CBC, "aaac111aebc3a53a97dbab5237a3ddc8");
}
function encrypt_1($password,$username) {
	$salt = "8f9783e5c7082bb357ecae98c185de8127467fd812d1c3334fa04d3b445b2fa7".hash("sha256","f9bebf7d12386".$username."5f1b".$username."08436873".$usernam."e321c2544e082ee5bd96d18d".$username."b9adceebbe".$username."f20901761e".$username."86a104e1e1355ce9915170dd520adff807ec3f4c6d2fc5c78d6adf814c72");
	return hash("sha512",md5(md5(hash("sha512",md5($salt.$password.$salt.$salt.md5(md5($username)))))));
}
?>