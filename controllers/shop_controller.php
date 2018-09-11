<?php

class Shop_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function package(){

    	$country = $this->model->query('location')->country->find( array('enabled'=>1) );
        $this->view->setData('countryList', $country['items'] );

        $this->view ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                    ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )

                    ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );

        // $this->view->setPage('icon', 'connectdevelop');
        // $this->view->setPage('title', 'ซีรี่ย์ทัวร์');



        $this->view->setData('listOpt', array(

            'data' => array(
                'bookingStatus' => $this->model->query('system')->booking_status()
            ),
            
            'keys' => array(
                0=>array('class'=>'td-no', 'text'=>'#', 'key'=>'seq'), 
                array('class'=>'td-status', 'text'=>'สถานะ', 'key'=>'status_arr'), 
                array('class'=>'td-traveling', 'text'=>'วันเดินทาง', 'key'=>'date_str'), 
                array('class'=>'td-bus td-number', 'text'=>'Bus', 'key'=>'bus_str', 'multiple'=>true), 
                array('class'=>'td-price', 'text'=>'ราคา', 'key'=>'price_str', 'multiple'=>true), 
                array('class'=>'td-seat td-number', 'text'=>'ที่นั่ง', 'key'=>'seat_str', 'multiple'=>true), 
                array('class'=>'td-booking td-number', 'text'=>'จอง', 'key'=>'bookingCountVal', 'multiple'=>true), 
                array('class'=>'td-accept', 'text'=>'รับได้', 'key'=>'wanted_str', 'multiple'=>true), 
                array('class'=>'td-number', 'text'=>'FP', 'key'=>'fullpayment', 'multiple'=>true), 
                array('class'=>'td-content', 'text'=>'Booking', 'key'=>'booking_str', 'multiple'=>true), 
                array('class'=>'td-content2', 'text'=>'W/L', 'key'=>'waitlist_str', 'multiple'=>true), 
                array('class'=>'td-actions', 'text'=>'Actions', 'key'=>'action_str', 'multiple'=>true)
            ),
        ));

        $this->view->render('shop/package/display'); 
    }
}