<?php
	session_start();
	require_once('classes.php');
	require_once('functions.php');
	$loggedin = $sf->checkLoggedIn();
	if($loggedin=="1"){
		if($_GET['p']=="logout"){
			$sf->logout();
			$content .= $sf->openURL('./');
		}
		elseif(isset($_GET['p']) && !empty($_GET['p']) && !is_null($_GET['p'])){
			$page = $_GET['p'];
			if(isset($_GET['s']) && !empty($_GET['s']) && !is_null($_GET['s'])){
				$section = $_GET['s'];
				if($page=="settings"){
					$content .= "
					<script type='text/javascript'>
						loadNavi('home');
						loadPage('settings_$section');
						loadSidebar('settings');
					</script>";
				}
			}
			else {
				$content .= "
				<script type='text/javascript'>
					loadNavi('home');
					loadPage('$page');
					loadSidebar('$page');
				</script>";
			}
		}
		else {
			$content .= "
			<script type='text/javascript'>
				loadNavi('home');
				loadPage('home');
				loadSidebar('home');
			</script>";
		}
		$enabled_feeds = $sf->getFeedIcons();
		$top_content .= "
		<div style='top:10; right: 10; position: absolute;'>
			$enabled_feeds
		</div>";
	}
	elseif($loggedin=="0"){
		$content .= "
		<script type='text/javascript'>
			loadNavi('home');
			loadPage('home');
			loadSidebar('home');
		</script>";
	}
	else {
		$content .= "Woops there was an error!";
	}
	require_once('template.php');
?>