<?php

// Declaring variables.
$c=""; // Content
$s=""; // Sidebar

require_once("inc/Login.class.php");
$Login = new Login(8);

if(isset($_GET["login"])){

	$Login->login();
	header("Location: ./");

} else if(isset($_GET["logout"])){

	$Login->logout();
	header("Location: ./");

} else {

	if($Login->status()){
		$userID=$_SESSION["userID"];

		require_once("inc/User.class.php");
		$User=new User($userID);
		$fullName=$User->getFullName();

		$c.="Welcome ".$fullName.". <a href='?logout'>Logout</a>.";
		$s.="Awesome Sidebar";
	}
	else {
		$c.="Welcome to Social Feed. <a href='?login'>Login</a>.";
		$s.="Sidebar";
	}

}

require_once("inc/template.inc.php");