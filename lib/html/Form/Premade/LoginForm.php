<?php
    require_once('Html/Forms/Form.php');
    require_once('Html/Forms/Input.php');
    require_once('Html/Forms/Submit.php');
    /**
     * lib-cobolt LoginForm class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class LoginForm extends Form {
        // public function __construct($attrs) {
        public function __construct() {
            parent::__construct(func_get_args());
            $this->inner_html = array(
                new Input(
                    array("type"=>"email",
                          "placeholder"=>"email",
                          "name"=>"email")
                ),
                new Input(
                    array("type"=>"password",
                          "placeholder"=>"password",
                          "name"=>"password")
                ),
                new Submit()
            );
            // parent::__construct($attrs, $children);
        }
    }
?>