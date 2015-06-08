<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
class Logger extends Base{

	public static function log($log_name,$text){
		$handle = fopen( ROOT . DS . 'logs' . DS . strtolower($log_name) . '.txt','a+');
		$result = fwrite($handle, self::timestamp() .' '.$text."\n");
		if( !  $result ) {
			throw new Exception('Could not log to ' . ROOT . DS . 'logs' . DS . $log_name .'.txt. Can PHP write here?');
		}

	}
	
	# Special function for intro
	public static function log_no_timestamp($log_name,$text){
		$handle = fopen( ROOT . DS . 'logs' . DS . strtolower($log_name) . '.txt','a+');
		$result = fwrite($handle, $text."\n");
		if( !  $result ) {
			throw new Exception('Could not log to ' . ROOT . DS . 'logs' . DS . $log_name .'.txt. Can PHP write here?');
		}

	}
	
	public static function log_error($text){
		$handle = fopen( ROOT . DS . 'logs' . DS . 'system_errors.txt','a+');
		
		if(Settings::system_logging() && Settings::system_error_logging()){
			$result = fwrite($handle, self::timestamp() .' '.$text."\n");
			if( !  $result ) {
				throw new Exception('Could not log to ' . ROOT . DS . 'logs' . DS . $log_name .'.txt. Can PHP write here?');
			}
		}
	}
	
	
	
	public static function timestamp(){
		return date('[Y-m-d H:m:s]:');
	}

}
