<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Router extends Base{
    

    private $controller_name;
    private $action_name;
    private $_arguments = array();
	
    public function __construct(){
        parent::__construct();
        #Defaults are set at construction to avoid repetition.
        $this->controller_name = Settings::system_controller_default();
        $this->action_name = Settings::system_action_default();
	
    }
    
    public function resolveUrl(){
		if(isset($_GET[Settings::system_querystring_holder()])){
                
            #Our internal querystring is held in a single get parameter defined in config.php
            $querystring = $_GET[Settings::system_querystring_holder()];
            #echo $querystring;exit;
            if( strlen($querystring) !== 0 ){
                $raw_arguments = rtrim($querystring,'/');
                $raw_arguments = explode('/',$querystring);

                if(count($raw_arguments) == 1){
                    #controller only
                    #default action
                    $this->setControllerName(ucfirst($raw_arguments[0]));
                    
                    unset($raw_arguments[0]);
                    $this->_arguments = $raw_arguments;
                    
                }elseif(count($raw_arguments > 1)){
                
                    $this->setControllerName(ucfirst($raw_arguments[0]));
                    if($raw_arguments[1] != ""){
                        $this->setActionName(lcfirst($raw_arguments[1]));
                    }
                    unset($raw_arguments[0]);
                    unset($raw_arguments[1]);
                    $this->_arguments = array_values($raw_arguments);
                }
            }	
        }
        Logger::log('system','Controller: ' . $this->getControllerName());
        Logger::log('system','Action: ' . $this->getActionName());
        $arguments = print_r($this->_arguments, true);
        Logger::log('system', 'Arguments: ' . $arguments);
	
    }
	
	
		
    # Set the controller name
	private function setControllerName($controller_name){
		$this->controller_name = ucfirst($controller_name);
	}
	
	public function getControllerName(){
		return $this->controller_name;
	}
	
	# Set the action name 
	private function setActionName($action_name){
		$this->action_name = lcfirst($action_name);
	}
	
	public function getActionName(){
		return $this->action_name;
	}	
	
	private function setArguments($arguments){
		$this->_arguments = $arguments;
	}
	
	public function getArguments(){
		return $this->_arguments;
	}
	
	public function getArgument($key){
		if(isset($this->_argument[$key]))
			return $this->_argument[$key];
		else
			return false;
	}
}
