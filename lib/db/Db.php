<?php
/**
 * C.php
 *
 * lib-cobolt 
 * General purpose web development library.
 *
 * @package lib-cobolt
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 
 */


/**
 * Db class
 * The main aim of this class is to provide one clean static factory interface
 * to database classes
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @package lib-cobolt
 * @subpackage Db
 */
class Db{

	public static function MySQL(){
		require_once('Db/MySQL.php');
		$args = func_get_args();
		$db = new MySQL($args[0],$args[1],$args[2],false);
		if(isset($args[3])){
			$db->useDb($args[3]);
		}
		return $db;
	}
	
	#Database with persistence enabled	
	public static function MySQLP(){
		require_once('Db/MySQL.php');
		$args = func_get_args();
		$db = new Db($args[0],$args[1],$args[2],true);
		if(isset($args[3])){
			$db->useDb($args[3]);
		}
		return $db;
	}
	
	
}
