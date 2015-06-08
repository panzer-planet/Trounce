<?php
    require_once('Html/Forms/Select.php');
    require_once('Html/Forms/Form.php');
    require_once('Html/Forms/Submit.php');
    /**
     * lib-cobolt RegisterForm class
     * 
     * @package lib-cobolt
     * @subpackage Html
     * @author Ant Cosentino <antony@mycosentino.com>
     */
    class RegisterForm extends Form {
        // public function __construct($attrs) {
      public function __construct() {
        $args = func_get_args();
            $children = array(
                new Input(
                        array("type"=>"email",
                              "placeholder"=>"email",
                              "name"=>"email")
                ),
                new Input(
                        array("type"=>"password",
                              "placeholder"=>"password",
                              "name"=>"pass")
                ),
                new Input(
                        array("type"=>"password",
                              "placeholder"=>"confirm",
                              "name"=>"passConf")
                ),
                new Select(
                        array("name"=>"dropdown"),
                        array("option1",
                              "option2",
                              "option3")
                ),
                new Submit()
            );
            if (count($args) == 1) {
              parent::__construct($children);
            }
            // parent::__construct($attrs, $children);
        }
    }
?>
