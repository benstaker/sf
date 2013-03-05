var xmlhttp; // Settings Sections
var xmlhttp2; // Disable Feeds
var xmlhttp3; // Enable Feeds
var xmlhttp4; // Twitter User Change
var xmlhttp5; // Add Feeds
var xmlhttp6; // Follow Users
var xmlhttp7; // Youtube User Change
var xmlhttp8; // Stop Following All Feeds
var xmlhttp9; // Stop Following Certain Feed
var xmlhttp10; // Facebook User Change

function load_settings(section){
	xmlhttp=GetXmlHttpObject();
	if(xmlhttp==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	url=url+"?e="+section;
	xmlhttp.onreadystatechange=function(){
		stateChanged(xmlhttp,'blank','xmlhttp','settings_box','blank','blank');
	};
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}

function disable_feed(feed){
	xmlhttp2=GetXmlHttpObject();
	if(xmlhttp2==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="feeds_disable="+feed;
	xmlhttp2.onreadystatechange=function(){
		stateChanged(xmlhttp2,feed,'xmlhttp2','settings_box','blank','blank');
	};
	xmlhttp2.open("POST",url,true);
	xmlhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp2.setRequestHeader("Content-length", params.length);
    xmlhttp2.setRequestHeader("Connection", "close");
	xmlhttp2.send(params);
}

function enable_feed(feed){
	xmlhttp3=GetXmlHttpObject();
	if(xmlhttp3==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="feeds_enable="+feed;
	xmlhttp3.onreadystatechange=function(){
		stateChanged(xmlhttp3,feed,'xmlhttp3','settings_box','blank','blank');
	};
	xmlhttp3.open("POST",url,true);
	xmlhttp3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp3.setRequestHeader("Content-length", params.length);
    xmlhttp3.setRequestHeader("Connection", "close");
	xmlhttp3.send(params);
}

function feed_length(dcont,cont){
	var element = document.getElementById(cont);
	var element2 = document.getElementById(dcont);
	var feed_length = element.value.length;
	var feed_lim = 150;
	feed_lim = (150 - feed_length);
	if(feed_lim <= 10){
		feed_lim = '<span style="color: #FF0000;">'+feed_lim+'</span>';
	}
	element2.innerHTML = feed_lim;
}

function twit_usr_chg(cont,feed,user){
	var element = document.getElementById(cont);
	xmlhttp4=GetXmlHttpObject();
	if(xmlhttp4==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="twit_usr_chg="+element.value;
	if(user=="s0c1alf33d5"){
		params = params+"&enable_twit=yes"
	}
	xmlhttp4.onreadystatechange=function(){
		stateChanged(xmlhttp4,feed,'xmlhttp4','settings_box','blank','blank');
	};
	xmlhttp4.open("POST",url,true);
	xmlhttp4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp4.setRequestHeader("Content-length", params.length);
    xmlhttp4.setRequestHeader("Connection", "close");
	xmlhttp4.send(params);
}

function feed_update(cont){
	var element = document.getElementById(cont);
	xmlhttp5=GetXmlHttpObject();
	if(xmlhttp5==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="feed_update="+element.value;
	xmlhttp5.onreadystatechange=function(){
		stateChanged(xmlhttp5,'blank','xmlhttp5','last_feed','blank','blank');
	};
	xmlhttp5.open("POST",url,true);
	xmlhttp5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp5.setRequestHeader("Content-length", params.length);
    xmlhttp5.setRequestHeader("Connection", "close");
    xmlhttp5.send(params);
}

function follow_me(follow,user){
	xmlhttp6=GetXmlHttpObject();
	if(xmlhttp6==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="follow_user="+follow;
	xmlhttp6.onreadystatechange=function(){
		stateChanged(xmlhttp6,'blank','xmlhttp6','followed_status',follow,user);
	};
	xmlhttp6.open("POST",url,true);
	xmlhttp6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp6.setRequestHeader("Content-length", params.length);
    xmlhttp6.setRequestHeader("Connection", "close");
	xmlhttp6.send(params);
}

function yout_usr_chg(cont,feed,user){
	var element = document.getElementById(cont);
	xmlhttp7=GetXmlHttpObject();
	if(xmlhttp7==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="yout_usr_chg="+element.value;
	if(user=="s0c1alf33d5"){
		params = params+"&enable_yout=yes"
	}
	xmlhttp7.onreadystatechange=function(){
		stateChanged(xmlhttp7,feed,'xmlhttp7','settings_box','blank','blank');
	};
	xmlhttp7.open("POST",url,true);
	xmlhttp7.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp7.setRequestHeader("Content-length", params.length);
    xmlhttp7.setRequestHeader("Connection", "close");
	xmlhttp7.send(params);
}

function stop_follow_me(follow,user){
	xmlhttp8=GetXmlHttpObject();
	if(xmlhttp8==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="stop_follow_user="+follow;
	xmlhttp8.onreadystatechange=function(){
		stateChanged(xmlhttp8,'blank','xmlhttp8','followed_status',follow,user);
	};
	xmlhttp8.open("POST",url,true);
	xmlhttp8.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp8.setRequestHeader("Content-length", params.length);
    xmlhttp8.setRequestHeader("Connection", "close");
	xmlhttp8.send(params);
}

function stop_following(follow,user,feed){
	xmlhttp9=GetXmlHttpObject();
	if(xmlhttp9==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="stop_following_user="+follow+"&stop_following_feed="+feed;
	xmlhttp9.onreadystatechange=function(){
		stateChanged(xmlhttp9,'blank','xmlhttp9',follow+"_"+feed,'blank','blank');
	};
	xmlhttp9.open("POST",url,true);
	xmlhttp9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp9.setRequestHeader("Content-length", params.length);
    xmlhttp9.setRequestHeader("Connection", "close");
	xmlhttp9.send(params);
}

function face_usr_chg(cont,feed,user){
	var element = document.getElementById(cont);
	xmlhttp10=GetXmlHttpObject();
	if(xmlhttp10==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="inc/change.php";
	var params="face_usr_chg="+element.value;
	if(user=="s0c1alf33d5"){
		params = params+"&enable_face=yes"
	}
	xmlhttp10.onreadystatechange=function(){
		stateChanged(xmlhttp10,feed,'xmlhttp10','settings_box','blank','blank');
	};
	xmlhttp10.open("POST",url,true);
	xmlhttp10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp10.setRequestHeader("Content-length", params.length);
    xmlhttp10.setRequestHeader("Connection", "close");
	xmlhttp10.send(params);
}

function watch_yout(id,type,author){
	var yout_box = document.getElementById('yout_'+type+'_'+id+'_'+author);
	yout_box.innerHTML = "<object width='492' height='300'><param name='movie' value='http://www.youtube.com/v/"+id+"&hl=en&fs=1&'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/"+id+"&hl=en&fs=1&' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='492' height='300'></embed></object>";
}

function stateChanged(ajax,feed,name,dcont,follow,user){
	xmlhttp = ajax;
	var element = document.getElementById(dcont);
	if(xmlhttp.readyState==4){
		element.innerHTML = xmlhttp.responseText;
		if(name=="xmlhttp2" || name=="xmlhttp3" || name=="xmlhttp4" || name=="xmlhttp7" || name=="xmlhttp10"){
			load_settings(feed);
		}
		else if(name=="xmlhttp6" || name=="xmlhttp8"){
			if(name=="xmlhttp6"){
				//Change button to Stop Following Me
				document.getElementById('followed_button').innerHTML = "<input type=\"button\" id=\"stop_follow_me\" class=\"follow_me\" onClick=\"stop_follow_me('"+follow+"','"+user+"');\" value=\"Stop Following Me!\"/>";
			}
			else if(name=="xmlhttp8"){
				//Change button to Follow Me
				document.getElementById('followed_button').innerHTML = "<input type=\"button\" id=\"follow_me\" class=\"follow_me\" onClick=\"follow_me('"+follow+"','"+user+"');\" value=\"Follow Me!\"/>";
			}
		}
	}
}

function GetXmlHttpObject(){
	if(window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if(window.ActiveXObject){
		// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}