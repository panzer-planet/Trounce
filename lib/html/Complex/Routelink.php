<?php
require_once('Html/Base/HtmlElement.php');

class Routelink extends HtmlElement{
    private $func_name;
    private $link_text;
    private $arguments;
    private $gets;
    private $action;
    private $method = 'get';
    
    /**
     * @param string
     * @param string
     * @param array
     */
    public function __construct($link_text, $func_name, $arguments){
        
        $gets = array();
        foreach($arguments as $argument){
            if(is_array($argument)){
                #How could you represent an array in get paremeters?
                #throw new InvalidRoutelinkArgumentsException();
                die('cant pass arrays');
            }    
        }
        
        $this->arguments = $arguments;
        $this->func_name = $func_name;
        $this->link_text = $link_text;
        $this->action = $_SERVER['PHP_SELF'];
        if(isset($_GET['func_name']) && $_GET['func_name'] == $this->func_name){
            
            $this->execute();
        }
        
    }
    
    private function execute(){
        unset($_GET['func_name']);
        
        call_user_func_array($this->func_name,$_GET);    
        header('Location: '.$_SERVER['PHP_SELF']);
    }
    
    public function render(){
        echo $this->toHtml();   
    }
    
    public function toHtml(){
        $html = "<form action=\"{$this->action}\" method=\"{$this->method}\">";
        $html .= "<input type=\"hidden\" name=\"func_name\" value=\"{$this->func_name}\" />";
        for($i = 0; $i < count($this->arguments); $i++){
            $html .= "<input type=\"hidden\" name=\"arg{$i}\" value=\"{$this->arguments[$i]}\" />";
        }
        $html .= "<input type=\"submit\" value=\"{$this->link_text}\" />";
        $html .= "</form>";
        return $html;
    }
    
}
