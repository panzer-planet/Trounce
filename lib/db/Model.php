<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


class Model{
    /**
     * The Database object
     */
    protected $db;
     
    public function __construct(){

        $this->db = new T_DB(App::$_config['db_type']);
        
        $success = $this->db->connect(
                App::$_config['db_host'],
                App::$_config['db_username'],
                App::$_config['db_password'],
                App::$_config['db_name']
            );
        if($success){
            return $this;
        }else{
            Logger::write('error',$this->db->error_message);
            if(App::$_config['debug']){
                die('<p style="color:red;">FATAL ERROR: '.$this->db->error_message.'</p>');
            }
            return false;
        }
    }
    
    public function get_db_error_message(){
        return $this->db->error_message;
    }
}
