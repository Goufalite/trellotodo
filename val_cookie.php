<?php
include "config.inc.php";

if (isset($_POST["cookie"]))
{
	setcookie("trellocookie",encode($_POST["cookie"]),time()+86400*365*20);
	echo "<script>window.location='index.php';</script>";
	exit;
}
else
{
	echo "<script>window.location='trellotodo_cookie.php';</script>";
	exit;
}
?>