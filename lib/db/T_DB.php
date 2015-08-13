<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class T_DB {
	
	const TYPE_MYSQL = 1;
	const TYPE_PGSQL = 2;
	
	const ERR_NO_ERROR = 0;
    const ERR_CANT_CONNECT = 1;
    const ERR_INVALID_PARAMETER = 2;
    const ERR_BAD_QUERY = 3;
    
    const CHARSET_UTF8 = 1;
    
    /**
     * Type of self
     * @var int
     */
    public $db_type;
    
	/**
	 * Server hostname
	 * @var string
	 */
	public $host;
	
	/**
	 * The name of the self currently in use
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
	public $error_message = "";
	
	/**
	 * Holds an error code
	 * @var int
	 */
	public $error_code = self::ERR_NO_ERROR;
	
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
    
    /**
     * Create the database based on which type of database
     *  The type of database. Supported databases:
     *  mysql
     *  pgsql
     * @param string $db_type The type of database as a string
     * @return bool true on success and false on error
     */
	public function __construct($db_type){
        if(empty($db_type) | !is_string($db_type)){
            $this->error_message = "Argument 1 for ".__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        $int_type = $this->validDbType($db_type);
        if($int_type){
            $this->db_type = $int_type;
            return true;
        }else{
            return false;
        }
	}
	/**
	 * Is the string used to construct the database
	 * a valid type.
	 * @param string $db_type The type of database as a string
	 * @return int The database type or false on error
	 */
	private function validDbType($db_type){
        switch($db_type){
            case 'mysql':
                return self::TYPE_MYSQL;
            break;
            
            case 'pogsql':
                return self::TYPE_PGSQL;
            break;
            
            default:
                return false;
            break;
        }
	}
	
	/**
     * Connect to a MySQL database using the current settings
     * @param string $host
     *  The hostname of the server
     * @param string $username
     *  The username for the server
     * @param string $password
     *  The password for the server
     * @param string $db_name
     *  The name of the database
     * @param int $port 
     *  The port number of the server
     * @param int $charset
     *  The charset for the database to use
     * @return bool true on success false on error
     */
    public function connect($host = "", $username = "", $password = "", $db_name = "", $port = 0, $charset = 0){
        if(empty($host) | !is_string($host)){
            $this->error_message = "Argument 1 for ".__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($username) | !is_string($username)){
            $this->error_message = "Argument 2 for ".__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($password) | !is_string($password)){
            $this->error_message = "Argument 3 for ".__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($db_name) | !is_string($db_name)){
            $this->error_message = "Argument 4 for ".__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(!is_int($port)){
            $this->error_message = "Argument 5 for ".__method__.' must be aa integer';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(!is_int($charset)){
            $this->error_message = "Argument 6 for ".__method__.' must be aa integer';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;
        $this->port = $port;
        $this->charset = $charset;
        try{
            switch($this->db_type){
                case self::TYPE_MYSQL:
                    $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
                    if($this->charset){ $dsn .= ";charset={$this->charset}"; }
                    if($this->port){ $dsn .= ";port={$this->port}"; }
                    $this->pdo = new PDO($dsn, $this->username, $this->password);
                break;
                case self::TYPE_PGSQL;
                    $dsn = "pgsql:host={$this->host};dbname={$this->db_name};user={$this->username};password={$this->password}";
                    if($this->port){ $dsn .= ";port={$this->port}"; }
                    $this->pdo = new PDO($dsn);
                break;
                default:
                    $this->error_message = "Invalid database type";
                    $this->error_code = self::ERR_UNKNOWN;
                break;
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->connected = true;
            return true;
        }catch(PDOException $pe) {
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_UNKNOWN;
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
	 * @param string $sql
	 *  The SQL code for the database to run
     * @return array An associate array of the results or false
	 */
	public function query($sql){
        if(empty($sql) | !is_string($sql)){
            $this->error_message = 'Argument 1 for '.__method__.' must be a non-empty string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        try{
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $pe){
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_BAD_QUERY;
            return false;
        }   
	}

	/**
	 * Execute a statement with or without binding parameters.
	 *  Values should be represented by ? and an array of values
	 *  can be passed for each ? for PDO to bind safely
	 * @param string $sql
	 *  The SQL code for the database to run
	 * @param array $binding 
	 * * An optional array of parameters for PDO to bind to the SQL
     * @return int Rows affected or false on error
	 */
	public function execute($sql, $binding = array()) {
        if(empty($sql) | !is_string($sql)){
            $this->error_message = 'Argument 1 for '.__method__.' must be a non-empty string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(!is_array($binding)){
            $this->error_message = 'Argument 2 for '.__method__.' must be an array';
            $this->error_code = self::ERR_INVALID_PARAMETER;
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
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_BAD_QUERY;
            return false;
        }
	}
	
	/**
	 * This method inserts data into a database table
	 * @param string $table
	 * 	The name of the table to insert data into
	 * @param array $data
	 * 	A `key => val` pair where the keys are column names and the values
	 * 	are arrays of row data.
     * @return int Rows affect or false on error
	 */
	public function insert($table, $data){
        if(empty($table) | !is_string($table)){
            $this->error_message = 'Argument 1 for '.__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($data) | !is_array($data)){
            $this->error_message = 'Argument 2 for '.__method__.' must be an array';
            $this->error_code = self::ERR_INVALID_PARAMETER;
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
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_BAD_QUERY;
            return false;
        }
	}
	
    /**
     * Update a table in the database
     * @param string $table
     *  The name of the table
     * @param array $data
     *  A `key => val` pair where the keys are column names and the values
     *  are arrays of row data.
     * @param string $conditions
     *  The condition on which to update
     * @return int Rows affect or false on error
     */
	public function update($table, $data, $conditions){
        if(empty($table) | !is_string($table)){
            $this->error_message = 'Argument 1 for '.__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($data) | !is_array($data)){
            $this->error_message = 'Argument 2 for '.__method__.' must be an array';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }

        $binding = array();
        $set_str = "";
        foreach($data as $field => $val){
            $set_str .= ",{$field} = ?";
            $binding[] = $val;
        }
        $set_str = substr($set_str,1);
        $binding = array_merge($binding,$conditions[1]);
        $sql = "UPDATE {$table} SET {$set_str} WHERE {$conditions[0]}";
        try{
            $stmt = $this->pdo->prepare($sql);
            $i = 1;
            foreach($binding as $val){
                $stmt->bindValue($i,$val,$this->getPDOParam($val));
                $i++;
            }
            $stmt->execute();
            return $stmt->rowCount() ? $stmt->rowCount(): false;
        }catch(PDOException $pe){
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_BAD_QUERY;
            return false;
       }
	}
	
	/**
	 * Delete records from the database
	 * @param string $table the name of the table
	 * @param array $conditions The condition on which to update
     * @return int Rows affect or false on error
	 * @TEST Not tested
	 */
	public function delete($table,$conditions){
        if(empty($table) | !is_string($table)){
            $this->error_message = 'Argument 1 for '.__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        if(empty($conditions) | !is_array($conditions)){
            $this->error_message = 'Argument 2 for '.__method__.' must be an array';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
		try{
            $sql = "DELETE FROM {$table} WHERE {$conditions[0]}";
            $stmt = $this->pdo->prepare($sql);
            $i = 1;
            foreach($conditions[1] as $val){
                $stmt->bindValue($i,$val,$this->getPDOParam($val));
                $i++;
            }
            $stmt->execute();
            return $stmt->rowCount() ? $stmt->rowCount(): false;
        }catch(PDOException $pe){
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_BAD_QUERY;
            return false;
        }
	}
	
	/**
	 * Get the matching PDO::PARAM_* for the variable
	 * @param mixed a variable
	 * @return int PDO::PARAM_*
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
     * @param string $table the name of the table
     * @return int Rows affect or false on error
	 */
	public function countRows($table){
        if(empty($table) | !is_string($table)){
            $this->error_message = 'Argument 1 for '.__method__.' must be a string';
            $this->error_code = self::ERR_INVALID_PARAMETER;
            return false;
        }
        try{
            $stmt = $db->query('SELECT * FROM '.$table_name);
            return $stmt->rowCount();
        }catch(PDOException $pe){
            $this->error_message = $pe->getMessage();
            $this->error_code = self::ERR_BAD_QUERY;
        }
	}
}
