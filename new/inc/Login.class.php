<?php

require_once("User.class.php");

Class Login extends User {

	protected $_userID; // User ID.
	private $_User; // User object.

	public function __construct($userID=false){

		$this->_startSession();
		if($userID!==false) $this->_userID=$userID;

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

	public function login(){

		if($this->_check()){
			$_SESSION["userID"]=$this->_userID;
			return true;
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