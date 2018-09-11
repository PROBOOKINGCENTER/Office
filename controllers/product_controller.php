<?php

class Product_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function package($action=''){

        /*$results = $this->model->query('tour')->find( array('limit'=>2) );
        foreach ($results['items'] as $key => $value) {

            $results['items'][$key]['periodList'] = $this->model->query('tour')->period->lists( array(
                'series' => $value['id'],
                'sort' => 'per_date_start', 
                'dir' => 'asc',
                'state'=> array(0,1,2),
                'with_booking'=>1,
            ) );
        }
        print_r($results); die;*/

        if( $action=='lists' && $this->format=='json' ){
            
            $results = $this->model->query('tour')->find();

            foreach ($results['items'] as $key => $value) {
                
                $results['items'][$key]['periodList'] = $this->model->query('tour')->period->lists( array(
                    'series' => $value['id'],
                    'sort' => 'per_date_start', 
                    'dir' => 'asc',
                    'state'=> array(0,1,2),
                    'with_booking'=>1,
                ) );
            }

            echo json_encode($results);
        }
        else if($action=='period' && $this->format=='json'){

            $results = $this->model->query('tour')->period->lists();
            echo json_encode($results);
        }
        else{
        	$country = $this->model->query('location')->country->find( array('enabled'=>1) );
            $this->view->setData('countryList', $country['items'] );

            $this->view ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                        ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )

                        ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );


            // $results = $this->model->query('tour')->find();
            // print_r($results); die;

            // $this->view->setPage('icon', 'connectdevelop');
            // $this->view->setPage('title', 'ซีรี่ย์ทัวร์');


            $this->view->setData( 'countryList', $this->model->query('tour')->countryList() );
            $this->view->setData( 'airlineList', $this->model->query('tour')->airlineList() );

            $this->view->setData('listOpt', array(
                'url' => URL.'product/package/lists',
                'period_url' => URL.'product/package/period',
                'data' => array(
                    'bookingStatus' => $this->model->query('system')->booking_status()
                ),
                
                'keys' => array(
                    0=>array('class'=>'td-no', 'text'=>'#', 'key'=>'seq'), 
                    // array('class'=>'td-status', 'text'=>'สถานะ', 'key'=>'status_arr'), 
                    array('class'=>'td-traveling', 'text'=>'Period', 'key'=>'date_str'), 
                    array('class'=>'td-bus td-number', 'text'=>'Bus', 'key'=>'bus_str', 'multiple'=>true), 
                    array('class'=>'td-status', 'text'=>'Status', 'key'=>'bus_status_str', 'multiple'=>true), 
                    array('class'=>'td-price', 'text'=>'ราคา', 'key'=>'price_str', 'multiple'=>true), 
                    array('class'=>'td-seat td-number', 'text'=>'ที่นั่ง', 'key'=>'seat', 'multiple'=>true), 
                    array('class'=>'td-booking td-number', 'text'=>'จอง', 'key'=>'bookingCountVal', 'multiple'=>true), 
                    array('class'=>'td-accept', 'text'=>'รับได้', 'key'=>'seat_balance', 'multiple'=>true), 
                    array('class'=>'td-number', 'text'=>'FP', 'key'=>'fullpayment', 'multiple'=>true), 
                    array('class'=>'td-content', 'text'=>'Booking', 'key'=>'booking_str', 'multiple'=>true), 
                    array('class'=>'td-content2', 'text'=>'W/L', 'key'=>'waitlist_str', 'multiple'=>true), 
                    array('class'=>'td-actions', 'text'=>'Actions', 'key'=>'action_str', 'multiple'=>true)
                ),
            ));



            $this->view->render('product/package/display'); 
        }
    }
}