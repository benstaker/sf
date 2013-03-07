<?php

if(!isset($scripts) || empty($scripts)) $scripts=array();
if(!isset($stylesheets) || empty($stylesheets)) $stylesheets=array();

$navi="";
$navi_arr=array(
	array("./", "Home"),
	array("about.php", "About"),
	array("contact.php", "Contact")
);

require_once("Login.class.php");
if(!isset($Login)) $Login=new Login();

// If the user is logged in.
if($Login->status()){

	$navi_arr[count($navi_arr)]=array("index.php?logout", "Logout");

} else {

	$navi_arr[count($navi_arr)]=array("login.php", "Login");
	$navi_arr[count($navi_arr)]=array("signup.php", "Signup");

}

foreach($navi_arr as $na){
	if(isset($na[2]) && is_array($na[2]) && count($na[2], COUNT_RECURSIVE)>0){
		$navi.="<li>\n\t\t<a href=\"".$na[0]."\"";
		if($page==$na[1]) $navi.=" class=\"selected\"";
		$navi.=">".$na[1]."</a>\n";
		$navi.="\t<ul class=\"dropdown\">\n";
		foreach($na[2] as $dd){
			$navi.="\t\t<li>\n\t\t\t<a href=\"".$dd[0]."\"";
			if($page==$dd[1]) $navi.=" class=\"selected\"";
			$navi.=">".$dd[1]."</a>\n\t\t</li>\n";
		}
		$navi.="\t</ul>\n";
		$navi.="</li>\n";
	} else {
		$navi.="<li>\n<a href=\"".$na[0]."\"";
		if($page==$na[1]) $navi.=" class=\"selected\"";
		$navi.=">".$na[1]."</a>\n</li>\n";
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta name="description" content="Social Feed is a social networking website that aggrogrates feeds from Facebook, Twitter and YouTube into one news feed.">
		<meta name="author" content="Benjamin Staker">

		<base href="./" target="_parent">

		<title>Social Feed</title>
		
		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet/less" type="text/css" href="css/style.less">
		<?php

		foreach($stylesheets as $stylesheet){
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$stylesheet."\">\n";
		}

		?>

	</head>
	<body>
		<section class="wrapper">
			<header>
				<section class="logo">&nbsp;</section>
			</header>
			<nav>
				<ul>
					<?php echo $navi; ?>
				</ul>
			</nav>
			<section class="content">
				<?php if(isset($c) && !empty($c)) echo $c; ?>
			</section>
			<aside>
				<?php if(isset($s) && !empty($s)) echo $s; ?>
			</aside>
			<footer>
				<p>&copy; 2009-2013 Social Feed. All Rights Reserved.</p>
			</footer>
		</section>

		<!-- Scripts -->
		<script src="js/less-1.3.3.min.js"></script>
		<?php

		foreach($scripts as $script){
			echo "<script src=\"".$script."\"></script>\n";
		}

		?>
	</body>
</html>