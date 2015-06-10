<?php
/**
 * DefaultController.php
 *
 * Kobolt
 * General purpose web development library.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 *
 * This is an example of a controller
 */

#Controller name must container Controller suffix
class DefaultController extends Controller{

    #Actions must be public functions containing the Action suffix
	public function defaultAction(){
	 
		
	    App::setTheme('default');//assumed default
	    $this->showLayout('default');
	}
	
	public function aboutAction(){
	  $this->showLayout('default');
	}
}
