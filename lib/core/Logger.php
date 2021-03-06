<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
class Logger{
	# Special function for intro
	public static function write($log_name,$text, $timestamp = false){
        $ts = "";
        if($timestamp){
            $ts = date('[Y-m-d H:m:s]:');
        }
		$handle = fopen( ROOT . DS . 'logs' . DS . strtolower($log_name) . '.txt','a+');
		if($handle){
            $result = fwrite($handle, $ts . ' ' . $text."\n");
            if($result){return true;}
		}
			throw new Exception('Could not log to ' . ROOT . DS . 'logs' . DS . $log_name .'.txt. Can PHP write here?');

	}
	# TODO move to util function
	public static function timestamp(){
		return date('[Y-m-d H:m:s]:');
	}

}
