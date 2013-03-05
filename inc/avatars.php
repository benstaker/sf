<?php
	session_start();
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	if(isset($username) && isset($password)){
		if(isset($_GET['u'])){
			$avatar_user = $_GET['u'];
		}
		else {
			$avatar_user = $username;
		}
		include('db.php');
		$sql = "SELECT avatar FROM usercred WHERE user='$avatar_user'";
		$result = mysql_query($sql);
		list($avatar) = mysql_fetch_array($result);
		echo $avatar;
	}
	else {
		echo "You need to login to retrieve your avatar!";
	}
?>