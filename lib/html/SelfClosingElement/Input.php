<?php
    require_once('Html/Base/SelfClosing.php');
    /**
     * lib-cobolt Input class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class Input extends SelfClosing {
        // protected $tag = 'input';
        // public function __construct($attrs) {
        //     parent::__construct($attrs);
        public function __construct($args) {
            parent::__construct($args);
            $this->tag = 'input';
        }
    }
?>