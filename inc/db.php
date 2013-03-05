<?php
	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'sf';
	$con = mysql_connect($host, $user, $pass);
	$dbcon = mysql_select_db($db) or die('Cannot select database: '.mysql_error());
	
?>