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
    
    public function __construct(){
      $this->views_to_render = array();
    }
    public function assembleLayout($layout_name){
	    
	$filename = ROOT . DS .'app' . DS .Settings::app_folder(). DS . 'layouts' . DS . strtolower($layout_name).'.xml';
	$default = ROOT . DS .'app' . DS .Settings::app_folder(). DS . 'layouts' . DS .'default.xml';
	$this->filename = $filename;
	
	
	if(file_exists($filename)){
	  #specified layout
	  $this->view_list = $this->parseLayoutFile($filename);
	}elseif(file_exists($default)){
	  #default fallback
	  $this->view_list = $this->parseLayoutFile($default);
	}else{
	  DIE('COULD NOT FIND '.$filename.' OR THE DEFAULT');  
	}
	$this->renderLayout();
	
    }
    
    public function getViewList(){
      return $this->view_list;
    }
    
    private function showBlock($block_name){
      if(isset($this->view_list[App::getRouter()->getActionName()][$block_name])){
	$block = $this->view_list[App::getRouter()->getActionName()][$block_name];
      }else{
	$block = $this->view_list['default'][$block_name];
      }
      
      foreach($block as $view){
	require_once ROOT . DS . 'app' . DS . Settings::app_folder() . DS . 'views' . DS . $view.'.php';
      }
      
    }
    
    private function renderLayout(){
	require_once ROOT . DS . 'app'. DS .Settings::app_folder(). DS .'themes'. DS .Settings::app_theme().'.php';
    }
    
    
    private function parseLayoutFile($file_location){

      $parsed_layout = array();
      $layouts = simplexml_load_file($file_location);
      foreach($layouts as $element){
	$layout_name =  (string)$element['name'];
	foreach($element->block as $block){
	  $block_name =  (string)$block['name'];
	  foreach($block->view as $view){
	    $parsed_layout[$layout_name][$block_name][] = (string)$view;
	  }
	}
      }
      return $parsed_layout;
 
	      
    }
 
    
}
