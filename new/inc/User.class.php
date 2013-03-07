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

	protected function _checkUserID($userID=false){

		if($userID!==false) $this->_userID=$userID;
		else if(isset($this->_userID) && !empty($this->_userID)) $userID=$this->_userID;
		else {
			$this->log(" checkUserID() - User ID was not supplied.");
			return false;
		}

		$options=array();
		$options["WHERE"]=array(array("id", $userID));
		$options["COLUMN"]=array("id");

		$this->_Database->selectTable("usercred", $options);

		if($this->_Database->numberOfRows() > 0) return true;
		else {
			$this->log(" checkUserID() - User ID does not exist.");
			return false;
		}

	}

	public function get($column=false, $userID=false){

		if($this->_checkUserID($userID)){
			if($column!==false){

				// Allowed columns array.
				$column_arr=array("user", "email", "fname", "lname", "ulev",
								  "avatar", "time", "timezone", "bio", "bebo",
								  "facebook", "myspace", "profile", "twitter", "youtube");

				// Check if supplied column is in the array.
				if(in_array($column, $column_arr)){
					$options=array();
					$options["WHERE"]=array(array("id", $this->_userID));
					$options["COLUMN"]=array($column);

					$this->_Database->selectTable("usercred", $options);
					$user=$this->_Database->fetchAssociation();

					$this->log(" get() - Returned <strong>".$column."</strong> successfully.");

					return $user[$column];
				} else {
					$this->log(" get() - Column <strong>".$column."</strong> does not exist.");
					return false;
				}
			}
		} else return false;

	}

	// Text
	public function getUsername($userID=false){

		return $this->get("user", $userID);

	}

	// Text
	public function getEmailAddress($userID=false){

		return $this->get("email", $userID);
		
	}

	// Text
	public function getForename($userID=false){

		return $this->get("fname", $userID);
		
	}

	// Text
	public function getSurname($userID=false){

		return $this->get("lname", $userID);
		
	}

	// Text
	public function getFullName($userID=false){

		$forename=$this->getForename($this->_userID);
		$surname=$this->getSurname($this->_userID);
		if(isset($forename) && !empty($forename) && isset($surname) && !empty($surname))
			return $forename." ".$surname;
		else return false;
		
	}

	// Integer
	public function getUserLevel($userID=false){

		return $this->get("ulev", $userID);
		
	}

	// Blob
	public function getAvatar($userID=false){

		return $this->get("avatar", $userID);
		
	}

	// Integer
	public function getTime($userID=false){

		return $this->get("time", $userID);
		
	}

	// Integer
	public function getTimezone($userID=false){

		return $this->get("timezone", $userID);
		
	}

	// Text
	public function getBio($userID=false){

		return $this->get("bio", $userID);
		
	}

	// Boolean
	public function getBebo($userID=false){

		return $this->get("bebo", $userID);
		
	}

	// Boolean
	public function getFacebook($userID=false){

		return $this->get("facebook", $userID);
		
	}

	// Boolean
	public function getMySpace($userID=false){

		return $this->get("myspace", $userID);
		
	}

	// Boolean
	public function getProfile($userID=false){

		return $this->get("profile", $userID);
		
	}

	// Boolean
	public function getTwitter($userID=false){

		return $this->get("twitter", $userID);
		
	}

	// Boolean
	public function getYouTube($userID=false){

		return $this->get("youtube", $userID);
		
	}

}