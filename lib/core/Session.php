<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

/**
 * Light session wrapper
 */
class Session {
    
    public static function getSessionId(){
        return session_id();
    }
    
    public static function get($name){
        return $_SESSION[$name];
    }
    
    public static function set($name, $value){
        $_SESSION[$name] = $value;
    }
    
    

}