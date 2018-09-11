<?php

class Sales_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->error();
    }

    public function due()
    {

        $this->view->setPage('icon', 'calendar-check-o');
        $this->view->setPage('title', 'Due Date Alert');


        $this->view->setPage('on', 'sales_due');
    	$this->view->render('sales/due/display');
    }

    public function payment()
    {
        $this->view->setPage('on', 'sales_payment_daily');
        $this->view->render('sales/payment/daily');
    }
}