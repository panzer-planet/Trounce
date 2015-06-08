<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


/* The registry holds information that must be accessable
 * throughout the application for the duration of the request.
 */ 
 
class Registry {
    
    private static $items = array();
    
    public static function set($key, $value){
        Registry::$items[$key] = $value;
    }
    
    public static function get($key){
        return Registry::$items[$key];
    }
    
}
