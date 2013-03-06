<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * Constants
 */
require_once("constants.inc.php");
/*
 * Log class
 */
require_once("log.inc.php");

// {{{ Database

/**
 * This Class is responsible for dealing with Database queries.
 *
 * @package    PHP Login
 * @author     Benjamin Staker <bassatcollege@gmail.com>
 * @copyright  2012-2013 Benjamin Staker
 * @link       https://github.com/bass2k8/php_login/
 *
 * @linkedin   http://uk.linkedin.com/in/benstaker/
 * @twitter    @benstaker
 * @website    http://benstaker.com
 */
Class Database {

	// {{{ properties

    /**
     * Log object.
     *
     * Potential values are ''.
     *
     * @var object
     * @access protected
     */
	protected $_log;

	/**
     * Define whether to output logging or not.
     *
     * Potential values are ''.
     *
     * @var boolean
     * @access protected
     */
	protected $_logging;

	/**
     * PDO database object.
     *
     * Potential values are ''.
     *
     * @var object
     * @access private
     */
	private $_db;

	/**
     * SQL Query.
     *
     * Potential values are ''.
     *
     * @var object
     * @access private
     */
	private $_query;
	
	/**
     * Whether a table is selected or not.
     *
     * Potential values are ''.
     *
     * @var boolean
     * @access private
     */
	private $_tableSelected;

	// }}}

	// {{{ __construct()

	/**
     * <TITLE HERE>
     *
     * <SUMMARY HERE>
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @param boolean $logging a boolean that determines whether logging occurs.
     *
     * @access public
     */
	public function __construct($logging=false){
		/*
		 * Initialising the variables.
		 */
		$this->_query=$this->_tableSelected=false;
		$this->_logging=$logging;

		/*
		 * Create a log object, if logging is enabled.
		 */
		if($this->_logging) $this->_log = new Log(get_class($this));

		/*
		 * Connect to the database.
		 */
		$this->_connect();
	}

	// }}}

	// {{{ __destruct()

	/**
     * <TITLE HERE>
     *
     * Close the connection to the Database Server.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     *
     * @access public
     */
	public function __destruct(){
		/*
		 * If there already is a query, close it.
		 */
		if($this->_query) $this->_query->closeCursor();
		$this->_db=null;

		if($this->_logging) $this->_log->addToLog("Closed connection to <strong>".DB_SERVER."</strong> successfully.");
	}

	// {{{ _connect()

	/**
     * <TITLE HERE>
     *
     * Connecting to the Database Server.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @return boolean the status (boolean) of connecting to the database server.
     *
     * @access private
     */
	private function _connect(){
		/*
		 * Create a PDO object.
		 */
		$this->_db = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USER, DB_PASS);
		$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode.

		/*
		 * If there aren't any PDO errors, return true.
		 */
		if(!$this->_PDOErrors()){
			if($this->_logging) $this->_log->addToLog("Connected to <strong>".DB_SERVER."</strong> successfully.");
			if($this->_logging) $this->_log->addToLog("Selected the database <strong>".DB_DATABASE."</strong> successfully.");
			return true;
		} else {
			if($this->_logging) $this->_log->addToLog("Could not connect to the Database server.");
			return false;
		}
		
	}
	
	// }}}

	// {{{ _PDOErrors()

	/**
     * <TITLE HERE>
     *
     * Check for any errors with PDO.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @return boolean the status (boolean) whether there were any PDO errors.
     *
     * @access private
     */
	private function _PDOErrors(){
		$error=$this->_db->errorInfo();

		/*
		 * If there are PDO errors, return true.
		 */
		if($error[2]) return true;
		else return false;
	}

	// }}}

	// {{{ query()

	/**
     * <TITLE HERE>
     *
     * Querying the Database.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @param string $sql a string that stores the SQL query.
     * @param array $params an array that holds any extra parameters, such as WHERE, LIKE, etc.
     *
     * @return boolean the status (boolean) of the query.
     *
     * @access private
     */
	public function query($sql="", $params=array()){
		$error_status=false;

		/*
		 * If there already is a query, close it.
		 */
		if($this->_query) $this->_query->closeCursor();
		
		/*
		 * Attempt to query the SQL.
		 */
		try {
			if(count($params, COUNT_RECURSIVE)>0){
				$this->_query=$this->_db->prepare($sql);
				foreach($params as $p){
					if(isset($p[2])) $this->_query->bindParam($p[0], $p[1], $p[2]);
					else $this->_query->bindParam($p[0], $p[1]);
					if($this->_logging) $this->_log->addToLog("Param <strong>".$p[1].":</strong> was binded to <strong>".$p[0]."</strong>.");
				}
				$this->_query->execute();
			} else $this->_query = $this->_db->query($sql);
		} catch(PDOException $e){

			/*
			 * If an error was caught, set error_status to true and return false.
			 */
			$error_status=true;
			if($this->_logging) $this->_log->addToLog("<strong>Error:</strong> ".$e->getMessage());
			return false;
		}

		/*
		 * If there were no errors, return true.
		 */
		if(!$error_status){
			if($this->_logging) $this->_log->addToLog("Queried <strong>".$sql."</strong> successfully.");
			return true;
		}
	}

	// }}}

	// {{{ numberOfRows()

	/**
     * <TITLE HERE>
     *
     * Return the number of rows affected by the last SQL statement.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @return boolean the status (boolean) of checking how many rows there were.
     *
     * @access public
     */
	public function numberOfRows() {
		/*
		 * If a query has been executed, continue.
		 */
		if($this->_query){
			/*
			 * If table is selected, fetch the association.
			 */
			if($this->_tableSelected){
				$numberOfRows=$this->_query->rowCount();

				/*
				 * If there aren't any PDO errors, return the association.
				 */
				if(!$this->_PDOErrors()){
					if($this->_logging) $this->_log->addToLog("Returned number of rows successfully.");
					return $numberOfRows;
				} else {
					if($this->_logging) $this->_log->addToLog("Cannot return number of rows; an error occured.");
					return false;
				}
			} else {
				if($this->_logging) $this->_log->addToLog("Cannot return number of rows; no table is selected.");
				return false;
			}
			
		} else {
			if($this->_logging) $this->_log->addToLog("Cannot return number of rows; there is no query.");
			return false;
		}
	}

	// }}}

	// {{{ fetchAssociation()

	/**
     * <TITLE HERE>
     *
     * Fetch association.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @return boolean the status (boolean) of fetching the association.
     *
     * @access public
     */
	public function fetchAssociation(){
		/*
		 * If a query has been executed, continue.
		 */
		if($this->_query){
			/*
			 * If table is selected, fetch the association.
			 */
			if($this->_tableSelected){
				$assoc = $this->_query->fetch(PDO::FETCH_ASSOC);

				/*
				 * If there aren't any PDO errors, return the association.
				 */
				if(!$this->_PDOErrors()){
					if($this->_logging) $this->_log->addToLog("Fetched the association successfully.");
					return $assoc;
				} else {
					if($this->_logging) $this->_log->addToLog("Cannot fetch association; an error occured.");
					return false;
				}
			}
			else {
				if($this->_logging) $this->_log->addToLog("Cannot fetch association; no table is selected.");
				return false;
			}
			
		} else {
			if($this->_logging) $this->_log->addToLog("Cannot fetch association; there is no query.");
			return false;
		}
	}

	// }}}

	// {{{ selectTable()

	/**
     * <TITLE HERE>
     *
     * Select a Table.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @param string $table a string that contains the name of the table being selected.
     * @param array $options_arr an array containing options such as WHERE, LIKE, etc.
     *
     * @return boolean the status (boolean) of selecting the table.
     *
     * @access public
     */
	public function selectTable($table="", $options_arr=array()){
		$options_sql="";
		$params=array();

		/*
		 * If supplied options is an array.
		 */
		if(is_array($options_arr)){
			/*
			 * If options array isn't empty.
			 */
			if(count($options_arr, COUNT_RECURSIVE)!=0){
				/*
				 * If WHERE option is in array.
				 */
				if(array_key_exists('WHERE', $options_arr)){
					/*
					 * If WHERE arguments are supplied.
					 */
					if(count($options_arr["WHERE"], COUNT_RECURSIVE)!=0){
						$options_sql.=" WHERE ";
						/*
						 * Go through where_arr array.
						 */
						foreach($options_arr["WHERE"] as $where){
							if(isset($where[2])) $operator=" ".$where[2]." ";
							else $operator="=";
							array_push($params, array(":".$where[0], $where[1]));
							$options_sql .= "`".$where[0]."`".$operator.":".$where[0]." AND ";
						}
						/*
						 * Remove "AND" and white space.
						 */
						$options_sql=substr($options_sql, 0, -5);
					}
				}

				/*
				 * If ORDER BY option is in array.
				 */
				if(array_key_exists('ORDER BY', $options_arr)){
					/*
					 * If ORDER BY arguments are supplied.
					 */
					if(count($options_arr["ORDER BY"], COUNT_RECURSIVE)!=0){
						$options_sql.=" ORDER BY `".$options_arr["ORDER BY"][0]."` ".$options_arr["ORDER BY"][1];
					}
				}

				/*
				 * If LIMIT option is in array.
				 */
				if(array_key_exists('LIMIT', $options_arr)){
					/*
					 * If LIMIT arguments are supplied.
					 */
					if(count($options_arr["LIMIT"], COUNT_RECURSIVE)!=0){
						$options_sql.=" LIMIT ".$options_arr["LIMIT"][0].", ".$options_arr["LIMIT"][1];
					}
				}

			}

			/*
			 * SQL statement.
			 */
			$this->query("SELECT * FROM `$table`".$options_sql, $params);

			/*
			 * If there aren't any PDO errors, return true.
			 */
			if(!$this->_PDOErrors()){
				if($this->_logging) $this->_log->addToLog("Selected <strong>".$table."</strong> successfully.");
				$this->_tableSelected=true;
				return true;
			} else {
				if($this->_logging) $this->_log->addToLog("Could not select <strong>".$table."</strong>.");
				return false;
			}
		} else {
			if($this->_logging) $this->_log->addToLog("An array was not supplied.");
			return false;
		}
	}

	// }}}

	// {{{ insertInto()

	/**
     * <TITLE HERE>
     *
     * Insert a row into the specified table.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @param string $table a string that contains the name of the table where the data is being inserted into.
     * @param array $into_arr an array containing the insertion data.
     *
     * @return boolean the status (boolean) of inserting into the table.
     *
     * @access public
     */
	public function insertInto($table="", $into_arr=array()){
		/*
		 * To prevent future errors with fetching Association.
		 */
		$this->_tableSelected=false;
		$into_sql=$values_sql="";
		$params=array();

		/*
		 * If variables are arrays, continue.
		 */
		if(is_array($into_arr)){
			/*
			 * Go through into_arr array.
			 */
			foreach($into_arr as $ia){
				if(isset($ia[2])) array_push($params, array(":".$ia[0], $ia[1], $ia[2]));
				else array_push($params, array(":".$ia[0], $ia[1]));
				$into_sql .= "`".$ia[0]."`, ";
				$values_sql .= ":".$ia[0].", ";
			}
			/*
			 * Remove comma and white space.
			 */
			$into_sql=substr($into_sql, 0, -2);
			/*
			 * Remove comma and white space.
			 */
			$values_sql=substr($values_sql, 0, -2);

			/*
			 * SQL statement.
			 */
			$this->query("INSERT INTO `$table` ($into_sql) VALUES ($values_sql)", $params);

			/*
			 * If there aren't any PDO errors, return true.
			 */
			if(!$this->_PDOErrors()){
				if($this->_logging) $this->_log->addToLog("Inserted into <strong>".$table."</strong> successfully.");
				return true;
			} else {
				if($this->_logging) $this->_log->addToLog("Could not insert into <strong>".$table."</strong>.");
				return false;
			}
		} else {
			if($this->_logging) $this->_log->addToLog("An array was not supplied.");
			return false;
		}
	}

	// }}}

	// {{{ deleteFrom()

	/**
     * <TITLE HERE>
     *
     * Delete a row from the specified table.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @param string $table a string that contains the name of the table being deleted.
     * @param array $where_arr an array containing the list of columns
     *						   and values that must match in order for the row to be deleted.
     *
     * @return boolean the status (boolean) of deleting the row from the table.
     *
     * @access public
     */
	public function deleteFrom($table="", $where_arr=array()){
		/*
		 * To prevent future errors with fetching Association.
		 */
		$this->_tableSelected=false;
		$where_sql="";
		$params=array();

		/*
		 * If variables are arrays, continue.
		 */
		if(is_array($where_arr)){
			/*
			 * Go through where_arr array.
			 */
			foreach($where_arr as $wa){
				array_push($params, array(":".$wa[0], $wa[1]));
				$where_sql .= "`".$wa[0]."`=:".$wa[0]." AND ";
			}
			/*
			 * Remove "AND" and white space.
			 */
			$where_sql=substr($where_sql, 0, -5);

			/*
			 * SQL statement.
			 */
			$this->query("DELETE FROM `$table` WHERE $where_sql", $params);

			/*
			 * If there aren't any PDO errors, return true.
			 */
			if(!$this->_PDOErrors()){
				if($this->_logging) $this->_log->addToLog("Deleted row from <strong>".$table."</strong> successfully.");
				return true;
			} else {
				if($this->_logging) $this->_log->addToLog("Could not delete from <strong>".$table."</strong>.");
				return false;
			}
		} else {
			if($this->_logging) $this->_log->addToLog("An array was not supplied.");
			return false;
		}
	}

	// }}}

	// {{{ updateTable()

	/**
     * <TITLE HERE>
     *
     * Update a row in the specified table.
     *
     * Here's an example of how to format examples:
     * <code>
     * 
     * <CODE HERE>
     * 
     * </code>
     *
     * @param string $table a string that contains the name of the table being updated.
     * @param array $set_arr an array containing the list of columns
     * 						 and values that will be changed.
     * @param array $where_arr an array containing the list of columns
     *						   and values that must match in order for the row to be updated.
     *
     * @return boolean the status (boolean) of updating the table.
     *
     * @access public
     */
	public function updateTable($table="", $set_arr=array(), $where_arr=array()){
		/*
		 * To prevent future errors with fetching Association.
		 */
		$this->_tableSelected=false;
		$set_sql=$where_sql="";
		$params=array();

		/*
		 * If variables are arrays, continue.
		 */
		if(is_array($set_arr) && is_array($where_arr)){
			/*
			 * Go through set_arr array.
			 */
			foreach($set_arr as $sa){
				$param="set_".$sa[0];
				array_push($params, array(":".$param, $sa[1]));
				$set_sql .= "`".$sa[0]."`=:".$param.", ";
			}
			/*
			 * Remove comma and white space.
			 */
			$set_sql=substr($set_sql, 0, -2);

			/*
			 * Go through where_arr array.
			 */
			foreach($where_arr as $wa){
				$param="where_".$sa[0];
				array_push($params, array(":".$param, $wa[1]));
				$where_sql .= "`".$wa[0]."`=:".$param." AND ";
			}
			/*
			 * Remove "AND" and white space.
			 */
			$where_sql=substr($where_sql, 0, -5);

			/*
			 * SQL statement.
			 */
			$this->query("UPDATE `$table` SET $set_sql WHERE $where_sql", $params);

			/*
			 * If there aren't any PDO errors, return true.
			 */
			if(!$this->_PDOErrors()){
				if($this->_logging) $this->_log->addToLog("Updated row in <strong>".$table."</strong> successfully.");
				return true;
			} else {
				if($this->_logging) $this->_log->addToLog("Could not update row in <strong>".$table."</strong>.");
				return false;
			}
		} else {
			if($this->_logging) $this->_log->addToLog("An array was not supplied.");
			return false;
		}
	}

	// }}}

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>