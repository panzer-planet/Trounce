<?php
    require_once('Html/Base/Empty.php');
    class Select extends Empty {
        // protected $tag = 'select';
        // public function __construct($attrs, $inner_html) {
        //     parent::__construct();
        public function __construct() {
            parent::__construct(func_get_args());
            $this->tag = 'select';
            // $this->attrs($attrs);
            // $this->inner_html = $inner_html;
        }
        protected function getChildHtml() {
            if ($this->inner_html) {
                if (is_array($this->inner_html)) {
                    $ret = "";
                    foreach ($this->inner_html as $option) {
                        $ret .= "\n<option>{$option}</option>";
                    }
                    return $ret;
                } else {
                    return "\n<option>{$this->inner_html}</option>";
                }
            } else {
                return "";
            }
        }
    }
?>