<?php



class AdminController extends Controller{

    
	public function defaultAction(){
		$this->showLayout('admin');
	}
	
	public function loginAction(){
        if( isset($_POST['email']) & isset($_POST['password']) ){
            
            $email = App::$_request->post('email');
            $password = App::$_request->post('password');
            $user_model = new UserModel();
            if($user_model->checkLogin($email,$password)){
                #redirect to default action
                
            }
        }else{
            #show login
        }
        
        $this->showLayout('admin');
        
	}
}
