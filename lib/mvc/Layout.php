<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Layout extends Base{
    private $filename;
    #functions go here
	public function assembleLayout($layout_name){
		
        $filename = ROOT . DS .'app' . DS .Settings::app_folder(). DS . 'layouts' . DS . strtolower($layout_name).'.xml';
        if(file_exists($filename)){
			
            $this->parseLayoutFile($filename);
        }
        $this->filename = $filename;
	}
    
    private function parseLayoutFile($file_location){
        $xml = simplexml_load_file($file_location);
        echo '<pre>';print_r((array)$xml);
		
		foreach($xml as $k => $v){
			if($k == 'comment'){ continue; }
			
			if(strpos($k,'_')){ #There must be an underscore by convention
				#this is a controller/action node
				$this->processRouteLayout($v);
			}else{
				#ignore invalid
			}
			
		}
		
    }
	
	private function processRouteLayout($controller_layout_xml){
		foreach($controller_layout_xml as $k => $v){
			if($k == 'comment'){ continue; }
			
		}
	}
}
