<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


class Trounce {
    
    public static function Run($config){
        
        #Put the global settings into the registry
        #Registry::set('settings',$settings);
		Settings::load($config);
               
        #If debugging is on, notify the user
        if($config['system_debug']){
            echo 'Debugging is on';
			ini_set('display_errors',1);
        }
        
		Logger::log_no_timestamp('system','##########################################');
		Logger::log_no_timestamp('system','###       Welcome to ' . FRAMEWORK_NAME . '        ###');
		Logger::log_no_timestamp('system','###    ' . date("l jS F Y H:m:s") . '   ###');
		Logger::log_no_timestamp('system','##########################################');
	
        #Include the app's main file
        require_once ROOT . DS . 'app'. DS . $config['app_folder'].'/index.php';
    }
}
