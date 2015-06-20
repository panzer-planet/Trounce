<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

class Controller extends Base{

	
    public function __construct(){
        parent::__construct();
        # Nothing should go here because otherwise
        # controllers will all need a __construct
    }
	
    protected function showLayout(){
        $args = func_get_args();
        if(isset($args[0])){
            $layout_name = $args[0];
            if(isset($args[1]) && is_array($args[1])){
                $variables = $args[1];
            }
        }else{
            throw new Exception(__class__ . __method__ . 'expects 1 or 2 arguments');
        }
        if(isset($variables)){
            $layout = new Layout();
            $layout->render($layout_name,$variables);
        }else{
            $layout = new Layout(); 
            $layout->render($layout_name);
        }
        
        
    }
    

}
