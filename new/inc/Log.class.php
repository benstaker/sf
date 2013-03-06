<?php

Class Log {

	private $_log = array(); // Log Array.
	private $_name; // Name of Log.

	public function __construct($name){
		$this->_name = $name;
	}

	public function __destruct(){
		$this->outputLog();
	}

	// Add to the log.
	public function addToLog($text){
		array_push($this->_log, $text);
	}

	// Output the log in an un-ordered list.
	public function outputLog(){
		echo "<ul id=\"log_".$this->_name."\">\n";
		foreach ($this->_log as $log) {
			echo "\t<li>".$log."</li>\n";
		}
		echo "</ul>\n";
	}

}

?>