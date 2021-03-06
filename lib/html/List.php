<?php
/**
 * List.php
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
/**
 * lib-cobolt HtmlException class
 * 
 * This is the base HtmlException class for exceptions in
 * the Html package
 * 
 * @package lib-cobolt
 * @subpackage Html
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @copyright 2013 Werner Roets
 */

class List extends HtmlElement{
    private $items;
    
    public function __construct($attributes,$type,$items){
        parent::__construct();
        if(is_string($type)){
            if($type == 'ul'){
                $this->tag = 'ul';
            }elseif($type == 'ol'){
                $this->tag = 'ol';
            }else{
                die('no such list type');
                #todo exception
            }
        }else{
            #todo exception
        }
        $this->items = $items;
        $this->inner_html = $this->generateList();
        $this->attrs = $attributes;
    }
    
    private function getItems(){
        return $this->items;
    }
    private function generateList(){
        $items = $this->items;
        $list_html = "";
        foreach($items as $item){
            $list_html .= "<li>{$item}</li>";            
        }
        return $list_html;
    }
}
 