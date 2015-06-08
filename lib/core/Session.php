<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


# This class simply wraps $_SESSION for consistancy.
class Session{
	
	public static function get($key){
		return $_SESSION[$key];
	}
	
	public static function set($key, $value){
		$_SESSION[$key] = $value;
	}

}