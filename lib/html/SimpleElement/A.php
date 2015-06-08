<?php
/**
 * A.php is used to create anchor links
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

require_once('Html/Base/SimpleElement.php');

/**
 * lib-cobolt A class
 * 
 * This class provides anchor link creation
 * 
 * @package lib-cobolt
 * @subpackage Html
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @copyright 2013 Werner Roets
 */
class A extends SimpleElement {
    // protected $tag = 'a';
    public function __construct($arguments){
        parent::__construct($arguments);
        $this->tag = 'a';
        // $this->attr('href',$to);
        // $this->inner_html = $inner_html;
    }
}
?>