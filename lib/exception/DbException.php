<?php
/**
 * DbException.php contains DBException and children
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

class DbException extends Exception{
	const CODE_PREFIX = 55;
	public function __construct($message, $code){
		parent::__construct($message,$code, null);
	}
}

class DbNoDatabaseSelectedException extends DbException{
	public function __construct(){
		parent::__construct("No Database selected, see Db.php function useDb(string)",self::CODE_PREFIX.'0'); 
	}
}

class DbCouldNotConnectException extends DbException{
	public function __construct($reason){
		parent::__construct("Could not connect to the database",self::CODE_PREFIX.'1');
	}
}

class DbDoesNotExistException extends DbException{

	public function __construct(){
		parent::__construct("Database does not exist",self::CODE_PREFIX.'2');
	}

}

class DbInvalidErrorModeException extends DbException{
	public function __construct(){
		parent::__construct("Invalid Error Mode Exception", self::CODE_PREFIX.'3');
	}
}

class DbCouldNotCreateDatabaseException extends DbException{
	public function __construct($database_name,$e){
		parent::__construct($e->getMessage(),self::CODE_PREFIX.'4');
	}
}

class DbCouldNotCreateTableException extends DbException{
	public function __construct($table_name,$e){
		parent::__construct($e->getMessage(),self::CODE_PREFIX.'5');
	}
}
