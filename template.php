<![if !IE]>
<div style="position: fixed; top: 0; left: 0; background: url('img/clouds-copy.png'); background-repeat: no-repeat; height: 100%; width: 50%;"></div>
<div style="position: fixed; top: 0; right: 0; background: url('img/clouds-right.png'); background-repeat: no-repeat; height: 100%; width: 50%;"></div>
<![endif]>
<?php
	$footer .= "Social Feed &copy; 2009. All Rights Reserved.";
	$header .= "<img src='img/logo-4.png'/>";
?>
<html>
	<head>
		<script type="text/javascript" src="api_ajax.js"></script>
		<link href="style.css" rel="stylesheet" type="text/css"/>
		<!--[if gte IE 7]>
			<link href="iestyle.css" rel="stylesheet" type="text/css"/>
		<![endif]-->
		<?php echo $head;?>
	</head>
	<body>
		<!--[if gte IE 7]>
			<a href='Social_Feed_Proper.png'>What Social Feed should look like in a proper Browser!</a>
		<![endif]-->
		<div id='wrapper'>
			<?php echo $top_content;?>
			<div id='header'>
				<?php echo $header;?>
			</div>
			<div id='navi'>
				<?php echo $navi;?>
			</div>
			<div id='content_box'>
				<?php if(isset($content)){echo $content;}else{echo "<img src='loading.gif'/>";}?>
			</div>
			<div id='sidebar'>
				<?php if(isset($sidebar)){echo $sidebar;}else{echo "<img src='loading.gif'/>";}?>
			</div>
			<div id='footer'>
				<?php echo $footer;?>
			</div>
		</div>
	</body>
</html>