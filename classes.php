<?php
	session_start();
	class SocialFeed {
		function connectDB(){
			$host = 'localhost';
			$user = 'bass2k8_admin';
			$pass = 'b1a9s93';
			$db = 'bass2k8_sf';
			$con = mysql_connect($host, $user, $pass);
			$dbcon = mysql_select_db($db) or die('Cannot select database: '.mysql_error());
		}
		
		function openURL($link){
			echo "
			<script type=\"text/javascript\">
		    	url = '$link';
		    	window.location.href = url;
		    </script>";
		}
		
		function changeBio($new_bio){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				if(isset($new_bio) && !empty($new_bio) && !is_null($new_bio)){
					$this->connectDB();
					$uid = $this->getUid($this->getUsername());
					$sql = "UPDATE usercred SET bio='$new_bio' WHERE id='$uid'";
					$result = mysql_query($sql);
					return $new_bio;
				}
			}
		}
		
		function changeEmail($new_email,$new_email2){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				if(isset($new_email) && !empty($new_email) && !is_null($new_email)){
					if(isset($new_email2) && !empty($new_email2) && !is_null($new_email2)){
						if($new_email==$new_email2){
							$cur_email = $this->getEmail();
							if($new_email==$cur_email){
								$new_email = "<span style='color: #FF0000;'>Please enter a new Email Address, not your current one.</span>";
							}
							else{
								$this->connectDB();
								$email_chk_sql = "SELECT * FROM usercred WHERE email='$new_email'";
								$email_chk_result = mysql_query($email_chk_sql);
								$email_chk_num = mysql_num_rows($email_chk_result);
								if($email_chk_num >= 1){
									$new_email = "<span style='color: #FF0000;'>Email Address is already in use.</span>";
								}
								else {
									$uid = $this->getUid($this->getUsername());
									$sql = "UPDATE usercred SET email='$new_email' WHERE id='$uid'";
									$result = mysql_query($sql);
								}
							}
						}
						else {
							$new_email = "<span style='color: #FF0000;'>Your Email Addresses do not match.</span>";
						}
					}
					else {
						$new_email = "<span style='color: #FF0000;'>Please retype your new Email Address.</span>";
					}
				}
				else {
					$new_email = "<span style='color: #FF0000;'>Please enter your new Email Address.</span>";
				}
				return $new_email;
			}
		}
		
		function changePassword($new_pass,$new_pass2){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				if(isset($new_pass) && !empty($new_pass) && !is_null($new_pass)){
					if(isset($new_pass2) && !empty($new_pass2) && !is_null($new_pass2)){
						if($new_pass==$new_pass2){
							$this->connectDB();
							$uid = $this->getUid($this->getUsername());
							$new_pass = md5($new_pass);
							$sql = "UPDATE usercred SET pass='$new_pass' WHERE id='$uid'";
							$result = mysql_query($sql);
							$this->createSession('password',$new_pass);
							$change_pass = "<span style='color: #009900;'>Your Password has been successfully changed.</span>";
						}
						else {
							$change_pass = "<span style='color: #FF0000;'>Your Passwords do not match.</span>";
						}
					}
					else {
						$change_pass = "<span style='color: #FF0000;'>Please retype your new Password.</span>";
					}
				}
				else {
					$change_pass = "<span style='color: #FF0000;'>Please enter your new Password.</span>";
				}
				return $change_pass;
			}
		}
		
		function getProfileFeeds($username,$starts,$limits){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				if(is_array($username)){
					$uids = $username;
				}
				else {
					$uid = $this->getUid($username);
				}
				if(is_array($username)){
					$prof_feeds = array();
					foreach($username as $uid){
						$profile_chk = $this->checkProfileUid($uid);
					    if($profile_chk==1){
					    	$sql = "SELECT * FROM prof_feeds WHERE uid='$uid'";
					    	$result = mysql_query($sql);
					    	$usr_sql = "SELECT user FROM usercred WHERE id='$uid'";
					    	$usr_result = mysql_query($usr_sql);
					    	list($usr) = mysql_fetch_array($usr_result);
					    	$avatar = $this->getAvatar($usr,'24px','24px');
					    	while(list($id,$feed,$time) = mysql_fetch_array($result)){
					    		$time = $this->timeZone('0',$time);
					    		array_push($prof_feeds, array('time'=>$time, 'username'=>"<a href=\"javascript:public_user('$usr')\">$usr</a>", 'feed'=>$feed, 'id'=>$avatar));
					    	}
					    }
					}
				}
				else {
					$prof_feeds = array();
					$profile_chk = $this->checkProfileUid($uid);
					if($profile_chk==1){
					    $sql = "SELECT * FROM prof_feeds WHERE uid='$uid'";
					    $result = mysql_query($sql);
					    $usr_sql = "SELECT user FROM usercred WHERE id='$uid'";
					    $usr_result = mysql_query($usr_sql);
					    list($usr) = mysql_fetch_array($usr_result);
					    $avatar = $this->getAvatar($usr,'24px','24px');
					    while(list($id,$feed,$time) = mysql_fetch_array($result)){
					    	$time = $this->timeZone('0',$time);
					    	array_push($prof_feeds, array('time'=>$time, 'username'=>"<a href=\"javascript:public_user('$usr')\">$usr</a>", 'feed'=>$feed, 'id'=>$avatar));
					    }
					}
				}
				if(isset($prof_feeds) && !empty($prof_feeds) && !is_null($prof_feeds)){
					rsort($prof_feeds);
					$this->createSession('profile',$prof_feeds);
				}
				$feed_list .= $this->combineFeeds($starts,$limits);
				return $feed_list;
			}
		}
		
		function getUsername(){
			$ses_user = $_SESSION['username'];
			return $ses_user;
		}
		
		function getPassword(){
			$ses_pass = $_SESSION['password'];
			return $ses_pass;
		}
		
		function getUid($username){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT id FROM usercred WHERE user='$username'";
				$result = mysql_query($sql);
				if($result){
					list($uid) = mysql_fetch_array($result);
				}
				return $uid;
			}
		}
		
		function getFirstName(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT fname FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($fname) = mysql_fetch_array($result);
				}
				return $fname;
			}
		}
		
		function getEmail(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT email FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($email) = mysql_fetch_array($result);
				}
				return $email;
			}
		}
		
		function getUserLevel(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT ulev FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($ulev) = mysql_fetch_array($result);
				}
				if($ulev==1){
					$ulev = "System Administrator";
				}
				elseif($ulev==9){
					$ulev = "Registered User";
				}
				return $ulev;
			}
		}
		
		function getBio(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT bio FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($bio) = mysql_fetch_array($result);
				}
				if(!$bio || empty($bio) || is_null($bio)){
					$bio = $this->getUsername()." hasn't written a Bio.";
				}
				return $bio;
			}
		}
		
		function getAvatar($username,$width,$height){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$avatar = "<img src='?avatar=$username' width='$width' height='$height'/>";
				return $avatar;
			}
		}
		
		function checkBebo(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT bebo FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($bebo_chk) = mysql_fetch_array($result);
					if($bebo_chk==1){
						$sql = "SELECT * FROM bebo_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
						$result = mysql_query($sql);
						if($result){
							list($bebo_feed) = mysql_fetch_array($result);
							if(isset($bebo_feed) && !empty($bebo_feed) && !is_null($bebo_feed)){
								$bebo = 1;
							}
							elseif(!isset($bebo_feed) || empty($bebo_feed) || is_null($bebo_feed)){
								$bebo = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `bebo_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$bebo = $this->checkBebo();
						}
					}
				}
				return $bebo;
			}
		}
		
		function checkBeboUid($uid){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT bebo FROM usercred WHERE id='$uid'";
				$result = mysql_query($sql);
				if($result){
					list($bebo_chk) = mysql_fetch_array($result);
					if($bebo_chk==1){
						$sql = "SELECT * FROM bebo_feeds WHERE uid='$uid'";
						$result = mysql_query($sql);
						if($result){
							list($bebo_feed) = mysql_fetch_array($result);
							if(isset($bebo_feed) && !empty($bebo_feed) && !is_null($bebo_feed)){
								$bebo = 1;
							}
							elseif(!isset($bebo_feed) || empty($bebo_feed) || is_null($bebo_feed)){
								$bebo = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `bebo_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$bebo = $this->checkBebo();
						}
					}
					else {
						$bebo = $bebo_chk;
					}
				}
				return $bebo;
			}
		}
		
		function checkFacebook(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT facebook FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($face_chk) = mysql_fetch_array($result);
					if($face_chk==1){
						$sql = "SELECT * FROM face_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
						$result = mysql_query($sql);
						if($result){
							list($face_feed) = mysql_fetch_array($result);
							if(isset($face_feed) && !empty($face_feed) && !is_null($face_feed)){
								$facebook = 1;
							}
							elseif(!isset($face_feed) || empty($face_feed) || is_null($face_feed)){
								$facebook = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `face_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$facebook = $this->checkFacebook();
						}
					}
				}
				return $facebook;
			}
		}
		
		function checkFacebookUid($uid){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT facebook FROM usercred WHERE id='$uid'";
				$result = mysql_query($sql);
				if($result){
					list($face_chk) = mysql_fetch_array($result);
					if($face_chk==1){
						$sql = "SELECT * FROM face_feeds WHERE uid='$uid'";
						$result = mysql_query($sql);
						if($result){
							list($face_feed) = mysql_fetch_array($result);
							if(isset($face_feed) && !empty($face_feed) && !is_null($face_feed)){
								$facebook = 1;
							}
							elseif(!isset($face_feed) || empty($face_feed) || is_null($face_feed)){
								$facebook = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `face_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$facebook = $this->checkFacebook();
						}
					}
					else {
						$facebook = $face_chk;
					}
				}
				return $facebook;
			}
		}
		
		function getFacebookUser(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$facebook = $this->checkFacebook();
				if($facebook==1){
					$this->connectDB();
					$uid = $this->getUid($this->getUsername());
					$sql = "SELECT user FROM face_feeds WHERE uid='$uid'";
					$result = mysql_query($sql);
					if($result){
						list($face_user) = mysql_fetch_array($result);
						return $face_user;
					}
				}
			}
		}
		
		function getTwitterUser(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$twitter = $this->checkTwitter();
				if($twitter==1){
					$this->connectDB();
					$uid = $this->getUid($this->getUsername());
					$sql = "SELECT user FROM twit_feeds WHERE uid='$uid'";
					$result = mysql_query($sql);
					if($result){
						list($twit_user) = mysql_fetch_array($result);
						return $twit_user;
					}
				}
			}
		}
		
		function getYouTubeUser(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$youtube = $this->checkYouTube();
				if($youtube==1){
					$this->connectDB();
					$uid = $this->getUid($this->getUsername());
					$sql = "SELECT user FROM you_feeds WHERE uid='$uid'";
					$result = mysql_query($sql);
					if($result){
						list($yout_user) = mysql_fetch_array($result);
						return $yout_user;
					}
				}
			}
		}
		
		function checkMySpace(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT myspace FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($mysp_chk) = mysql_fetch_array($result);
					if($mysp_chk==1){
						$sql = "SELECT * FROM mysp_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
						$result = mysql_query($sql);
						if($result){
							list($mysp_feed) = mysql_fetch_array($result);
							if(isset($mysp_feed) && !empty($mysp_feed) && !is_null($mysp_feed)){
								$myspace = 1;
							}
							elseif(!isset($mysp_feed) || empty($mysp_feed) || is_null($mysp_feed)){
								$myspace = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `mysp_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$myspace = $this->checkMySpace();
						}
					}
				}
				return $myspace;
			}
		}
		
		function checkMySpaceUid($uid){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT myspace FROM usercred WHERE id='$uid'";
				$result = mysql_query($sql);
				if($result){
					list($mysp_chk) = mysql_fetch_array($result);
					if($mysp_chk==1){
						$sql = "SELECT * FROM mysp_feeds WHERE uid='$uid'";
						$result = mysql_query($sql);
						if($result){
							list($mysp_feed) = mysql_fetch_array($result);
							if(isset($mysp_feed) && !empty($mysp_feed) && !is_null($mysp_feed)){
								$myspace = 1;
							}
							elseif(!isset($mysp_feed) || empty($mysp_feed) || is_null($mysp_feed)){
								$myspace = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `mysp_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$myspace = $this->checkMySpace();
						}
					}
					else {
						$myspace = $mysp_chk;
					}
				}
				return $myspace;
			}
		}
		
		function checkProfile(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT profile FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($prof_chk) = mysql_fetch_array($result);
					if($prof_chk==1){
						$sql = "SELECT * FROM prof_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
						$result = mysql_query($sql);
						if($result){
							list($prof_feed) = mysql_fetch_array($result);
							if(isset($prof_feed) && !empty($prof_feed) && !is_null($prof_feed)){
								$profile = 1;
							}
							elseif(!isset($prof_feed) || empty($prof_feed) || is_null($prof_feed)){
								$profile = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `prof_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$twitter = $this->checkProfile();
						}
					}
				}
				return $profile;
			}
		}
		
		function checkProfileUid($uid){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT profile FROM usercred WHERE id='$uid'";
				$result = mysql_query($sql);
				if($result){
					list($prof_chk) = mysql_fetch_array($result);
					if($prof_chk==1){
						$sql = "SELECT * FROM prof_feeds WHERE uid='$uid'";
						$result = mysql_query($sql);
						if($result){
							list($prof_feed) = mysql_fetch_array($result);
							if(isset($prof_feed) && !empty($prof_feed) && !is_null($prof_feed)){
								$profile = 1;
							}
							elseif(!isset($prof_feed) || empty($prof_feed) || is_null($prof_feed)){
								$profile = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `prof_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$profile = $this->checkProfile();
						}
					}
					else {
						$profile = $prof_chk;
					}
				}
				return $profile;
			}
		}
		
		function checkTwitter(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT twitter FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($twit_chk) = mysql_fetch_array($result);
					if($twit_chk==1){
						$sql = "SELECT * FROM twit_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
						$result = mysql_query($sql);
						if($result){
							list($twit_feed) = mysql_fetch_array($result);
							if(isset($twit_feed) && !empty($twit_feed) && !is_null($twit_feed)){
								$twitter = 1;
							}
							elseif(!isset($twit_feed) || empty($twit_feed) || is_null($twit_feed)){
								$twitter = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `twit_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$twitter = $this->checkTwitter();
						}
					}
				}
				return $twitter;
			}
		}
		
		function checkTwitterUid($uid){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT twitter FROM usercred WHERE id='$uid'";
				$result = mysql_query($sql);
				if($result){
					list($twit_chk) = mysql_fetch_array($result);
					if($twit_chk==1){
						$sql = "SELECT * FROM twit_feeds WHERE uid='$uid'";
						$result = mysql_query($sql);
						if($result){
							list($twit_feed) = mysql_fetch_array($result);
							if(isset($twit_feed) && !empty($twit_feed) && !is_null($twit_feed)){
								$twitter = 1;
							}
							elseif(!isset($twit_feed) || empty($twit_feed) || is_null($twit_feed)){
								$twitter = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `twit_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$twitter = $this->checkTwitter();
						}
					}
					else {
						$twitter = $twit_chk;
					}
				}
				return $twitter;
			}
		}
		
		function checkYouTube(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT youtube FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($yout_chk) = mysql_fetch_array($result);
					if($yout_chk==1){
						$sql = "SELECT * FROM you_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
						$result = mysql_query($sql);
						if($result){
							list($yout_feed) = mysql_fetch_array($result);
							if(isset($yout_feed) && !empty($yout_feed) && !is_null($yout_feed)){
								$youtube = 1;
							}
							elseif(!isset($yout_feed) || empty($yout_feed) || is_null($yout_feed)){
								$youtube = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `yout_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$youtube = $this->checkYouTube();
						}
					}
				}
				return $youtube;
			}
		}
		
		function checkYouTubeUid($uid){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT youtube FROM usercred WHERE id='$uid'";
				$result = mysql_query($sql);
				if($result){
					list($yout_chk) = mysql_fetch_array($result);
					if($yout_chk==1){
						$sql = "SELECT * FROM you_feeds WHERE uid='$uid'";
						$result = mysql_query($sql);
						if($result){
							list($yout_feed) = mysql_fetch_array($result);
							if(isset($yout_feed) && !empty($yout_feed) && !is_null($yout_feed)){
								$youtube = 1;
							}
							elseif(!isset($yout_feed) || empty($yout_feed) || is_null($yout_feed)){
								$youtube = 0;
							}
						}
						elseif(!$result){
							$sql = "CREATE TABLE IF NOT EXISTS `yout_feeds` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` tinytext COLLATE utf8_unicode_ci NOT NULL,`uid` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;";
							$result = mysql_query($sql);
							$youtube = $this->checkYouTube();
						}
					}
					else {
						$youtube = $yout_chk;
					}
				}
				return $youtube;
			}
		}
		
		function postFeed($message){
			$loggedin = $this->checkLoggedIn();
			if($loggedin==1){
				$profile_chk = $this->checkProfile();
				if($profile_chk==1){
					if(isset($message) && !empty($message) && !is_null($message)){
						$this->connectDB();
						$username = $this->getUsername();
						$uid = $this->getUid($username);
						$time = $this->getTimeZoneDateTime(0);
						$sql = "INSERT INTO `prof_feeds`(`feed`,`time`,`uid`) VALUES ('$message','$time','$uid')";
						$result = mysql_query($sql);
						$time = $this->getDateTime($time);
						$avatar = $this->getAvatar($username,'24px','24px');
						$post_feed = "
						<span class='feed_title'>$avatar <b>$username:</b></span><br />
						<div class='feed_box'>
							<span class='feed_content'>$message</span><br />
							<span class='feed_date'>$time</span>
						</div><br />";
						return $post_feed;
					}
				}
			}
		}
		
		function checkLoggedIn(){
			$username = $this->getUsername();
			$password = $this->getPassword();
			if(isset($username) && isset($password)){
				if(!empty($username) && !empty($password)){
					if(!is_null($username) && !is_null($password)){
						$message = "You are Logged in!";
						$status = 1;
					}
					else {
						$message = "You need to Login!";
						$status = 0;
					}
				}
				else {
					$message = "You need to Login!";
					$status = 0;
				}
			}
			else {
				$message = "You need to Login!";
				$status = 0;
			}
			return $status;
		}
		
		function login($username,$password){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="0"){
				if(isset($username) && !empty($username) && !is_null($username)){
					if(isset($password) && !empty($password) && !is_null($password)){
						$password = $password;
						$this->connectDB();
						$sql = "SELECT user,pass FROM usercred WHERE user='$username'";
						$result = mysql_query($sql);
						if($result){
							list($luser,$lpass) = mysql_fetch_array($result);
							if($luser==$username){
								if($lpass==$password){
									$this->createSession('username',$username);
									$this->createSession('password',$password);
								}
								else{
									$login .= "<a href='javascript:loadPage(\"home\");'><-- Go Back</a><br /><span style='color: #FF0000;'>You have entered an incorrect Password.</span>";
								}
							}
							else{
								$login .= "<a href='javascript:loadPage(\"home\");'><-- Go Back</a><br /><span style='color: #FF0000;'>You have entered an incorrect Username.</span>";
							}
						}
					}
					else {
						$login .= "<a href='javascript:loadPage(\"home\");'><-- Go Back</a><br /><span style='color: #FF0000;'>Please enter a Password.</span>";
					}
				}
				else {
					$login .= "<a href='javascript:loadPage(\"home\");'><-- Go Back</a><br /><span style='color: #FF0000;'>Please enter a Username.</span>";
				}
			}
			else {
				$login .= "<a href='javascript:loadPage(\"home\");'><-- Go Back</a><br /><span style='color: #FF0000;'>You are already Logged in!</span>";
			}
			return $login;
		}
		
		function signup($signup_fname,$signup_lname,$signup_uname,$signup_email,$signup_email2,$signup_pass,$signup_pass2){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="0"){
				$this->connectDB();
				if(isset($signup_fname) && !empty($signup_fname) && !is_null($signup_fname)){
					if(isset($signup_lname) && !empty($signup_lname) && !is_null($signup_lname)){
						if(isset($signup_uname) && !empty($signup_uname) && !is_null($signup_uname)){
							$uname_chk_sql = "SELECT * FROM usercred WHERE user='$signup_uname'";
							$uname_chk_result = mysql_query($uname_chk_sql);
							$uname_chk_num = mysql_num_rows($uname_chk_result);
							if($uname_chk_num >= 1){
								$signup .= "<span style='color: #FF0000;'>Username is already in use.</span>";
							}
							else {
								if(isset($signup_pass) && !empty($signup_pass) && !is_null($signup_pass)){
									if(isset($signup_pass2) && !empty($signup_pass2) && !is_null($signup_pass2)){
										$signup_pass = md5($signup_pass);
										$signup_pass2 = md5($signup_pass2);
										if($signup_pass==$signup_pass2){
											if(isset($signup_email) && !empty($signup_email) && !is_null($signup_email)){
												if(isset($signup_email2) && !empty($signup_email2) && !is_null($signup_email2)){
													if($signup_email==$signup_email2){
														$email_chk_sql = "SELECT * FROM usercred WHERE email='$signup_email'";
														$email_chk_result = mysql_query($email_chk_sql);
														$email_chk_num = mysql_num_rows($email_chk_result);
														if($email_chk_num >= 1){
															$signup .= "<span style='color: #FF0000;'>Email Address is already in use.</span>";
														}
														else {
															$time = $this->getTimeZoneDateTime(0);
															$tmpName = 'img/no_avatar.png';
															$fp = fopen($tmpName, 'r');
															$no_avatar = fread($fp, filesize($tmpName));
															$no_avatar = addslashes($no_avatar);
															fclose($fp);
		    												$sql = "INSERT INTO `usercred` (`user`,`pass`,`email`,`fname`,`lname`,`ulev`,`time`,`bio`,`avatar`,`bebo`,`facebook`,`myspace`,`profile`,`twitter`,`youtube`) VALUES ('$signup_uname','$signup_pass','$signup_email','$signup_fname','$signup_lname','9','$time','','$no_avatar','0','0','0','1','0','0')";
		    												$result = mysql_query($sql) or die('Cannot add user: '.mysql_error());
		    												$uid = mysql_insert_id();
		    												$sql2 = "INSERT INTO `prof_feeds` (`feed`,`time`,`uid`) VALUES ('Welcome $signup_uname to Social Feed :)','$time','$uid')";
		    												$result2 = mysql_query($sql2) or die('Cannot add user: '.mysql_error());
		    												$sql3 = "INSERT INTO follows (`user`,`uid`,`time`,`suser`,`suid`,`profile`) VALUES ('$signup_uname','$uid','$time','bass2k8','8','1')";
		    												$result3 = mysql_query($sql3) or die('Cannot add user: '.mysql_error());
		    												$sql4 = "INSERT INTO you_feeds (`user`,`uid`) VALUES ('s0c1alf33d5','$uid')";
		    												$result4 = mysql_query($sql4) or die('Cannot add user: '.mysql_error());
		    												$sql5 = "INSERT INTO twit_feeds (`user`,`uid`) VALUES ('s0c1alf33d5','$uid')";
		    												$result5 = mysql_query($sql5) or die('Cannot add user: '.mysql_error());
		    												$sql6 = "INSERT INTO face_feeds (`user`,`uid`) VALUES ('0','$uid')";
		    												$result6 = mysql_query($sql6) or die('Cannot add user: '.mysql_error());
		    												$sql7 = "INSERT INTO follows (`user`,`uid`,`time`,`suser`,`suid`,`profile`) VALUES ('$signup_uname','$uid','$time','jobe','4','1')";
		    												$result7 = mysql_query($sql7) or die('Cannot add user: '.mysql_error());
		    												$this->createSession('username',$signup_uname);
															$this->createSession('password',$signup_pass);
														}
													}
													else {
														$signup .= "<span style='color: #FF0000;'>Your Email Addresses do not match.</span>";
													}
												}
												else {
													$signup .= "<span style='color: #FF0000;'>Please retype your Email Address.</span>";
												}
											}
											else {
												$signup .= "<span style='color: #FF0000;'>Please enter your Email Address.</span>";
											}
										}
										else {
											$signup .= "<span style='color: #FF0000;'>Your Passwords do not match.</span>";
										}
							    	}
							    	else {
							    		$signup .= "<span style='color: #FF0000;'>Please retype your Password.</span>";
							    	}
								}		
								else	 {
							    	$signup .= "<span style='color: #FF0000;'>Please enter a Password.</span>";
								}		
							}
						}
						else {
							$signup .= "<span style='color: #FF0000;'>Please enter a Username.</span>";
						}
					}
					else {
						$signup .= "<span style='color: #FF0000;'>Please enter your Last Name.</span>";
					}
				}
				else {
					$signup .= "<span style='color: #FF0000;'>Please enter your First Name.</span>";
				}
			}
			return $signup;
		}
		
		function loginForm(){
			$login_form = "
			<table>
				<tr>
					<td><b>Username:</b></td>
					<td><input type='text' id='login_username' name='login_username' onKeyPress='submitLogin(event)'/></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Password:</b></td>
					<td><input type='password' id='login_password' name='login_password' onKeyPress='submitLogin(event)'/></td>
					<td><input type='button' id='login_button' onClick=\"login()\" value='Login'/></td>
				</tr>
			</table>";
			return $login_form;
		}
		
		function loginFormSide(){
			$login_form = "
			<table>
				<tr>
					<td><b>Username:</b></td>
				</tr>
				<tr>
					<td><input type='text' id='login_username' name='login_username' onKeyPress='submitLogin(event)'/></td>
				</tr>
				<tr>
					<td><b>Password:</b></td>
				</tr>
				<tr>
					<td><input type='password' id='login_password' name='login_password' onKeyPress='submitLogin(event)'/></td>
				</tr>
				<tr>
					<td><input type='button' id='login_button' onClick=\"login()\" value='Login'/></td>
					
				</tr>
			</table>";
			return $login_form;
		}
		
		function arrayFollowing(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$uid = $this->getUid($this->getUsername());
				$prof_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$prof_result = mysql_query($prof_sql);
				$suid_arr = array();
				while(list($suid) = mysql_fetch_array($prof_result)){
					array_push($suid_arr,"$suid");
				}
				return $suid_arr;
			}
		}
		
		function listFollowing(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$uid = $this->getUid($this->getUsername());
				$prof_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$prof_result = mysql_query($prof_sql);
				while(list($prof_suid) = mysql_fetch_array($prof_result)){
					$prof_user_sql = "SELECT user FROM usercred WHERE id='$prof_suid'";
					$prof_user_result = mysql_query($prof_user_sql);
					list($prof_user) = mysql_fetch_array($prof_user_result);
					if($prof_user!="s0c1alf33d5" && $prof_user!="0" && isset($prof_user) && !is_null($prof_user)){
						$following .= "
						<br /><a href=\"javascript:public_user('$prof_user')\"><img src=\"functions.php?avatar=$prof_user\" width=\"24px\"/> $prof_user</a><br />";
					}
				}
				$bebo_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$bebo_result = mysql_query($bebo_sql);
				while(list($bebo_suid) = mysql_fetch_array($bebo_result)){
					$bebo_user_sql = "SELECT user FROM bebo_feeds WHERE uid='$bebo_suid'";
					$bebo_user_result = mysql_query($bebo_user_sql);
					list($bebo_user) = mysql_fetch_array($bebo_user_result);
					if($bebo_user!="s0c1alf33d5" && $bebo_user!="0" && isset($bebo_user) && !is_null($bebo_user)){
						$bebo_avatar_sql = "SELECT user FROM usercred WHERE id='$bebo_suid'";
						$bebo_avatar_result = mysql_query($bebo_avatar_sql);
						list($bebo_avatar) = mysql_fetch_array($bebo_avatar_result);
						$following .= "
						<br /><a href=\"http://www.bebo.com/$bebo_user\"><img src=\"img/bebo_32.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$bebo_avatar\" width=\"24px\"/> $bebo_user</a><br />";
				    }
				}
				$face_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$face_result = mysql_query($face_sql);
				while(list($face_suid) = mysql_fetch_array($face_result)){
					$face_user_sql = "SELECT user FROM face_feeds WHERE uid='$face_suid'";
					$face_user_result = mysql_query($face_user_sql);
					list($face_user) = mysql_fetch_array($face_user_result);
					if($face_user!="s0c1alf33d5" && $face_user!="0" && isset($face_user) && !is_null($face_user)){
						$face_avatar_sql = "SELECT user FROM usercred WHERE id='$face_suid'";
						$face_avatar_result = mysql_query($face_avatar_sql);
						list($face_avatar) = mysql_fetch_array($face_avatar_result);
						$following .= "
						<br /><a href=\"http://www.facebook.com/profile.php?id=$face_user\"><img src=\"img/FaceBook_24x24.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$face_avatar\" width=\"24px\"/> $face_user</a><br />";
				    }
				}
				$mysp_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$mysp_result = mysql_query($mysp_sql);
				while(list($mysp_suid) = mysql_fetch_array($mysp_result)){
					$mysp_user_sql = "SELECT user FROM mysp_feeds WHERE uid='$mysp_suid'";
					$mysp_user_result = mysql_query($mysp_user_sql);
					list($mysp_user) = mysql_fetch_array($mysp_user_result);
					if($mysp_user!="s0c1alf33d5" && $mysp_user!="0" && isset($mysp_user) && !is_null($mysp_user)){
						$mysp_avatar_sql = "SELECT user FROM usercred WHERE id='$mysp_suid'";
						$mysp_avatar_result = mysql_query($mysp_avatar_sql);
						list($mysp_avatar) = mysql_fetch_array($mysp_avatar_result);
						$following .= "
						<br /><a href=\"http://www.myspace.com/$mysp_user\"><img src=\"img/myspace_32.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$mysp_avatar\" width=\"24px\"/> $mysp_user</a><br />";
				    }
				}
				$twit_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$twit_result = mysql_query($twit_sql);
				while(list($twit_suid) = mysql_fetch_array($twit_result)){
					$twit_user_sql = "SELECT user FROM twit_feeds WHERE uid='$twit_suid'";
					$twit_user_result = mysql_query($twit_user_sql);
					list($twit_user) = mysql_fetch_array($twit_user_result);
					if($twit_user!="s0c1alf33d5" && $twit_user!="0" && isset($twit_user) && !is_null($twit_user)){
						$twit_avatar_sql = "SELECT user FROM usercred WHERE id='$twit_suid'";
						$twit_avatar_result = mysql_query($twit_avatar_sql);
						list($twit_avatar) = mysql_fetch_array($twit_avatar_result);
						$following .= "
						<br /><a href=\"http://www.twitter.com/$twit_user\"><img src=\"img/Twitter_24x24.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$twit_avatar\" width=\"24px\"/> $twit_user</a><br />";
				    }
				}
				$yout_sql = "SELECT suid FROM follows WHERE uid='$uid'";
				$yout_result = mysql_query($yout_sql);
				while(list($yout_suid) = mysql_fetch_array($yout_result)){
					$yout_user_sql = "SELECT user FROM you_feeds WHERE uid='$yout_suid'";
					$yout_user_result = mysql_query($yout_user_sql);
					list($yout_user) = mysql_fetch_array($yout_user_result);
					if($yout_user!="s0c1alf33d5" && $yout_user!="0" && isset($yout_user) && !is_null($yout_user)){
						$yout_avatar_sql = "SELECT user FROM usercred WHERE id='$yout_suid'";
						$yout_avatar_result = mysql_query($yout_avatar_sql);
						list($yout_avatar) = mysql_fetch_array($yout_avatar_result);
						$following .= "
						<br /><a href=\"http://www.youtube.com/user/$yout_user\"><img src=\"img/Youtube_24x24.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$yout_avatar\" width=\"24px\"/> $yout_user</a><br />";
				    }
				}
				return $following;
			}
		}
		
		function listFollowers(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$uid = $this->getUid($this->getUsername());
				$prof_sql = "SELECT uid FROM follows WHERE suid='$uid'";
				$prof_result = mysql_query($prof_sql);
				while(list($prof_suid) = mysql_fetch_array($prof_result)){
					$prof_user_sql = "SELECT user FROM usercred WHERE id='$prof_suid'";
					$prof_user_result = mysql_query($prof_user_sql);
					list($prof_user) = mysql_fetch_array($prof_user_result);
					if($prof_user!="s0c1alf33d5" && $prof_user!="0" && isset($prof_user) && !is_null($prof_user)){
						$followers .= "
						<br /><a href=\"javascript:public_user('$prof_user')\"><img src=\"functions.php?avatar=$prof_user\" width=\"24px\"/> $prof_user</a><br />";
					}
				}
				$bebo_sql = "SELECT uid FROM follows WHERE suid='$uid'";
				$bebo_result = mysql_query($bebo_sql);
				while(list($bebo_suid) = mysql_fetch_array($bebo_result)){
					$bebo_user_sql = "SELECT user FROM bebo_feeds WHERE uid='$bebo_suid'";
					$bebo_user_result = mysql_query($bebo_user_sql);
					list($bebo_user) = mysql_fetch_array($bebo_user_result);
					if($bebo_user!="s0c1alf33d5" && $bebo_user!="0" && isset($bebo_user) && !is_null($bebo_user)){
						$bebo_avatar_sql = "SELECT user FROM usercred WHERE id='$bebo_suid'";
						$bebo_avatar_result = mysql_query($bebo_avatar_sql);
						list($bebo_avatar) = mysql_fetch_array($bebo_avatar_result);
						$followers .= "
						<br /><a href='http://www.bebo.com/$bebo_user'><img src=\"img/bebo_32.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$bebo_avatar\" width=\"24px\"/> $bebo_user</a><br />";
				    }
				}
				$face_sql = "SELECT uid FROM follows WHERE suid='$uid'";
				$face_result = mysql_query($face_sql);
				while(list($face_suid) = mysql_fetch_array($face_result)){
					$face_user_sql = "SELECT user FROM face_feeds WHERE uid='$face_suid'";
					$face_user_result = mysql_query($face_user_sql);
					list($face_user) = mysql_fetch_array($face_user_result);
					if($face_user!="s0c1alf33d5" && $face_user!="0" && isset($face_user) && !is_null($face_user)){
						$face_avatar_sql = "SELECT user FROM usercred WHERE id='$face_suid'";
						$face_avatar_result = mysql_query($face_avatar_sql);
						list($face_avatar) = mysql_fetch_array($face_avatar_result);
						$followers .= "
						<br /><a href='http://www.facebook.com/profile.php?id=$face_user'><img src=\"img/FaceBook_24x24.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$face_avatar\" width=\"24px\"/> $face_user</a><br />";
				    }
				}
				$mysp_sql = "SELECT uid FROM follows WHERE suid='$uid'";
				$mysp_result = mysql_query($mysp_sql);
				while(list($mysp_suid) = mysql_fetch_array($mysp_result)){
					$mysp_user_sql = "SELECT user FROM mysp_feeds WHERE uid='$mysp_suid'";
					$mysp_user_result = mysql_query($mysp_user_sql);
					list($mysp_user) = mysql_fetch_array($mysp_user_result);
					if($mysp_user!="s0c1alf33d5" && $mysp_user!="0" && isset($mysp_user) && !is_null($mysp_user)){
						$mysp_avatar_sql = "SELECT user FROM usercred WHERE id='$mysp_suid'";
						$mysp_avatar_result = mysql_query($mysp_avatar_sql);
						list($mysp_avatar) = mysql_fetch_array($mysp_avatar_result);
						$followers .= "
						<br /><a href='http://www.myspace.com/$mysp_user'><img src=\"img/myspace_32.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$mysp_avatar\" width=\"24px\"/> $mysp_user</a><br />";
				    }
				}
				$twit_sql = "SELECT uid FROM follows WHERE suid='$uid'";
				$twit_result = mysql_query($twit_sql);
				while(list($twit_suid) = mysql_fetch_array($twit_result)){
					$twit_user_sql = "SELECT user FROM twit_feeds WHERE uid='$twit_suid'";
					$twit_user_result = mysql_query($twit_user_sql);
					list($twit_user) = mysql_fetch_array($twit_user_result);
					if($twit_user!="s0c1alf33d5" && $twit_user!="0" && isset($twit_user) && !is_null($twit_user)){
						$twit_avatar_sql = "SELECT user FROM usercred WHERE id='$twit_suid'";
						$twit_avatar_result = mysql_query($twit_avatar_sql);
						list($twit_avatar) = mysql_fetch_array($twit_avatar_result);
						$followers .= "
						<br /><a href='http://www.twitter.com/$twit_user'><img src=\"img/Twitter_24x24.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$twit_avatar\" width=\"24px\"/> $twit_user</a><br />";
				    }
				}
				$yout_sql = "SELECT uid FROM follows WHERE suid='$uid'";
				$yout_result = mysql_query($yout_sql);
				while(list($yout_suid) = mysql_fetch_array($yout_result)){
					$yout_user_sql = "SELECT user FROM you_feeds WHERE uid='$yout_suid'";
					$yout_user_result = mysql_query($yout_user_sql);
					list($yout_user) = mysql_fetch_array($yout_user_result);
					if($yout_user!="s0c1alf33d5" && $yout_user!="0" && isset($yout_user) && !is_null($yout_user)){
						$yout_avatar_sql = "SELECT user FROM usercred WHERE id='$yout_suid'";
						$yout_avatar_result = mysql_query($yout_avatar_sql);
						list($yout_avatar) = mysql_fetch_array($yout_avatar_result);
						$followers .= "
						<br /><a href='http://www.youtube.com/user/$yout_user'><img src=\"img/Youtube_24x24.png\" width=\"24px\"/>
				    	<img src=\"functions.php?avatar=$yout_avatar\" width=\"24px\"/> $yout_user</a><br />";
				    }
				}
				return $followers;
			}
		}
		
		function numFeeds(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT * FROM prof_feeds WHERE uid='".$this->getUid($this->getUsername())."'";
				$result = mysql_query($sql);
				if($result){
					$num_feeds = mysql_num_rows($result);
					return $num_feeds;
				}
			}
		}
		
		function numFollowing(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT * FROM follows WHERE uid='".$this->getUid($this->getUsername())."'";
				$result = mysql_query($sql);
				if($result){
					$num_following = mysql_num_rows($result);
					return $num_following;
				}
			}
		}
		
		function numFollowers(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT * FROM follows WHERE suid='".$this->getUid($this->getUsername())."'";
				$result = mysql_query($sql);
				if($result){
					$num_followers = mysql_num_rows($result);
					return $num_followers;
				}
			}
		}
		
		function feedsInfo(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$num_feeds = $this->numFeeds();
				$num_following = $this->numFollowing();
				$num_followers = $this->numFollowers();
				$feeds_info = "
				<table width='100%'>
					<tr>
						<td class='num_feeds'>$num_feeds</td>
						<td class='num_following'>$num_following</td>
						<td class='num_followers'>$num_followers</td>
					</tr>
					<tr>
						<td class='num_feeds_title'><a href=\"javascript:loadPage('profile');\"><b>Feeds</b></a></td>
						<td class='num_following_title'><a href=\"javascript:loadPage('settings_following');loadSidebar('settings');\"><b>Following</b></a></td>
						<td class='num_followers_title'><a href=\"javascript:loadPage('settings_followers');loadSidebar('settings');\"><b>Followers</b></a></td>
					</tr>
				</table>";
				return $feeds_info;
			}
		}
		
		function getFeedIcons(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$bebo = $this->checkBebo();
				if($bebo==1){
					$bebo = "<img src='img/bebo_32.png' width='24px'/>";
				}
				else {
					$bebo = "";
				}
				$facebook = $this->checkFacebook();
				if($facebook==1){
					$facebook = "<img src='img/FaceBook_24x24.png' width='24px'/>";
				}
				else {
					$facebook = "";
				}
				$myspace = $this->checkMySpace();
				if($myspace==1){
					$myspace = "<img src='img/myspace_32.png' width='24px'/>";
				}
				else {
					$myspace = "";
				}
				$twitter = $this->checkTwitter();
				if($twitter==1){
					$twitter = "<img src='img/Twitter_24x24.png' width='24px'/>";
				}
				else {
					$twitter = "";
				}
				$youtube = $this->checkYouTube();
				if($youtube==1){
					$youtube = "<img src='img/Youtube_24x24.png' width='24px'/>";
				}
				else {
					$youtube = "";
				}
				$feeds = "
				<span id='bebo_icon_stat'>$bebo</span>
				<span id='face_icon_stat'>$facebook</span>
				<span id='mysp_icon_stat'>$myspace</span>
				<span id='twit_icon_stat'>$twitter</span>
				<span id='yout_icon_stat'>$youtube</span>";
				return $feeds;
			}
		}
		
		function logout(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
		}
		
		function getLastName(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT lname FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($lname) = mysql_fetch_array($result);
				}
				return $lname;
			}
		}
		
		function getFullName(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$first_name = $this->getFirstName();
				$last_name = $this->getLastName();
				$full_name = "$first_name $last_name";
				return $full_name;
			}
		}
		
		function getTimeStamp(){
			$loggedin = $this->checkLoggedIn();
			if($loggedin=="1"){
				$this->connectDB();
				$sql = "SELECT timezone FROM usercred WHERE user='".$this->getUsername()."'";
				$result = mysql_query($sql);
				if($result){
					list($timezone) = mysql_fetch_array($result);
				}
			}
			if(isset($timezone)){
				$GMT = $timezone;
			}
			elseif(!$timezone){
				$GMT = "0";
			}
			$timestamp = $this->getTimeZoneDateTime($GMT);
			return $timestamp;
		}
		
		function dateToTimeStamp($seconds,$minutes,$hours,$day,$month,$year){
			echo $seconds.$minutes.$hours.$day.$month.$year;
			$timestamp = mktime($hours,$minutes,$seconds,$month,$day,$year);
			return $timestamp;
		}
		
		function combineFeeds($start,$limit){
			$feeds = array();
			$facebook = $_SESSION['facebook'];
			if(isset($facebook) && !empty($facebook) && !is_null($facebook)){
			    foreach($facebook as $face_feed){
			    	array_push($feeds, $face_feed);
			    }
			}
			$profile = $_SESSION['profile'];
			if(isset($profile) && !empty($profile) && !is_null($profile)){
			    foreach($profile as $prof_feed){
			    	array_push($feeds, $prof_feed);
			    }
			}
			$twitter = $_SESSION['twitter'];
			if(isset($twitter) && !empty($twitter) && !is_null($twitter)){
			    foreach($twitter as $twit_feed){
			    	array_push($feeds, $twit_feed);
			    }
			}
			$youtube = $_SESSION['youtube'];
			if(isset($youtube) && !empty($youtube) && !is_null($youtube)){
			    foreach($youtube as $yout_feed){
			    	array_push($feeds, $yout_feed);
			    }
			}
			if(isset($feeds) && !empty($feeds) && !is_null($feeds) && is_array($feeds)){
				rsort($feeds);
				$feeds = array_slice($feeds, $start, $limit);
				foreach($feeds as $feed){
				    $username = $feed['username'];
				    $avatar = $feed['id'];
				    $message = $feed['feed'];
				    $time = $feed['time'];
				    $time = $this->getDateTime($time);
				    $feed_disp .= "
				    <span class='feed_title'>$avatar <b>$username:</b></span><br />
				    <div class='feed_box'>
				    	<span class='feed_content'>$message</span><br />
				    	<span class='feed_date'>$time</span>
				    </div><br />";
				}
				$_SESSION['facebook'] = "";
				$_SESSION['profile'] = "";
				$_SESSION['twitter'] = "";
				$_SESSION['youtube'] = "";
				unset($_SESSION['facebook']);
				unset($_SESSION['profile']);
				unset($_SESSION['twitter']);
				unset($_SESSION['youtube']);
				return $feed_disp;
			}
		}
		
		function getTimeZoneDateTime($GMT){
			$timestamp=time();
			$timezones = array(
			'-12'=>'Pacific/Kwajalein',
			'-11'=>'Pacific/Samoa',
			'-10'=>'Pacific/Honolulu',
			'-9'=>'America/Juneau',
			'-8'=>'America/Los_Angeles',
			'-7'=>'America/Denver',
			'-6'=>'America/Mexico_City',
			'-5'=>'America/New_York',
			'-4'=>'America/Caracas',
			'-3.5'=>'America/St_Johns',
			'-3'=>'America/Argentina/Buenos_Aires',
			'-2'=>'Atlantic/Azores',
			'-1'=>'Atlantic/Azores',
			'0'=>'Europe/London',
			'1'=>'Europe/Paris',
			'2'=>'Europe/Helsinki',
			'3'=>'Europe/Moscow',
			'3.5'=>'Asia/Tehran',
			'4'=>'Asia/Baku',
			'4.5'=>'Asia/Kabul',
			'5'=>'Asia/Karachi',
			'5.5'=>'Asia/Calcutta',
			'6'=>'Asia/Colombo',
			'7'=>'Asia/Bangkok',
			'8'=>'Asia/Singapore',
			'9'=>'Asia/Tokyo',
			'9.5'=>'Australia/Darwin',
			'10'=>'Pacific/Guam',
			'11'=>'Asia/Magadan',
			'12'=>'Asia/Kamchatka'
			);
			date_default_timezone_set($timezones[$GMT]);
			if($GMT==-2)$timestamp-=3600;
			return $timestamp;
		}
		
		function timeZone($GMT,$timestamp){
			$multiplier = 6;
			$param = "+";
			$unix_hour = 3600;
			if($multiplier!=0){
				$excess = $param.($multiplier * $unix_hour);
			}
			elseif($multiplier==0){
				$excess = $param."1";
			}
			if($GMT==-12){
				$new_stamp = ($timestamp - 43200).$excess;
			}
			elseif($GMT==-11){
				$new_stamp = ($timestamp - 39600).$excess;
			}
			elseif($GMT==-10){
				$new_stamp = ($timestamp - 36000).$excess;
			}
			elseif($GMT==-9){
				$new_stamp = ($timestamp - 32400).$excess;
			}
			elseif($GMT==-8){
				$new_stamp = ($timestamp - 28800).$excess;
			}
			elseif($GMT==-7){
				$new_stamp = ($timestamp - 25200).$excess;
			}
			elseif($GMT==-6){
				$new_stamp = ($timestamp - 21600).$excess;
			}
			elseif($GMT==-5){
				$new_stamp = ($timestamp - 18000).$excess;
			}
			elseif($GMT==-4){
				$new_stamp = ($timestamp - 14400).$excess;
			}
			elseif($GMT==-3){
				$new_stamp = ($timestamp - 10800).$excess;
			}
			elseif($GMT==-2){
				$new_stamp = ($timestamp - 7200).$excess;
			}
			elseif($GMT==-1){
				$new_stamp = ($timestamp - 3600).$excess;
			}
			elseif($GMT==0){
				$new_stamp = ($timestamp + $excess);
			}
			elseif($GMT==+12){
				$new_stamp = ($timestamp + 43200).$excess;
			}
			elseif($GMT==+11){
				$new_stamp = ($timestamp + 39600).$excess;
			}
			elseif($GMT==+10){
				$new_stamp = ($timestamp + 36000).$excess;
			}
			elseif($GMT==+9){
				$new_stamp = ($timestamp + 32400).$excess;
			}
			elseif($GMT==+8){
				$new_stamp = ($timestamp + 28800).$excess;
			}
			elseif($GMT==+7){
				$new_stamp = ($timestamp + 25200).$excess;
			}
			elseif($GMT==+6){
				$new_stamp = ($timestamp + 21600).$excess;
			}
			elseif($GMT==+5){
				$new_stamp = ($timestamp + 18000).$excess;
			}
			elseif($GMT==+4){
				$new_stamp = ($timestamp + 14400).$excess;
			}
			elseif($GMT==+3){
				$new_stamp = ($timestamp + 10800).$excess;
			}
			elseif($GMT==+2){
				$new_stamp = ($timestamp + 7200).$excess;
			}
			elseif($GMT==+1){
				$new_stamp = ($timestamp + 3600).$excess;
			}
			return $new_stamp;
		}
		
		function getCurTime(){
			return date("H:ia",$this->getTimeStamp());
		}
		
		function getCurDate(){
			return date("jS F Y",$this->getTimeStamp());
		}
		
		function getDateTime($timestamp){
			$time = date("H:ia",$timestamp);
			$date = date("jS F Y",$timestamp);
			$date_time = "$time on $date";
			return $date_time;
		}
		
		function createSession($name,$value){
			$_SESSION[$name] = "";
			$_SESSION[$name] = $value;
		}
	}
?>