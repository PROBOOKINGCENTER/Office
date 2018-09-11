<?php

class Document_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
    	
    	$this->view->setPage('on', 'document');
        $this->view->setPage('icon', 'files-o');
        $this->view->setPage('title', 'Document');

        $this->view->render('document/lists/display');
    }

}