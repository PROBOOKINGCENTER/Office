<?php

class Accounting_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->error();
    }

    public function payment()
    {
        if( $this->format=='json' ) {

            $results = $this->model->query('payment')->find();

            $results['$items'] = $this->fn->q('listbox')->paymentRows($results['items'], $results['options']);        
            echo json_encode($results);
        }
        else{

            // print_r($this->model->query('payment')->find()); die;

            $this->view->setData('listOpt', array(
                'title' => 'Payment',
                'icon' => 'money',
                'datatable' => $this->fn->q('listbox')->paymentColumn(),

                'url' => URL.'accounting/payment',
                'is_float' => true,

                'controls' => array(

                      '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                    // , '<a class="btn" title="Show Sidebar" data-control-action="showsidebar">Sidebar</a>'
                ),
            ) );


            $this->view->setData('statusList', $this->model->query('payment')->status() );
            $this->view->setData('bookingStatusList', $this->model->query('system')->booking_status() );

            $this->view->setPage('on', 'accounting_payment');
            $this->view->setPage('icon', 'check-circle-o');
            $this->view->setPage('title', 'Payment');            
            $this->view->render('accounting/payment/lists/display');

            
        }
    	
    }
    
}