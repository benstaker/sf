var xmlhttp; // Login
var xmlhttp2; // Twitter Tweets - ** Depreceated **
var xmlhttp3; // Load Pages Dynamically
var xmlhttp4; // Load Sidebar Dynamically
var xmlhttp5; // Signup
var xmlhttp6; // Load Navi Dynamically
var xmlhttp7; // Logout
var xmlhttp8; // Post Feed
var xmlhttp9; // Change Bio
var xmlhttp10; // Change Email Address
var xmlhttp11; // Change Password
var xmlhttp12; // Facebook User Change
var xmlhttp13; // Twitter User Change
var xmlhttp14; // YouTube User Change
var xmlhttp15; // Disable Feed
var xmlhttp16; // Enable Feed
var xmlhttp17; // Public Profile
var xmlhttp18; // Public Profile

function login(){
    var username_field = document.getElementById('login_username').value;
    var password_field = document.getElementById('login_password').value;
    xmlhttp=GetXmlHttpObject();
    if(xmlhttp==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="login_username="+username_field+"&login_password="+password_field;
    xmlhttp.onreadystatechange=function(){
    	stateChanged(xmlhttp,'xmlhttp','content_box','blank');
    };
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", params.length);
    xmlhttp.setRequestHeader("Connection", "close");
    xmlhttp.send(params);
}

/*function twitter(username,status,id,time){
    xmlhttp2=GetXmlHttpObject();
    if(xmlhttp2==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="twit_user="+username+"&twit_status="+status+"&twit_id="+id+"&twit_time="+time;
    xmlhttp2.onreadystatechange=function(){
    	stateChanged(xmlhttp2,'xmlhttp2','twitter_div','blank');
    };
    xmlhttp2.open("POST",url,true);
    xmlhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp2.setRequestHeader("Content-length", params.length);
    xmlhttp2.setRequestHeader("Connection", "close");
    xmlhttp2.send(params);
}*/

function loadPage(page){
	xmlhttp3=GetXmlHttpObject();
    if(xmlhttp3==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var random = Math.floor(Math.random()*101);
    var url="functions.php?p="+page+"&r="+random;
    xmlhttp3.onreadystatechange=function(){
    	stateChanged(xmlhttp3,'xmlhttp3','content_box','blank');
    };
    xmlhttp3.open("GET",url,true);
    xmlhttp3.send(null);
}

function loadSidebar(sidebar){
	xmlhttp4=GetXmlHttpObject();
    if(xmlhttp4==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var random = Math.floor(Math.random()*101);
    var url="functions.php?s="+sidebar+"&r="+random;
    xmlhttp4.onreadystatechange=function(){
    	stateChanged(xmlhttp4,'xmlhttp4','sidebar','blank');
    };
    xmlhttp4.open("GET",url,true);
    xmlhttp4.send(null);
}

function signup(){
	var check_field = document.getElementById('signup_check').value;
    var fname_field = document.getElementById('signup_fname').value;
    var lname_field = document.getElementById('signup_lname').value;
    var uname_field = document.getElementById('signup_uname').value;
    var pass_field = document.getElementById('signup_pass').value;
    var pass2_field = document.getElementById('signup_pass2').value;
    var email_field = document.getElementById('signup_email').value;
    var email2_field = document.getElementById('signup_email2').value;
    xmlhttp5=GetXmlHttpObject();
    if(xmlhttp5==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="signup_check="+check_field+"&signup_fname="+fname_field+"&signup_lname="+lname_field+"&signup_uname="+uname_field+"&signup_pass="+pass_field+"&signup_pass2="+pass2_field+"&signup_email="+email_field+"&signup_email2="+email2_field;
    xmlhttp5.onreadystatechange=function(){
    	stateChanged(xmlhttp5,'xmlhttp5','sidebar','blank');
    };
    xmlhttp5.open("POST",url,true);
    xmlhttp5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp5.setRequestHeader("Content-length", params.length);
    xmlhttp5.setRequestHeader("Connection", "close");
    xmlhttp5.send(params);
}

function loadNavi(navi){
	xmlhttp6=GetXmlHttpObject();
    if(xmlhttp6==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var random = Math.floor(Math.random()*101);
    var url="functions.php?n="+navi+"&r="+random;
    xmlhttp6.onreadystatechange=function(){
    	stateChanged(xmlhttp6,'xmlhttp6','navi','blank');
    };
    xmlhttp6.open("GET",url,true);
    xmlhttp6.send(null);
}

function logout(){
	xmlhttp7=GetXmlHttpObject();
    if(xmlhttp7==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var random = Math.floor(Math.random()*101);
    var url="functions.php?p=logout&r="+random;
    xmlhttp7.onreadystatechange=function(){
    	stateChanged(xmlhttp7,'xmlhttp7','content_box','blank');
    };
    xmlhttp7.open("GET",url,true);
    xmlhttp7.send(null);
}

function post_feed(){
	var post_message = document.getElementById('post_message').value;
	var post_box = document.getElementById('post_feed');
    var post_feeds = post_box.innerHTML;
    xmlhttp8=GetXmlHttpObject();
    if(xmlhttp8==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="new_post="+post_message;
    xmlhttp8.onreadystatechange=function(){
    	stateChanged(xmlhttp8,'xmlhttp8','post_feed',post_feeds);
    };
    xmlhttp8.open("POST",url,true);
    xmlhttp8.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp8.setRequestHeader("Content-length", params.length);
    xmlhttp8.setRequestHeader("Connection", "close");
    xmlhttp8.send(params);
    post_message = "";
}

function change_bio(){
	var new_bio = document.getElementById('bio_text').value;
    xmlhttp9=GetXmlHttpObject();
    if(xmlhttp9==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="new_bio="+new_bio;
    xmlhttp9.onreadystatechange=function(){
    	stateChanged(xmlhttp9,'xmlhttp9','current_bio','blank');
    };
    xmlhttp9.open("POST",url,true);
    xmlhttp9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp9.setRequestHeader("Content-length", params.length);
    xmlhttp9.setRequestHeader("Connection", "close");
    xmlhttp9.send(params);
}

function change_email(){
	var new_email = document.getElementById('email_text').value;
	var new_email2 = document.getElementById('email_text2').value;
    xmlhttp10=GetXmlHttpObject();
    if(xmlhttp10==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="new_email="+new_email+"&new_email2="+new_email2;
    xmlhttp10.onreadystatechange=function(){
    	stateChanged(xmlhttp10,'xmlhttp10','current_email','blank');
    };
    xmlhttp10.open("POST",url,true);
    xmlhttp10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp10.setRequestHeader("Content-length", params.length);
    xmlhttp10.setRequestHeader("Connection", "close");
    xmlhttp10.send(params);
}

function change_pass(){
	var new_pass = document.getElementById('pass_text').value;
	var new_pass2 = document.getElementById('pass_text2').value;
    xmlhttp11=GetXmlHttpObject();
    if(xmlhttp11==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var url="functions.php";
    var params="new_pass="+new_pass+"&new_pass2="+new_pass2;
    xmlhttp11.onreadystatechange=function(){
    	stateChanged(xmlhttp11,'xmlhttp11','password_status','blank');
    };
    xmlhttp11.open("POST",url,true);
    xmlhttp11.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp11.setRequestHeader("Content-length", params.length);
    xmlhttp11.setRequestHeader("Connection", "close");
    xmlhttp11.send(params);
}

function face_usr_chg(cont,user){
	var element = document.getElementById(cont);
	xmlhttp12=GetXmlHttpObject();
	if(xmlhttp12==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="functions.php";
	var params="face_usr_chg="+element.value;
	if(user=="s0c1alf33d5"){
		params = params+"&enable_face=yes"
	}
	xmlhttp12.onreadystatechange=function(){
		stateChanged(xmlhttp12,'xmlhttp12','facebook_status','blank');
	};
	xmlhttp12.open("POST",url,true);
	xmlhttp12.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp12.setRequestHeader("Content-length", params.length);
    xmlhttp12.setRequestHeader("Connection", "close");
	xmlhttp12.send(params);
}

function twit_usr_chg(cont,user){
	var element = document.getElementById(cont);
	xmlhttp13=GetXmlHttpObject();
	if(xmlhttp13==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="functions.php";
	var params="twit_usr_chg="+element.value;
	if(user=="s0c1alf33d5"){
		params = params+"&enable_twit=yes"
	}
	xmlhttp13.onreadystatechange=function(){
		stateChanged(xmlhttp13,'xmlhttp13','twitter_status','blank');
	};
	xmlhttp13.open("POST",url,true);
	xmlhttp13.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp13.setRequestHeader("Content-length", params.length);
    xmlhttp13.setRequestHeader("Connection", "close");
	xmlhttp13.send(params);
}

function yout_usr_chg(cont,user){
	var element = document.getElementById(cont);
	xmlhttp14=GetXmlHttpObject();
	if(xmlhttp14==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="functions.php";
	var params="yout_usr_chg="+element.value;
	if(user=="s0c1alf33d5"){
		params = params+"&enable_yout=yes"
	}
	xmlhttp14.onreadystatechange=function(){
		stateChanged(xmlhttp14,'xmlhttp14','youtube_status','blank');
	};
	xmlhttp14.open("POST",url,true);
	xmlhttp14.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp14.setRequestHeader("Content-length", params.length);
    xmlhttp14.setRequestHeader("Connection", "close");
	xmlhttp14.send(params);
}

function disable_feed(feed){
	xmlhttp15=GetXmlHttpObject();
	if(xmlhttp15==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="functions.php";
	var params="feeds_disable="+feed;
	xmlhttp15.onreadystatechange=function(){
		stateChanged(xmlhttp15,'xmlhttp15','feed_status',feed);
	};
	xmlhttp15.open("POST",url,true);
	xmlhttp15.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp15.setRequestHeader("Content-length", params.length);
    xmlhttp15.setRequestHeader("Connection", "close");
	xmlhttp15.send(params);
}

function enable_feed(feed){
	xmlhttp16=GetXmlHttpObject();
	if(xmlhttp16==null){
		alert ("Browser does not support HTTP Request");
		return;
	}
	var url="functions.php";
	var params="feeds_enable="+feed;
	xmlhttp16.onreadystatechange=function(){
		stateChanged(xmlhttp16,'xmlhttp16','feed_status',feed);
	};
	xmlhttp16.open("POST",url,true);
	xmlhttp16.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp16.setRequestHeader("Content-length", params.length);
    xmlhttp16.setRequestHeader("Connection", "close");
	xmlhttp16.send(params);
}

function public_user(username){
	xmlhttp17=GetXmlHttpObject();
    if(xmlhttp17==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var random = Math.floor(Math.random()*101);
    var url="functions.php?p=public&r="+random+"&u="+username;
    xmlhttp17.onreadystatechange=function(){
    	stateChanged(xmlhttp17,'xmlhttp17','content_box','blank');
    };
    xmlhttp17.open("GET",url,true);
    xmlhttp17.send(null);
}

function olderFeeds(place,number,user){
	xmlhttp18=GetXmlHttpObject();
	if(place=="public"){
		place = place+"&u="+user;
	}
    if(xmlhttp18==null){
    	alert ("Browser does not support HTTP Request");
    	return;
    }
    var random = Math.floor(Math.random()*101);
    var url="functions.php?p="+place+"&r="+random+"&s="+number;
    var feed_cont = 'older_feeds_'+number;
    xmlhttp18.onreadystatechange=function(){
    	stateChanged(xmlhttp18,'xmlhttp18',feed_cont,'blank');
    };
    xmlhttp18.open("GET",url,true);
    xmlhttp18.send(null);
}

function stateChanged(ajax,name,cont,post_feed){
	xmlhttp = ajax;
    var element = document.getElementById(cont);
    if(xmlhttp.readyState==1){
    	if(name!="xmlhttp2"){
    		element.innerHTML = "<img src='loading.gif'/>";
    	}
    }
    if(xmlhttp.readyState==4){
    	if(!xmlhttp.responseText){
    		if(name=="xmlhttp"){
    			window.location.href = 'index.php';
    		}
    		if(name=="xmlhttp5" || name=="xmlhttp7"){
    			loadNavi('home');
    			loadPage('home');
				loadSidebar('home');
    		}
    	}
    	else if(name=="xmlhttp15" || name=="xmlhttp16"){
    		element.innerHTML = xmlhttp.responseText;
    		if(name=="xmlhttp15"){
    			if(post_feed=="bebo"){
    				document.getElementById('bebo_icon_stat').innerHTML="";
    			}
    			if(post_feed=="facebook"){
    				document.getElementById('face_icon_stat').innerHTML="";
    			}
    			if(post_feed=="myspace"){
    				document.getElementById('mysp_icon_stat').innerHTML="";
    			}
    			if(post_feed=="twitter"){
    				document.getElementById('twit_icon_stat').innerHTML="";
    			}
    			if(post_feed=="youtube"){
    				document.getElementById('yout_icon_stat').innerHTML="";
    			}
    		}
    		if(name=="xmlhttp16"){
    			if(post_feed=="bebo"){
    				document.getElementById('bebo_icon_stat').innerHTML="<img src='../img/bebo_32.png' width='24px'/>";
    			}
    			if(post_feed=="facebook"){
    				document.getElementById('face_icon_stat').innerHTML="<img src='../img/FaceBook_24x24.png' width='24px'/>";
    			}
    			if(post_feed=="myspace"){
    				document.getElementById('mysp_icon_stat').innerHTML="<img src='../img/myspace_32.png' width='24px'/>";
    			}
    			if(post_feed=="twitter"){
    				document.getElementById('twit_icon_stat').innerHTML="<img src='../img/Twitter_24x24.png' width='24px'/>";
    			}
    			if(post_feed=="youtube"){
    				document.getElementById('yout_icon_stat').innerHTML="<img src='../img/Youtube_24x24.png' width='24px'/>";
    			}
    		}
    		loadPage('settings_'+post_feed);
    	}
    	else if(name=="xmlhttp8"){
    		element.innerHTML = xmlhttp.responseText+post_feed;
    	}
    	else {
    		element.innerHTML = xmlhttp.responseText;
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

/*						 *
 *	Javascript Functions *
 *						 */

function submitLogin(ev){
	var enter = DetectEnterPressed(ev);
	if(enter){
		login();
	}
}

function DetectEnterPressed(e){
	var characterCode;
	if(e && e.which){ // NN4 specific code
		e = e;
		characterCode = e.which;
	}
	else {
		e = event;
		characterCode = e.keyCode; // IE specific code
	}
	if(characterCode == 13){
		return true;
	}
	else {
		return false;
	}
}
function watch_yout(id,type,author,time){
	var yout_box = document.getElementById('yout_'+type+'_'+id+'_'+author+'_'+time);
	yout_box.innerHTML = "<object width='492' height='300'><param name='movie' value='http://www.youtube.com/v/"+id+"&hl=en&fs=1&'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/"+id+"&hl=en&fs=1&' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='492' height='300'></embed></object>";
	var hide_box = document.getElementById("watch_yout_"+type+"_"+id+"_"+author+"_"+time);
	hide_box.innerHTML = "<a href=\"javascript:hide_yout('"+id+"','"+type+"','"+author+"','"+time+"')\">Hide</a>";
}

function hide_yout(id,type,author,time){
	var yout_box = document.getElementById('yout_'+type+'_'+id+'_'+author+'_'+time);
	yout_box.innerHTML = "";
	var hide_box = document.getElementById("watch_yout_"+type+"_"+id+"_"+author+"_"+time);
	hide_box.innerHTML = "<a href=\"javascript:watch_yout('"+id+"','"+type+"','"+author+"','"+time+"')\">Watch</a>";
}