<?php



class AdminController extends Controller{

    
	public function defaultAction(){
		$this->showLayout('admin');
	}
	
	public function loginAction(){
        if( isset($_POST['email']) & isset($_POST['password']) ){
            $email = App::$_request->post('email',true);
            $password = App::$_request->post('password',true);
            $user_model = new UserModel();
            if($user_model->checkLogin($email,$password)){
                App::$_response->redirect(Loc::url('admin/default'));
            }
        }else{
            #show login
            $this->showLayout('admin');
        }
	}
}
