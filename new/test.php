<?php

require_once("inc/User.class.php");
$User = new User(8);

echo "<br />";
echo $User->getUsername();

echo "<br />";
echo $User->getEmailAddress();

echo "<br />";
echo $User->getForename();

echo "<br />";
echo $User->getSurname();

echo "<br />";
echo $User->getFullName();

echo "<br />";
echo $User->getUserLevel();

//echo "<br />";
//echo $User->getAvatar();

echo "<br />";
echo $User->getTime();

echo "<br />";
echo $User->getTimezone();

echo "<br />";
echo $User->getBio();

echo "<br />";
echo $User->getBebo();

echo "<br />";
echo $User->getFacebook();

echo "<br />";
echo $User->getMySpace();

echo "<br />";
echo $User->getProfile();

echo "<br />";
echo $User->getTwitter();

echo "<br />";
echo $User->getYouTube();
