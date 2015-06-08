<?php
/**
 * HtmlElement.php is the parent class of all Html
 *
 * lib-cobolt 
 * General purpose web development library.
 *
 * @package lib-cobolt
 * @subpackage Html
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 
 */
?>

<?php

class HtmlElement{
    private $html;
    private $attributes;
    protected $tag;
    protected $inner_html;
    protected $self_closing;

     public function __construct() {
        $this->attributes = array();
    }
    
    public function __get($name){
        switch($name){
            case 'html':
                return $this->getHtml();
            break;
        }
    }
    
    public function __set($name,$value){
        switch($name){
            
        }
    }

    public function __call($name, $value) {
        
    }
    
    public function render(){
         echo $this->toHtml();
    }
    
    public function attr($name, $val){
        $this->attributes[$name] = $val;
    }
    
    public function attrs($attrs){
        foreach($attrs as $name => $val){
            $this->attr($name, $val);
        }
    }

    public function rmAttr($name){
        unset($this->attributes[$name]);
    }
    


    private static function attributeToHtml($name, $value) {
        return ($name && !$value) ? "{$name}" : "{$name}=\"{$value}\"";
    }
    
    private function attrsToString() {
        if (!count($this->attributes)) {
            return false;
        } else {
            $html = '';
            
            foreach ($this->attributes as $name => $value) { 
                $html .= ' '.HtmlElement::attributeToHtml($name, $value);
            }
            return $html;
        }
    }
    
    protected function getChildHtml() {
        if ($this->inner_html) {
            if (is_array($this->inner_html)) {
                $ret = "";
                foreach ($this->inner_html as $element) {
                    $ret .= ($element instanceof HtmlElement) ? $element->toHtml() : $element;
                }
                return $ret;
            } else {
                return $this->inner_html;
            }    
        } else {
            return "";
        }
    }
    
    protected function toHtml(){
        
        $attrs = $this->attrsToString();
        
        $child_html = $this->getChildHtml();
        
        if($attrs = $this->attrsToString()){
            $html = "<{$this->tag} {$attrs}";
        }else{
            $html = "<{$this->tag}"; 
        }
        if($this->self_closing){
            $html .= ">";
        }else{
            $html .= ">{$child_html}</{$this->tag}>";
        }
        $this->html = $html;
        return $html;
    }

}