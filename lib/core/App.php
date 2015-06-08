<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class App {
	
	private static $_router;
	#private $_controller;			# The current controller object
	private static $theme_name, $name, $title;	
	
	public static function getRouter(){
		return self::$_router;
	}
	public static function setName($app_name){
		self::$name = $app_name;
	}
	
	public static function getName(){
		return self::$name;
	}
	/**
	 * Set the theme of the site
	 * @param $theme_name the name of the theme
	 */
	public static function setTheme($theme_name){
		self::$theme_name = strtolower($theme_name);
	}
	
	/**
	 * Get the _theme_name of the site
	 * @return string theme of the site
	 */
	public static function getTheme(){
		return self::$theme_name;
	}
	
	/**
	 * Getter for title
	 * @return The title of the application or page
	 */
	public static function getTitle(){
		return self::$title;
	}
	
	/**
	 * Setter for title
	 * @param string title The title for the page
	 */
	 public static function setTitle($title){
	 	self::$title = $title;
	 }	
	
	/**
	 * Returns the path of the currrently
	 * enabled app and theme
	 **/
	public static function getThemePath(){
		return 'app/'.Settings::app_folder().'/themes/'.self::getTheme().'/index.php';
		
	}
	
	public static function run(){
	
		Logger::log('system', 'Launching app: ' . self::getName());
		
		#Resolve URL
		Logger::log('system', 'Resolving URL: ' . $_SERVER['QUERY_STRING']);
		$router = new Router;
		self::$_router = $router;
		self::$_router->resolveUrl();
		
		#Initialise the controller
		Logger::log('system','Initialising controller');
		$controller_name = self::$_router->getControllerName();
		if($_controller = self::initController($controller_name)){

			#Run the action code
			$action_name = self::$_router->getActionName();
			#$this->_controller->$action_name(); The way below might be faster
			
			if(method_exists($_controller,$action_name)){
				Logger::log('system', 'Firing action: '.self::$_router->getActionName());
				call_user_func(array($_controller, $action_name));
			}else{
				self::display404();
			}
		}else{
			self::display404();
		}
		
		
		#include $this->getThemePath();
	}
	
	private static function display404(){
		header("HTTP/1.1 404 Not Found");
		require_once ROOT . DS .'app'. DS . self::$name.'/error_pages/NotFound.php';
	}
	
	private static function initController($controller_name){
	
		#echo ROOT . DS . 'app'. DS . self::$name. DS . 'controllers'. DS . $controller_name .'.php';exit;
		#/*
		if(!@include( ROOT . DS . 'app'. DS . self::$name. DS . 'controllers'. DS . $controller_name .'.php')){

			return false;
		}else{
			
			$controller = new $controller_name();
			#$this->_controller->app = $this; #make the app available to the controller actions
			return $controller;
		}
		#*/
	}
	
	
}
