<?php

require_once("Database.class.php");

Class User extends Database {

	private $_Database; // Database object.
	protected $_userID; // User ID.
	public $name; // Name of class.

	public function __construct($userID=false){

		$this->connect();
		$this->name=get_class($this);
		if($userID!==false) $this->_userID=$userID;
		else $this->log("__construct() - User ID was not supplied.");

	}

	public function connect(){

		$this->_Database=new Database(true);
		
	}

	public function log($message){

		if(isset($this->_Database) && $this->_Database->_logging) $this->_Database->_log->addToLog("<strong>".$this->name.":</strong> ".$message);

	}

	private function checkUserID($userID=false){

		if($userID!==false) $this->_userID=$userID;
		else if(isset($this->_userID) && !empty($this->_userID)) $userID=$this->_userID;
		else {
			$this->log(" checkUserID() - User ID was not supplied.");
			return false;
		}

		$options=array();
		$options["WHERE"]=array(array("id", $userID));
		$this->_Database->selectTable("usercred", $options);

		if($this->_Database->numberOfRows() > 0) return true;
		else {
			$this->log(" checkUserID() - User ID does not exist.");
			return false;
		}

	}

	// Text
	public function getUsername($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["user"];

		} else return false;

	}

	// Text
	public function getEmailAddress($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["email"];

		} else return false;
		
	}

	// Text
	public function getForename($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["fname"];

		} else return false;
		
	}

	// Text
	public function getSurname($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["lname"];

		} else return false;
		
	}

	// Text
	public function getFullName($userID=false){

		if($this->checkUserID($userID)){

			$forename=$this->getForename($this->_userID);
			$surname=$this->getSurname($this->_userID);
			return $forename." ".$surname;

		} else return false;
		
	}

	// Integer
	public function getUserLevel($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["ulev"];

		} else return false;
		
	}

	// Blob
	public function getAvatar($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["avatar"];

		} else return false;
		
	}

	// Integer
	public function getTime($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["time"];

		} else return false;
		
	}

	// Integer
	public function getTimezone($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["timezone"];

		} else return false;
		
	}

	// Text
	public function getBio($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["bio"];

		} else return false;
		
	}

	// Boolean
	public function getBebo($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["bebo"];

		} else return false;
		
	}

	// Boolean
	public function getFacebook($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["facebook"];

		} else return false;
		
	}

	// Boolean
	public function getMySpace($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["myspace"];

		} else return false;
		
	}

	// Boolean
	public function getProfile($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["profile"];

		} else return false;
		
	}

	// Boolean
	public function getTwitter($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["twitter"];

		} else return false;
		
	}

	// Boolean
	public function getYouTube($userID=false){

		if($this->checkUserID($userID)){

			$options=array();
			$options["WHERE"]=array(array("id", $this->_userID));

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			return $user["youtube"];

		} else return false;
		
	}

}