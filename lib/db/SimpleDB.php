<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class SimpleDB{
	
	const TYPE_MYSQL = 1;
	const TYPE_PGSQL = 2;
	
	const ERR_NO_ERROR = 0;
    const ERR_CANT_CONNECT = 1;
    const ERR_INVALID_PARAMETER = 2;
    
    const CHARSET_UTF8 = 1;
    
    /**
     * Type of database
     * @var int
     */
    public $db_type;
    
	/**
	 * Server hostname
	 * @var string
	 */
	public $host;
	
	/**
	 * The name of the database currently in use
	 * @var string
	 */
	public $db_name;
	
	/**
	 * Server username
	 * @var string
	 */
	public $username;
	
	/**
	 * Server password
	 * @var string
	 */
	public $password;
	
	/**
	 * PHP Data Object
	 * @var PDO
	 */
	private $pdo;

	/**
	 * Connected to server
	 * @var bool
	 */
	public $connected = false;
	
	/**
     * Holds the last error
     * @var string
     */
	public $error = "";
	
	/**
	 * Holds an error code
	 * @var int
	 */
	public $error_code = SimpleDB::ERR_NO_ERROR;
	
	/**
     * The charset
     * @var string
     */
	public $charset = 'utf8';
	
    /**
     * The network port
     * @var int
     */
    public $port;
    
	public function __construct($db_type){
        #TODO ensure valid
        $this->db_type = $db_type;
	}
	
	
	/**
     * Connect to a MySQL database using the current settings
     * @returns true on successs, false on error
     * @param string hostname
     * @param string username
     * @param string password
     */
    public function connect($host = "", $username = "", $password = "", $db_name = "", $port = 0, $charset = 0){
        if(empty($host) | !is_string($host)){
            $this->error_message = "Argument 1 for ".__method__.' must be a string';
            $this->error_code = SimpleDB::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($username) | !is_string($username)){
            $this->error_message = "Argument 2 for ".__method__.' must be a string';
            $this->error_code = SimpleDB::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($password) | !is_string($password)){
            $this->error_message = "Argument 3 for ".__method__.' must be a string';
            $this->error_code = SimpleDB::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($db_name) | !is_string($db_name)){
            $this->error_message = "Argument 4 for ".__method__.' must be a string';
            $this->error_code = SimpleDB::ERR_INVALID_PARAMETER;
            return false;
        }
        if(!is_int($port)){
            $this->error_message = "Argument 5 for ".__method__.' must be aa integer';
            $this->error_code = SimpleDB::ERR_INVALID_PARAMETER;
            return false;
        }
        if(!is_int($charset)){
            $this->error_message = "Argument 6 for ".__method__.' must be aa integer';
            $this->error_code = SimpleDB::ERR_INVALID_PARAMETER;
            return false;
        }
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;
        $this->port = $port;
        $this->charset = $charset;
     
        switch($this->db_type){
            case SimpleDB::TYPE_MYSQL:
                $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
                if($this->charset){ $dsn .= ";charset={$this->charset}"; }
                if($this->port){ $dsn .= ";port={$this->port}"; }
                $this->pdo = new PDO($dsn, $this->username, $this->password);
            break;
            case SimpleDB::TYPE_PGSQL;
                $dsn = "pgsql:host={$this->host};dbname={$this->db_name};user={$this->username};password={$this->password}";
                if($this->port){ $dsn .= ";port={$this->port}"; }
                $this->pdo = new PDO($dsn);
            break;
        }
        try{
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->connected = true;
            return true;
        }catch(PDOException $pe) {
            $this->error = $pe->getMessage();
            return false;
        }
    }

	/**
	 * Disconnect by destroying the PDO object
	 */
	public function disconnect(){
		$this->pdo = null;
	}
	
	/**
	 * Make a query
	 * @param string query
	 */
	public function query($sql){
        if(empty($sql) | !is_string($sql)){
            $this->error = 'Argument 1 for '.__method__.' must be a non-empty string';
            return false;
        }
        try{
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $pe){
            $this->error = $pe->getMessage();
            return false;
        }   
	}

	/**
	 * Execute a statement with or without binding
	 * @param string statement
	 * @param array binding (optional)
	 */
	public function execute($sql, $binding = array()) {
        if(empty($sql) | !is_string($sql)){
            $this->error = 'Argument 1 for '.__method__.' must be a non-empty string';
            return false;
        }
        if(!is_array($binding)){
            $this->error = 'Argument 2 for '.__method__.' must be an array';
            return false;
        }
        try{
            $stmt = $this->pdo->prepare($sql);
            if(!empty($binding)){
                $i = 1;
                foreach($binding as $val){
                    $stmt->bindValue($i,$val,$this->getPDOParam($val));
                    $i++;
                }
            }    
            $stmt->execute();
            return $stmt->rowCount() ? $stmt->rowCount(): false;
        }catch(PDOException $pe){
            $this->error = $pe->getMessage();
            return false;
        }
	}
	
	/**
	 *
	 * This method inserts data into a database table
	 * 
	 * @param string $table_name
	 * 	The name of the table to insert data into
	 * @param array $data
	 * 	A `$key => val` pair where the keys are column names and the values
	 * 	are arrays of row data.
	 */
	public function insert($table, $data){
        if(empty($table) | !is_string($table)){
            $this->error = 'Argument 1 for '.__method__.' must be a string';
            return false;
        }
        if(empty($data) | !is_array($data)){
            $this->error = 'Argument 2 for '.__method__.' must be an array';
            return false;
        }
		$fields = "";
		$values = "";
		$i = 0; #Field numbers
		$binding = array();
		$fields = implode(',',array_keys($data));
		$values = implode(',',array_fill(0,count($data),'?'));
		$sql = "INSERT INTO {$table} ({$fields}) VALUES({$values})";
        try{
            $stmt = $this->pdo->prepare($sql);
            $i = 1;
            foreach($data as $field => $val){
                $stmt->bindValue($i,$val,$this->getPDOParam($val));
                $i++;
            }
            $stmt->execute();
            if($stmt->rowCount()){ 
                return $stmt->rowCount(); 
            }else{
                return false;
            }
        }catch(PDOException $pe){
            $this->error = $pe->getMessage();
            return false;
        }
	}
	

	# array("filmID = :field1 AND filmName = :field2",array(122,"James Bond"));
	public function update($table, $data, $conditions){
        if(empty($table) | !is_string($table)){
            $this->error = 'Argument 1 for '.__method__.' must be a string';
            return false;
        }
        if(empty($data) | !is_array($data)){
            $this->error = 'Argument 2 for '.__method__.' must be an array';
            return false;
        }
        $i = 0;
        $binding = array();
        $set_str = "";
        
        foreach($data as $field => $val){
            $set_str .= ",{$field} = ?";
            $binding[] = $val;
            $i++;
        }
        $set_str = substr($set_str,1);
        
        $binding = array_merge($binding,$conditions[1]);
        
        $where_str = $conditions[0];
        
        $sql = "UPDATE {$table} SET {$set_str} WHERE {$where_str}";
        
        try{
            $stmt = $this->pdo->prepare($sql);
            $i = 0;
            foreach($binding as $val){
                $stmt->bindValue($i,$val,$this->getPDOParam($val));
                $i++;
            }
            $stmt->execute();
            return $stmt->rowCount() ? $stmt->rowCount(): false;
        }catch(PDOException $pe){
            $this->error = $pe->getMessage();
            return false;
       }
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
	 * Delete records from the database
	 * @param string $table_name the name of the table
	 * @param string $where_clause SQL compatible where clause
	 * TEST Not tested
	 */
	public function delete($table,$conditions){
        if(empty($table) | !is_string($table)){
            $this->error = 'Argument 1 for '.__method__.' must be a string';
            return false;
        }
        if(empty($conditions) | !is_array($conditions)){
            $this->error = 'Argument 2 for '.__method__.' must be an array';
            return false;
        }
		$clause = $conditions[0];
		
		
		try{
            $sql = "DELETE FROM {$table} WHERE {$clause}";
           # die(print_r($conditions[1],true));
            $stmt = $this->pdo->prepare($sql);
            $i = 1;
            foreach($conditions[1] as $val){
                $stmt->bindValue($i,$val,$this->getPDOParam($val));
                $i++;
            }
            $stmt->execute();
            return $stmt->rowCount() ? $stmt->rowCount(): false;
        }catch(PDOException $pe){
            $this->error = $pe->getMessage();
            return false;
        }
	}
	
	/**
	 * Get the matching PDO::PARAM_* for the variable
	 * @param mixed a variable
	 * @returns int PDO::PARAM
	 */
	private function getPDOParam($val){
         switch(gettype($val)){
                case 'boolean':
                    return PDO::PARAM_BOOL;
                break;
                case 'integer':
                    return PDO::PARAM_INT;
                break;
                case 'string':
                    return PDO::PARAM_STR;
                break;
                default:
                    return PDO::PARAM_STR;
                break;
            }
    }
	
	/**
	 * Get the number of records in a table
	 * @param string
	 */
	public function countRows($table){
        if(empty($table) | !is_string($table)){
            $this->error = 'Argument 1 for '.__method__.' must be a string';
            return false;
        }
        try{
            $stmt = $db->query('SELECT * FROM '.$table_name);
            return $stmt->rowCount();
        }catch(PDOException $pe){
            $this->error = $pe->getMessage();
        }
	}


}
