<?php

// Declaring variables.
$c=""; // Content
$s=""; // Sidebar
$page=$title="Signup"; // Page Title.

require_once("inc/Login.class.php");
$Login = new Login();

if(!$Login->status()){
	
	$c.="Signup form here.";
	$s.="Sidebar";

} else header("Location: ./");

require_once("inc/template.inc.php");