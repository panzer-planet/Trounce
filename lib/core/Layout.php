<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

/**
 * Render layouts to the output buffer
 */
class Layout{
    
    /**
     * @var string The filename of the layout file
     */
    private $filename;
    
    /**
     * @var array A list of views to be rendered
     */
    private $view_list;
    
    /**
     * @var string The name of the layout
     */
    private $layout_name;
    
    /**
     * @var string The name of the theme
     */
    private $theme_name;
    
    /**
     * @var SimpleXMLElement The XML layout file handle
     */
    private $xml_file;
    
    /**
     * @var array Variables that need to be made available to views
     */
    private $variables;
    
    /**
     * @var bool Was the file loaded?
     */
    private $loaded = false;
    
    public function __construct($layout_name){
        $this->layout_name = $layout_name;
        $default = ROOT . DS .'app' . DS . 'layouts' . DS .'default.xml';
        $this->filename = ROOT . DS .'app' . DS . 'layouts' . DS . strtolower($layout_name).'.xml';
       
        $this->xml_file = @simplexml_load_file($this->filename);
        if($this->xml_file){
            $this->loaded = true;
            $this->theme_name =  $this->xml_file['theme'];
            return true;
        }
        return false;
        
    }
    


    /**
     * Add a CSS <link> element
     * @note consider moving to HTML lib
     * @param string Name of the CSS file
     */
    private function addCss($filename){
        
        return '<link href="http://'.$_SERVER['HTTP_HOST'].'/css/'.$filename.'" rel="stylesheet">';
    }
    
    /**
     * Add a JS <script> element
     * @note consider moving to HTML lib
     * @param string Name of the JS file
     */
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
        if(isset($xml_layout_element[0])){
            foreach($xml_layout_element[0]->block as $block){
                if($block['name'] == $block_name){
                    foreach($block as $view){
                        /* We should not assume these
                        * are all blocks
                        */
                        
                        $views[] = (string)$view;
                    }
                }
            }
        }else{
            if(App::$_config['debug']){
                echo '<p style="color:red">This action does not exist in the layout</p>';
            }
         
            Logger::write('system',"This action does not exist in the layout");
        }
        return $views;
    }

    public function hasAction($action_name){
        if(!$this->loaded) return false;
        $xpath = '//layout[@action=\''.$action_name.'\']';
        $xml_layout_element = $this->xml_file->xpath($xpath);
        return $xml_layout_element ? true : false;
    }
    
    protected function showBlock($block_name, $views = array()){

        
         # The current action's layout is selectd
        $xpath = '//layout[@action=\''.App::$_router->getActionName().'\']';
        
         # An SimpleXMLElement is created on the layout node
        $xml_layout_element = $this->xml_file->xpath($xpath);
        if(!$xml_layout_element){ return false; }
        #print_r($xml_layout_element);exit;
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
            
            $this->renderView($view,$this->variables);   
        }
    }
    /**
     * Renders a block
     * @param string $block_name
     * @param array $variables
     */
    private function renderView($view, $vars = array()){   
   
        if(isset($vars)){
            # Here the variables are 
            # rescoped for access in the block
            foreach($vars as $k => $v){
                $$k = $v;
            }
            
        }
        if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $view.'.php')){
            require_once ROOT . DS . 'app' . DS . 'views' . DS . $view.'.php';
        }else{
            #File not found
            echo '<div style="color: red;">VIEW NOT FOUND</div>';
        }
    }
    
    public function render($variables = array()){
        $this->variables = $variables;
        #ob_start();
        if($this->loaded){
            if(file_exists(ROOT . DS . 'app'. DS .'themes'. DS . $this->theme_name .'.php')){
                require_once ROOT . DS . 'app'. DS .'themes'. DS . $this->theme_name .'.php';
            }else{
                #File not found
                throw new Exception(__method__ .' Theme file not found');
            }
        }
        #$buffer = ob_get_clean();
    }
    
    
   
    
}
