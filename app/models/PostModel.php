<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


class PostModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    
    public function add($title, $content ){
        $result = $this->db->insert('posts',array('title' => $title, 'content' => $content));
        
    }
    
    public function edit($id, $field, $value) {
        return $this->db->update('posts', array($field => $value), array('id = ?', array($id)));
    }
    
    public function getAll(){
        return $this->db->query("SELECT * FROM posts");
    }
}
