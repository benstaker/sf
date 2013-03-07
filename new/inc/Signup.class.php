<?php

require_once("functions.inc.php");
require_once("Database.class.php");

Class Signup extends Database {
	
	private $_Database; // Database object.
	public $name; // Name of class.

	public function __construct(){

		$this->connect();
		$this->name=get_class($this);

	}

	public function connect(){

		$this->_Database=new Database(true);
		
	}

	public function log($message=false){

		if($message!==false){

			if(isset($this->_Database) && $this->_Database->_logging)
			$this->_Database->_log->addToLog("<strong>".$this->name.":</strong> ".$message);

		}

	}
	
	public function add($addArray=false){

		$arrayLength=7; // What the array must count to.

		// If addArray is an array.
		if(is_array($addArray)){
			// If addArray array count is correct.
			if(count($addArray, COUNT_RECURSIVE)===$arrayLength){
				$i=0;
				foreach($addArray as $aa){
					// If current index is for Last Name, Avatar OR Bio.
					if($i===6) $check=checkVariable($aa, true);
					else $check=checkVariable($aa);

					if(!$check){
						$this->log('$addArray["'.$i.'"] is not valid.');
						return false;
					}
					
					$i++;
				}

				// Check if the passwords match.
				if($addArray[1]===$addArray[2]){
					// Check if the email addresses match.
					if($addArray[3]===$addArray[4]){

						require_once("User.class.php");
						$User=new User();

						// Check if user doesn't exist.
						if($User->exist("user", $addArray[0])===false){
							// Check if email isn't already in use.
							if($User->exist("email", $addArray[3])===false){
								$into=array(
									array("user", $addArray[0]),
									array("pass", md5($addArray[1])),
									array("email", $addArray[3]),
									array("fname", $addArray[5]),
									array("lname", $addArray[6]),
									array("ulev", 9),
									array("avatar", ""),
									array("time", time()),
									array("timezone", 0),
									array("bio", ""),
									array("bebo", 0),
									array("facebook", 0),
									array("myspace", 0),
									array("profile", 1),
									array("twitter", 0),
									array("youtube", 0)
								);
								$this->_Database->insertInto("usercred", $into);
								return true;
							} else {
								$this->log('Email address <strong>'.$addArray[3].'</strong> already in use.');
								return false;
							}

						} else {
							$this->log('User <strong>'.$addArray[0].'</strong> already exists.');
							return false;
						}

					} else {
						$this->log('Email Addresses do not match.');
						return false;
					}
				}  else {
					$this->log('Passwords do not match.');
					return false;
				}
			} else {
				$this->log('Array count must equal: <strong>'.$arrayLength.'</strong>.');
				return false;
			}
		} else {
			$this->log('Supplied argument is not an array.');
			return false;
		}

	}

}