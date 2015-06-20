<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Router extends Base{
    
#$app_config['']
    private $controller_name;
    private $action_name;
    private $arguments = array();
	
    public function __construct(){
        
        # Defaults are set at construction to avoid repetition
        parent::__construct();
        $this->controller_name = Config::$system['controller_default'];
        $this->action_name = Config::$system['action_default'];
        $this->arguments = array();
    }
    /**
     * This function extracts information from the query string
     * to pass to Trounce for processing
     */
    public function resolveUrl(){
    
        # If there is nothing in the specified $_GET variable
        # we skip everything and it will default to the correct route
		if(isset($_GET[Config::$system['querystring_holder']])){
                
            # Our internal querystring is held in a single get parameter defined in config.php
            $querystring = $_GET[Config::$system['querystring_holder']];
            # Special chars are converted for safety
            $querystring = strip_tags($querystring);
            $querystring = htmlspecialchars($querystring);
          
            # If the querystring is present but empty we skip
            # everything and the it will fall onto defaults
            if( strlen($querystring) !== 0 ){
            
                # Trim off any trailing slashes otherwise explode
                # will contain an empty element at the end
                $querystring = rtrim($querystring,'/');
                
                # Create array of raw arguments
                $raw_arguments = explode('/',$querystring);
                
                # If there is a controller but no action specified
                # the default action is used
                if(count($raw_arguments) == 1){

                    $this->setControllerName(ucfirst($raw_arguments[0]));                   
                 
                # If there are two or more arguments then
                # both controller and action are set
                }elseif(count($raw_arguments > 1)){
                
                    $this->setControllerName(ucfirst($raw_arguments[0]));
                    $this->setActionName(lcfirst($raw_arguments[1]));
                    
                    # The first two raw arguments are dropped and
                    # the rest are taken as arguments to the controller
                    unset($raw_arguments[0]);
                    unset($raw_arguments[1]);
                    $this->arguments = array_values($raw_arguments);
                }
            }	
        }
        Logger::log('system','Controller: ' . $this->getControllerName());
        Logger::log('system','Action: ' . $this->getActionName());
        $arguments = print_r($this->arguments, true);
        Logger::log('system', 'Arguments: ' . $arguments);
	
    }
	
	
		
    
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

	public function getArguments(){
		return $this->arguments;
	}
	
	public function getArgument($key){
		if(isset($this->arguments[$key]))
			return $this->arguments[$key];
		else
			return false;
	}
}
