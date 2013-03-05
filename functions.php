<?php
	require_once('classes.php');
	$sf = new SocialFeed();
	if(isset($_GET['avatar'])){
		$loggedin = $sf->checkLoggedIn();
	    if($loggedin=="1"){
	    	$avatar = $_GET['avatar'];
	    	$sf->connectDB();
	    	$av_uid_sql = "SELECT id FROM usercred WHERE user='$avatar'";
	    	$av_uid_result = mysql_query($av_uid_sql);
	    	list($uid) = mysql_fetch_array($av_uid_result);
	    	$sql = "SELECT avatar FROM usercred WHERE id='$uid'";
	    	$result = mysql_query($sql);
	    	if($result){
	    		list($avatar) = mysql_fetch_array($result);
	    		 header('Content-Length: '.strlen($avatar));
				 header("Content-type: image/jpg");
				 echo $avatar;
				 exit();
	    	}
	    }
	}
	elseif(isset($_GET['r'])){
		$loggedin = $sf->checkLoggedIn();
	    if($loggedin=="1"){
	    	$username = $sf->getUsername();
			if(isset($_GET['p'])){
				$page = $_GET['p'];
				$start = 0;
				$limit = 5;
				if(isset($_GET['s']) && !empty($_GET['s']) && !is_null($_GET['s'])){
					$start = $_GET['s'];
				}
				else {
					$limit = 20;
				}
				$start_orig = ($start + 1);
				$start = ($start * $limit);
				if($page=="home"){
					$following_uids = $sf->arrayFollowing();
					$feeds = $sf->getProfileFeeds($following_uids,$start,$limit);
					if(isset($_GET['s']) && !empty($_GET['s']) && !is_null($_GET['s'])){
						
					}
					else {
						$content .= "
						<textarea id='post_message' style='height: 45px; width: 555px;'></textarea><br />
						<input type='button' id='post_button' onClick='post_feed()' value='Post'/>
						<br /><br />
						<div id='post_feed'></div>";
					}
					$content .= "
					$feeds
					<div id='older_feeds_$start_orig'>
						<center><input type='button' onClick='olderFeeds(\"home\",\"$start_orig\",\"blank\")' value='Older Feeds'/></center>
					</div>";
				}
				elseif($page=="profile"){
					$feeds = $sf->getProfileFeeds($username,$start,$limit);
					$content .= "
					$feeds
					<div id='older_feeds_$start_orig'>
						<center><input type='button' onClick='olderFeeds(\"profile\",\"$start_orig\",\"blank\")' value='Older Feeds'/></center>
					</div>";
				}
				elseif($page=="public"){
					$pub_user = $_GET['u'];
					$feeds = $sf->getProfileFeeds($pub_user,$start,$limit);
					$content .= "
					$feeds
					<div id='older_feeds_$start_orig'>
						<center><input type='button' onClick='olderFeeds(\"public\",\"$start_orig\",\"$pub_user\")' value='Older Feeds'/></center>
					</div>";
				}
				elseif($page=="settings"){
					$content .= "
					<span class=\"feed_title\">Home:</span>
    				<div class=\"feed_box\">
    					Welcome to the Settings page, please select a section in the sidebar to your right <b>--></b>
    				</div>";
				}
				elseif($page=="settings_avatar"){
				$avatar = $sf->getAvatar($username,'100px','100px');
					$content .= "
					<span class=\"feed_title\">Avatar:</span>
    				<div class=\"feed_box\">
						<b>Current Avatar:</b><br />
						$avatar<br /><br />
						<form name=\"change_avatar_form\" enctype=\"multipart/form-data\" action=\"functions.php\" method=\"post\">
							<input type=\"hidden\" name=\"change_avatar_confirm\" value=\"1\"/>
							<b>Choose an avatar to upload:</b> <input name=\"change_avatar\" type=\"file\"/><br /><br />
							<input type=\"submit\" name=\"change_avatar_submit\" value=\"Upload Avatar\" />
						</form>
					</div>";
				}
				elseif($page=="settings_bio"){
					$bio = $sf->getBio();
					$cur_bio = stripslashes($bio);
					$sym = array('/','<','>','"',"'");
					$sym_chg = array('&#47;','&lt;','&gt;','&quot;','&#39;');
					$cur_bio = str_replace($sym, $sym_chg, $cur_bio);
					$content .= "
					<span class=\"feed_title\">Bio:</span>
    				<div class=\"feed_box\">
    					<b>Current Bio:</b> <span id='current_bio'>$bio</span><br /><br />
    					<b>Change Bio:</b> <input type='text' id='bio_text' value='$cur_bio'/>
    					<input type='button' id='bio_button' onClick='change_bio()' value='Change Bio'/>
    				</div>";
				}
				elseif($page=="settings_email"){
					$email = $sf->getEmail();
					$content .= "
					<span class=\"feed_title\">Email Address:</span>
    				<div class=\"feed_box\">
    					<b>Current Email:</b> <span id='current_email'>$email</span><br /><br />
    					<b>New Email:</b> <input type='text' id='email_text'/><br />
    					<b>Retype Email:</b> <input type='text' id='email_text2'/>
    					<input type='button' id='email_button' onClick='change_email()' value='Change Email'/>
    				</div>";
				}
				elseif($page=="settings_pass"){
					$content .= "
					<span class=\"feed_title\">Password:</span>
    				<div class=\"feed_box\">
    					<b>Status:</b> <span id='password_status'></span><br /><br />
    					<b>New Password:</b> <input type='password' id='pass_text'/><br />
    					<b>Retype Password:</b> <input type='password' id='pass_text2'/>
    					<input type='button' id='pass_button' onClick='change_pass()' value='Change Password'/>
    				</div>";
				}
				elseif($page=="settings_facebook"){
					$facebook = $sf->checkFacebook();
					if($facebook==0){
						$facebook = "<span id='feed_status'>(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('facebook')\">Enable?</a></span>";
					}
					elseif($facebook==1){
						$face_user = $sf->getFacebookUser();
						$facebook = "
						Setting up your Facebook is easy! Just login to Facebook, and go to your Profile, then copy the <b>id</b> from the address bar to the input box and click <b>change</b>.<br /><br /><img src=\"img/Facebook_How-to_1.png\" height=\"25px\"/><br /><br />
						And if you've setup your Facebook to show your username just view one of your album photos and get the <b>id</b> from there.<br /><br /><img src=\"img/Facebook_How-to_2.png\" height=\"25px\"/><br /><br />
						Or you can view one of your Profile Pictures and get the <b>subj</b> from there.<br /><br /><img src=\"img/Facebook_How-to_3.png\" height=\"21px\"/><br />
						<br /><b>Status:</b> <span id='facebook_status'></span><br />
						<span id='feed_status'>
							(<span class=\"feed_enabled\">Enabled</span>)
							<a href=\"javascript:disable_feed('facebook')\">Disable?</a>
						</span><br />
						<div class=\"input_text\"><input type=\"text\" name=\"face_usr_chg\" id=\"face_usr_chg\" value=\"$face_user\" class=\"input_text\"/></div>
						<input type=\"button\" name=\"facebook_submit\" onClick=\"face_usr_chg('face_usr_chg','$face_user')\" value=\"Change!\"/>";
					}
					$content .= "
					<span class=\"feed_title\"><img src=\"img/FaceBook_24x24.png\" width=\"24px\"/> Facebook:</span>
    				<div class=\"feed_box\">
    					$facebook
    				</div>";
				}
				elseif($page=="settings_twitter"){
					$twitter = $sf->checkTwitter();
					if($twitter==0){
						$twitter = "<span id='feed_status'>(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('twitter')\">Enable?</a></span>";
					}
					elseif($twitter==1){
						$twit_user = $sf->getTwitterUser();
						$twitter = "
						Setting up your Twitter is easy! Just type in your Twitter Username.<br />
						<br /><b>Status:</b> <span id='twitter_status'></span><br />
						<span id='feed_status'>
							(<span class=\"feed_enabled\">Enabled</span>)
							<a href=\"javascript:disable_feed('twitter')\">Disable?</a>
						</span><br />
						<div class=\"input_text\"><input type=\"text\" name=\"twit_usr_chg\" id=\"twit_usr_chg\" value=\"$twit_user\" class=\"input_text\"/></div>
						<input type=\"button\" name=\"twitter_submit\" onClick=\"twit_usr_chg('twit_usr_chg','$twit_user')\" value=\"Change!\"/>";
					}
					$content .= "
					<span class=\"feed_title\"><img src=\"img/Twitter_24x24.png\" width=\"24px\"/> Twitter:</span>
    				<div class=\"feed_box\">
    					$twitter
    				</div>";
				}
				elseif($page=="settings_youtube"){
					$youtube = $sf->checkYouTube();
					if($youtube==0){
						$youtube = "<span id='feed_status'>(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('youtube')\">Enable?</a></span>";
					}
					elseif($youtube==1){
						$yout_user = $sf->getYouTubeUser();
						$youtube = "
						Setting up your YouTube is easy! Just type in your YouTube Username.<br />
						<br /><b>Status:</b> <span id='youtube_status'></span><br />
						<span id='feed_status'>
							(<span class=\"feed_enabled\">Enabled</span>)
							<a href=\"javascript:disable_feed('youtube')\">Disable?</a>
						</span><br />
						<div class=\"input_text\"><input type=\"text\" name=\"yout_usr_chg\" id=\"yout_usr_chg\" value=\"$yout_user\" class=\"input_text\"/></div>
						<input type=\"button\" name=\"youtube_submit\" onClick=\"yout_usr_chg('yout_usr_chg','$yout_user')\" value=\"Change!\"/>";
					}
					$content .= "
					<span class=\"feed_title\"><img src=\"img/Youtube_24x24.png\" width=\"24px\"/> YouTube:</span>
    				<div class=\"feed_box\">
    					$youtube
    				</div>";
				}
				elseif($page=="settings_followers"){
					$content .= "
					<span class=\"feed_title\">Followers:</span>
    				<div class=\"feed_box\">
					".$sf->listFollowers().
					"</div>";
				}
				elseif($page=="settings_following"){
					$content .= "
					<span class=\"feed_title\">Following:</span>
    				<div class=\"feed_box\">
					".$sf->listFollowing().
					"</div>";
				}
			}
			elseif(isset($_GET['n'])){
				$content .= "
				<a href=\"javascript:loadPage('home');loadSidebar('home');\">Home</a> | 
				<a href=\"javascript:loadPage('about');loadSidebar('about');\">About</a> | 
				<a href=\"javascript:loadPage('contact');loadSidebar('contact');\">Contact</a> | 
				<a href=\"javascript:loadPage('profile');loadSidebar('home');\">Profile</a> | 
				<a href=\"javascript:loadPage('settings');loadSidebar('settings');\">Settings</a> | 
				<a href=\"javascript:logout();\">Logout</a>";
			}
			elseif(isset($_GET['s'])){
				$sidebar = $_GET['s'];
				if($sidebar=="home"){
					$feeds_info = $sf->feedsInfo();
					$username = $sf->getUsername();
					$avatar = $sf->getAvatar($username,'50px','50px');
					$content .= "
					<table>
						<tr>
							<td>$avatar</td>
							<td>Welcome <a href=\"javascript:public_user('$username')\">$username</a>.</td>
						</tr>
					</table>
					$feeds_info";
				}
				elseif($sidebar=="settings"){
					$content .= "
					<a href=\"javascript:loadPage('settings');\"><h2>Home</h2></a><br />
					<hr />
					<h2>Account</h2><br />
					<a href=\"javascript:loadPage('settings_avatar');\">Avatar</a><br />
					<a href=\"javascript:loadPage('settings_bio');\">Bio</a><br />
					<a href=\"javascript:loadPage('settings_email');\">Email Address</a><br />
					<a href=\"javascript:loadPage('settings_pass');\">Password</a>
					<hr />
					<h2>Feeds</h2><br />
					<a href=\"javascript:loadPage('settings_facebook');\">Facebook</a><br />
					<a href=\"javascript:loadPage('settings_twitter');\">Twitter</a><br />
					<a href=\"javascript:loadPage('settings_youtube');\">YouTube</a>
					<hr />
					<h2>Subscriptions</h2><br />
					<a href=\"javascript:loadPage('settings_followers');\">Followers</a><br />
					<a href=\"javascript:loadPage('settings_following');\">Following</a>";
				}
			}
		}
		else{
			if(isset($_GET['p'])){
				$page = $_GET['p'];
				if($page=="home"){
		    		$content .= "Welcome to Social Feed.";
		    	}
		    	elseif($page=="signup"){
		    		$content .= "
					<h3>Name</h3>
					<div class=\"feed_box\">
					    <span class=\"feed_content\">
					    	<div class=\"signup_fname\"><b>First Name:</b> <input type=\"text\" id=\"signup_fname\" class=\"input_text\"/></div>
					    	<div class=\"signup_lname\"><b>Last Name:</b> <input type=\"text\" id=\"signup_lname\" class=\"input_text\"/></div>
					    	<input type='hidden' id='signup_check' value='Signup Check'/>
					    </span>
					    <br /><br />
					</div>
					<h3>Username & Password</h3>
					<div class=\"feed_box\">
					    <span class=\"feed_content\">
					    	<div class=\"signup_uname\"><b>Username</b>: <input type=\"text\" id=\"signup_uname\" class=\"input_text\"/></div>
					    	<div class=\"signup_pass\"><b>Password:</b> <input type=\"password\" id=\"signup_pass\" class=\"input_text\"/></div>
					    	<div class=\"signup_pass2\"><b>Confirm</b>: <input type=\"password\" id=\"signup_pass2\" class=\"input_text\"/></div>
					    </span>
					</div>
					<h3>Email Address</h3>
					<div class=\"feed_box\">
					    <span class=\"feed_content\">
					    	<div class=\"signup_email\"><b>Email Address:</b> <input type=\"text\" id=\"signup_email\" class=\"input_text\"/></div>
					    	<div class=\"signup_email2\"><b>Confirm Email Address</b>: <input type=\"text\" id=\"signup_email2\" class=\"input_text\"/></div>
					    	<br />
					    	<input type=\"button\" id=\"signup_submit\" onClick=\"signup();\" value=\"Sign Up\"/>
					    </span>
					</div>";
		    	}
		    }
		    elseif(isset($_GET['n'])){
				$content .= "
				<a href=\"javascript:loadPage('home');loadSidebar('home');\">Home</a> | 
				<a href=\"javascript:loadPage('about');loadSidebar('about');\">About</a> | 
				<a href=\"javascript:loadPage('contact');loadSidebar('contact');\">Contact</a> | 
				<a href=\"javascript:loadPage('signup');loadSidebar('signup');\">Signup</a> | 
				<a href=\"javascript:loadPage('home');loadSidebar('home');\">Login</a>";
			}
		    elseif(isset($_GET['s'])){
				$sidebar = $_GET['s'];
		    	if($sidebar=="home"){
		    		$content .= $sf->loginFormSide();
		    	}
		    	elseif($sidebar=="signup"){
		    		$content .= "Signing up is easy! Just Enter your Name, Username, Password and Email.";
		    	}
		    }
		}
		if(isset($_GET['p'])){
			$page = $_GET['p'];
			if($page=="about"){
				$content .= "
				Social Feed is the only place you need to visit to view your social feeds.
				Social Feed is intended to be a safe, easy social networking tool to help you channel all your social content into one manageable place.
				Currently Social Feed supports the main, most popular Social Networking sites, which integrates Facebook, Twitter and YouTube updates and displays them in chronological order.
				<br /><br />
				Social Feed was designed and developed by a 16 year old lad named Ben Staker, during the Summer of 2009.
			    In August 2009 Social Feed Alpha was complete, and private testing began in late August.
				After 2 years of abadoning Social Feed, Ben Staker is now at University studying a Web Technologies course in Portsmouth, for which he will be continuingly developing for Social Feed in his spare time.
			    <br /><br />
			    <table>
			    	<tr>
			    		<td style='text-align: center;'><h3>Ben Staker</h3> - Lead Developer</td>
			    	</tr>
			    	<tr>
			    		<td style='text-align: center;'><img src=\"img/bass2k8.jpg\" width=\"170px\"/></td>
			    	</tr>
			    	<tr>
			    		<td style='text-align: center;'>
			    			<span class='feed_date'>
			    				Works actively as Social Feeds lead developer, Ben codes in PHP / XHTML / CSS & Javascript.
			    				Self taught, Independent Learner, now in College starting a course in IT Practitioners - National Diploma.
			    				My Skills in PHP are near to <a href='http://img171.imageshack.us/img171/6227/screenshot184.png'>perfect</a>.
			    			</span>
			    		</td>
			    	</tr>
			    </table>
			    ";
			}
			elseif($page=="contact"){
			    $content .= "
			    <form method=\"post\" enctype=\"multipart/form-data\" action=\"http://www.formspring.com/forms/index.php\" class=\"fsForm fsSingleColumn\" id=\"fsForm685692\" name=\"fsForm685692\">
			          <input type=\"hidden\" name=\"form\" value=\"685692\" /> <input type=\"hidden\" name=\"viewkey\" value=\"yDH9FEEKfd\" /> <input type=\"hidden\" name=\"hidden_fields\" id=\"hidden_fields685692\" value=\"\" /> <input type=\"hidden\" name=\"_submit\" value=\"1\" /> <input type=\"hidden\" name=\"incomplete\" id=\"incomplete685692\" value=\"\" />
			          <div class=\"fsPage\" id=\"fsPage685692-1\">
			            <table class=\"fsSection fsTable\" id=\"fsSection6966428\" cellspacing=\"0\" cellpadding=\"0\">
			              <tbody>
			                <tr valign=\"top\" class=\"fsRowTop\">
			                  <td class=\"fsRowOpen\">
			                    &nbsp;
			                  </td>
			                  <td colspan=\"1\" class=\"fsRowBody\"></td>
			                  <td class=\"fsRowClose\">
			                    &nbsp;
			                  </td>
			                </tr>
			                <tr valign=\"top\" id=\"fsRow685692-1\" class=\"fsRow fsSectionRow\">
			                  <td class=\"fsRowOpen\">
			                    &nbsp;
			                  </td>
			                  <td colspan=\"1\" class=\"fsRowBody fsCell fsSectionCell\" id=\"fsCell6966428\">
			                    <div class=\"FSSectionTop\"></div>
			                    <h2 class=\"fsSectionHeading\">
			                      <h2>Contact Social Feed</h2>
			                    </h2>
			                    <div class=\"fsSectionText\">
			    <br />
			                    </div>
			                    <div class=\"FSSectionBottom\"></div>
			                  </td>
			                  <td class=\"fsRowClose\">
			                    &nbsp;
			                  </td>
			                </tr>
			                <tr valign=\"top\" id=\"fsRow685692-2\" class=\"fsRow fsFieldRow fsLastRow\" style=\"\">
			                  <td class=\"fsRowOpen\">
			                    &nbsp;
			                  </td>
			                  <td class=\"fsRowBody fsCell fsFieldCell fsFirst fsLast fsLabelVertical\" colspan=\"1\" id=\"fsCell6966429\">
			       
			                    <div class=\"fsSubFieldGroup\">
			                      <div class=\"fsSubField\">
			          <b>First Name</b><br>
			    <div class=\"input_text\"><input type=\"text\" id=\"field6966429-first\" name=\"field6966429-first\" size=\"20\" value=\"\" class=\"input_text fsField fsFieldName fsRequired\" /></div><br /><br />
			                      </div>
			                      <div class=\"fsSubField\">
			                       <b>Last Name</b><br><div class=\"input_text\"><input type=\"text\" id=\"field6966429-last\" name=\"field6966429-last\" size=\"20\" value=\"\" class=\"input_text fsField\" /></div><br /><br />
			                      </div>
			                    </div>
			                    <div class=\"clear\"></div>
			                  </td>
			                  <td class=\"fsRowClose\">
			                    &nbsp;
			                  </td>
			                </tr>
			                <tr valign=\"top\" id=\"fsRow685692-3\" class=\"fsRow fsFieldRow fsLastRow\" style=\"\">
			                  <td class=\"fsRowOpen\">
			                    &nbsp;
			                  </td>
			                  <td class=\"fsRowBody fsCell fsFieldCell fsFirst fsLast fsLabelVertical\" colspan=\"1\" id=\"fsCell6966430\">
			                   <b>Email</b><br>
			                    <div class=\"input_text\"><input type=\"text\" id=\"field6966430\" name=\"field6966430\" size=\"50\" value=\"\" class=\"input_text fsField fsFormatEmail fsRequired\" /></div><br /><br />
			                  </td>
			                  <td class=\"fsRowClose\">
			                    &nbsp;
			                  </td>
			                </tr>
			                <tr valign=\"top\" id=\"fsRow685692-4\" class=\"fsRow fsFieldRow fsLastRow\" style=\"\">
			                  <td class=\"fsRowOpen\">
			                    &nbsp;
			                  </td>
			                  <td class=\"fsRowBody fsCell fsFieldCell fsFirst fsLast fsLabelVertical\" colspan=\"1\" id=\"fsCell6966431\">
			                    <label class=\"fsLabel\" for=\"field6966431\"><b>Message</b></label><br> 
			                    <textarea id=\"field6966431\" name=\"field6966431\" rows=\"10\" cols=\"50\" class=\"fsField\"></textarea>
			                  </td>
			                </tr>
			              </tbody>
			            </table>
			          </div>
			          <div id=\"fsSubmit685692\" class=\"fsSubmit fsPagination\">
			            <input id=\"fsSubmitButton685692\" class=\"fsSubmitButton\" type=\"submit\" value=\"Submit Form\" />
			          </div>
			        </form>";
			}
			elseif($page=="logout"){
				$content .= $sf->logout();
			}
		}
		elseif(isset($_GET['s'])){
			$sidebar = $_GET['s'];
		    if($sidebar=="about"){
		    	$content .= "If you're a member of Social Feed you're automatically following us ;)<br /><br />Avatars: <a href='http://www.faceyourmanga.com/'>Face Your Manga</a>.";
		    }
		    elseif($sidebar=="contact"){
		    	$content .= "Fill out this form to send us a message. We will get back to you as soon as we can.";
		    }
		}
	}
	elseif(isset($_POST['change_avatar_confirm'])){
		$sf->connectDB();
		$tmpName = $_FILES['change_avatar']['tmp_name'];
		$fp = fopen($tmpName, 'r');
		$upload_avatar = fread($fp, filesize($tmpName));
		$upload_avatar = addslashes($upload_avatar);
		fclose($fp);
		$sql = "UPDATE usercred SET avatar='$upload_avatar' WHERE user='$username'";
		$result = mysql_query($sql) or die('Cannot change avatar: '.mysql_error());
		$content .= $sf->openURL('./?p=settings&s=avatar');
	}
	elseif($_POST['signup_check']){
		$signup_fname = $_POST['signup_fname'];
		$signup_lname = $_POST['signup_lname'];
		$signup_uname = $_POST['signup_uname'];
		$signup_email = $_POST['signup_email'];
		$signup_email2 = $_POST['signup_email2'];
		$signup_pass = $_POST['signup_pass'];
		$signup_pass2 = $_POST['signup_pass2'];
		$content .= $sf->signup($signup_fname,$signup_lname,$signup_uname,$signup_email,$signup_email2,$signup_pass,$signup_pass2);
	}
	elseif(isset($_POST['feeds_disable'])){
		$feed_type = $_POST['feeds_disable'];
		$sf->connectDB();
		$uid = $sf->getUid($sf->getUsername());
		$sql = "UPDATE usercred SET $feed_type='0' WHERE id='$uid'";
		$result = mysql_query($sql);
		$content .= "
		(<span class=\"feed_disabled\">Disabled</span>)
		<a href=\"javascript:enable_feed('$feed_type')\">Enable?</a>";
	}
	elseif(isset($_POST['feeds_enable'])){
		$feed_type = $_POST['feeds_enable'];
		$sf->connectDB();
		$uid = $sf->getUid($sf->getUsername());
		$sql = "UPDATE usercred SET $feed_type='1' WHERE id='$uid'";
		$result = mysql_query($sql);
		$content .= "
		(<span class=\"feed_enabled\">Enabled</span>)
		<a href=\"javascript:disable_feed('$feed_type')\">Disable?</a>";
	}
	elseif(isset($_POST['face_usr_chg'])){
		$new_face_usr = $_POST['face_usr_chg'];
	    $sf->connectDB();
	    $uid = $sf->getUid($sf->getUsername());
	    if(isset($_POST['enable_face'])){
	    	if($_POST['enable_face']=="yes"){
	    		$sql = "UPDATE face_feeds SET user='$new_face_usr' WHERE (uid='$uid' AND user='s0c1alf33d5')";
	    		$result = mysql_query($sql);
	    	}
	    }
	    $chk_face_sql = "SELECT * FROM face_feeds WHERE uid='$uid'";
	    $chk_face_result = mysql_query($chk_face_sql);
	    if($chk_face_result){
	    	list($fid,$fusr,$fuid) = mysql_fetch_array($chk_face_result);
	    }
	    if($fid){
	    	$sql = "UPDATE face_feeds SET user='$new_face_usr' WHERE uid='$uid'";
	    	$result = mysql_query($sql);
	    }
	    else {
	    	$sql = "INSERT INTO face_feeds (`user`,`uid`) VALUES ('$new_face_usr','$uid')";
	    	$result = mysql_query($sql);
	    }
	    $content .= "<span style='color: #009900;'>You have successfully changed your Facebook user.</span>";
	}
	elseif(isset($_POST['twit_usr_chg'])){
	    $new_twit_usr = $_POST['twit_usr_chg'];
	    $sf->connectDB();
	    $uid = $sf->getUid($sf->getUsername());
	    if(isset($_POST['enable_twit'])){
	    	if($_POST['enable_twit']=="yes"){
	    		$sql = "UPDATE twit_feeds SET user='$new_twit_usr' WHERE (uid='$uid' AND user='s0c1alf33d5')";
	    		$result = mysql_query($sql);
	    	}
	    }
	    $chk_twit_sql = "SELECT * FROM twit_feeds WHERE uid='$uid'";
	    $chk_twit_result = mysql_query($chk_twit_sql);
	    if($chk_twit_result){
	    	list($tid,$tusr,$tuid) = mysql_fetch_array($chk_twit_result);
	    }
	    if($tid){
	    	$sql = "UPDATE twit_feeds SET user='$new_twit_usr' WHERE uid='$uid'";
	    	$result = mysql_query($sql);
	    }
	    else {
	    	$sql = "INSERT INTO twit_feeds (`user`,`uid`) VALUES ('$new_twit_usr','$uid')";
	    	$result = mysql_query($sql);
	    }
	    $content .= "<span style='color: #009900;'>You have successfully changed your Twitter user.</span>";
	}
	elseif(isset($_POST['yout_usr_chg'])){
	    $new_yout_usr = $_POST['yout_usr_chg'];
	    $sf->connectDB();
	    $uid = $sf->getUid($sf->getUsername());
	    if(isset($_POST['enable_yout'])){
	    	if($_POST['enable_yout']=="yes"){
	    		$sql = "UPDATE you_feeds SET user='$new_yout_usr' WHERE (uid='$uid' AND user='s0c1alf33d5')";
	    		$result = mysql_query($sql);
	    	}
	    }
	    $chk_yout_sql = "SELECT * FROM you_feeds WHERE uid='$uid'";
	    $chk_yout_result = mysql_query($chk_yout_sql);
	    if($chk_yout_result){
	    	list($yid,$yusr,$yuid) = mysql_fetch_array($chk_yout_result);
	    }
	    if($yid){
	    	$sql = "UPDATE you_feeds SET user='$new_yout_usr' WHERE uid='$uid'";
	    	$result = mysql_query($sql);
	    }
	    else {
	    	$sql = "INSERT INTO you_feeds (`user`,`uid`) VALUES ('$new_yout_usr','$uid')";
	    	$result = mysql_query($sql);
	    }
	    $content .= "<span style='color: #009900;'>You have successfully changed your YouTube user.</span>";
	}
	elseif($_POST['new_post']){
		$new_post = $_POST['new_post'];
		$content .= $sf->postFeed($new_post);
	}
	elseif($_POST['new_bio']){
		$new_bio = $_POST['new_bio'];
		$content .= $sf->changeBio($new_bio);
	}
	elseif($_POST['new_email']){
		$new_email = $_POST['new_email'];
		$new_email2 = $_POST['new_email2'];
		$content .= $sf->changeEmail($new_email,$new_email2);
	}
	elseif($_POST['new_pass']){
		$new_pass = $_POST['new_pass'];
		$new_pass2 = $_POST['new_pass2'];
		$content .= $sf->changePassword($new_pass,$new_pass2);
	}
	elseif($_POST['login_username']){
		$login_username = $_POST['login_username'];
		$login_password = md5($_POST['login_password']);
		$content .= $sf->login($login_username,$login_password);
	}
	if(isset($content)){
		echo $content;
	}
?>