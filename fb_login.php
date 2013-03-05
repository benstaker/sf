<?php
	require_once('facebook.php');
	$appapikey = 'd409cde519e4a29f7dfe0ecee500d280';
	$appsecret = '8df9a689a002f2ff7f44baba2278a75f';
	$facebook = new Facebook($appapikey, $appsecret);
	$user = $facebook->require_login();
?>