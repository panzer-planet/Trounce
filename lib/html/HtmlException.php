<?php
/**
 * HtmlException.php
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
 * lib-cobolt HtmlException class
 * 
 * This is the base HtmlException class for exceptions in
 * the Html package
 * 
 * @package lib-cobolt
 * @subpackage Html
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @copyright 2013 Werner Roets
 */
class HtmlException extends Exception{
   const CODE_PREFIX = 45;
   public function __construct($message, $code){
       parent::__construct($message,$code, null);
   }
}

class InvalidConstructorArgumentException extends HtmlException{
    public function __construct(){
        parent::__construct("This constructor argument is invalid",self::CODE_PREFIX.'1');
    }
}

 