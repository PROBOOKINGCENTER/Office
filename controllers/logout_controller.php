<?php

class Logout_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->error();
    }

    public function admin() {
        
        if( empty($this->me) || $this->format != 'json' ){
            $this->error();
        }

        $this->view->setPage('theme', 'login');
        $this->view->render('confirm_logout');
    }

}