<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Layout extends Base{

    private $filename;
    private $view_list;
    private $layout_name;
    private $theme_name;
    private $xml_file;
    private $variables;
    
    public function __construct($layout_name, $variables){
        parent::__construct();
        $this->views_to_render = array();
        $this->layout_name = $layout_name;
        $this->variables = $variables;
    }
    
    public function renderLayout(){
	    
        $filename = ROOT . DS .'app' . DS .Config::$app['app_name']. DS . 'layouts' . DS . strtolower($this->layout_name).'.xml';
        $default = ROOT . DS .'app' . DS .Config::$app['app_name']. DS . 'layouts' . DS .'default.xml';
        $this->filename = $filename;
        $this->xml_file = simplexml_load_file($filename);
        $this->theme_name =  $this->xml_file['theme'];

        $this->renderTheme();
	
    }
    
    public function getViewList(){
        return $this->view_list;
    }
    
    private function addCss($filename){
        
        return '<link href="http://'.$_SERVER['HTTP_HOST'].'/css/'.$filename.'" rel="stylesheet">';
    }
    
    private function addJs($filename){
        return '<script src="http://'.$_SERVER['HTTP_HOST'].'/js/'.$filename.'"></script>';
    }
    /**
     * Get a list of all the views contained in a given block
     * in the order at which they should be rendered
     * @param SimpleXMLElement $xml_layout_element
     * @param string $block_name;
     */
    private function getBlockViews($xml_layout_element,$block_name){

        $views = [];
        
        foreach($xml_layout_element[0]->block as $block){
            if($block['name'] == $block_name){
                foreach($block as $view){
                    $views[] = (string)$view;
                }
            }
        }     
        return $views;
    }

    
    private function showBlock($block_name, $views = [] ){
        
         # The current action's layout is selectd
        $xpath = '//layout[@action=\''.App::getRouter()->getActionName().'\']';
         # An SimpleXMLElement is created on the layout node
        $xml_layout_element = $this->xml_file->xpath($xpath);
         # We get an array of views for the specified block
        $views = $this->getBlockViews($xml_layout_element,$block_name);
        
        #If nothing was found for currrent action, try default action
        if(count($views) == 0){
            $xpath = '//layout[@action=\'default\']';
            $xml_layout_element = $this->xml_file->xpath($xpath);
            $views = $this->getBlockViews($xml_layout_element,$block_name);
        }
         # Render each block
        foreach($views as $view){
            
            $this->renderBlock($view,$this->variables);   
        }
    }
    
    private function renderBlock($view, $vars){
         # Here the variables are 
         # rescoped for convenience
        foreach($vars as $k => $v){
            $$k = $v;
        }
        if(file_exists(ROOT . DS . 'app' . DS . Config::$app['app_name'] . DS . 'views' . DS . $view.'.php')){
            require_once ROOT . DS . 'app' . DS . Config::$app['app_name'] . DS . 'views' . DS . $view.'.php';
        }else{
            #File not found
            echo '<div style="color: red;">VIEW NOT FOUND</div>';
        }
    }
    
    private function renderTheme(){

        if(file_exists(ROOT . DS . 'app'. DS .Config::$app['app_name']. DS .'themes'. DS . $this->theme_name .'.php')){
            require_once ROOT . DS . 'app'. DS .Config::$app['app_name']. DS .'themes'. DS . $this->theme_name .'.php';
        }else{
            #File not found
            die('THEME NOT FOUND');
        }
    }
   
    
}
