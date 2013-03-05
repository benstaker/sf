<?php
	require_once('../api/classes.php');
	$sf = new SocialFeed();
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$title = 'Micro-Blogging';
	$header = "<h1>Micro-Blogging</h1>";
	$go = $_GET['go'];
	$go = "http://$go";
	$this_url = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	if(isset($username) && isset($password)){
		$content .= "<script type=\"text/javascript\" src=\"settings.js\"></script>";
		if(isset($_POST['change_avatar_confirm'])){
			include('db.php');
			$tmpName = $_FILES['change_avatar']['tmp_name'];
			$fp = fopen($tmpName, 'r');
			$upload_avatar = fread($fp, filesize($tmpName));
			$upload_avatar = addslashes($upload_avatar);
			fclose($fp);
			$sql = "UPDATE usercred SET avatar='$upload_avatar' WHERE user='$username'";
			$result = mysql_query($sql) or die('Cannot change avatar: '.mysql_error());
			$content .= "
			<script type=\"text/javascript\">
				url = '../main.php?public=$username';
				window.location.href = url;
			</script>";
		}
		if(isset($_POST['change_bio'])){
			include('db.php');
			$change_bio = $_POST['change_bio'];
			$sql = "UPDATE usercred SET bio='$change_bio' WHERE user='$username'";
			$result = mysql_query($sql) or die('Cannot change bio: '.mysql_error());
			$content .= "
			<script type=\"text/javascript\">
				url = '../main.php?public=$username';
				window.location.href = url;
			</script>";
		}
		elseif(isset($_POST['change_email'])){
			include('db.php');
			$change_email = $_POST['change_email'];
			$change_email2 = $_POST['change_email2'];
			if($change_email==$change_email2){
			    $sql = "SELECT email FROM `usercred` WHERE `email`='$change_email'";
			    $result = mysql_query($sql);
			    if($result){
			    	list($db_email) = mysql_fetch_array($result);
			    }
			    if($db_email==$change_email){
			    	$content .= "The Email Address <b>$change_email</b> is already in use!";
			    }
			    else {
			    	$sql = "UPDATE usercred SET email='$change_email' WHERE user='$username'";
			    	$result = mysql_query($sql) or die('Cannot change email: '.mysql_error());
			    	$content .= "
			    	<script type=\"text/javascript\">
			    		url = '../main.php?public=$username';
			    		window.location.href = url;
			    	</script>";
			    }
			}
			else {
			    $content .= "Email Addresses do not match!";
			}
		}
		elseif(isset($_POST['change_pass'])){
			include('db.php');
			$change_pass = md5($_POST['change_pass']);
			$change_pass2 = md5($_POST['change_pass2']);
			if($change_pass==$change_pass2){
			    	$sql = "UPDATE usercred SET pass='$change_pass' WHERE user='$username'";
			    	$result = mysql_query($sql) or die('Cannot change pass: '.mysql_error());
			    	$_SESSION['password'] = $change_pass;
			    	$content .= "
			    	<script type=\"text/javascript\">
			    		url = '../main.php?public=$username';
			    		window.location.href = url;
			    	</script>";
			}
			else {
			    $content .= "Passwords do not match!";
			}
		}
		elseif($_GET['e']=="home"){
			$content .= "
			<span class=\"feed_user\">Account Settings:</span>
    		<div class=\"prof_feed_div\">
				<h3><u>Email</u></h3><br />
				<form name=\"change_email_form\" action=\"inc/change.php?go=$this_url\" method=\"post\">
					<table>
						<tr>
							<td class=\"settings_name\">New Email Address:</td>
							<td><div class=\"input_text\"><input type=\"text\" name=\"change_email\" class=\"input_text\"/></div></td>
							<td></td>
						</tr>
						<tr>
							<td class=\"settings_name\">Confirm New Email Address:</td>
							<td><div class=\"input_text\"><input type=\"text\" name=\"change_email2\" class=\"input_text\"/></div></td>
							<td><input type=\"submit\" name=\"change_email_submit\" value=\"Change Email\"/></td>
						</tr>
					</table>
				</form>
				<br />
				<h3><u>Password</u></h3><br />
				<form name=\"change_pass_form\" action=\"inc/change.php?go=$this_url\" method=\"post\">
					<table>
						<tr>
							<td class=\"settings_name\">New Password:</td>
							<td><div class=\"input_text\"><input type=\"password\" name=\"change_pass\" class=\"input_text\"/></div></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class=\"settings_name\">Confirm New Password:</td>
							<td><div class=\"input_text\"><input type=\"password\" name=\"change_pass2\" class=\"input_text\"/></div></td>
							<td><input type=\"submit\" name=\"change_pass_submit\" value=\"Change Password\"/></td>
						</tr>
					</table>
				</form>
			</div>";
		}
		elseif($_GET['e']=="avatar"){
			$content .= "
			<span class=\"feed_user\">Avatar:</span>
    		<div class=\"prof_feed_div\">
				<b>Current Avatar:</b><br />
				<img src=\"inc/avatars.php?u=$username\" width=\"100px\" height=\"100px\"/><br /><br />
				<form name=\"change_avatar_form\" enctype=\"multipart/form-data\" action=\"inc/change.php?go=$this_url\" method=\"post\">
					<input type=\"hidden\" name=\"change_avatar_confirm\" value=\"1\"/>
					Choose an avatar to upload: <div class=\"input_text\"><input name=\"change_avatar\" id=\"change_avatar\" type=\"file\" class=\"input_text\"/></div><br />
					<input type=\"submit\" name=\"change_avatar_submit\" value=\"Upload Avatar\" /><br />
				</form>
			</div>";
		}
		elseif($_GET['e']=="bio"){
			include('db.php');
			$sql = "SELECT bio FROM usercred WHERE user='$username'";
			$result = mysql_query($sql);
			if($result){
				list($cur_bio) = mysql_fetch_array($result);
			}
			$content .= "
			<span class=\"feed_user\">Bio:</span>
    		<div class=\"prof_feed_div\">
				<b>Current Bio:</b> $cur_bio";
			$cur_bio = stripslashes($cur_bio);
			$sym = array('/','<','>','"',"'");
			$sym_chg = array('&#47;','&lt;','&gt;','&quot;','&#39;');
			$cur_bio = str_replace($sym, $sym_chg, $cur_bio);
			$content .= "
				<br /><br />
				<form name=\"change_bio_form\" action=\"inc/change.php?go=$this_url\" method=\"post\">
					<b>New Bio:</b><br />
					<div class=\"input_text\"><input type=\"text\" name=\"change_bio\" id=\"change_bio\" class=\"input_text\" value=\"$cur_bio\"/></div>
					<input type=\"submit\" name=\"change_bio_submit\" value=\"Change Bio\"/><br />
				</form>
			</div>";
		}
		elseif($_GET['e']=="email"){
			$content .= "
			<span class=\"feed_user\">Email Address:</span>
    		<div class=\"prof_feed_div\">
				<form name=\"change_email_form\" action=\"inc/change.php?go=$this_url\" method=\"post\">
					<table>
						<tr>
							<td class=\"settings_name\">New Email Address:</td>
							<td><div class=\"input_text\"><input type=\"text\" name=\"change_email\" class=\"input_text\"/></div></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class=\"settings_name\">Confirm New Email Address:</td>
							<td><div class=\"input_text\"><input type=\"text\" name=\"change_email2\" class=\"input_text\"/></div></td>
							<td><input type=\"submit\" name=\"change_email_submit\" value=\"Submit!\"/></td>
						</tr>
					</table>
				</form>
			</div>";
		}
		elseif($_GET['e']=="pass"){
			$content .= "
			<span class=\"feed_user\">Password:</span>
    		<div class=\"prof_feed_div\">
				<form name=\"change_pass_form\" action=\"inc/change.php?go=$this_url\" method=\"post\">
					<table>
						<tr>
							<td class=\"settings_name\">New Password:</td>
							<td><div class=\"input_text\"><input type=\"password\" name=\"change_pass\" class=\"input_text\"/></div></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class=\"settings_name\">Confirm New Password:</td>
							<td><div class=\"input_text\"><input type=\"password\" name=\"change_pass2\" class=\"input_text\"/></div></td>
							<td><input type=\"submit\" name=\"change_pass_submit\" value=\"Submit!\"/></td>
						</tr>
					</table>
				</form>
			</div>";
		}
		elseif(isset($_POST['feeds_disable'])){
			$feed_type = $_POST['feeds_disable'];
			include('db.php');
			$sql = "UPDATE usercred SET $feed_type='0' WHERE user='$username'";
			$result = mysql_query($sql);
		}
		elseif(isset($_POST['feeds_enable'])){
			$feed_type = $_POST['feeds_enable'];
			include('db.php');
			$sql = "UPDATE usercred SET $feed_type='1' WHERE user='$username'";
			$result = mysql_query($sql);
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
			$chk_face_sql = "SELECT * FROM face_feeds WHERE uid='$uid'";
			$chk_face_result = mysql_query($chk_face_sql);
			if($chk_face_result){
				list($fid,$fusr,$fuid) = mysql_fetch_array($chk_face_result);
			}
			if(!$fid){
				$sql = "INSERT INTO face_feeds (`user`,`uid`) VALUES ('0','$uid')";
				$result = mysql_query($sql);
			}
		}
		elseif(isset($_POST['feed_update'])){
			$feed_cont = $_POST['feed_update'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
			$time = time();
			$sql = "INSERT INTO prof_feeds (`feed`,`time`,`uid`) VALUES ('$feed_cont','$time','$uid')";
			$result = mysql_query($sql);
			$content .= $feed_cont;
		}
		elseif(isset($_POST['face_usr_chg'])){
			$new_face_usr = $_POST['face_usr_chg'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
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
		}
		elseif(isset($_POST['twit_usr_chg'])){
			$new_twit_usr = $_POST['twit_usr_chg'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
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
		}
		elseif(isset($_POST['yout_usr_chg'])){
			$new_yout_usr = $_POST['yout_usr_chg'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
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
		}
		elseif(isset($_POST['follow_user'])){
			$follow_user = $_POST['follow_user'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
			$suid_sql = "SELECT id FROM usercred WHERE user='$follow_user'";
			$suid_result = mysql_query($suid_sql);
			if($suid_result){
				list($suid) = mysql_fetch_array($suid_result);
			}
			$chk_fols_sql = "SELECT id,user,uid,time,suser,suid,profile FROM follows WHERE (uid='$uid' AND suid='$suid')";
			$chk_fols_result = mysql_query($chk_fols_sql);
			if($chk_fols_result){
				list($fols_id,$fols_usr,$fols_uid,$fols_time,$fols_suser,$fols_suid,$fols_profile) = mysql_fetch_array($chk_fols_result);
			}
			if($fols_id){
				$sql = "UPDATE follows SET facebook='1', profile='1', twitter='1', youtube='1' WHERE id='$fols_id'";
				$result = mysql_query($sql);
			}
			else {
				$time = time();
				$sql = "INSERT INTO follows (`user`,`uid`,`time`,`suser`,`suid`,`facebook`,`profile`,`twitter`,`youtube`) VALUES ('$username','$uid','$time','$follow_user','$suid','1','1','1','1')";
				$result = mysql_query($sql);
			}
			$content .= "<span class=\"following\">Following</span>&nbsp;&nbsp;";
		}
		elseif(isset($_POST['stop_following_user'])){
			$stop_user = $_POST['stop_following_user'];
			$stop_feed = $_POST['stop_following_feed'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
			$suid_sql = "SELECT id FROM usercred WHERE user='$stop_user'";
			$suid_result = mysql_query($suid_sql);
			if($suid_result){
				list($suid) = mysql_fetch_array($suid_result);
			}
			$chk_fols_sql = "SELECT id FROM follows WHERE (uid='$uid' AND suid='$suid')";
			$chk_fols_result = mysql_query($chk_fols_sql);
			if($chk_fols_result){
				list($fols_id) = mysql_fetch_array($chk_fols_result);
			}
			if($fols_id){
				$sql = "UPDATE follows SET $stop_feed='0' WHERE id='$fols_id'";
				$result = mysql_query($sql) or die(mysql_error());
			}
			$content .= "<br /><span class=\"not_following\">You are now not following <u>$stop_user</u>'s $stop_feed Feed.</span><br />";
		}
		elseif(isset($_POST['stop_follow_user'])){
			$stop_follow_user = $_POST['stop_follow_user'];
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
			}
			$suid_sql = "SELECT id FROM usercred WHERE user='$stop_follow_user'";
			$suid_result = mysql_query($suid_sql);
			if($suid_result){
				list($suid) = mysql_fetch_array($suid_result);
			}
			$chk_fols_sql = "SELECT id,user,uid,time,suser,suid,profile FROM follows WHERE (uid='$uid' AND suid='$suid')";
			$chk_fols_result = mysql_query($chk_fols_sql);
			if($chk_fols_result){
				list($fols_id,$fols_usr,$fols_uid,$fols_time,$fols_suser,$fols_suid,$fols_profile) = mysql_fetch_array($chk_fols_result);
			}
			if($fols_id){
				$sql = "DELETE FROM follows WHERE id='$fols_id'";
				$result = mysql_query($sql) or die(mysql_error());
			}
			else {
				$time = time();
				$sql = "INSERT INTO follows (`user`,`uid`,`time`,`suser`,`suid`,`profile`,`twitter`,`youtube`) VALUES ('$username','$uid','$time','$stop_follow_user','$suid','0','0','0')";
				$result = mysql_query($sql);
			}
			$content .= "<span class=\"not_following\">Not Following</span>&nbsp;&nbsp;";
		}
		elseif($_GET['e']=="bebo"){
			include('db.php');
			$sql = "SELECT bebo FROM usercred WHERE user='$username'";
			$result = mysql_query($sql);
			if($result){
				list($be) = mysql_fetch_array($result);
			}
			if($be==0){
				$sbe = "(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('bebo')\">Enable?</a>";
			}
			elseif($be==1){
				$sbe = "(<span class=\"feed_enabled\">Enabled</span>) <a href=\"javascript:disable_feed('bebo')\">Disable?</a>";
			}
			$content .= "
			<span class=\"feed_user\"><img src=\"img/bebo_32.png\" width=\"24px\"/> Bebo:</span>
    		<div class=\"prof_feed_div\">
    			$sbe
    		</div>";
		}
		elseif($_GET['e']=="facebook"){
			include('db.php');
			$sql = "SELECT id,facebook FROM usercred WHERE user='$username'";
			$result = mysql_query($sql);
			if($result){
				list($uid,$fa) = mysql_fetch_array($result);
			}
			$uid_sql = "SELECT user FROM face_feeds WHERE uid='$uid'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($fa_user) = mysql_fetch_array($uid_result);
			}
			if($fa==0){
				$sfa = "(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('facebook')\">Enable?</a>";
			}
			elseif($fa==1){
				$sfa = "
				Setting up your Facebook is easy! Just go to login to Facebook, and go to your Profile, then copy the <b>id</b> from the address bar to the input box and click <b>change</b>.<br /><br /><img src=\"./img/Facebook_How-to_1.png\" height=\"25px\"/><br /><br />
				And if you've setup your Facebook to show your username just view one of your album photos and get the <b>id</b> from there.<br /><br /><img src=\"./img/Facebook_How-to_2.png\" height=\"25px\"/><br /><br />
				Or you can view one of your Profile Pictures and get the <b>subj</b> from there.<br /><br /><img src=\"./img/Facebook_How-to_3.png\" height=\"21px\"/><br /><br />
				(<span class=\"feed_enabled\">Enabled</span>)
				<a href=\"javascript:disable_feed('facebook')\">Disable?</a><br />
				<div class=\"input_text\"><input type=\"text\" name=\"face_usr_chg\" id=\"face_usr_chg\" value=\"$fa_user\" class=\"input_text\"/></div>
				<input type=\"button\" name=\"facebook_submit\" onClick=\"face_usr_chg('face_usr_chg','facebook','$fa_user')\" value=\"Change!\"/>";
			}
			$content .= "
			<span class=\"feed_user\"><img src=\"img/FaceBook_24x24.png\" width=\"24px\"/> FaceBook:</span>
    		<div class=\"prof_feed_div\">
    			$sfa
    		</div>";
		}
		elseif($_GET['e']=="myspace"){
			include('db.php');
			$sql = "SELECT myspace FROM usercred WHERE user='$username'";
			$result = mysql_query($sql);
			if($result){
				list($my) = mysql_fetch_array($result);
			}
			if($my==0){
				$smy = "(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('myspace')\">Enable?</a>";
			}
			elseif($my==1){
				$smy = "(<span class=\"feed_enabled\">Enabled</span>) <a href=\"javascript:disable_feed('myspace')\">Disable?</a>";
			}
			$content .= "
			<span class=\"feed_user\"><img src=\"img/myspace_32.png\" width=\"24px\"/> MySpace:</span>
    		<div class=\"prof_feed_div\">
    			$smy
    		</div>";
		}
		elseif($_GET['e']=="twitter"){
			include('db.php');
			$sql = "SELECT id,twitter FROM usercred WHERE user='$username'";
			$result = mysql_query($sql);
			if($result){
				list($uid,$tw) = mysql_fetch_array($result);
			}
			$uid_sql = "SELECT user FROM twit_feeds WHERE uid='$uid'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($tw_user) = mysql_fetch_array($uid_result);
			}
			if($tw==0){
				$stw = "(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('twitter')\">Enable?</a>";
			}
			elseif($tw==1){
				$stw = "
				Setting up Twitter on Social Feed is one of the easiest things you'll ever do... Just enter your Twitter <b>username</b> in the box below and click change!<br /><br />
				(<span class=\"feed_enabled\">Enabled</span>)
				<a href=\"javascript:disable_feed('twitter')\">Disable?</a><br />
				<div class=\"input_text\"><input type=\"text\" name=\"twit_usr_chg\" id=\"twit_usr_chg\" value=\"$tw_user\" class=\"input_text\"/></div>
				<input type=\"button\" name=\"twitter_submit\" onClick=\"twit_usr_chg('twit_usr_chg','twitter','$tw_user')\" value=\"Change!\"/>";
			}
			$content .= "
			<span class=\"feed_user\"><img src=\"img/Twitter_24x24.png\" width=\"24px\"/> Twitter:</span>
    		<div class=\"prof_feed_div\">
    			$stw
    		</div>";
		}
		elseif($_GET['e']=="youtube"){
			include('db.php');
			$sql = "SELECT id,youtube FROM usercred WHERE user='$username'";
			$result = mysql_query($sql);
			if($result){
				list($uid,$yo) = mysql_fetch_array($result);
			}
			$uid_sql = "SELECT user FROM you_feeds WHERE uid='$uid'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($yo_user) = mysql_fetch_array($uid_result);
			}
			if($yo==0){
				$syo = "(<span class=\"feed_disabled\">Disabled</span>) <a href=\"javascript:enable_feed('youtube')\">Enable?</a>";
			}
			elseif($yo==1){
				$syo = "
				Setting up YouTube on Social Feed is one of the easiest things you'll ever do... Just enter your YouTube <b>username</b> in the box below and click change!<br /><br />
				(<span class=\"feed_enabled\">Enabled</span>)
				<a href=\"javascript:disable_feed('youtube')\">Disable?</a><br />
				<div class=\"input_text\"><input type=\"text\" name=\"yout_usr_chg\" id=\"yout_usr_chg\" value=\"$yo_user\" class=\"input_text\"/></div>
				<input type=\"button\" name=\"youtube_submit\" onClick=\"yout_usr_chg('yout_usr_chg','youtube','$yo_user')\" value=\"Change!\"/>";
			}
			$content .= "
			<span class=\"feed_user\"><img src=\"img/Youtube_24x24.png\" width=\"24px\"/> YouTube:</span>
    		<div class=\"prof_feed_div\">
    			$syo
    		</div>";
		}
		elseif($_GET['e']=="follows"){
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
				$bebo_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND bebo='1')";
				$bebo_result = mysql_query($bebo_sql);
				while(list($bid,$buser,$buid,$btime,$bsuser,$bsuid) = mysql_fetch_array($bebo_result)){
					//$bebo_user_sql = "SELECT user FROM bebo_feeds WHERE uid='$bsuid' ORDER BY user ASC";
					//$bebo_user_result = mysql_query($bebo_user_sql);
					//list($b_user) = mysql_fetch_array($bebo_user_result);
					//if($b_user!="s0c1alf33d5" && isset($b_user) && !is_null($b_user)){
				    //	$bebo_fols .= "
				    //<br />
				    //<img src=\"img/bebo_32.png\" width=\"24px\"/>
				    //<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $b_user
				    //<br />";
				    //}
				}
				$face_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND facebook='1')";
				$face_result = mysql_query($face_sql);
				while(list($fid,$fuser,$fuid,$ftime,$fsuser,$fsuid) = mysql_fetch_array($face_result)){
					$face_user_sql = "SELECT user FROM face_feeds WHERE uid='$fsuid' ORDER BY user ASC";
					$face_user_result = mysql_query($face_user_sql);
					list($f_user) = mysql_fetch_array($face_user_result);
					if($f_user!="s0c1alf33d5" && isset($f_user) && !is_null($f_user)){
				    	$face_fols .= "
				    	<br />
				    	<img src=\"img/FaceBook_24x24.png\" width=\"24px\"/>
				    	<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $f_user
				    	<br />";
				    }
				}
				$mysp_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND myspace='1')";
				$mysp_result = mysql_query($mysp_sql);
				while(list($mid,$muser,$muid,$mtime,$msuser,$msuid) = mysql_fetch_array($mysp_result)){
					$mysp_user_sql = "SELECT user FROM mysp_feeds WHERE uid='$msuid' ORDER BY user ASC";
					$mysp_user_result = mysql_query($mysp_user_sql);
					list($m_user) = mysql_fetch_array($mysp_user_result);
					if($m_user!="s0c1alf33d5" && isset($m_user) && !is_null($m_user)){
				    	$mysp_fols .= "
				    	<br />
				    	<img src=\"img/myspace_32.png\" width=\"24px\"/>
				    	<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $m_user
				    	<br />";
				    }
				}
				$prof_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND profile='1') ORDER BY suser ASC";
				$prof_result = mysql_query($prof_sql);
				while(list($pid,$puser,$puid,$ptime,$psuser,$psuid) = mysql_fetch_array($prof_result)){
				    $prof_fols .= "
				    <div id=\"$psuser"."_profile\">
				    	<br />
				    	<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
				    	<a href=\"main.php?public=$psuser\">$psuser</a> - 
				    	<input type=\"button\" value=\"Stop\" onClick=\"stop_following('$psuser','$puser','profile');\"/>
				    	<br />
				    </div>";
				}
				$twit_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND twitter='1')";
				$twit_result = mysql_query($twit_sql);
				while(list($tid,$tuser,$tuid,$ttime,$tsuser,$tsuid) = mysql_fetch_array($twit_result)){
					$prof_sql = "SELECT suser FROM follows WHERE (suid='$tsuid' AND twitter='1') ORDER BY suser ASC";
					$prof_result = mysql_query($prof_sql);
					list($psuser) = mysql_fetch_array($prof_result);
					$twit_user_sql = "SELECT user FROM twit_feeds WHERE uid='$tsuid' ORDER BY user ASC";
					$twit_user_result = mysql_query($twit_user_sql);
					list($t_user) = mysql_fetch_array($twit_user_result);
					if($t_user!="s0c1alf33d5" && isset($t_user) && !is_null($t_user)){
				    	$twit_fols .= "
				    	<div id=\"$psuser"."_twitter\">
				    		<br />
				    		<img src=\"img/Twitter_24x24.png\" width=\"24px\"/>
				    		<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
				    		<a href=\"http://www.twitter.com/$t_user\">$t_user</a> - 
				    		<input type=\"button\" value=\"Stop\" onClick=\"stop_following('$psuser','$t_user','twitter');\"/>
				    		<br />
				    	</div>";
				    }
				}
				$yout_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND youtube='1')";
				$yout_result = mysql_query($yout_sql);
				while(list($yid,$yuser,$yuid,$ytime,$ysuser,$ysuid) = mysql_fetch_array($yout_result)){
					$prof_sql = "SELECT suser FROM follows WHERE (suid='$ysuid' AND youtube='1') ORDER BY suser ASC";
					$prof_result = mysql_query($prof_sql);
					list($psuser) = mysql_fetch_array($prof_result);
					$yout_user_sql = "SELECT user FROM you_feeds WHERE uid='$ysuid' ORDER BY user ASC";
					$yout_user_result = mysql_query($yout_user_sql);
					list($y_user) = mysql_fetch_array($yout_user_result);
					if($y_user!="s0c1alf33d5" && isset($y_user) && !is_null($y_user)){
						$yout_fols .= "
						<div id=\"$psuser"."_youtube\">
							<br />
							<img src=\"img/Youtube_24x24.png\" width=\"24px\"/>
							<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
							<a href=\"http://www.youtube.com/user/$y_user\">$y_user</a> - 
				    		<input type=\"button\" value=\"Stop\" onClick=\"stop_following('$psuser','$y_user','youtube');\"/>
							<br />
						</div>";
					}
				}
				if(isset($bebo_fols) || isset($face_fols) || isset($mysp_fols) || isset($prof_fols) || isset($twit_fols) || isset($yout_fols)){
					$bans = $bebo_fols.$face_fols.$mysp_fols.$prof_fols.$twit_fols.$yout_fols;
				}
				elseif(!isset($bebo_fols) && !isset($face_fols) && !isset($mysp_fols) && !isset($prof_fols) && !isset($twit_fols) && !isset($yout_fols)){
					$bans = "<span class=\"feed_disabled\">You are not following anyone.</span>";
				}
				$follows .= "
				<h2>Following:</h2>
				<div class=\"prof_feed_div\">
    		   		$bans
    			</div>";
			}
			$content .= "$follows";
		}
		elseif($_GET['e']=="followers"){
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
				$bebo_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (suid='$uid' AND bebo='1')";
				$bebo_result = mysql_query($bebo_sql);
				while(list($bid,$buser,$buid,$btime,$bsuser,$bsuid) = mysql_fetch_array($bebo_result)){
					//$bebo_user_sql = "SELECT user FROM bebo_feeds WHERE uid='$bsuid' ORDER BY user ASC";
					//$bebo_user_result = mysql_query($bebo_user_sql);
					//list($b_user) = mysql_fetch_array($bebo_user_result);
					//if($b_user!="s0c1alf33d5" && isset($b_user) && !is_null($b_user)){
				    //	$bebo_fols .= "
				    //<br />
				    //<img src=\"img/bebo_32.png\" width=\"24px\"/>
				    //<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $b_user
				    //<br />";
				    //}
				}
				$face_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (suid='$uid' AND facebook='1')";
				$face_result = mysql_query($face_sql);
				while(list($fid,$fuser,$fuid,$ftime,$fsuser,$fsuid) = mysql_fetch_array($face_result)){
					$prof_sql = "SELECT user,suser FROM follows WHERE (suid='$fsuid' AND facebook='1') ORDER BY suser ASC";
					$prof_result = mysql_query($prof_sql);
					list($puser,$psuser) = mysql_fetch_array($prof_result);
					$face_user_sql = "SELECT user FROM face_feeds WHERE uid='$fuid' ORDER BY user ASC";
					$face_user_result = mysql_query($face_user_sql);
					list($f_user) = mysql_fetch_array($face_user_result);
					if($f_user!="0" && isset($f_user) && !is_null($f_user)){
				    	$face_fols .= "
				    	<div id=\"$psuser"."_facebook\">
				    		<br />
				    		<img src=\"img/FaceBook_24x24.png\" width=\"24px\"/>
				    		<img src=\"inc/avatars.php?u=$puser\" width=\"24px\"/>
				    		<a href=\"http://www.facebook.com/profile.php?id=$f_user\">$f_user</a>
				    		<br />
				    	</div>";
				    }
				}
				$mysp_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (suid='$uid' AND myspace='1')";
				$mysp_result = mysql_query($mysp_sql);
				while(list($mid,$muser,$muid,$mtime,$msuser,$msuid) = mysql_fetch_array($mysp_result)){
					$mysp_user_sql = "SELECT user FROM mysp_feeds WHERE uid='$msuid' ORDER BY user ASC";
					$mysp_user_result = mysql_query($mysp_user_sql);
					list($m_user) = mysql_fetch_array($mysp_user_result);
					if($m_user!="s0c1alf33d5" && isset($m_user) && !is_null($m_user)){
				    	$mysp_fols .= "
				    	<br />
				    	<img src=\"img/myspace_32.png\" width=\"24px\"/>
				    	<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $m_user
				    	<br />";
				    }
				}
				$prof_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (suid='$uid' AND profile='1') ORDER BY suser ASC";
				$prof_result = mysql_query($prof_sql);
				while(list($pid,$puser,$puid,$ptime,$psuser,$psuid) = mysql_fetch_array($prof_result)){
				    $prof_fols .= "
				    <div id=\"$psuser"."_profile\">
				    	<br />
				    	<img src=\"inc/avatars.php?u=$puser\" width=\"24px\"/>
				    	<a href=\"main.php?public=$puser\">$puser</a>
				    	<br />
				    </div>";
				}
				$twit_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (suid='$uid' AND twitter='1')";
				$twit_result = mysql_query($twit_sql);
				while(list($tid,$tuser,$tuid,$ttime,$tsuser,$tsuid) = mysql_fetch_array($twit_result)){
					$prof_sql = "SELECT user,suser FROM follows WHERE (suid='$tsuid' AND twitter='1') ORDER BY suser ASC";
					$prof_result = mysql_query($prof_sql);
					list($puser,$psuser) = mysql_fetch_array($prof_result);
					$twit_user_sql = "SELECT user FROM twit_feeds WHERE uid='$tuid' ORDER BY user ASC";
					$twit_user_result = mysql_query($twit_user_sql);
					list($t_user) = mysql_fetch_array($twit_user_result);
					if($t_user!="s0c1alf33d5" && isset($t_user) && !is_null($t_user)){
				    	$twit_fols .= "
				    	<div id=\"$psuser"."_twitter\">
				    		<br />
				    		<img src=\"img/Twitter_24x24.png\" width=\"24px\"/>
				    		<img src=\"inc/avatars.php?u=$puser\" width=\"24px\"/>
				    		<a href=\"http://www.twitter.com/$t_user\">$t_user</a>
				    		<br />
				    	</div>";
				    }
				}
				$yout_sql = "SELECT id,user,uid,time,suser,suid FROM follows WHERE (uid='$uid' AND youtube='1')";
				$yout_result = mysql_query($yout_sql);
				while(list($yid,$yuser,$yuid,$ytime,$ysuser,$ysuid) = mysql_fetch_array($yout_result)){
					$prof_sql = "SELECT suser FROM follows WHERE (suid='$ysuid' AND youtube='1') ORDER BY suser ASC";
					$prof_result = mysql_query($prof_sql);
					list($psuser) = mysql_fetch_array($prof_result);
					$yout_user_sql = "SELECT user FROM you_feeds WHERE uid='$ysuid' ORDER BY user ASC";
					$yout_user_result = mysql_query($yout_user_sql);
					list($y_user) = mysql_fetch_array($yout_user_result);
					if($y_user!="s0c1alf33d5" && isset($y_user) && !is_null($y_user)){
						$yout_fols .= "
						<div id=\"$psuser"."_youtube\">
							<br />
							<img src=\"img/Youtube_24x24.png\" width=\"24px\"/>
							<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
							<a href=\"http://www.youtube.com/user/$y_user\">$y_user</a>
							<br />
						</div>";
					}
				}
				if(isset($bebo_fols) || isset($face_fols) || isset($mysp_fols) || isset($prof_fols) || isset($twit_fols) || isset($yout_fols)){
					$bans = $prof_fols.$bebo_fols.$face_fols.$mysp_fols.$twit_fols.$yout_fols;
				}
				elseif(!isset($bebo_fols) && !isset($face_fols) && !isset($mysp_fols) && !isset($prof_fols) && !isset($twit_fols) && !isset($yout_fols)){
					$bans = "<span class=\"feed_disabled\">No-one is following you.</span>";
				}
				$follows .= "
				<h2>Followers:</h2>
				<div class=\"prof_feed_div\">
    		   		$bans
    			</div>";
			}
			$content .= "$follows";
		}
		elseif($_GET['e']=="banned"){
			include('db.php');
			$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
			$uid_result = mysql_query($uid_sql);
			if($uid_result){
				list($uid) = mysql_fetch_array($uid_result);
				$bebo_sql = "SELECT id,user,uid,time,buser,buid FROM banned WHERE (uid='$uid' AND bebo='1')";
				$bebo_result = mysql_query($bebo_sql);
				while(list($bid,$buser,$buid,$btime,$bsuser,$bsuid) = mysql_fetch_array($bebo_result)){
					//$bebo_user_sql = "SELECT user FROM bebo_feeds WHERE uid='$bsuid' ORDER BY user ASC";
					//$bebo_user_result = mysql_query($bebo_user_sql);
					//list($b_user) = mysql_fetch_array($bebo_user_result);
					//if($b_user!="s0c1alf33d5" && isset($b_user) && !is_null($b_user)){
				    //	$bebo_fols .= "
				    //<br />
				    //<img src=\"img/bebo_32.png\" width=\"24px\"/>
				    //<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $b_user
				    //<br />";
				    //}
				}
				$face_sql = "SELECT id,user,uid,time,buser,buid FROM banned WHERE (uid='$uid' AND facebook='1')";
				$face_result = mysql_query($face_sql);
				while(list($fid,$fuser,$fuid,$ftime,$fsuser,$fsuid) = mysql_fetch_array($face_result)){
					$face_user_sql = "SELECT user FROM face_feeds WHERE uid='$fsuid' ORDER BY user ASC";
					$face_user_result = mysql_query($face_user_sql);
					list($f_user) = mysql_fetch_array($face_user_result);
					if($f_user!="s0c1alf33d5" && isset($f_user) && !is_null($f_user)){
				    	$face_fols .= "
				    	<br />
				    	<img src=\"img/FaceBook_24x24.png\" width=\"24px\"/>
				    	<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $f_user
				    	<br />";
				    }
				}
				$mysp_sql = "SELECT id,user,uid,time,buser,buid FROM banned WHERE (uid='$uid' AND myspace='1')";
				$mysp_result = mysql_query($mysp_sql);
				while(list($mid,$muser,$muid,$mtime,$msuser,$msuid) = mysql_fetch_array($mysp_result)){
					$mysp_user_sql = "SELECT user FROM mysp_feeds WHERE uid='$msuid' ORDER BY user ASC";
					$mysp_user_result = mysql_query($mysp_user_sql);
					list($m_user) = mysql_fetch_array($mysp_user_result);
					if($m_user!="s0c1alf33d5" && isset($m_user) && !is_null($m_user)){
				    	$mysp_fols .= "
				    	<br />
				    	<img src=\"img/myspace_32.png\" width=\"24px\"/>
				    	<img src=\"inc/avatars.php?u=$username\" width=\"24px\"/> $m_user
				    	<br />";
				    }
				}
				$prof_sql = "SELECT id,user,uid,time,buser,buid FROM banned WHERE (uid='$uid' AND profile='1') ORDER BY buser ASC";
				$prof_result = mysql_query($prof_sql);
				while(list($pid,$puser,$puid,$ptime,$psuser,$psuid) = mysql_fetch_array($prof_result)){
				    $prof_fols .= "
				    <div id=\"$psuser"."_profile\">
				    	<br />
				    	<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
				    	<a href=\"main.php?public=$psuser\">$psuser</a> - 
				    	<input type=\"button\" value=\"Stop\" onClick=\"stop_following('$psuser','$puser','profile');\"/>
				    	<br />
				    </div>";
				}
				$twit_sql = "SELECT id,user,uid,time,buser,buid FROM banned WHERE (uid='$uid' AND twitter='1')";
				$twit_result = mysql_query($twit_sql);
				while(list($tid,$tuser,$tuid,$ttime,$tsuser,$tsuid) = mysql_fetch_array($twit_result)){
					$prof_sql = "SELECT buser FROM banned WHERE (buid='$tsuid' AND twitter='1') ORDER BY buser ASC";
					$prof_result = mysql_query($prof_sql);
					list($psuser) = mysql_fetch_array($prof_result);
					$twit_user_sql = "SELECT user FROM twit_feeds WHERE uid='$tsuid' ORDER BY user ASC";
					$twit_user_result = mysql_query($twit_user_sql);
					list($t_user) = mysql_fetch_array($twit_user_result);
					if($t_user!="s0c1alf33d5" && isset($t_user) && !is_null($t_user)){
				    	$twit_fols .= "
				    	<div id=\"$psuser"."_twitter\">
				    		<br />
				    		<img src=\"img/Twitter_24x24.png\" width=\"24px\"/>
				    		<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
				    		<a href=\"http://www.twitter.com/$t_user\">$t_user</a> - 
				    		<input type=\"button\" value=\"Stop\" onClick=\"stop_following('$psuser','$t_user','twitter');\"/>
				    		<br />
				    	</div>";
				    }
				}
				$yout_sql = "SELECT id,user,uid,time,buser,buid FROM banned WHERE (uid='$uid' AND youtube='1')";
				$yout_result = mysql_query($yout_sql);
				while(list($yid,$yuser,$yuid,$ytime,$ysuser,$ysuid) = mysql_fetch_array($yout_result)){
					$prof_sql = "SELECT buser FROM banned WHERE (buid='$ysuid' AND youtube='1') ORDER BY buser ASC";
					$prof_result = mysql_query($prof_sql);
					list($psuser) = mysql_fetch_array($prof_result);
					$yout_user_sql = "SELECT user FROM you_feeds WHERE uid='$ysuid' ORDER BY user ASC";
					$yout_user_result = mysql_query($yout_user_sql);
					list($y_user) = mysql_fetch_array($yout_user_result);
					if($y_user!="s0c1alf33d5" && isset($y_user) && !is_null($y_user)){
						$yout_fols .= "
						<div id=\"$psuser"."_youtube\">
							<br />
							<img src=\"img/Youtube_24x24.png\" width=\"24px\"/>
							<img src=\"inc/avatars.php?u=$psuser\" width=\"24px\"/>
							<a href=\"http://www.youtube.com/user/$y_user\">$y_user</a> - 
				    		<input type=\"button\" value=\"Stop\" onClick=\"stop_following('$psuser','$y_user','youtube');\"/>
							<br />
						</div>";
					}
				}
				if(isset($bebo_fols) || isset($face_fols) || isset($mysp_fols) || isset($prof_fols) || isset($twit_fols) || isset($yout_fols)){
					$bans = $bebo_fols.$face_fols.$mysp_fols.$prof_fols.$twit_fols.$yout_fols;
				}
				elseif(!isset($bebo_fols) && !isset($face_fols) && !isset($mysp_fols) && !isset($prof_fols) && !isset($twit_fols) && !isset($yout_fols)){
					$bans = "<span class=\"feed_disabled\">You haven't Banned anyone yet.</span>";
				}
				$banned .= "
				<h2>Bans:</h2>
				<div class=\"prof_feed_div\">
    		   		$bans
    			</div>";
			}
			$content .= "$banned";
		}
		else {
			$content .= "
			<script type=\"text/javascript\">
				url = 'main.php?p=settings';
				window.location.href = url;
			</script>";
		}
	}
	else {
		$content .= "You are not logged in!";
	}
	echo $content;
?>