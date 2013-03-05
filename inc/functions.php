<?php
	function display_feeds($username,$place,$feed_type){
		require_once('api/classes.php');
		$sf = new SocialFeed();
		include('inc/db.php');
		$uid_sql = "SELECT id FROM usercred WHERE user='$username'";
		$uid_result = mysql_query($uid_sql) or die(mysql_error());
		list($uid) = mysql_fetch_array($uid_result);
		if($feed_type=="facebook"){
			$suid_sql = "SELECT suid FROM follows WHERE (uid='$uid' AND facebook='1')";
			$suid_result = mysql_query($suid_sql) or die(mysql_error());
		}
		elseif($feed_type=="profile"){
			$suid_sql = "SELECT suid FROM follows WHERE (uid='$uid' AND profile='1')";
			$suid_result = mysql_query($suid_sql) or die(mysql_error());
		}
		elseif($feed_type=="twitter"){
    		$suid_sql = "SELECT suid FROM follows WHERE (uid='$uid' AND twitter='1')";
			$suid_result = mysql_query($suid_sql) or die(mysql_error());
    	}
    	elseif($feed_type=="youtube"){
    		$suid_sql = "SELECT suid FROM follows WHERE (uid='$uid' AND youtube='1')";
			$suid_result = mysql_query($suid_sql) or die(mysql_error());
    	}
		$user_id = array();
		$uid_users = array();
		$face_users = array();
		$face_uid = array();
		$prof_users = array();
		$twit_users = array();
		$twit_uid = array();
		$yout_users = array();
		$yout_uid = array();
		while(list($suid) = mysql_fetch_array($suid_result)){
    	    array_push($user_id,"$suid");
    	    $uid_user_sql = "SELECT user FROM usercred WHERE id='$suid'";
			$uid_user_result = mysql_query($uid_user_sql);
			list($uid_user_user) = mysql_fetch_array($uid_user_result);
			array_push($uid_users,"$uid_user_user");
			if($feed_type=="facebook"){
				$face_user_sql = "SELECT user,uid FROM face_feeds WHERE uid='$suid'";
				$face_user_result = mysql_query($face_user_sql);
				if($face_user_result){
					list($face_user_user,$face_user_uid) = mysql_fetch_array($face_user_result);
					if($face_user_user!="s0c1alf33d5" && isset($face_user_user) && !is_null($face_user_user)){
						array_push($face_users,"$face_user_user");
						array_push($face_uid,"$face_user_uid");
					}
				}
			}
			elseif($feed_type=="profile"){
				$prof_user_sql = "SELECT id FROM prof_feeds WHERE uid='$suid'";
				$prof_user_result = mysql_query($prof_user_sql);
				if($prof_user_result){
					list($prof_user_id) = mysql_fetch_array($prof_user_result);
					array_push($prof_users,"$prof_user_id");
				}
			}
			elseif($feed_type=="twitter"){
				$twit_user_sql = "SELECT user,uid FROM twit_feeds WHERE uid='$suid'";
				$twit_user_result = mysql_query($twit_user_sql);
				if($twit_user_result){
					list($twit_user_user,$twit_user_uid) = mysql_fetch_array($twit_user_result);
					if($twit_user_user!="s0c1alf33d5" && isset($twit_user_user) && !is_null($twit_user_user)){
						array_push($twit_users,"$twit_user_user");
						array_push($twit_uid,"$twit_user_uid");
					}
				}
			}
			elseif($feed_type=="youtube"){
				$yout_user_sql = "SELECT user,uid FROM you_feeds WHERE uid='$suid'";
				$yout_user_result = mysql_query($yout_user_sql);
				if($yout_user_result){
					list($yout_user_user,$yout_user_uid) = mysql_fetch_array($yout_user_result);
					if($yout_user_user!="s0c1alf33d5" && isset($yout_user_user) && !is_null($yout_user_user)){
						array_push($yout_users,"$yout_user_user");
						array_push($yout_uid,"$yout_user_uid");
					}
				}
			}
    	}
    	$uid_users_count = count($uid_users);
    	$face_users_count = count($face_users);
    	$prof_users_count = count($prof_users);
    	$twit_users_count = count($twit_users);
    	$yout_users_count = count($yout_users);
		foreach($user_id as $uids){
			$query_uid .= "uid='$uids' OR ";
		}
		if($place=="profile"){
			$query_uid = "uid='$uid'";
		}
		if($place=="home"){
			$query_uid = substr($query_uid, 0, -4);
			$query_uid = "($query_uid)";
		}
		if($feed_type=="facebook"){
			require_once 'facebook.php';
			$appapikey = 'd409cde519e4a29f7dfe0ecee500d280';
			$appsecret = '8df9a689a002f2ff7f44baba2278a75f';
			$facebook = new Facebook($appapikey, $appsecret);
			$user = $facebook->require_login();
			if($place=="home"){
    			foreach($face_uid as $fa_uid){
    				$face_chk_sql = "SELECT facebook FROM usercred WHERE id='$fa_uid'";
					$face_chk_result = mysql_query($face_chk_sql);
					if($face_chk_result){
						list($face_chk) = mysql_fetch_array($face_chk_result);
						if($face_chk=="1"){
    						if($face_users_count > 1){
    							foreach($face_users as $fa_user){
									$status = $facebook->api_client->call_method("facebook.status.get", array('uid' => $fa_user, 'limit' => '5'));
									$count = count($status);
									for($i=0;$i<=$count;$i++){
										$message = $status[$i]['message'];
										$usr_unix = $status[$i]['time'];
										$face_uid = $status[$i]['uid'];
										if($face_uid){
										    $friends2 = $facebook->api_client->users_getinfo($face_uid, 'first_name,last_name,pic,profile_url');
										    $_fname = $friends2[0]['first_name'];
										    $_lname = $friends2[0]['last_name'];
										    $_pic = $friends2[0]['pic'];
										    $_url = $friends2[0]['profile_url'];
										}
										if(isset($usr_unix) && !empty($usr_unix)){
										    $usr_time = date("H:ia",$usr_unix);
										    $usr_date = date("jS F Y",$usr_unix);
										}
										if(isset($message) && !empty($message)){
										   $func_facebook .= "
										    <a href=\"$_url\"><img src=\"img/FaceBook_24x24.png\"/> <b>$_fname $_lname</b></a> 
										    <div class=\"prof_feed_div\">
										    	<span class=\"feed_content\">$message</span><br />
										    	<span class=\"feed_date\">$usr_time on $usr_date</span>
										    </div><br />";
										}
									}
    							}
    							return $func_facebook;
    						}
    						elseif($face_users_count == 1){
    							$status = $facebook->api_client->call_method("facebook.status.get", array('uid' => $face_users[0], 'limit' => '5'));
								$count = count($status);
								for($i=0;$i<=$count;$i++){
								    $message = $status[$i]['message'];
								    $usr_unix = $status[$i]['time'];
								    $face_uid = $status[$i]['uid'];
								    if($face_uid){
								        $friends2 = $facebook->api_client->users_getinfo($face_uid, 'first_name,last_name,pic,profile_url');
								        $_fname = $friends2[0]['first_name'];
								        $_lname = $friends2[0]['last_name'];
								        $_pic = $friends2[0]['pic'];
								        $_url = $friends2[0]['profile_url'];
								    }
								    if(isset($usr_unix) && !empty($usr_unix)){
								        $usr_time = date("H:ia",$usr_unix);
								        $usr_date = date("jS F Y",$usr_unix);
								    }
								    if(isset($message) && !empty($message)){
								       $func_facebook .= "
								        <a href=\"$_url\"><img src=\"img/FaceBook_24x24.png\"/> <b>$_fname $_lname</b></a> 
								        <div class=\"prof_feed_div\">
								        	<span class=\"feed_content\">$message</span><br />
								        	<span class=\"feed_date\">$usr_time on $usr_date</span>
								        </div><br />";
								    }
								}
    							return $func_facebook;
    						}
    						elseif($face_users_count < 1){
    							$error = "<span class=\"error_msg\">You are not following anyones FaceBook feeds.</span><br /><br />";
    							return $error;
    						}
    					}
    				}
    			}
    		}
    		elseif($place=="profile"){
    			$face_chk_sql = "SELECT facebook FROM usercred WHERE id='$uid'";
				$face_chk_result = mysql_query($face_chk_sql);
				if($face_chk_result){
				    list($face_chk) = mysql_fetch_array($face_chk_result);
				    if($face_chk=="1"){
    			    	$face_user_sql = "SELECT user FROM face_feeds WHERE uid='$uid'";
						$face_user_result = mysql_query($face_user_sql);
						if($face_user_result){
							list($face_user_user) = mysql_fetch_array($face_user_result);
							if(isset($face_user_user) && !empty($face_user_user) && $face_user_user!="0"){
				    			$status = $facebook->api_client->call_method("facebook.status.get", array('uid' => $face_user_user, 'limit' => '5'));
				        		$count = count($status);
				        		for($i=0;$i<=$count;$i++){
				        			$message = $status[$i]['message'];
				        			$usr_unix = $status[$i]['time'];
				        			$face_uid = $status[$i]['uid'];
				        			if($face_uid){
				        			    $friends2 = $facebook->api_client->users_getinfo($face_uid, 'first_name,last_name,pic,profile_url');
				        			    $_fname = $friends2[0]['first_name'];
				        			    $_lname = $friends2[0]['last_name'];
				        			    $_pic = $friends2[0]['pic'];
				        			    $_url = $friends2[0]['profile_url'];
				        			}
				        			if(isset($usr_unix) && !empty($usr_unix)){
				        			    $usr_time = date("H:ia",$usr_unix);
				        			    $usr_date = date("jS F Y",$usr_unix);
				        			}
				        			if(isset($message) && !empty($message)){
				        			   $func_facebook .= "
				        			    <a href=\"$_url\"><img src=\"img/FaceBook_24x24.png\"/> <b>$_fname $_lname</b></a> 
				        			    <div class=\"prof_feed_div\">
				        			    	<span class=\"feed_content\">$message</span><br />
				        			    	<span class=\"feed_date\">$usr_time on $usr_date</span>
				        			    </div><br />";
				        			}
				        		}
    			    			return $func_facebook;
    			    		}
						}
    			    }
    			    elseif($face_chk=="0"){
    					$error = "<span class=\"error_msg\"><u>$username</u> Hasn't enabled their Facebook Feeds.</span><br /><br />";
						return $error;
    				}
    			}
    		}
		}
		elseif($feed_type=="profile"){
			if($prof_users_count >= 1 || $place=="profile"){
				$prof_sql = "SELECT id,feed,time,uid FROM prof_feeds WHERE $query_uid ORDER BY time DESC";
				$prof_result = mysql_query($prof_sql);
				$time = array();
				while(list($id,$feed,$ftime,$usrid) = mysql_fetch_array($prof_result)){
    			    array_push($time,array("$ftime","$id","$feed"));
    			}
    			rsort($time);
    			$time = array_slice($time, 0, 5);
    			$count2 = count($time);
    			$count2 = ($count2 - 1);
    			for($i = 0; $i <= $count2; $i++){
    			    $usr_unix = $sf->timeZone('0',$time[$i][0]);
    			    $usr_time = date("H:ia",$usr_unix);
				    $usr_date = date("jS F Y",$usr_unix);
    			    $usr_id = $time[$i][1];
    			    $usr_feed = $time[$i][2];
    			    $fuid_sql = "SELECT uid FROM prof_feeds WHERE id='$usr_id'";
    			    $fuid_result = mysql_query($fuid_sql);
    			    list($usr_uid) = mysql_fetch_array($fuid_result);
    			    $usr_sql = "SELECT user,email,fname,lname FROM usercred WHERE id='$usr_uid'";
    			    $usr_result = mysql_query($usr_sql);
    			    list($usr_user,$usr_email,$usr_fname,$usr_lname) = mysql_fetch_array($usr_result);
    			    $users4 .= "
    			    <a href=\"main.php?public=$usr_user\"><img src=\"inc/avatars.php?u=$usr_user\" width=\"24px\"/> <span class=\"feed_user\">$usr_user</span></a>
    			    <div class=\"prof_feed_div\">
    			    	<span class=\"feed_content\">$usr_feed</span><br />
    			    	<span class=\"feed_date\">$usr_time on $usr_date</span>
    			    	<br /><br />
    			    </div>";
    			}
    			return $users4;
    		}
    	}
    	elseif($feed_type=="twitter"){
    		if($place=="home"){
    			foreach($twit_uid as $tw_uid){
    				$twit_chk_sql = "SELECT twitter FROM usercred WHERE id='$tw_uid'";
					$twit_chk_result = mysql_query($twit_chk_sql);
					if($twit_chk_result){
						list($twit_chk) = mysql_fetch_array($twit_chk_result);
						if($twit_chk=="1"){
    						if($twit_users_count >= 1){
    							$func_twitter .= "<script type=\"text/javascript\" src=\"js/blogger.js\"></script>";
    							foreach($twit_users as $tw_user){
    								$func_twitter .= "
									<div id=\"twitter_div\">
										<div id=\"twitter_update_list_$tw_user\"></div>
									</div>
									<script type=\"text/javascript\" src=\"http://twitter.com/statuses/user_timeline/$tw_user.json?callback=twitterCallback2&amp;count=5\"></script>";
    							}
    							return $func_twitter;
    						}
    						elseif($twit_users_count < 1){
    							$error = "<span class=\"error_msg\">You are not following anyones Twitter feeds.</span><br /><br />";
    							return $error;
    						}
    					}
    				}
    			}
    		}
    		elseif($place=="profile"){
    			$twit_chk_sql = "SELECT twitter FROM usercred WHERE id='$uid'";
				$twit_chk_result = mysql_query($twit_chk_sql);
				if($twit_chk_result){
					list($twit_chk) = mysql_fetch_array($twit_chk_result);
					if($twit_chk=="1"){
    					$twit_user_sql = "SELECT user FROM twit_feeds WHERE uid='$uid'";
						$twit_user_result = mysql_query($twit_user_sql);
						if($twit_user_result){
							list($twit_user_user) = mysql_fetch_array($twit_user_result);
						}
    					$func_twitter = "
						<div id=\"twitter_div\">
							<div id=\"twitter_update_list_$twit_user_user\"></div>
						</div>
						<script type=\"text/javascript\" src=\"js/blogger.js\"></script>
						<script type=\"text/javascript\" src=\"http://twitter.com/statuses/user_timeline/$twit_user_user.json?callback=twitterCallback2&amp;count=5\"></script>";
    					return $func_twitter;
    				}
    				elseif($twit_chk=="0"){
    					$error = "<span class=\"error_msg\"><u>$username</u> Hasn't enabled their Twitter Feeds.</span><br /><br />";
						return $error;
    				}
    			}
    		}
    	}
    	/*elseif($feed_type=="youtube"){
    		require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
			Zend_Loader::loadClass('Zend_Gdata_YouTube');
			Zend_Loader::loadClass('Zend_Gdata_AuthSub');
			Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
			$yt = new Zend_Gdata_YouTube();
			$yt->setMajorProtocolVersion(2);
    		if($place=="home"){
    			foreach($yout_uid as $yt_uid){
    				$yout_chk_sql = "SELECT youtube FROM usercred WHERE id='$yt_uid'";
					$yout_chk_result = mysql_query($yout_chk_sql);
					if($yout_chk_result){
						list($yout_chk) = mysql_fetch_array($yout_chk_result);
						if($yout_chk=="1"){
    						if($yout_users_count >= 1){
    						    foreach($yout_users as $yuser){
    						    	$yusers .= "$yuser,";
    						    }
    						    $yusers = substr($yusers, 0, -1);
							    $activityFeed = $yt->getActivityForUser("$yusers");
							    return printActivityFeed($activityFeed);
							}
							elseif($yout_users_count < 1){
    						    $error = "<span class=\"error_msg\">You are not following anyones YouTube feeds.</span><br /><br />";
    						    return $error;
    						}
    					}
    				}
    			}
    		}
    		elseif($place=="profile"){
    			$yout_chk_sql = "SELECT youtube FROM usercred WHERE id='$uid'";
				$yout_chk_result = mysql_query($yout_chk_sql);
				if($yout_chk_result){
					list($yout_chk) = mysql_fetch_array($yout_chk_result);
					if($yout_chk=="1"){
						$yout_user_sql = "SELECT user FROM you_feeds WHERE uid='$uid'";
						$yout_user_result = mysql_query($yout_user_sql);
						if($yout_user_result){
							list($yout_user_user) = mysql_fetch_array($yout_user_result);
						}
						$activityFeed = $yt->getActivityForUser("$yout_user_user");
						return printActivityFeed($activityFeed);
					}
					elseif($yout_chk=="0"){
						$error = "<span class=\"error_msg\"><u>$username</u> Hasn't enabled their Youtube Feeds.</span><br /><br />";
						return $error;
					}
				}
    		}
    	}*/
    }
    /*function printActivityFeed($activityFeed){
    	global $youtube;
    	$limit = 5;
    	$i = 1;
    	foreach($activityFeed as $activityEntry){
    		if($i<=$limit){
    	    	$author = $activityEntry->getAuthorName();
    	    	$vid_user = $activityEntry->getUsername()->text;
    	    	$vid_id = $activityEntry->getVideoId()->text;
    	    	$vid_rating = $activityEntry->getRatingValue();
    	    	$activityType = $activityEntry->getActivityType();
    	    	$updated = $activityEntry->getUpdated();
    	    	$year = substr($updated, 0, 4);
    	    	$month = substr($updated, 5, 2);
    	    	$day = substr($updated, 8, 2);
    	    	$hour = substr($updated, 11, 2);
    	    	$minute = substr($updated, 14, 2);
    	    	$second = substr($updated, 17, 2);
    	    	$usr_unix = mktime($hour, $minute, $second, $month, $day, $year);
    	    	$usr_time = date("H:ia",$usr_unix);
    	    	$usr_date = date("jS F Y",$usr_unix);
    	    	$updated = "$usr_time on $usr_date";
    	    	$yicon = "<img src=\"img/Youtube_24x24.png\"/>";
    	    	switch($activityType){
    	    		case 'video_rated':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Rated video <a href=\"http://www.youtube.com/watch?v=$vid_id\">http://www.youtube.com/watch?v=$vid_id</a> $vid_rating stars</span> - <a href=\"javascript:watch_yout('$vid_id','rated','$author')\">Watch</a><br /><span class=\"feed_date\">$updated</span><br /><div id=\"yout_rated_$vid_id"."_$author\"></div></div><br />";
    	    		break;
    	    		case 'video_shared':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Shared video <a href=\"http://www.youtube.com/watch?v=$vid_id\">http://www.youtube.com/watch?v=$vid_id</a></span> - <a href=\"javascript:watch_yout('$vid_id','shared','$author')\">Watch</a><br /><span class=\"feed_date\">$updated</span><br /><div id=\"yout_shared_$vid_id"."_$author\"></div></div><br />";
    	    		break;
    	    		case 'video_favorited':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Favorited video <a href=\"http://www.youtube.com/watch?v=$vid_id\">http://www.youtube.com/watch?v=$vid_id</a></span> - <a href=\"javascript:watch_yout('$vid_id','favourited','$author')\">Watch</a><br /><span class=\"feed_date\">$updated</span><br /><div id=\"yout_favourited_$vid_id"."_$author\"></div></div><br />";
    	    		break;
    	    		case 'video_commented':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Commented on video <a href=\"http://www.youtube.com/watch?v=$vid_id\">http://www.youtube.com/watch?v=$vid_id</a></span> - <a href=\"javascript:watch_yout('$vid_id','comment','$author')\">Watch</a><br /><span class=\"feed_date\">$updated</span><br /><div id=\"yout_comment_$vid_id"."_$author\"></div></div><br />";
    	    		break;
    	    		case 'video_uploaded':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Uploaded video <a href=\"http://www.youtube.com/watch?v=$vid_id\">http://www.youtube.com/watch?v=$vid_id</a></span> - <a href=\"javascript:watch_yout('$vid_id','uploaded','$author')\">Watch</a><br /><span class=\"feed_date\">$updated</span><br /><div id=\"yout_uploaded_$vid_id"."_$author\"></div></div><br />";
    	    		break;
    	    		case 'friend_added':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Friended <a href=\"http://www.youtube.com/user/$vid_user\">$vid_user</a></span><br /><span class=\"feed_date\">$updated</span></div><br />";
    	    		break;
    	    		case 'user_subscription_added':
    	    		$youtube .= "<a href=\"http://www.youtube.com/user/$author\">$yicon <span class=\"feed_user\">$author</span></a><div class=\"prof_feed_div\"><span class=\"feed_content\">Subscribed to channel of <a href=\"http://www.youtube.com/user/$vid_user\">$vid_user</a></span><br /><span class=\"feed_date\">$updated</span></div><br />";
    	    		break;
    	    	}
    	    	$i++;
    	    }
    	}
    	return $youtube;
    }*/
?>