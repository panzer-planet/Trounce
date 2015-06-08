<?php
/**
 * Html.php
 *
 * lib-cobolt 
 * General purpose web development library.
 *
 * @package lib-cobolt
 * @subpackage Html
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 
 */
?>

<?php
/**
 * Html class
 * The main aim of this class is to provide one clean static factory interface
 * to many different types of HtmlElement children in a flexible manner, providing
 * a few conventions for constructors of different types of elements
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @package lib-cobolt
 * @subpackage Html
 */
class Html {
    #DECLARE ELEMENTS IN THIS ARRAY
    private static $elements = array(
        'a'         => 'Html/SimpleElement/A.php',
        'form'      => 'Html/SimpleElement/Form.php',
        
        'br'        => 'Html/SelfClosingElement/Br.php',
        'hr'        => 'Html/SelfClosingElement/Hr.php',
        'img'       => 'Html/SelfClosingElement/Img.php',
        'input'     => 'Html/SelfClosingElement/Input.php',
        
        'Table'     => 'Html/Table.php',
    );
    
    /**
     * executes when a call to an undefined static function
     * occurs
     * @param string $name the name function
     * @param array $arguments the arguments of the function
     */
    public static function __callStatic($name,$arguments){
        
        #If the function is declared in the $elements property
        if(key_exists($name,Html::$elements)){
            $element = Html::bakeElement($name);
            return new $element($arguments);
        }else{
            #custom element
            die('custom element');
        }
 
    }
    
    private static function bakeElement($name){
        require_once(Html::$elements[$name]);
        return strtoupper($name);
    }
    
	# SPECIAL ELEMENTS #
	    
    public static function Table($data){
       require_once('Html/Table.php');
       return new Table($data);
    }
    public static function Routelink($link_text,$func_name,$arguments){
        require_once('Html/Parents/Routelink.php');
        return new Routelink($link_text,$func_name,$arguments);
    }
   
  
}
