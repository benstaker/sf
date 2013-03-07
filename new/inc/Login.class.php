<?php

require_once("User.class.php");

Class Login extends User {

	private $_Database; // Database object.
	private $_User; // User object.

	protected $_userID; // User ID.
	public $name; // Name of class.

	public function __construct($userID=false){

		$this->connect();
		$this->name=get_class($this);

		$this->_startSession();
		if($userID!==false) $this->_userID=$userID;

	}

	public function connect(){

		require_once("Database.class.php");
		$this->_Database=new Database(true);
		
	}

	public function log($message=false){

		/*if($message!==false){

			if(isset($this->_Database) && $this->_Database->_logging)
			$this->_Database->_log->addToLog("<strong>".$this->name.":</strong> ".$message);
		
		}*/

	}

	private function _startSession(){

		if(!isset($_SESSION) || empty($_SESSION)){
			session_start();
			return true;
		} else return false;

	}

	public function status(){

		if(isset($_SESSION["userID"]) && !empty($_SESSION["userID"])) return true;
		else return false;

	}

	private function _check(){

		$this->_User=new User($this->_userID);
		return $this->_User->_checkUserID();

	}

	public function login($username=false, $password=false){

		if(!$this->status()){

			$options=array();
			$options["WHERE"]=array(array("user", $username), array("pass", md5($password)));
			$options["COLUMN"]=array("id");

			$this->_Database->selectTable("usercred", $options);
			$user=$this->_Database->fetchAssociation();
			$this->_userID=$user["id"];

			if($this->_Database->numberOfRows() > 0){
				$_SESSION["userID"]=$this->_userID;
				return true;
			} else return false;

		}
		else return false;

	}

	public function logout(){

		if($this->status()){
			unset($_SESSION["userID"]);
			return true;
		} else return false;

	}

}