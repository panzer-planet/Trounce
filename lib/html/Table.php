<?php
/**
 * Table.php
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
require_once('Html/Base/HtmlElement.php');
require_once('Html/HtmlException.php');
/**
 * lib-cobolt Table class
 * 
 * This class provides html table rendering
 * 
 * @package lib-cobolt
 * @subpackage Html
 * @author Werner Roets <cobolt.exe@gmail.com>
 * @copyright 2013 Werner Roets
 */
class Table extends HtmlElement {
    // protected $tag = 'table';
    private $data;
    
    public function __construct($data){
        parent::__construct();
        $this->tag = 'table';
        $this->data = $data;
        
        $this->inner_html = $this->generateTable();
    }
    
    
    private function generateTable(){
        $data = $this->data;
        $table_html = "";
        foreach($data as $row => $values){
            $table_html .= "<tr>";
            foreach($values as $col){
                
                if($col instanceof HtmlElement){
                    $col = $col->toHtml();
                }elseif(is_array($col)){
                    $col = $this->generateTable($col);
                }
                $table_html .= "<td>{$col}</td>";
            }
            $table_html .= "</tr>";
            
        }
        return $table_html;
    }
    
    private function normalize(){
            
    }
    
    private function getData(){
        return $this->data;
    }
}
