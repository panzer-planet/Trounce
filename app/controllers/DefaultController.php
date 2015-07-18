<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

#Controller name must container Controller suffix
class DefaultController extends Controller{


	public function defaultAction(){
	#$host = null, $username = null, $password = null, $db_name = null
	     $this->showLayout('default');
	     
	     $smysql = new SimpleDB(SimpleDB::TYPE_MYSQL);
	     
	     $result  = $smysql->connect('localhost','root','root','test');
	     
	     if(!$result){ die($smysql->error); }
	     
	    # $result = $smysql->delete('users', array('id = ? OR id = ?',array(2,3)));
	     
	     $result = $smysql->insert('users',array('name' => 'john' ,'surname' =>'smith','id' => 2));
	     
	     
	     #$result = $smysql->execute("insert into users (name, surname, id) values (?, ?, ?)",array('this','guy',10));
	     /*
	     $result =  $smysql->update('users',
            array('name' => 'sdggdgsdgdss','surname' => 'PETERfdsfdsfsddfsSCHMIDT'),
            array("id=?",array(2))
	     );
	     */
	     if(!$result){
            echo (bool)$result.' FAIL';
            
            die($smysql->error);
         }else{
            echo 'success';
            print_r($result);
         }
	     
	     
	}
	
	public function aboutAction($name){
        App::setTheme('default');//assumed default
        $this->showLayout('default',['name' => $name]);
	}
	
	public function contactAction(){
	
        if(App::$_request->post('email')){
            echo 'YAY '.App::$_request->post('email');
        }else{
            echo 'NAY';
        }
        $this->showLayout('default');
    }
    
    public function testAction(){
        App::$_response->setStatusCode(404);
        App::$_response->send();
    }
}
