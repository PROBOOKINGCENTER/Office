<?php

class Booking_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index($id='', $tab='')
    {

        /*$results = $this->model->query('tour')->codeList( array('country'=>1) );
        print_r($results); die;*/
        if( !empty($id) ){

            Session::init();
            $bookingOpt = Session::get('bookingOpt');
            if( empty($bookingOpt) ) $bookingOpt = array();

            if( $this->format=='json' ){
                $bookingOpt['tab'] = $tab;
                Session::set('bookingOpt', $bookingOpt);


                $item = $this->model->get( $id );

                if( $tab=='booking' || $tab=='payment' ){
                    $extraList = $this->model->extralist->find( array('booking'=> $id ) );
                }

                if( $tab=='booking' ){


                    $id = $item['per_id'];
                    $bus = $item['bus_no'];
                    if( empty($id) || empty($bus) ) $this->error();

                    $period = $this->model->query('tour')->period->get( $id, array('bus'=>$bus, 'with_booking'=>1) );
                    if( empty($period) ) $this->error();

                    $this->view->setData('settingForm', array(
                        'data' => $period,
                        'salesList' => $this->model->salesList(),
                        'agencyList' => $this->model->agencyList(),
                        'datePayment' => $this->model->setDatePayment($period['date_start'], $period['deposit'], $period['cancel_mode']),
                        'extraList' => $this->model->query('system')->extralists->find( array('enabled'=>1) ),
                        'extraListData' => $extraList,


                        'booking' => $item,


                        // 'roomTypeLists' => 
                    ) );
                }
                else if( $tab=='payment' ){

                    $this->view->setData( 'paymentList', $this->model->query('payment')->lists( array('booking'=>$id, 'status'=>array(0,1) ) ));
                    $this->view->setData( 'extraList', $extraList);
                    $this->view->setData( 'discountList', $this->model->discount->find( array('booking'=> $id ) ) );
                }
                

                $this->view->setData( 'item', $item );
                $this->view->render("booking/profile/tabs/{$tab}");

            }
            else{

                if( empty($tab) ){

                    $tab = !empty($bookingOpt['tab'])? $bookingOpt['tab']: 'booking';
                }
                else{
                    Session::set('bookingOpt', $bookingOpt);
                }

                
                $item = $this->model->get( $id );
                if( empty($item) ) $this->error();


                $title = $item['code'];
                $title .= '<span class="ui-status" style="vertical-align: top;margin-left: 8px;margin-top: 2px;background-color:'.$item['status_arr']['color'].'">'.$item['status_arr']['name'].'</span>';

                if( $item['is_guarantee'] ){
                    $title .= '<i class="mlm fc-red fc-blink icon-thumbs-up"></i>';
                }

                $tabs = array();
                $tabs[] = array('id'=>'booking', 'name'=>'รายละเอียดการจอง', 'link'=>URL."booking/{$item['id']}/booking");
                $tabs[] = array('id'=>'payment', 'name'=>'การชำระเงิน', 'link'=>URL."booking/{$item['id']}/payment");
                $tabs[] = array('id'=>'traveler', 'name'=>'ข้อมูลผู้เดินทาง', 'link'=>URL."booking/{$item['id']}/traveler");

                $opt = array(
                    'title' => 'จัดการจองทัวร์',
                    'controls' => array(
                          '<a class="btn" title="Refresh List" data-control-action="refresh"><i class="icon-refresh"></i></a>'
                        // , '<a class="btn" title="Show Sidebar" data-control-action="showsidebar">Sidebar</a>'
                    ),
                );

                $opt['tab'] = array(
                    'current' => $tab,
                    'items' => $tabs
                );


                $this->view->setData( 'opt', $opt );
                $breadcrumps[] = array('text' => 'จองทัวร์', 'link' => URL."booking");

                $this->view->setData( 'tab', $tab );
                $this->view->setData( 'item', $item );
                $this->view->render('booking/profile/display');
            }
        }
        else{

            // print_r($this->model->find()); die;
            $this->view->setPage('icon', 'check-square-o');
            $this->view->setPage('title', 'Booking');


            $this->view->setData('listOpt', array(
                'title' => 'Booking',
                'icon' => 'check-square-o',
                'datatable' => $this->fn->q('listbox')->table_booking_col(),

                'url' => URL.'booking/lists',
                'is_float' => true,

                'controls' => array(

                      '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                    // , '<a class="btn" title="Show Sidebar" data-control-action="showsidebar">Sidebar</a>'
                ),
            ) );


            $this->view 

                    ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                    ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )

                    ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );


            $this->view->setData( 'statusList', $this->model->query('system')->booking_status() );
            $this->view->setData( 'countryList', $this->model->query('tour')->countryList() );
            $this->view->setData( 'airlineList', $this->model->query('tour')->airlineList() );
            $this->view->setData( 'salesList', $this->model->salesList() );


            $this->view->render('booking/lists/display');
        }
    }

    public function lists()
    {
    	// sleep(10);

        /*$this->view->setData( 'statusList', $this->model->query('system')->booking_status() );
    	$this->view->setData( 'results',  $this->model->find() );
        $this->view->render("booking/lists/json");*/

        $results = $this->model->find();
        // print_r($results); die;

        $results['$items'] = $this->fn->q('listbox')->booking_tour_rows($results['items'], $results['options']);        
        // $this->view->render("tour/ajax/items");
        echo json_encode($results);

    }



    public function create($id=null, $bus=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        if( empty($id) || empty($bus) ) $this->error();

        $item = $this->model->query('tour')->period->get( $id, array('bus'=>$bus, 'with_booking'=>1) );
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        // print_r( $item ); die; 

        $this->view->setData('settingForm', array(
            'data' => $item,
            'salesList' => $this->model->salesList(),
            'agencyList' => $this->model->agencyList(),
            'datePayment' => $this->model->setDatePayment($item['date_start'], $item['deposit'], $item['cancel_mode']),
            'extraList' => $this->model->query('system')->extralists->find( array('enabled'=>1) )
        ) );
        
        if( $this->format == 'json' ){
            $this->view->setPage('path', 'Themes/admin/forms/booking');
            $this->view->render("form");
        }
        else{

            $this->view->elem('body')->addClass('page');
            $this->view->render("booking/create/display");
        }
    }
    public function save()
    {
        if( empty($_POST) || empty($this->me) ) $this->error();
        // echo '<pre>'; print_r($_POST); echo '</pre>'; die;
        
        $periodID = isset($_POST['per_id'])? $_POST['per_id']: '';
        $bus = isset($_POST['bus_no']) ? $_POST['bus_no']: '';

        $item = $this->model->query('tour')->period->get($periodID, array('bus'=>$bus, 'with_booking'=>1) );
        if( empty($item) ) $this->error();
        // echo '<pre>'; print_r($item); echo '</pre>'; die;

        $SUM = array('subtotal'=>0, 'discount'=>0, 'total'=>0, 'pax'=>0 );

        $datePayment = $this->model->setDatePayment($item['date_start'], $item['deposit'], $item['cancel_mode']);
        // echo '<pre>'; print_r($datePayment); echo '</pre>'; die;

        # รายการ พิเศษ
        $postExtra = isset($_POST['extra']) ? $_POST['extra']: array();
        $extraList = array(); $extraAmount = 0; $seq =0;
        if( !empty($postExtra) ){
            for ($i=0; $i < count($postExtra['name']); $i++) {

                if( empty($postExtra['name'][$i]) || empty($postExtra['qty'][$i]) ) continue;
                $seq ++;

                $price = str_replace(',', '', $postExtra['price'][$i]);
                $total = $price*$postExtra['qty'][$i];

                $extraAmount += $total;

                $_extra = array(
                    'extra_name' => $postExtra['name'][$i],
                    'extra_price' => $price,
                    'extra_value' => $postExtra['qty'][$i],
                    'extra_total' => $total,
                    'extra_seq' => $seq,
                );

                if( isset($postExtra['id'][$i]) ){
                    $_extra['extra_aid'] = $postExtra['id'][$i];
                }
                $extraList[] = $_extra;
            }
        }

        # จำนวนผู้เดินทาง
        $traveler = array();
        $traveler[] = array('id'=>'adult', 'name'=> 'Adult', 'price'=> $item['price_1'], 'is_pax'=>1);
        $traveler[] = array('id'=>'child', 'name'=> 'Child', 'price'=> $item['price_2'], 'is_pax'=>1);
        $traveler[] = array('id'=>'childNoBed', 'name'=> 'Child No Bed', 'price'=> $item['price_3'], 'is_pax'=>1);
        $traveler[] = array('id'=>'infant', 'name'=> 'Infant', 'price'=> $item['price_4']);
        $traveler[] = array('id'=>'joinland', 'name'=> 'Joinland', 'price'=> $item['price_5'], 'is_pax'=>1);

        $seq = 0;
        $bookingDetail = array(); $totalSeat = 0;
        foreach ($traveler as $key => $value) {
            $seq++;
            
            if( !empty($_POST['traveler'][$value['id']]) ){
                $totalSeat += $_POST['traveler'][$value['id']];

                $SUM['subtotal'] += $value['price']*$_POST['traveler'][$value['id']];
            }
            else{
                // $arr['error'][$value['id']] = $value['error'];
            }

            $val = !empty($_POST['traveler'][$value['id']])? $_POST['traveler'][$value['id']]: 0;
            if( $val==0 ) continue;
            
            $bookingDetail[] = array(
                'book_list_code' => $seq,
                'book_list_name' => $value['name'],
                'book_list_qty' => $val,
                'book_list_price' => $value['price'],
                'book_list_total' => $val*$value['price']
            );

            if( !empty($value['is_pax']) ){
                $SUM['pax'] += $val;
            }
        }

        if( $totalSeat <= 0 ){
            $arr['error']['traveler'] = 'ใส่จำนวนที่นั่ง';

        }
        else if( $totalSeat > $item['bus']['wanted'] ){
            $arr['error']['traveler'] = "จำนวนที่นั่งไม่พอ<br> คุณสามารถจองได้ {$item['bus']['wanted']} ที่นั่งเท่านั้น";
        }

        // Room Type
        $SUM['subtotal'] += $item['single_charge'] * !empty( $_POST['room']['single'] ) ? $_POST['room']['single']: 0;

        # Discount
        $discountExtra = 0;
        if( !empty($_POST['discount_extra']) ){
            $discountExtra = $item["discount_extra"] * $totalSeat;
        }

        $promotion=$this->model->getPromotion( date("Y-m-d") );
        if( $promotion > 0 ){
            $discountExtra += $promotion*$totalSeat;
        }


        $comOffice = $item['com_company_agency']*$totalSeat;
        $comAgency = $item['com_agency']*$totalSeat;

        $SUM['discount'] += $totalSeat*$item['discount']; // โปรไฟไหม้
        $SUM['discount'] += $comOffice;
        $SUM['discount'] += $comAgency;

        $SUM['discount'] += $discountExtra;


        $status = $item['bus']['wanted']<=0 ? '05': '10'; // 00 = จอง, 05=รอ

        // echo '<pre>'; print_r($SUM); echo '</pre>'; die;

        $verify = array();
        $verify['sales'] = array('err'=>'กรุณาเลือก Sale Contact');
        $verify['company'] = array('err'=>'ใส่ข้อมูลในช่องนี้');
        $verify['agent'] = array('err'=>'ใส่ข้อมูลในช่องนี้');
        $verify['book_cus_name'] = array('err'=>'ใส่ชื่อลูกค้า');
        $verify['book_cus_tel'] = array('err'=>'ใส่เบอร์โทรลูกค้า');
        foreach ($verify as $key => $value) {
            if( isset($_POST[$key]) ){
                // $postData[$key] = $_POST[$key];

                if( empty($_POST[$key]) && $value['err'] ){
                    $arr['error'][$key] = $value['err'];
                }
            }
        }


        if( empty($arr['error']) ){

            if( empty($_POST['confirm']) ){

                // $arr['data'] = $this->model->query('tour')->period->get( $periodID, array('bus'=>$bus, 'with_booking'=>1) );

                $arr['actions'] = array(
                    'call' => "bookConfirm",
                );
            }
            else{
                $prefixNumber = $this->model->prefixNumber();

                $booking = !empty($prefixNumber['pre_booking'])? intval($prefixNumber['pre_booking']): 1;
                $invoice = !empty($prefixNumber['pre_invoice'])? intval($prefixNumber['pre_invoice']): 1;
                $year = !empty($prefixNumber['pre_year'])? intval($prefixNumber['pre_year']): date('Y');
                $month = !empty($prefixNumber['pre_month'])? intval($prefixNumber['pre_month']): date('m');

                $running_booking = sprintf("%04s", $booking);
                $running_invoice = sprintf("%04s", $invoice);

                $year = substr(sprintf("%02d", $year), 2);
                $month = sprintf("%02d", $month);
                $bookCode = "B{$year}/{$month}{$running_booking}";

                // echo '<pre>'; print_r($SUM); echo '</pre>';

                $SUM['total'] = ($extraAmount+$SUM['subtotal'])-$SUM['discount'];

                /*-- insert: booking --*/
                $bookPost = array(
                    "book_code"=>$bookCode, // running_booking
                    "book_date"=>date('c'), // date now
                    "invoice_code"=>"I{$year}/{$month}{$running_invoice}", // running_invoice
                    "invoice_date"=>date('c'), // date now
                    "agen_id"=>$this->me['id'], // login: id
                    "user_id"=>$_POST['sales'], // POST: sale_id
                    "per_id"=>$periodID, // period: id
                    "bus_no"=> $bus,  // POST: bus


                    "book_master_deposit"=>$datePayment['deposit']['value']*$totalSeat, // จำนวนเงินที่ต้องมัดจำ Master
                    "book_due_date_deposit"=>$datePayment['deposit']['date'], // กำหนดจ่ายเงินมัดจำ
                    "book_master_full_payment"=>$SUM['total']-$datePayment['deposit']['value'], // จำนวนเงินที่ต้องจ่ายเต็ม Master
                    "book_due_date_full_payment"=>$datePayment['fullpayment']['date'], // กำหนดจ่ายเงิน Full payment

                    "status"=>$status,
                    "book_total"=>$SUM['subtotal'], // book_total // ยอดรวมรายการทั้งหมด
                    "book_discount"=> $SUM['discount'], // หากมีส่วนลดเพิ่มเติมจาก Period
                    "book_amountgrandtotal"=> $SUM['total'], // book_amountgrandtotal ยอดรวมสุทธิ
                    "book_comment"=> trim($_POST['book_comment']), // POST: comment

                    "book_com_agency_company"=> $comOffice,  // period: per_com_company_agency
                    "book_com_agency"=> $comAgency, // period: per_com_agency

                    "book_room_twin"=>$_POST['room']['twin'], 
                    "book_room_double"=>$_POST['room']['double'], 
                    "book_room_triple"=>$_POST['room']['triple'], 
                    "book_room_tripletwin"=>$_POST['room']['tripletwin'], 
                    "book_room_single"=>$_POST['room']['single'], 

                    "book_pax"=>$SUM['pax'], 
                    "book_extralist_total"=>$extraAmount, 

                    "create_date"=>date('c'),
                    "create_user_id"=>$this->me['id'],

                    "update_date"=>date('c'),
                    "update_user_id"=>$this->me['id'],

                    "book_cus_name"=>$_POST['book_cus_name'],
                    "book_cus_tel"=>$_POST['book_cus_tel'],

                    "remark"=>trim($_POST['remark']),
                    "book_is_guarantee"=> !empty($_POST['book_is_guarantee']) ? $_POST['book_is_guarantee']: '',
                );

                /*-- Insert: booking --*/
                // echo '<pre>'; print_r($bookPost); echo '</pre>'; die;
                $this->model->insert($bookPost);

                if( !empty($bookPost['id']) ){

                    /*-- Insert: booking_list --*/
                    // echo '<pre>'; print_r($bookingDetail); echo '</pre>';
                    foreach ($bookingDetail as $key => $value) {

                        $value['create_user_id'] = $this->me['id'];
                        $value['create_date'] = date('c');
                        
                        $value['update_user_id'] = $this->me['id'];
                        $value['update_date'] = date('c');

                        $value['book_code'] = $bookCode;
                        $this->model->detail->insert($value);
                    }


                    foreach ($extraList as $key => $value) {
                        
                        $value['create_uid'] = $this->me['id'];
                        $value['create_date'] = date('c');
                        
                        $value['update_uid'] = $this->me['id'];
                        $value['update_date'] = date('c');

                        $value['extra_bid'] = $bookPost['id'];
                        $this->model->extralist->insert($value);
                    }

                    /* -- update: prefixnumber -- */
                    $this->model->prefixNumberUpdate(1, array(
                        'pre_booking' => $booking+1,
                        'pre_invoice' => $invoice+1
                    ) );

                    $arr['message'] = 'Saved.';
                    $arr['data'] = $this->model->query('tour')->period->get( $periodID, array('bus'=>$bus, 'with_booking'=>1) );

                    $arr['actions'] = array(
                        'call' => "nBook",
                    );
                }
                else{
                    $arr['message'] = 'การจองล้มเหลว, ลองใหม่';
                    $arr['error'] = 1;
                }
            }
        }

        // echo '<pre>'; print_r($arr); echo '</pre>'; die;
        echo json_encode($arr);
    }

    



    public function tabs($id='', $tab='')
    {
        Session::init();

        $bookingOpt = Session::get('bookingOpt');
        if( empty($bookingOpt) ) $bookingOpt = array();

        $bookingOpt['tab'] = $tab;
        Session::set('bookingOpt', $bookingOpt);
        
        // $this->view->render("tabs/");
        // $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        $item = $this->model->get( $id );

        if( $tab=='booking' || $tab=='payment' ){
            $extraList = $this->model->extralist->find( array('booking'=> $id ) );
        }

        if( $tab=='booking' ){


            $id = $item['per_id'];
            $bus = $item['bus_no'];
            if( empty($id) || empty($bus) ) $this->error();

            $period = $this->model->query('tour')->period->get( $id, array('bus'=>$bus, 'with_booking'=>1) );
            if( empty($period) ) $this->error();

            $this->view->setData('settingForm', array(
                'data' => $period,
                'salesList' => $this->model->salesList(),
                'agencyList' => $this->model->agencyList(),
                'datePayment' => $this->model->setDatePayment($period['date_start'], $period['deposit'], $period['cancel_mode']),
                'extraList' => $this->model->query('system')->extralists->find( array('enabled'=>1) ),
                'extraListData' => $extraList,


                'booking' => $item,


                // 'roomTypeLists' => 
            ) );
        }
        else if( $tab=='payment' ){

            $this->view->setData( 'paymentList', $this->model->query('payment')->lists( array('booking'=>$id, 'status'=>array(0,1) ) ));
            $this->view->setData( 'extraList', $extraList);
            $this->view->setData( 'discountList', $this->model->discount->find( array('booking'=> $id ) ) );
        }
        

        $this->view->setData( 'item', $item );
        $this->view->render("booking/profile/tabs/{$tab}");
    }

    public function update()
    {
        // booking_verify_form
        
        if( empty($_POST) || empty($this->me) ) $this->error();
        // echo '<pre>'; print_r($_POST); echo '</pre>'; die;

        $id = isset($_POST['per_id'])? $_POST['per_id']: '';
        $item = $this->model->get( $id );
        if( empty($item) ) $this->error();
        // echo '<pre>'; print_r($item); echo '</pre>'; die;



        $periodID = isset($_POST['per_id'])? $_POST['per_id']: '';
        $bus = isset($_POST['bus_no']) ? $_POST['bus_no']: '';

        $period = $this->model->query('tour')->period->get($periodID, array('bus'=>$bus) );
        if( empty($period) ) $this->error();
        // echo '<pre>'; print_r($period); echo '</pre>'; die;
        // , $item['create_date']


        $SUM = array('subtotal'=>0, 'discount'=>0, 'total'=>0, 'pax'=>0 );
        $datePayment = $this->model->setDatePayment($period['date_start'], $period['deposit'], $period['cancel_mode']);
        // echo '<pre>'; print_r($datePayment); echo '</pre>'; die;


        # รายการ พิเศษ
        $postExtra = isset($_POST['extra']) ? $_POST['extra']: array();
        $extraList = array(); $extraAmount = 0; $seq =0;
        if( !empty($postExtra) ){
            for ($i=0; $i < count($postExtra['name']); $i++) {

                if( empty($postExtra['name'][$i]) || empty($postExtra['qty'][$i]) ) continue;
                $seq ++;

                $price = str_replace(',', '', $postExtra['price'][$i]);
                $total = $price*$postExtra['qty'][$i];

                $extraAmount += $total;

                $_extra = array(
                    'extra_name' => $postExtra['name'][$i],
                    'extra_price' => $price,
                    'extra_value' => $postExtra['qty'][$i],
                    'extra_total' => $total,
                    'extra_seq' => $seq,
                );

                if( isset($postExtra['id'][$i]) ){
                    $_extra['extra_aid'] = $postExtra['id'][$i];
                }
                $extraList[] = $_extra;
            }
        }
        // echo '<pre>'; print_r($extraList); echo '</pre>'; die;

        # จำนวนผู้เดินทาง
        $price_values = $period['bus']['price_values'];
        // echo '<pre>'; print_r($price_values); echo '</pre>'; die;

        $seq = 0;
        $travelerPost = $_POST['traveler'];
        $bookingDetail = array(); $totalSeat = 0;
        foreach ($price_values as $key => $value) {
            $seq++;
            
            if( !empty($travelerPost[$value['id']]) ){
                $totalSeat += $travelerPost[$value['id']];

                $SUM['subtotal'] += $value['price']*$travelerPost[$value['id']];
            }
            else{
                // $arr['error'][$value['id']] = $value['error'];
            }

            $val = !empty($travelerPost[$value['id']])? $travelerPost[$value['id']]: 0;
            if( $val==0 ) continue;
            
            $bookingDetail[] = array(
                'book_list_code' => $seq,
                'book_list_name' => $value['name'],
                'book_list_qty' => $val,
                'book_list_price' => $value['price'],
                'book_list_total' => $val*$value['price']
            );

            if( !empty($value['is_pax']) ){
                $SUM['pax'] += $val;
            }
        }
        // echo '<pre>'; print_r($bookingDetail); echo '</pre>'; die;


        # rooms of type
        $room_of_type = $this->model->query('system')->_roomOfType();
        $roomPost = $_POST['room'];
        $roomList = array();
        // echo '<pre>'; print_r($_POST['room']); echo '</pre>'; die;
        // echo '<pre>'; print_r($room_of_type); echo '</pre>'; die;

        $paxVal = 0;
        foreach ($room_of_type as $key => $value) {
            if( !empty($roomPost[$value['id']]) ){
                $paxVal+=$roomPost[$value['id']]*$value['quota'];
            }
        }

        if( $SUM['pax']!=$paxVal ){
            $arr['error']['room'] = 'จำนวนห้องพักไม่ถูกต้อง';
        }



        // $price_values = $period['bus']['single_charge'];

        /*if( $totalSeat <= 0 ){
            $arr['error']['traveler'] = 'ใส่จำนวนที่นั่ง';

        }
        else if( $totalSeat > $item['bus']['wanted'] ){
            $arr['error']['traveler'] = "จำนวนที่นั่งไม่พอ<br> คุณสามารถจองได้ {$item['bus']['wanted']} ที่นั่งเท่านั้น";
        }*/

        // Room Type
        $SUM['subtotal'] += $period['bus']['single_charge'] * !empty( $_POST['room']['single'] ) ? $_POST['room']['single']: 0;

        /*# Discount
        $discountExtra = 0;
        if( !empty($_POST['discount_extra']) ){
            $discountExtra = $item["discount_extra"] * $totalSeat;
        }

        $promotion=$this->model->getPromotion( date("Y-m-d") );
        if( $promotion > 0 ){
            $discountExtra += $promotion*$totalSeat;
        }


        $comOffice = $item['com_company_agency']*$totalSeat;
        $comAgency = $item['com_agency']*$totalSeat;

        $SUM['discount'] += $totalSeat*$item['discount']; // โปรไฟไหม้
        $SUM['discount'] += $comOffice;
        $SUM['discount'] += $comAgency;

        $SUM['discount'] += $discountExtra;


        $status = $item['bus']['wanted']<=0 ? '05': '10'; // 00 = จอง, 05=รอ

        // echo '<pre>'; print_r($SUM); echo '</pre>'; die;

        */

        

        # Discount
        $discountPost = isset($_POST['discount']) ? $_POST['discount']: array();
        $discountList = array();
        if( !empty($discountPost) ){
            
            $seq = 0;
            for ($i=0; $i < count($discountPost['name']); $i++) {
                $seq++;

                $price = str_replace(',', '', $discountPost['price'][$i]);
                $value = isset($discountPost['value'][$i]) ?$discountPost['value'][$i]: 0;
                $total = $price*$value;

                $_discount = array(
                    'disc_name' => $discountPost['name'][$i],
                    'disc_price' => $price,
                    'disc_value' => $value,
                    'disc_total' => $total,
                    'disc_seq' => $seq,
                );

                $SUM['discount'] += $total;
                $discountList[] = $_discount;
            }
        }

        // echo '<pre>'; print_r($discountList); echo '</pre>'; die;
        $discountExtra = !empty($_POST['discount_extra']) ? $_POST['discount_extra']: 0;
        $SUM['discount'] += $discountExtra; 



        # Input Verify
        $inputVerify = array();
        $inputVerify['sales'] = array('err'=>'กรุณาเลือก Sale Contact');
        $inputVerify['company'] = array('err'=>'ใส่ข้อมูลในช่องนี้');
        $inputVerify['agent'] = array('err'=>'ใส่ข้อมูลในช่องนี้');
        $inputVerify['book_cus_name'] = array('err'=>'ใส่ชื่อลูกค้า');
        $inputVerify['book_cus_tel'] = array('err'=>'ใส่เบอร์โทรลูกค้า');
        foreach ($inputVerify as $key => $value) {
            if( isset($_POST[$key]) ){
                // $postData[$key] = $_POST[$key];

                if( empty($_POST[$key]) && $value['err'] ){
                    $arr['error'][$key] = $value['err'];
                }
            }
        }

        if( empty($arr['error']) ){

            echo 'submit'; die; 

            $SUM['total'] = ($extraAmount+$SUM['subtotal'])-$SUM['discount'];
            echo '<pre>'; print_r($SUM); echo '</pre>'; die;


            $bookPost = array(
                    "book_code"=>$bookCode, // running_booking
                    "book_date"=>date('c'), // date now
                    "invoice_code"=>"I{$year}/{$month}{$running_invoice}", // running_invoice
                    "invoice_date"=>date('c'), // date now
                    "agen_id"=>$this->me['id'], // login: id
                    "user_id"=>$_POST['sales'], // POST: sale_id
                    "per_id"=>$periodID, // period: id
                    "bus_no"=> $bus,  // POST: bus


                    "book_master_deposit"=>$datePayment['deposit']['value']*$totalSeat, // จำนวนเงินที่ต้องมัดจำ Master
                    "book_due_date_deposit"=>$datePayment['deposit']['date'], // กำหนดจ่ายเงินมัดจำ
                    "book_master_full_payment"=>$SUM['total']-$datePayment['deposit']['value'], // จำนวนเงินที่ต้องจ่ายเต็ม Master
                    "book_due_date_full_payment"=>$datePayment['fullpayment']['date'], // กำหนดจ่ายเงิน Full payment

                    "status"=>$status,
                    "book_total"=>$SUM['subtotal'], // book_total // ยอดรวมรายการทั้งหมด
                    "book_discount"=> $SUM['discount'], // หากมีส่วนลดเพิ่มเติมจาก Period
                    "book_amountgrandtotal"=> $SUM['total'], // book_amountgrandtotal ยอดรวมสุทธิ
                    "book_comment"=> trim($_POST['book_comment']), // POST: comment

                    "book_com_agency_company"=> $comOffice,  // period: per_com_company_agency
                    "book_com_agency"=> $comAgency, // period: per_com_agency

                    "book_room_twin"=>$_POST['room']['twin'], 
                    "book_room_double"=>$_POST['room']['double'], 
                    "book_room_triple"=>$_POST['room']['triple'], 
                    "book_room_tripletwin"=>$_POST['room']['tripletwin'], 
                    "book_room_single"=>$_POST['room']['single'], 

                    "book_pax"=>$SUM['pax'], 
                    "book_extralist_total"=>$extraAmount, 

                    "create_date"=>date('c'),
                    "create_user_id"=>$this->me['id'],

                    "update_date"=>date('c'),
                    "update_user_id"=>$this->me['id'],

                    "book_cus_name"=>$_POST['book_cus_name'],
                    "book_cus_tel"=>$_POST['book_cus_tel'],

                    "remark"=>trim($_POST['remark']),
                    "book_is_guarantee"=> !empty($_POST['book_is_guarantee']) ? $_POST['book_is_guarantee']: '',
                );

                /*-- Insert: booking --*/
                echo '<pre>'; print_r($bookPost); echo '</pre>'; die;

        }

        echo '<pre>'; print_r($arr); echo '</pre>'; die;

        /*$traveler = array();
        $traveler[] = array('id'=>'adult', 'name'=> 'Adult', 'price'=> $item['price_1'], 'is_pax'=>1);
        $traveler[] = array('id'=>'child', 'name'=> 'Child', 'price'=> $item['price_2'], 'is_pax'=>1);
        $traveler[] = array('id'=>'childNoBed', 'name'=> 'Child No Bed', 'price'=> $item['price_3'], 'is_pax'=>1);
        $traveler[] = array('id'=>'infant', 'name'=> 'Infant', 'price'=> $item['price_4']);
        $traveler[] = array('id'=>'joinland', 'name'=> 'Joinland', 'price'=> $item['price_5'], 'is_pax'=>1);*/
    }


    /**/
    /* actions */
    /**/
    public function cancel($id='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->get( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            // print_r($_POST); die;

            try {
                $form = new Form();
                $form   ->post('status_cancel')
                        ->post('book_cancel')
                        ->post('remark_cancel')->val('is_empty');

                $form->submit();
                $postData = $form->fetch();


                if( empty($arr['error']) ){

                    $postData['cancel_date'] = date('c');
                    $postData['cancel_by'] = 'User by: ' . $this->me['id'];
                    $postData['cancel_user_id'] = $this->me['id'];
                    $postData['cancel_user_id'] = $this->me['id'];
                    $postData['status'] = 40;
                    $this->model->update( $id, $postData );

                    $arr['message'] = 'ยิกเลิกการจอง เรียบร้อยแล้ว';
                    $arr['redirect'] = URL.'booking';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            $this->view->setPage('path', 'Themes/admin/forms/booking');
            $this->view->render("cancel");
        }
    }
    public function payment($id='')
    {
        $id = isset($_REQUEST['id'])? $_REQUEST['id']: $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            try {
                $form = new Form();
                $form   ->post('bankbook_id')->val('is_empty')
                        ->post('pay_date')
                        ->post('pay_time')
                        ->post('pay_received')
                        ->post('remark');

                $form->submit();
                $postData = $form->fetch();

                if( empty($_POST['status']) ){
                    $arr['error']['status'] = 'ระบุสถานะ';
                }
                else{
                    $postData['book_status'] = $_POST['status'];
                }

                if( empty($_FILES['pay_url_file']) ){
                    $arr['error']['pay_url_file'] = 'กรุณาแนบไฟล์';
                }else{
                    $userfile = $_FILES['pay_url_file'];

                    $err='';
                    if(!$this->fn->q('file')->validate($err, $userfile, array('key'=>'pay_url_file', 'type'=>'img') ) ){
                        $arr['error']['pay_url_file'] = $err;
                    };
                }


                if( empty($arr['error']) ){

                    $postData['create_date'] = date('c');
                    $postData['create_user_id'] = $this->me['id'];

                    $postData['update_date'] = date('c');
                    $postData['update_user_id'] = $this->me['id'];

                    $postData['book_id'] = $id;
                    $this->model->query('payment')->insert( $postData );


                    // upload file
                    $folder = '../upload/payment/';
                    $directory = WWW_UPLOADS.'payment/';

                    $source = $userfile['tmp_name'];
                    $filename = $userfile['name'];

                    $filename = $this->fn->q('file')->createName($filename, $postData['id'], 'img', $this->me['id'] );
                    if( copy($source, $directory.$filename) ){

                        // 
                        $this->model->query('payment')->update( $postData['id'], array('pay_url_file'=>$folder.$filename) );
                    }


                    $arr['message'] = 'แจ้งการชำระเงิน เรียบร้อยแล้ว';
                    // $arr['redirect'] = URL.'booking';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);            
        }
        else{

            $this->view->setData('bookbank', $this->model->query('system')->bookbank->find( array('status'=>1) ) );

            $this->view->setData('item', $item );
            $this->view->setPage('path', 'Themes/admin/forms/booking');
            $this->view->render("payment");
        }
    }
    public function send_invoice($id='')
    {
        $id = isset($_REQUEST['id'])? $_REQUEST['id']: $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

        }
        else{
            $this->view->setData('item', $item );
            $this->view->setPage('path', 'Themes/admin/forms/booking');
            $this->view->render("send_invoice");
        }
    }
}