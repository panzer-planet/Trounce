<?php
    require_once('Html/Base/HtmlElement.php');
    /**
     * lib-cobolt SelfClosing class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class SelfClosing extends HtmlElement {


        public function __construct($args) {
            parent::__construct();
            # Here we define how SelfClosing elements should handle variations
            # of arguments
            $this->self_closing = true;

            if(count($args) > 0){
                
                $this->attrs($args[0]);    
            }
            
        }
    }
?>