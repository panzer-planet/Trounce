<?php
    require_once('Html/Base/SelfClosing.php');
    /**
     * lib-cobolt Br class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class Br extends SelfClosing {
        public function __construct($args) {
            
            parent::__construct($args);
            $this->tag = 'br';

        }
    }
?>