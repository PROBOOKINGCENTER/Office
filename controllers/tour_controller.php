<?php

class Tour_Controller extends Controller {

    public function __construct() {
        parent::__construct();

        $this->fileOpt = array(
            'word' => array( 'key'=>'file_word', 'type'=>'word', 'field'=>'ser_url_word'),
            'pdf' => array( 'key'=>'file_pdf', 'type'=>'pdf', 'field'=>'ser_url_pdf' ),
            'banner' => array( 'key'=>'file_image', 'type'=>'img', 'field'=>'ser_url_img_1' )
        );
    }

    public function index() {

        /*$results = $this->model->query('tour')->find( array() );
        print_r($results); die;*/
        
        $country = $this->model->query('location')->country->find( array('enabled'=>1) );
        $this->view->setData('countryList', $country['items'] );

        $this->view ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                    ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )

                    ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );

        $this->view->setPage('icon', 'connectdevelop');
        $this->view->setPage('title', 'ซีรี่ย์ทัวร์');


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

        $this->view->render('tour/lists/display');        
    }
    public function lists()
    {
        $results = $this->model->query('tour')->find( array() );
        // print_r($results); die; 
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function salesForce()
    {
        $results = $this->model->query('tour')->salesForce();
        // print_r($results); die;

        $results['$items'] = $this->fn->q('listbox')->table_tour_rows($results['items'], $results['options']);        
        // $this->view->render("tour/ajax/items");
        echo json_encode($results);
    }

    /*public function periodList()
    {
        
    }*/

    public function create($action='')
    {
        $this->view->setData( 'airlineList', $this->model->query('tour')->airlineList() );
        $this->view->setData( 'statusList', $this->model->query('tour')->status() );
        $this->view->setData( 'suggestList', $this->model->query('tour')->suggestList() );
        $this->view->setData( 'countryList', $this->model->query('tour')->countryList() );
        $this->view->setData( 'action', $action );

        
        $this->view->setPage('path', 'Themes/admin/forms/tour');
        $this->view->render("create"); 
    }
    public function save($id='', $action='')
    {
        if( empty($_POST) || empty($this->me) ) $this->error();

        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        $action = isset($_REQUEST['action']) ? $_REQUEST['action']: $id;
        if( !empty($id) ){
            $item = $this->model->get( $id  );
            if( empty($item) ) $this->error();
        }
        // print_r($_FILES); die;

        if( empty($item) ){

            $files = array(
                0=> array( 'key'=>'file_word', 'type'=>'word', 'field'=>'ser_url_word'),
                array( 'key'=>'file_pdf', 'type'=>'pdf', 'field'=>'ser_url_pdf' ),
                array( 'key'=>'file_image', 'type'=>'img', 'field'=>'ser_url_img_1' )
            );

            $arr = array();
            foreach ($files as $key => $val) {
                if( !empty($_FILES[$val['key']]) ){
                    
                    $err = '';
                    if(!$this->fn->q('file')->validate($err, $_FILES[$val['key']], $val) ){
                        $arr['error'][$val['key']] = $err;
                    };

                    $files[$key]['userfile'] = $_FILES[$val['key']];
                }
                else{
                    $arr['error'][$val['key']] = 'กรุณาแนบไฟล์';
                }
            }
        }
        else{
            $imageOpt = array( 'key'=>'file_image', 'type'=>'img', 'field'=>'ser_url_img_1' );
            if( !empty($_FILES['file_image']) ){
                $err = '';
                if($this->fn->q('file')->validate($err, $_FILES['file_image'], $imageOpt) ){
                    $imageOpt['userfile'] = $_FILES['file_image'];   
                }
                else{
                    $arr['error']['file_image'] = $err;
                }
            }
        }


        try {
            $form = new Form();
            $form   ->post('ser_code')->val('is_empty')

                    ->post('ser_name')->val('is_empty')

                    ->post('country_id')->val('is_empty')
                    ->post('city_id')

                    ->post('air_id')->val('is_empty')

                    ->post('remark')

                    ->post('ser_price')
                    ->post('ser_deposit')->val('is_empty')

                    // flight ขาไป
                    ->post('ser_go_flight_code')
                    ->post('ser_go_route')
                    ->post('ser_go_time')

                    // flight ขากลับ
                    ->post('ser_return_flight_code')
                    ->post('ser_return_route')
                    ->post('ser_return_time');

            $form->submit();
            $postData = $form->fetch();


            $postData['remark'] = trim($postData['remark']);
            
            $postData['ser_price'] = str_replace(',', '', $postData['ser_price']);
            $postData['ser_deposit'] = str_replace(',', '', $postData['ser_deposit']);


            if( empty($arr['error']) ){

                foreach (array('city_id') as $key) {
                    if( isset($_POST[$key]) ){
                        $postData[$key] = $_POST[$key];
                    }
                }
                
                $postData['update_user_id'] = $this->me['id'];
                $postData['update_date'] = date('c');

                if( !empty( $item) ){

                    $postData['status'] = isset($_POST['status'])? 1: 2;

                    if( isset($_POST['category']) ){
                        $this->model->categoryUpdate( $id, $_POST['category'] );
                    }
                    
                    
                    $this->model->update($id, $postData );

                    // delete image
                    if( empty($_POST['_file_image'])&&!empty($item['url_img_1'])){
                        $this->model->removeFile($id, $item['url_img_1'], 'ser_url_img_1');
                    }

                    if( !empty($imageOpt['userfile']) ){
                        $imageOpt['user_id'] = $this->me['id'];
                        $this->model->upload( $id, $imageOpt );
                    }

                    $arr['message'] = 'แก้ไขข้อมูลเรียบร้อย';
                    $arr['actions'] = array(
                        'update' => array("[item-id={$id}]", $postData),
                    );
                }
                else{

                    $postData['create_user_id'] = $this->me['id'];
                    $postData['create_date'] = date('c');

                    $postData['status'] = 0;
                    // print_r($postData); die;
                    $this->model->insert( $postData );

                    // upload file
                    $folder = '../upload/travel/';
                    $directory = WWW_UPLOADS.'travel/';
                    foreach ($files as $key => $val) {

                        $source = $val['userfile']['tmp_name'];
                        $filename = $val['userfile']['name'];

                        $filename = $this->fn->q('file')->createName($filename, $postData['id'], $val['type'], $this->me['id'] );

                        if( copy($source, $directory.$filename) ){
                            // 
                            $post[$val['field']] = $folder.$filename;
                            $this->model->update($postData['id'], $post);
                        }
                    }

                    $arr['message'] = "บันทึกข้อมูลเรียบร้อย";
                    $arr['redirect'] = URL."manage/tour/{$postData['id']}/period";

                }                
            }


        } catch (Exception $e) {

            $arr['error'] = !empty($arr['error'])
                ? array_merge( $arr['error'], $this->_getError($e->getMessage()) )
                : $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function files($type='', $id='', $action='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || !in_array($type, array('word', 'pdf', 'banner')) ) $this->error();

        $item = $this->model->get( $id  );
        if( empty($item) ) $this->error();

        if( $action=='remove' ){
            if( !empty($_POST) ){

                $fileOpt = $this->fileOpt[$type];

                $field = str_replace('ser_', '', $fileOpt['field']);
                if( !empty($item[$field]) ){
                    $this->model->removeFile( $id, $item[$field], $fileOpt['field'] );
                }

                $arr['message'] = 'ลบไฟล์เรียบร้อย';
                echo json_encode($arr);
            }
        }
        else{
            $this->view->setData('type', $type);
            $this->view->setData('item', $item);
            $this->view->setData('action', $action);
            $this->view->setData('fileOpt', $this->fileOpt[$type]);

            $this->view->setPage('path', 'Themes/admin/forms/tour/files');
            $this->view->render($action);
        }

        
    }
    public function upload($type='', $action='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        if( empty($this->me) || empty($id) || !in_array($type, array('word', 'pdf')) ) $this->error();

        $item = $this->model->get( $id  );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $fileOpt = $this->fileOpt[$type];

            if( !empty($_FILES[$fileOpt['key']]) ){
                $err = '';
                if($this->fn->q('file')->validate($err, $_FILES[$fileOpt['key']], $fileOpt) ){
                    $fileOpt['userfile'] = $_FILES[$fileOpt['key']];   
                }
                else{
                    $arr['error']['file'] = $err;
                }
            }
            else{
                $arr['error']['file'] = 'กรุณาแนบไฟล์';
            }


            if( empty($arr['error']) ){

                $field = str_replace('ser_', '', $fileOpt['field']);
                if( !empty($item[$field]) ){
                    $this->model->removeFile( $id, $item[$field], $fileOpt['field'] );
                }

                $fileOpt['user_id'] = $this->me['id'];
                $this->model->upload( $id, $fileOpt );
                $arr['message'] = 'อัพโหลดเรียบร้อย';
            }

            echo json_encode($arr);
        }
        else{
            $this->error();
        }
    }



    // 
    public function clone( $id='' )
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->get( $id, array('_field'=>'series.remark') );
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        // $this->view->setData( 'periodList', $this->model->query('tour')->period->find( array('series'=>$id) ) );

        $this->create('clone');
    }

    public function del( $id='' )
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->get( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){


            $periodList = $this->model->query('tour')->period->lists( array('series'=>$id) );
            // print_r($periodList); die;
            foreach ($periodList as $key => $value) {
                $this->model->query('tour')->period->remove($value);
            }

            // delete image
            foreach ($this->fileOpt as $key => $value) {

                $field = str_replace('ser_', '', $value['field']);
                if( !empty($item[$field]) ) {
                    $this->model->removeFile($id, $item[$field], $value['field']);
                }
            }

            $this->model->query('tour')->delete($id);

            $arr['message'] = 'ลบเรียบร้อย';
            $arr['actions'] = array('redirect'=>URL.'manage/tour');
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);

            $this->view->setPage('path', 'Themes/admin/forms/tour');
            $this->view->render('del');
        }
    }
    

    public function agencyList()
    {

        // print_r($this->model->booking->agencySalesList()); die;
        echo json_encode($this->model->booking->agencySalesList());
    }
        // public function period($id='', $bus=''){}
    public function period($id='', $bus='', $tab='booking')
    {
        if( $id=='save' ){

            $arr = array();

            # File
            $files = array(
                0=> array( 'key'=>'file_word', 'type'=>'word', 'field'=>'per_url_word', 'err'=>'กรุณาแนบไฟล์ใบเตรียมตัวเดินทางรูปแบบไฟล์ Word'),
                array( 'key'=>'file_pdf', 'type'=>'pdf', 'field'=>'per_url_pdf', 'err'=>'กรุณาแนบไฟล์ใบเตรียมตัวเดินทางรูปแบบไฟล์ PDF' ),
                // array( 'key'=>'cost_file', 'type'=>'word', 'field'=>'per_cost_file' )
            );
            foreach ($files as $key => $val) {
                if( !empty($_FILES[$val['key']]) ){
                    
                    $err = '';
                    if(!$this->fn->q('file')->validate($err, $_FILES[$val['key']], $val) ){
                        $arr['error'][$val['key']] = $err;
                    };

                    $files[$key]['userfile'] = $_FILES[$val['key']];
                }
                else{
                    $arr['error'][$val['key']] = !empty($val['err']) ? $val['err']: 'กรุณาแนบไฟล์';
                }
            }


            # Date
            $date = isset($_POST['date']) ? $_POST['date']: '';
            if( empty($date) ){
                $arr['error']['date'] = 'ป้อนวันที่เดินทาง';
            }
            else{
                $date = explode('-', $date);

                if( count($date)!=2 ){
                    $arr['error']['date'] = 'วันที่เดินทางไม่ถูกต้อง';
                }
                else{

                    $dataPost['per_date_start'] = date('Y-m-d', strtotime( trim($date[0]) ));
                    $dataPost['per_date_end'] = date('Y-m-d', strtotime( trim($date[1]) ));

                    if( strtotime($dataPost['per_date_end'])<strtotime($dataPost['per_date_start']) ){
                        $arr['error']['date'] = 'วันที่เดินทางไม่ถูกต้อง';
                    }
                }
            }

            $buslist = array(); $no = 0;
            for ($i=0; $i < count($_POST['buslist']['name']); $i++) { 
                $no++;
                $buslist[] = array(
                    'bus_no' => $no,
                    'bus_qty' => $_POST['buslist']['value'][$i], // seat
                );
            }

            // set key
            foreach (array('per_price_1', 'per_price_2', 'per_price_3', 'per_price_4', 'per_price_5', 'single_charge', 'per_com_company_agency', 'per_com_agency', 'per_discount') as $key) {
                $dataPost[$key] = '';
            }
            

            // price values
            $buslistOpt['price_values'] = array();
            for ($i=0; $i < count($_POST['prices']['name']); $i++) { 


                $val = str_replace(',', '', $_POST['prices']['value'][$i]);
                if( isset($_POST['prices']['key'][$i]) && isset($dataPost[$_POST['prices']['key'][$i]]) ){
                    $dataPost[$_POST['prices']['key'][$i]] = $val;
                }

                $buslistOpt['price_values'][$_POST['prices']['name'][$i]] = $val;
            }

            // discounts
            $buslistOpt['discounts'] = array();
            for ($i=0; $i < count($_POST['discounts']['name']); $i++) { 

                $val = str_replace(',', '', $_POST['discounts']['value'][$i]);
                if( isset($_POST['discounts']['key'][$i]) && isset($dataPost[$_POST['discounts']['key'][$i]]) ){
                    $dataPost[$_POST['discounts']['key'][$i]] = $val;
                }
                $buslistOpt['discounts'][$_POST['discounts']['name'][$i]] = $val;
            }


            // room of type
            $buslistOpt['room_of_types'] = array();
            for ($i=0; $i < count($_POST['roomoftypes']['name']); $i++) { 
                $val = str_replace(',', '', $_POST['roomoftypes']['value'][$i]);
                $buslistOpt['room_of_types'][$_POST['roomoftypes']['name'][$i]] = $val;
            }


            if( empty($arr['error']) ){

                $dataPost['remark'] = isset($_REQUEST['remark']) ? trim($_REQUEST['remark']): '';
                $dataPost['ser_id'] = isset($_REQUEST['ser_id']) ? trim($_REQUEST['ser_id']): '';
                $dataPost['cancel_mode'] = isset($_REQUEST['cancel_mode']) ? trim($_REQUEST['cancel_mode']): '';

                $dataPost['update_user_id'] = $this->me['id'];
                $dataPost['update_date'] = date('c');

                $dataPost['create_user_id'] = $this->me['id'];
                $dataPost['create_date'] = date('c');
                $dataPost['status'] = 1;

                $this->model->period->insert( $dataPost );
                $id = $dataPost['id'];

                if( !empty($id) ){

                    $buslistOpt['cancel_mode'] = $dataPost['cancel_mode'];
                    $no = 0;
                    foreach ($buslist as $key => $value) {
                        $no++;
                        

                        $value['per_id'] = $id;
                        $value['bus_options'] = json_encode( $buslistOpt );
                        $this->model->period->bus->insert( $value );
                    }

                    // upload file
                    foreach ($files as $key => $value) {

                        $value['user_id'] = $this->me['id'];
                        $this->model->period->upload( $id, $value );
                    }

                    $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                    $arr['actions'] = array(
                        'call' => "refreshProfile",
                    );
                }
                else{
                    $arr['error'] = 1;
                    $arr['message'] = 'ระบบเกิดข้อผิดพลาด, ลองใหม่';
                }

            }

            echo json_encode($arr);
            exit;
        }
        else if( $id=='add' ){

            $item = $this->model->get( $bus );
            if( empty($item) ) $this->erorr();

            $this->view->setData('cancelmodeList', $this->model->period->auto_cancel_mode());
            $this->view->setData('statusList', $this->model->period->status() );
            $this->view->setData('item', $item );


            $this->view->setPage('path', 'Themes/admin/forms/tour/period');
            $this->view->render("add");
            exit;
        }
        elseif( $id=='lists' ){
            $results = $this->model->query('tour')->period->lists();
            echo json_encode($results);
            exit;
        }
        else if( $id=='edit' ){

        }



        die;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->query('tour')->period->get( $id, array('bus'=>$bus) );
        // print_r($item); die;
        if( empty($item) ) $this->error();

        /*$booking = $this->model->query('booking')->find( array('period'=>$id, 'bus'=>$bus) );
        print_r($booking); die;*/
        $tour = $this->model->query('tour')->get( $item['ser_id'] );
        // print_r($tour); die;
        if( empty($item) ) $this->error();


        if( $this->format=='json' ){

            $this->view->render("tour/period/tabs/{$tab}");
        }
        else{

            $tabs = array();
            $tabs[] = array('id'=>'booking', 'name'=>'รายละเอียดการจอง', 'link'=>URL."tour/period/{$id}/{$bus}/booking");
            $tabs[] = array('id'=>'traveler', 'name'=>'ข้อมูลผู้เดินทาง', 'link'=>URL."tour/period/{$id}/{$bus}/traveler");

            
            $this->view->setData( 'opt', array(
                'title' => "{$tour['code']} - {$tour['name']}",
                'controls' => array(
                      '<a class="btn" title="Refresh List" data-control-action="refresh"><i class="icon-refresh"></i></a>'
                    // , '<a class="btn" title="Show Sidebar" data-control-action="showsidebar">Sidebar</a>'
                ),
                'tab' => array(
                    'current' => $tab,
                    'items' => $tabs
                )
            ) );

            $this->view->setData('tour', $tour);
            $this->view->setData('item', $item);
            $this->view->setData('bus', $bus);
            $this->view->setData('tab', $tab);

            // $this->view->setPage('icon', ' flag icoflag-'.strtolower($tour['country_code']));
            // $this->view->setPage('title', "{$tour['code']} - {$tour['name']}");
            // $this->view->setPage('titleDisplay', 'disable');


            $this->view->render('tour/period/display');
        }
    }


    /**/
    /* location */
    /**/
    public function location($type='', $action='', $id='')
    {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $path = "Themes/admin/forms/tour/location/{$type}";
        $this->view->setPage('path', $path);


        if( $type=='city' ){
            if( in_array($action, array('del','edit')) ){

                $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;

                $item = $this->model->{$type}->get($id);
                if( empty($item) ) $this->error();

                $this->view->setData('item', $item);
            }

            if( in_array($action, array('add','edit')) ){
                $this->view->setData('countryList', $this->model->country->lists(array('status'=>1)));
                $this->view->render('form');
            }
            elseif( $action=='del' ){

                if( !empty($_POST) ){

                    if( !empty($item['permit']['del']) ){

                        // delete image
                        if( !empty($item['img']) ){
                            $this->model->airline->deleteImage($id, $item['img'] );
                        }

                        $this->model->{$type}->delete( $id );
                        $arr['message'] = 'Deleted!';
                        $arr['url'] = 'refresh';
                    }
                    else{
                        $arr['error'] = 1;
                        $arr['message'] = "Can't Delete";
                    }

                    echo json_encode( $arr );
                }
                else{
                    $this->view->render('del');
                }
            }
            else if( $action=='save' ){

                if( !empty($_POST['id']) ){
                    $id = $_POST['id'];

                    $item = $this->model->{$type}->get($id);
                    if( empty($item) ) $this->error();
                }

                try {
                    $form = new Form();
                    $form
                        ->post('city_country_id')->val('is_empty')
                        ->post('city_name')->val('is_empty')
                        ->post('city_type')
                        ->post('city_description');

                    $form->submit();
                    $postData = $form->fetch();

                    if( empty($arr['error']) ){

                        if( !empty($item) ){
                            $this->model->{$type}->update( $id, $postData );
                        }
                        else{
                            $postData['city_enabled'] = 1;
                            $this->model->{$type}->insert( $postData );
                            $id = $postData['id'];
                        }

                        $arr['message'] = 'Saved!';
                        $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                    }

                } catch (Exception $e) {
                    $arr['error'] = $this->_getError($e->getMessage());
                }

                echo json_encode($arr);
                exit;
            }

            elseif( $action=='list' ){
                echo json_encode( $this->model->city->lists(array('enabled'=>1)) );
            }
            
        } // end: country

        /* country */
        if( $type=='country' ){

            if( in_array($action, array('del','edit')) ){

                $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;

                $item = $this->model->{$type}->get($id);
                if( empty($item) ) $this->error();

                $this->view->setData('item', $item);
            }

            if( in_array($action, array('add','edit')) ){
                $this->view->setData('flagList', $this->model->query('system')->flagList() );
                $this->view->render('form');
            }
            elseif( $action=='del' ){

                if( !empty($_POST) ){

                    if( !empty($item['permit']['del']) ){

                        // delete image
                        if( !empty($item['img']) ){
                            $this->model->airline->deleteImage($id, $item['img'] );
                        }

                        $this->model->{$type}->delete( $id );
                        $arr['message'] = 'Deleted!';
                        $arr['url'] = 'refresh';
                    }
                    else{
                        $arr['error'] = 1;
                        $arr['message'] = "Can't Delete";
                    }

                    echo json_encode( $arr );

                }
                else{
                    $this->view->render('del');
                }
            }
            else if( $action=='save' ){

                if( !empty($_POST['id']) ){
                    $id = $_POST['id'];

                    $item = $this->model->country->get($id);
                    if( empty($item) ) $this->error();
                }


                if( !empty($_FILES['file_image']) ){
                    $userfile = $_FILES['file_image'];

                    $err = '';
                    if(!$this->fn->q('file')->validate($err, $userfile, array('key'=>'file_image','type'=>'img')) ){
                        $arr['error']['file_image'] = $err;
                    };
                }

                try {
                    $form = new Form();
                    $form
                        ->post('country_name')->val('is_empty')
                        ->post('country_code')
                        ->post('country_description');

                    $form->submit();
                    $postData = $form->fetch();

                    if( empty($arr['error']) ){

                        $postData['update_user_id'] = $this->me['id'];
                        $postData['update_date'] = date('c');
                        $postData['status'] = isset($_POST['status']) ? 1: 0; // !empty($_POST['status']) ? $_POST['status']: 2;

                        if( !empty($item) ){
                            $this->model->country->update( $id, $postData );

                            // delete image
                            if( empty($_POST['_file_image'])&&!empty($item['img']) ){
                                $this->model->country->deleteImage($id, $item['img'] );
                            }
                        }
                        else{

                            $postData['create_user_id'] = $this->me['id'];
                            $postData['create_date'] = date('c');

                            $this->model->country->Insert( $postData );
                            $id = $postData['id'];
                        }

                        // upload file
                        if( !empty($userfile) ){
                            $this->model->country->insertImage( $userfile, $id, 'img', $this->me['id'] );
                        }

                        $arr['message'] = 'Saved!';
                        $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                    }
                } catch (Exception $e) {
                    $arr['error'] = $this->_getError($e->getMessage());
                }

                echo json_encode($arr);
                exit;
            }
        } // end: country
    }

    /**/
    /* airline */
    /**/
    public function airline($action='', $id='')
    {
        if( empty($this->me) || $this->format!='json' ) $this->error();
        $this->view->setPage('path',"Themes/admin/forms/tour/airline");


        if( in_array($action, array('del','edit')) ){

            $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;

            $item = $this->model->airline->get($id);
            if( empty($item) ) $this->error();

            $this->view->setData('item', $item);
        }

        if( in_array($action, array('add','edit')) ){
            $this->view->render('form');
        }
        elseif( $action=='del' ){

            if( !empty($_POST) ){

                if( !empty($item['permit']['del']) ){

                    // delete image
                    if( !empty($item['url_img']) ){
                        $this->model->airline->deleteImage($id, $item['url_img'] );
                    }

                    // $this->model->airline->delete( $id );
                    $arr['message'] = 'Deleted!';
                    $arr['url'] = 'refresh';
                }
                else{
                    $arr['error'] = 1;
                    $arr['message'] = "Can't Delete";
                }

                echo json_encode( $arr );

            }
            else{
                $this->view->render('del');
            }
        }
        else if( $action=='save' ){

            if( !empty($_POST['id']) ){
                $id = $_POST['id'];

                $item = $this->model->airline->get($id);
                if( empty($item) ) $this->error();
            }


            if( !empty($_FILES['file_image']) ){
                $userfile = $_FILES['file_image'];

                $err = '';
                if(!$this->fn->q('file')->validate($err, $userfile, array('key'=>'file_image','type'=>'img')) ){
                    $arr['error']['file_image'] = $err;
                };
            }

            try {
                $form = new Form();
                $form   ->post('air_name')->val('is_empty')
                        ->post('air_code')
                        ->post('remark');

                $form->submit();
                $postData = $form->fetch();

                if( empty($arr['error']) ){

                    $postData['update_user_id'] = $this->me['id'];
                    $postData['update_date'] = date('c');
                    $postData['status'] = isset($_POST['status']) ? 1: 0; // !empty($_POST['status']) ? $_POST['status']: 2;

                    if( !empty($item) ){
                        $this->model->airline->update( $id, $postData );

                        // image
                        if( empty($_POST['_file_image'])&&!empty($item['url_img']) ){
                            $this->model->airline->deleteImage($id, $item['url_img'] );
                        }
                    }
                    else{

                        $postData['create_user_id'] = $this->me['id'];
                        $postData['create_date'] = date('c');

                        $this->model->airline->Insert( $postData );
                        $id = $postData['id'];
                    }

                    // upload file
                    if( !empty($userfile) ){

                        $this->model->airline->insertImage( $userfile, $id, 'img', $this->me['id'] );
                    }

                    $arr['message'] = 'Saved!';
                    $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                }
            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
            exit;
        }
    }


}
