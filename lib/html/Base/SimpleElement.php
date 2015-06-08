<?php
    require_once('Html/Base/HtmlElement.php');
    /**
     * SimpleElement.php
     *
     * lib-cobolt 
     * General purpose web development library.
     *
     * @package lib-cobolt
     * @subpackage Html
     * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class SimpleElement extends HtmlElement {
        
        public function __construct($args) {
            parent::__construct();
            $this->self_closing = false;
            
            if (count($args) == 1) {
                if (is_array($args[0])) {
                    $this->attrs($args[0]);
                } else  {
                    $this->inner_html = $args[0];
                }    
            }elseif(count($args) == 2){
                $this->attrs($args[0]);
                $this->inner_html = $args[1];
            }else{
                
            }
        }
    }

?>
