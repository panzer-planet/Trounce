<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


class UserModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    /**
         * super dumb password checking
         */
    public function checkLogin($email, $password){
        $result = $this->db->query("select `email`,`password` from users");
       # print_r($result);exit;
        if($password == $result[0]['password']){
            return true;
        }
        return false;
        
    }
    
    public function addUser($email, $password, $name, $surname){
    
        $result = $this->db->insert('users',array('email' => $email,'name' => $name, 'surname' => $surname));
        if(!$result) die('Could not insert');
    }
    
    
}
