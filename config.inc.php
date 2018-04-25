<?php
define("KEY","YOURKEY");

function encode($str)
{
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KEY, $str, MCRYPT_MODE_ECB));
}

function decode($str)
{
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KEY, base64_decode($str), MCRYPT_MODE_ECB),"\0\4");
}

?>