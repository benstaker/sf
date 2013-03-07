<?php

require_once("inc/Signup.class.php");
$Signup=new Signup();

$array=array("bass2k8", "password", "password",
			 "bassatcollege@gmail.com", "bassatcollege@gmail.com",
			 "Benjamin", "Staker");
$Signup->add($array);