<?php
	$host = 'localhost';
	$user = 'traxc_ben';
	$pass = 'microblog123';
	$db = 'traxc_microblog';
	$con = mysql_connect($host, $user, $pass);
	$dbcon = mysql_select_db($db) or die('Cannot select database: '.mysql_error());
	
?>