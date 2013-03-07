<?php

// Declaring variables.
$c=""; // Content
$s=""; // Sidebar

require_once("inc/functions.inc.php");
require_once("inc/Login.class.php");
$Login = new Login();

if(isset($_POST["username"]) && isset($_POST["password"])){

	if($Login->login($_POST["username"], $_POST["password"])){
		header("Location: ./");
	}
	else {
		$c.="Username/Password is incorrect.";
		redirect("./", 4000);
	}

} else {

	if($Login->status()){
		
		$c.="You're already logged in :s";
		$s.="<a href=\"index.php?logout\">Logout</a> fool.";
		redirect("./", 10);

	} else {

		ob_start();
		?>
		<form action="login.php" method="post">
			<p>
				<label for="username">Username: </label>
				<input type="text" name="username" />
			</p>
			<p>
				<label for="password">Password: </label>
				<input type="password" name="password" />
			</p>
			<p>
				<button type="submit">Login!</button>
			</p>
		</form>
		<?php
		$c.=ob_get_clean();

		$s.="Sidebar";
	}

}

require_once("inc/template.inc.php");