<?php
    require_once('Html/Forms/Input.php');
    /**
     * lib-cobolt FileInput class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class FileInput extends Input {
        // public function __construct($attrs) {
        //     parent::__construct($attrs);
        public function __construct() {
            parent::__construct(func_get_args());
            $this->attr('type', 'file');
        }
    }
?>