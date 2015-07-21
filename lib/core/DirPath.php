<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


class DirPath{
    
    static function root(){
        return ROOT;
    }
 
    static function html(){
        return ROOT . DS . 'public';
    }
    
    static function js(){
        return ROOT . DS . 'public' . DS . 'js';
    }
    
    static function css(){
        return ROOT . DS . 'public' . DS . 'css';
    }
    
    static function app_config(){
        return ROOT . DS . 'app' . DS . 'config';
    }
    
 }