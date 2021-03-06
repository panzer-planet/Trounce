<?php
    require_once('Html/Base/SimpleElement.php');
    require_once('Html/SelfClosingElement/Input.php');
    /**
     * lib-cobolt Form class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class Form extends SimpleElement {
        private $fields;
        // protected $tag = 'form';
        // public function __construct($attrs, $inner_html) {
        //     parent::__construct($attrs, $inner_html);
        public function __construct($args) {
            $fields = array();
            parent::__construct($args);
            $this->tag = 'form';
        }
        
        public function addField(){
            $args = func_get_args();
            $n_args = func_num_args();
            if($n_args == 2){
                #create new input
            }elseif($n_args == 1){
                if($args[0] instanceof Input){
                    $this->fields[] = $args[0];
                }
            }
        }
    }
?>