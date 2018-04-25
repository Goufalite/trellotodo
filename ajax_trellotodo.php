<?php
include "config.inc.php";

session_start();
if (!isset($_SESSION["list"])) $_SESSION["list"] = "";
if (!isset($_SESSION["board"])) $_SESSION["board"] = "";

define("TRELLO_HOST","https://api.trello.com/1/");


define("TRELLO_TOKEN",@decode($_COOKIE["trellocookie"]));


//header("Content-type: application/json");
if (isset($_GET["fetch"]))
{
	$_SESSION["list"] = $_GET["list"];
	
	$ch = curl_init();
	$url = TRELLO_HOST."lists/".$_SESSION["list"]."/cards?".TRELLO_TOKEN;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	// firefox PEM standard
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/trello.crt");
	$res = curl_exec($ch);
	if($res === false)
	{
		echo 'Erreur Curl : ' . curl_error($ch);
	}
	else
	{
		echo $res;
	}
}
else if (isset($_GET["fetchboards"]))
{
	$ch = curl_init();
	$url = TRELLO_HOST."members/me/boards?".TRELLO_TOKEN;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	// firefox PEM standard
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/trello.crt");
	$res = curl_exec($ch);
	if($res === false)
	{
		echo 'Erreur Curl : ' . curl_error($ch);
	}
	else
	{
		echo $res;
	}
}
else if (isset($_GET["fetchlists"]))
{
	$_SESSION["board"] = $_GET["board"];
	
	$ch = curl_init();
	$url = TRELLO_HOST."boards/".$_SESSION["board"]."/lists?".TRELLO_TOKEN;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	// firefox PEM standard
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/trello.crt");
	$res = curl_exec($ch);
	if($res === false)
	{
		echo 'Erreur Curl : ' . curl_error($ch);
	}
	else
	{
		echo $res;
	}
}

if (isset($_POST["val"]))
{
	$ch = curl_init();
	$url = TRELLO_HOST."cards/";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/trello.crt");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "name=".urlencode($_POST["val"])."&due=null&idList=".$_SESSION["list"]."&urlSource=null&".TRELLO_TOKEN);	
	echo curl_exec($ch);
}

else if (isset($_POST["id"]))
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,TRELLO_HOST."cards/".$_POST["id"]);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/trello.crt");
	curl_setopt($ch, CURLOPT_POSTFIELDS, TRELLO_TOKEN);
	echo curl_exec($ch);
}


?>