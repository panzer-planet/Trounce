<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

#Controller name must container Controller suffix
class DefaultController extends Controller{


	public function defaultAction(){
	     $this->showLayout('default',[]);
	}
	
	public function aboutAction($name){
        App::setTheme('default');//assumed default
        $this->showLayout('default',['name' => $name]);
	}
	
	public function contactAction(){
        $this->showLayout('default');
    }
}
