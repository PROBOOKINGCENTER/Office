<?php

class Account_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->error();
    }

    public function logout()
    {
        if( empty($this->me) || empty($_POST) ){
            $this->error();
        }

        Session::init();
        Session::destroy();

        $redirect = !empty($_REQUEST['next']) ? $_REQUEST['next']: URL;
        Cookie::clear( COOKIE_KEY_USER );
        
        header('location:' . $redirect);
    }

    public function update($name='', $value='')
    {
        $name = !empty($_REQUEST['name']) ? $_REQUEST['name']: $name;
        $value = !empty($_REQUEST['value']) ? $_REQUEST['value']: $value;

        $post[$name] = trim($value);
        $this->model->query('users')->update( $this->me['id'], $post );

        echo json_encode(array('log'=> array('text'=>'Updated.'), 'post'=>$post));
    }
    public function settings()
    {
        
        $this->view->setPage('title', 'Personal account');
        // $this->view
        $this->view->setPage('on', 'my');
        $this->view->render("account/settings/display"); 
    }

}