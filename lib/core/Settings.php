<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Settings {
    
    private static $settings = array();
    
	public static function __callStatic($name, $arguments){
		if(!count($arguments)){ 
			if(isset(self::$settings[$name])){
				return self::$settings[$name]; 
			}else{
				throw new Exception(" INVALID SETTINGS NAME");
			}
		}elseif(count($arguments) == 1){
			Settings::$settings[$name] = $arguments[0];
		}else{
			#Too many arguments
			throw new Exception("Invalid arguments");
		}
			
	}
	
	public static function load(array $settings){
		Settings::$settings  = $settings; # self::$settingss?
	}
	
}