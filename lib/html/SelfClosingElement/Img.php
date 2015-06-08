<?php
    require_once('Html/Base/SelfClosing.php');
    /**
     * lib-cobolt Imh class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class Img extends SelfClosing {
        // protected $tag = 'img';
        // public function __construct($attrs) {
        //     parent::__construct($attrs);
        public function __construct($args) {
            
            parent::__construct($args);
            $this->tag = 'img';
        }
    }
?>