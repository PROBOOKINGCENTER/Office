<?php

class Notifications_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        

        $this->view->setPage('on', 'notifications');
        $this->view->render("notifications/display"); 
    }
}