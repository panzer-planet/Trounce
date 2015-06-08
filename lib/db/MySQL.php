<?php
/**
 * Db.php is a light database abstraction layer.
 *
 * lib-cobolt 
 * General purpose web development library.
 *
 * @package lib-cobolt
 * @subpackage Db
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 
 */
?>

<?php
require_once('lib/Db/DbException.php');

/**
 * lib-cobolt Db class
 * 
 * This class provides a simpler way of interacting with a MySQL
 * database using PDO
 * 
 * @package lib-cobolt
 * @subpackage Db
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @copyright 2013 Werner Roets
 */
class MySQL{
	
	/**
	 * Hide all errors mode
	 * @var int
	 */
	const E_SILENT = 0;
	
	/**
	 * Warning on error mode
	 * @var int
	 */
	const E_WARNING = 1;
	
	/**
	 * Exception on error mode
	 * @var int
	 */
	const E_EXCEPTION = 2;
	
	/**
	 * MySQL server hostname
	 * @var string
	 */
	public $host;
	
	/**
	 * The name of the database currently in use
	 * @var string
	 */
	public $db_name = null;
	
	/**
	 * MySQL server username
	 * @var string
	 */
	public $username;
	
	/**
	 * MySQL server password
	 * @var string
	 */
	private $password;
	
	/**
	 * Enable verbose mode, shows debug information
	 * @var bool
	 */
	private $verbose = false;
	
	/**
	 * Enable logging to text file
	 * @var bool
	 */
	private $logging = false;
	
	/**
	 * PHP Data Object
	 * @var PDO
	 */
	private $pdo;
	
	/**
	 * Persistent connection enabled
	 * @var bool
	 */
	private $persistent = false;
	
	/**
	 * The current error mode
	 * @var int
	 */
	private $error_mode = self::E_SILENT;
	
	/**
	 * The location of the log file
	 * @ var string
	 */
	private $log_location = 'logs/db.log';
	
	/**
	 * Connected to MySQL server
	 * @var bool
	 */
	private $connected = false;
	
	/**
	 * Getter for connected
	 * @return bool
	 */
	public function isConnected(){
		return $this->connected;
	}
	
	/**
	 * Getter for verbose
	 * @return bool
	 */
	public function isVerbose(){
		return $this->verbose;
	}
	
	/**
	 * Getter for logging
	 * @return bool
	 */
	public function isLogging(){
		return $this->logging;
	}

	/**
	 * @param string hostname
	 * @param string username
	 * @param string password
	 * @param bool persistent
	 */
	public function __construct($host,$username,$password){
		$args = func_get_args();
		if(count($args) >= 3){
			$this->persistent = false;
			$this->host = $host;
			$this->username = $username;
			$this->password = $password;
			if(count($args) == 4){
				$this->useDb($args[3]);	
			}
		}else{
			#exception
		}
		
	}
	
	/**
	 * Select a database or change to a database
	 * @param string database name
	 */
	public function useDb($db_name){
		if(isset($db_name)){
			#change db
			$this->db_name = $db_name;		
			$this->connect();
		}else{
			#first time
			$this->db_name = $db_name;
		}
	}
	
	/**
	 * Enabled/disabled verbose mode which echoes debug data
	 * @param bool
	 */
	public function setVerbose($v){
		# written so anything passed except 0/false sets true
		$this->verbose = $v ? true : false;
		$this->say('verbose mode on');
	}
	
	/**
	 * Logging to file enabled/disabled
	 * @param bool
	 */
	public function setLogging($v){
		$this->logging = $v ? true : false;
		$this->say('logging to '.$this->log_location);
	}

	/**
	 * Set the error mode E_SILENT, E_WARNING, E_EXCEPTION
	 * @param int
	 */
	public function setErrorMode($error_mode){
		if($this->isValidErrorMode($error_mode)){
			$this->error_mode = $error_mode;
			if($this->isConnected()){ 
		 		$this->setPdoErrMode($error_mode);			
			}
			
		}else{
			throw new DbInvalidErrorModeException();
		}
	}
	
	/**
	 * Enable or disable persistent connection
	 * @deprecated
	 * @param bool
	 */
	public function setPersistent($persistent){
		$this->persistent = $persistent;
		if($this->isConnected()){
			#We must reconnect to change persistence
			$this->connect();
		}
	}
	
	/**
	 * Returns true if is valid error mode
	 * @param int
	 */
	private function isValidErrorMode($e){
		return $e === self::E_EXCEPTION |
				$e === self::E_SILENT |
				$e === self::E_WARNING;
	}
	
