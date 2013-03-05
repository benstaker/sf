<?php
	if(isset($_GET['user'])){
		$user = $_GET['user'];
		$twitter .= "
		<script type=\"text/javascript\">
		    function twitterCallback2(twitters) {
		    	  var statusHTML = [];
		    	  for (var i=0; i<twitters.length; i++){
		    	    var username = twitters[i].user.screen_name;
		    	    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^\"\s\<\>]*[^.,;'\">\:\s\<\>\)\]\!])/g, function(url) {
		    	      return '<a href=\"'+url+'\">'+url+'</a>';
		    	    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
		    	      return  reply.charAt(0)+'<a href=\"http://twitter.com/'+reply.substring(1)+'\">'+reply.substring(1)+'</a>';
		    	    });
		    	    var ticon = \"<img src='img/Twitter_24x24.png'/>\";
		    	    //twitter(username,status,twitters[i].id,twitters[i].created_at);
		    	    document.write('NEW_'+username+'_'+status+'_'+twitters[i].id+'_'+twitters[i].created_at);
		    	  }
		    	}
		</script>
		<script type=\"text/javascript\" src=\"http://twitter.com/statuses/user_timeline/$user.json?callback=twitterCallback2&amp;count=5\"></script>";
		echo $twitter;
	}
?>