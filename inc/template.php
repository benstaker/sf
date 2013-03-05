
<div style="position: fixed; top: 0; left: 0; background: url('../img/clouds-copy.png'); background-repeat: no-repeat; height: 100%; width: 50%;"></div>
<div style="position: fixed; top: 0; right: 0; background: url('../img/clouds-right.png'); background-repeat: no-repeat; height: 100%; width: 50%;"></div>
<b>Beta testers can now begin to look at the re-coded site: <a href="http://socialfeed.net/api/">http://socialfeed.net/api/</a></b>
<?php
	session_start();
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$head .= "<script type=\"text/javascript\" src=\"js/settings.js\"></script>";
	$navi .= "<a href=\"main.php\">Home</a>";
	$footer .= "
	<div style=\"color: #6699CC; font-weight: bold;\">
		Social Feed &copy; 2009. All Rights Reserved. Company Logo's are copyrighted to their respectful companies/owners.
		<div style=\"position: absolute; top: 10; right: 10;\">
			<a href=\"main.php?p=about\">About</a> | <a href=\"main.php?p=contact\">Contact</a>
		</div>
	</div>";
	if(isset($username) && !empty($username) && isset($password) && !empty($password)){
		 $navi .= "
		  | <a href=\"main.php?public=$username\">Profile</a>
		  | <a href=\"main.php?p=search\">Find People</a>
		  | <a href=\"main.php?p=settings\">Settings</a>
		  | <a href=\"main.php?p=logout\">Logout</a>";
	}
	else {
		$navi .= "
		 | <a href=\"main.php?p=signup\">Signup</a>
		 | <a href=\"main.php?p=login\">Login</a>";
	}
	echo "
	<html>
		<head>
			<title>Social Feed$title</title>
            <link REL=\"SHORTCUT ICON\" HREF=\"http://socialfeed.net/img/Social_Feed_Icon_32x32.ico\">
			<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\"/>
			$head
		</head>
		<body>

			<div id=\"wrapper\">
				<div class=\"logo\">
					<img src=\"img/logo-4.png\" width=\"155px\"/>
				</div>
				<div id=\"navi\">
					$navi
					<div class=\"header_msg\">$header_msg</div>
				</div>
				$top_content
				<div id=\"body\">
					<div id=\"body_content\">
						$content
					</div>
				</div>
				$bottom_content
				<div id=\"sidebar\">
					<div id=\"sidebar_content\">
						$sidebar
					</div>
					<div id=\"sidebar_content\">
						<center>
						<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"160\" height=\"300\">
                    	<param name=\"movie\" value=\"http://www.bannerserver.com/banner/banner5_001.swf\">
                    	<param name=\"quality\" value=\"high\">
                    	<embed src=\"http://www.bannerserver.com/banner/banner5_001.swf\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"160\" height=\"300\"></embed>
                    	</object>
                    	</center>
                    </div>
				</div>
				<div id=\"footer\">
					$footer
				</div>
				<br />
			</div>
		</body>
	</hmtl>";
?>