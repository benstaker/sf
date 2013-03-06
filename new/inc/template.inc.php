<?php

if(!isset($scripts) || empty($scripts)) $scripts=array();
if(!isset($stylesheets) || empty($stylesheets)) $stylesheets=array();

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
					<li><a href="#">Home</a></li>
					<li><a href="#">About</a></li>
					<li><a href="#">Contact</a></li>
					<li><a href="#">Signup</a></li>
					<li><a href="#">Login</a></li>
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