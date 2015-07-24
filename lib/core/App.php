<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class App {
	/**
	 * @var string The title of the application
	 */
	public static $title;
	/**
	 * @var Router Holds a Router object
	 */
	public static $_router;
	/**
	 * @var Request Holds a Request object
	 */
	public static $_request;
	/**
	 * @var Response Holds a Response object
	 */
	public static $_response;
    
    /**
     * @var $_config
     */
     public static $_config = array();

	/**
	 * Run the application
	 *
	 * Trounce begins running the application by creating a Router,
	 * Request and Response object. It then uses the Router to
	 * resolve the querystring to a controller and action which is
	 * then run.
	 */
	
	public static function set_config($app_config){
        self::$_config = $app_config;
	}
	
	public static function run(){
	
		#Resolve URL
		Logger::write('system', 'Resolving URL: ' . $_SERVER['REQUEST_URI']);
		self::$_router = new Router;
		self::$_router->resolveUrl();
		
		# Create request object
		self::$_request = new Request;
		# Create response object
		self::$_response = new Response;
		
		#Initialise the controller
		Logger::write('system','Initialising '.self::$_router->getControllerName().'Controller');
		        
		if($_controller = self::initController(self::$_router->getControllerName())){
		      
			#Run the action code
			$action_name = self::$_router->getActionName().'Action';
			
			if(method_exists($_controller,$action_name)){
				Logger::write('system', 'Firing action: '.self::$_router->getActionName());
                $result = array();
				if(self::$_router->getArguments()){
                    $result = call_user_func_array(array($_controller, $action_name),self::$_router->getArguments());
                }else{
                    $result = call_user_func(array($_controller, $action_name));
                }
                $layout = new Layout();
                if($result){
                    $layout->render(self::$_router->getControllerName(),$result);
                }else{
                    $layout->render(self::$_router->getControllerName());
                }
			}else{
				self::display404();
			}
		}else{
			self::display404();
		}
	}

	private static function display404(){
		header("HTTP/1.1 404 Not Found");
		require_once ROOT . DS .'app'. DS . 'error_pages/NotFound.php';
	}
	
	/**
	 * Initialises a controller
	 * @param string $controller_name
	 */ 
	private static function initController($controller){
        $controller_class = $controller.'Controller';
	    #might need to do a try catch here to get it to 404 correctly
	    if(file_exists(ROOT . DS . 'app'.  DS . 'controllers'. DS . $controller_class.'.php')){
            require_once ROOT . DS . 'app'. DS . 'controllers'. DS . $controller_class.'.php';
            $controller = new $controller_class();
            return $controller;
	    }else{
            return false;
	    }
	}
	
	
}
