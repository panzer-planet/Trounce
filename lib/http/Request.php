<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
 
 class Request {
    private $_post;
    private $_get;
    private $_cookie;
    private $_files;
    
    public function __construct(){
        $this->_post = $_POST;
        if(App::$_config['enable_get'] && isset($_GET)){
            $this->_get = $_GET;
        }
        $this->_cookie = $_COOKIE;
        $this->_files = $_FILES;
        if(isset($_SESSION)){
            $this->_session &= $_SESSION;
        }
    }
    
    /**
     * Get a specified post variable, the second
     * parameter set to true to enable XSS filtering
     * @param string The name of thet post variable
     * @param boolean Enable XSS filtering
     */
    public function post($variable, $filter_xss = false){
        if($filter_xss)
            return Security::filter_xss($this->_post[$variable]);
        else
            return $this->_post[$variable];
    }
    
    /**
     * Get a specified get variable, the second
     * parameter set to true to enable XSS filtering
     * @param string The name of the get variable
     * @param boolean Enable XSS filtering
     */
    public function get(){
        $args = func_get_args();
        if( count($args) == 1 ){
            if(isset($this->_get[$args[0]])){
                return $this->_get[$args[0]];
            }else{
                return false;
            }
        }elseif( count($args) == 2 && $args[1] === true){
            return Security::filter_xss($this->_get[$arg[0]]);
        }
        return false;
    }
    
   /**
     * Get a specified cookie variable, the second
     * parameter set to true to enable XSS filtering
     * @param string The name of the cookie
     * @param boolean Enable XSS filtering
     */
    public function cookie(){
        $args = func_get_args();
        if( count($args) == 1 ){
            if(isset($this->_cookie[$args[0]])){
                return $this->_cookie[$args[0]];
            }else{
                return false;
            }    
        }elseif( count($args) == 2 && $args[1] === true){
            return Security::filter_xss($this->_cookie[$arg[0]]);
        }
        return false;
    }
    
    /**
     * Get a specified session variable, the second
     * parameter set to true to enable XSS filtering
     * @param string The name of the session variable
     * @param boolean Enable XSS filtering
     * @param $xss_filtering_enabled
     */
    public function session(){
        $args = func_get_args();
        if( count($args) == 1 ){
            if(isset($this->_session[$args[0]])){
                return $this->_session[$args[0]];
            }else{
                return false;
            }   
        }elseif( count($args) == 2 && $args[1] === true){
            return Security::filter_xss($this->_session[$arg[0]]);
        }
        return false;
    }
    
    
 }