	/**
	 * Sets the PDO ATTR_ERRMODE attribute based on the current
	 * value of $this->error_mode
	 */
	private function setPdoErrMode(){
		switch(strtoupper($this->error_mode)){
			case self::E_SILENT:
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);
				break;
			case self::E_WARNING:
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
				break;
			case self::E_EXCEPTION:
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				break;
		}
	}
	
	/**
	 * Connect to a MySQL database using the current settings
	 */
	private function connect(){
		if($this->host && $this->username && $this->password){
			try{
				#Go straight to a particular db or just connect to server
				$con_str = isset($this->db_name) ?
					'mysql:host='.$this->host.';dbname='.$this->db_name:
					'mysql:host='.$this->host;
				
				#Enable persistence?
				if($this->persistent){
					$this->pdo = new PDO($con_str,$this->username,$this->password,array(
						PDO::ATTR_PERSISTENT => true));					
				}else{
					$this->pdo = new PDO($con_str,$this->username,$this->password);
				}
				$this->connected = true;
				$this->setPdoErrMode();
			}catch(PDOException $pe){
				
				switch($pe->getCode()){
				
					case 1049:
						throw new DbDoesNotExistException();
					break;	
					case 2005:
						throw new DbCouldNotConnectException('Unknown');
					break;
				}
			}
		}else{
			throw new DbCouldNotConnectException('hostname, username or password not set');
		}
	}
	
	/**
	 * Disconnect by destroying the PDO object
	 */
	private function disconnect(){
		$this->pdo = null;
	}
	

	
	/**
	 * Write a string of text to file
	 * @param string
	 */
	private function log($tolog){
		if($this->logging){
			$this->say($tolog);
			#log to file
		}
	}

	/**
	 * Print to stdout for debug
	 * @param string
	 */
	private function say($tosay){
		if($this->verbose){
			if(is_array($tosay)){ 
				$tosay	= print_r($tosay,true);
			}
		echo 'CoboltDB Debug: '.$tosay.'<br/>';
		}
	}

	
	/**
	 * MySQL SELECT
	 *
	 * This method selects and returns data from a database table
	 * @param string
	 *		You can write in full sql like 'SELECT * FROM table'
	 *		or omit the SELECT like ' select('* FROM table');
	 * @return array
	 * 		Associative array of data retreived from the table
	 */
	public function select($select_query){
	
		$this->log('(select): '.$select_query);
		
		#If no Db name is set we cant select
		if(!isset($this->db_name)){
			throw new DbNoDatabaseSelectedException();
		}
		if(!$this->isConnected()){ $this->connect(); }
		
		$sql = trim($select_query);
		#we'll check if its shortened syntax
		if(strtoupper(substr($sql,0,6)) != 'SELECT'){
			$sql = 'SELECT '.$sql;
		}
		$stmt = $this->pdo->query($sql);
		$this->log($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * MySQL INSERT
	 *
	 * This method inserts data into a database table
	 * 
	 * @param string $table_name
	 * 	The name of the table to insert data into
	 * @param array $col_val
	 * 	A `$key => val` pair where the keys are column names and the values
	 * 	are arrays of row data.
	 */
	public function insert($table_name, $col_val){
		$this->log('(function) insert (tablename): '.$table_name.' (colvals): '.print_r($col_val,true));
		
		$columns = "";
		$values = "";
		$i = 0; #Field numbers
		
		foreach($col_val as $col => $val){
			$columns .= ','.$col;
			$values .= ','.':field'.$i++;
		}
		#remove leading comma
		$columns = substr($columns,1);
		$values = substr($values,1);
		
		$columns = '('.$columns.')';
		$values = 'VALUES('.$values.')';
		
		$i = 0;
		$binding = array();
		foreach($col_val as $col => $val){
			$binding[':field'.$i++] = $val; 
		}
		
		$sql = "INSERT INTO {$table_name} {$columns} {$values}";
		
		$this->log($sql);
		$this->log($binding);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($binding);
		if($stmt->rowCount()){ return $stmt->rowCount(); }
		return false;
	}
	
	public function update($table_name,$condition,$col_val){
        $this->log('(function) update (tablename): '.$table_name.' (colvals): '.print_r($col_val,true));
		$sql = "UPDATE {$table_name} SET ";
			$delim = '';
		foreach($col_val as $col => $val){
			if(is_string($val)){
				$sql .= $delim . ' `' . $col . '` = \''. $val.'\'';
			}else if(is_int($val)){
				$sql .= $delim . ' `' . $col . '` = '. $val;				
			}
			$delim = ',';
		}
		echo $sql;
		$this->pdo->exec($sql);
	}
	
	/**
	 * Create a new table
	 * @param string
	 */
	public function createTable($table_name){
		if(!$this->db_name){
			echo 'NO DATABASE SELECTED ';
			throw new DbCouldNotCreateTableException($table_name,$e);
		}
		$sql = "CREATE TABLE {$table_name}";
		try{
			$this->pdo->exec($sql);
		}catch(PDOException $e){
			throw new DbCouldNotCreateTableException($table_name,$e);
		}
	}
	
	/**
	 * Create a new database
	 * @param string
	 * @throws DbCouldNotCreateDatabaseException when database can't be created
	 */ 
	public function createDatabase($database_name){
		$sql = "CREATE DATABASE {$database_name}";
		try{
			$this->pdo->exec($sql);
		}catch(PDOException $e){
			throw new DbCouldNotCreateDatabaseException($database_name,$e);
		}
		
	}
	
	/**
	 * Delete records from the database
	 * @param string $table_name the name of the table
	 * @param string $where_clause SQL compatible where clause
	 */
	public function delete($table_name,$where_clause){
		$table_name = trim($table_name);
		$where_clause = trim($where_clause);
		$sql = "DELETE FROM {$table_name} WHERE {$where_clause}";
		$this->pdo->exec($sql);
	}
	
	/**
	 * Get the number of records in a table
	 * @param string
	 */
	public function countRows($table_name){
		$stmt = $db->query('SELECT * FROM '.$table_name);
		return $stmt->rowCount();
	}


}

