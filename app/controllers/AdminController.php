<?php



class AdminController extends Controller{

    
	public function defaultAction(){
        if(Session::get('logged_in')){
          #  $this->showLayout('admin');
		}else{
            App::$_response->redirect(Loc::url('admin/login'));
		}
	}
	
	public function loginAction(){
        
        if( isset($_POST['email']) & isset($_POST['password']) ){
            $email = App::$_request->post('email',true);
            $password = App::$_request->post('password',true);
            $user_model = new UserModel();
            if($user_model->checkLogin($email,$password)){
                
                Session::set('logged_in',true);
                App::$_response->redirect(Loc::url('admin/default'));
            }else{
                #show login
                return array("error" => "Wrong username and password");
            }
        }
	}
	
	public function logoutAction(){
        Session::set('logged_in',false);
        App::$_response->redirect(Loc::url('admin/default'));
	}
	
	public function addPostAction(){
        if( isset($_POST['title']) & isset($_POST['content'])){
            $title = App::$_request->post('title',true);
            $content = App::$_request->post('content',true);
            $posts =  new PostModel;
            $result = $posts->add($title,$content);
        }
         
	}
	
	public function viewPostsAction(){
            $posts = new PostModel;
            $entries = $posts->getAll();
            return array('entries' => $entries);
	}
	
	public function editPostsAction(){
        if (isset($_POST['content']) && isset($_POST['id'])) {
            $posts = new PostModel;
            $posts->edit(App::$_request->post('id',false), 'content', App::$_request->post('content',true));
            App::$_response->redirect(Loc::url('admin/viewposts'));
        }
	}
}
