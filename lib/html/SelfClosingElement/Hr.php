<?php
    require_once('Html/Base/SelfClosing.php');
    /**
     * lib-cobolt Hr class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class Hr extends SelfClosing {
        protected $tag = 'hr';
        // public function __construct($attrs) {
        //     parent::__construct($attrs);
        public function __construct($args) {
            parent::__construct($args);
            $this->tag = 'hr';
        }
    }
?>