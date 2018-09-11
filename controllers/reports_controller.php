<?php

class Reports_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index($section='sales_payment')
    {
    	
    	$this->view->setPage('on', 'reports');
        $this->view->setPage('icon', 'line-chart');
        $this->view->setPage('title', 'Reports');

        $this->view->setData('section', $section);

        $this->view ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                    ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )

                    ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );


        // print_r($this->model->query('system')->bookbank->lists()); die;
        $this->view->setData('bankbookList', $this->model->query('system')->bookbank->lists() );
        $this->view->setData('salesList', $this->model->query('booking')->salesList() );
        $this->view->setData('agencyList', $this->model->query('booking')->agencyList() );

        
        $this->view->setData('countryList', $this->model->query('location')->country->lists( array('enabled'=>1) ) );


        $this->view->render('reports/display');
    }

    public function sales($tab='payment')
    {
    	
    	$this->index("sales_{$tab}");
    }

    public function summary($tab='country')
    {
    	$this->index("summary_{$tab}");
    }

    public function invoice()
    {
    	$this->index("summary_invoice");
    }



    public function export( )
    {
        print_r($_POST); 
    }
}