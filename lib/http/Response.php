<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */
 
 class Response {
    private $status_code;
    private $content;
    private $headers;
    private $cookies;
    
    public function __construct(){
        $this->cookies = [];
        $this->headers = [];
        $this->status_code = http_response_code();
    }
    
    public function setStatusCode($code){
        if(is_int($code)){
            $this->status_code = $code;
        }
    }
    
    public function getStatusCode(){
        return $this->status_code;
    }
    
    public function setContent($content){
        $this->content = $content;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function addCookie($cookie){
        $this->cookies[] = $cookie;
    }
    
    public function setCookies($cookie_array){
        if(is_array($cookie_array)){
            $this->cookies = $cookie_array;
        }
    }
    
    public function getCookies(){
        return $this->cookies;
    }
    
    public function addRawHeader($header){
        $this->headers[] = $header;
    }
    
    public function send(){
        foreach($this->headers as $header){
            header($header);
        }
        http_response_code($this->status_code);
        echo $this->content;
    }
    
 }
