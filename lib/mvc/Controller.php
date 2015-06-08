<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Controller extends Base{

	#protected $_app; #Holds the app object to be available
	
    public function __construct(){
        parent::__construct();
		#$this->_app = $app;
    }
	#what all controllers can do
	
	
    protected function showView(){
	
    }
    
    
    protected function showLayout($layout_name){
	
	#require_once ROOT . DS . 'app' . DS . App::getName() . DS . 'themes' . DS . App::getTheme() .'.php';

        $layout = new Layout;
        $render_list = $layout->assembleLayout($layout_name);
        
        
        
    
    }
	
	/**
	 * This function is a wrapper that allows simple
	 * retreival of variables in controllerr
	 */
	 protected function arg($i){
		$router = $this->_app->getRouter();
		if( $router->getArgument($i) ){
			return $router->getArgument($i);
		}else{
			return '%UNDEFINED%';
		}
	 }
	 
	 /**
	 * This function is a wrapper that allows simple
	 * retreival of variables in controllerr
	 */
	 protected function args(){
		return $this->_app->getRouter()->getArguments();
	 }
	 
	
}
