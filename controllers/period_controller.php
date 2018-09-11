<?php

class Period_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->error();
    }

    public function add($id='', $action='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->query('tour')->get( $id );
        if( empty($item) ) $this->erorr();

        $this->view->setData('cancelmodeList', $this->model->query('tour')->period->auto_cancel_mode());
        $this->view->setData('statusList', $this->model->query('tour')->period->status() );
        $this->view->setData('item', $item );


        $this->view->setPage('path', 'Themes/admin/forms/tour/period');
        $this->view->render("add");
    }
    public function save()
    {
        $arr = array();

        # File
        $files = array(
            0=> array( 'key'=>'file_word', 'type'=>'word', 'field'=>'per_url_word', 'err'=>'กรุณาแนบไฟล์ใบเตรียมตัวเดินทางรูปแบบไฟล์ Word'),
            array( 'key'=>'file_pdf', 'type'=>'pdf', 'field'=>'per_url_pdf', 'err'=>'กรุณาแนบไฟล์ใบเตรียมตัวเดินทางรูปแบบไฟล์ PDF' ),
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
            

        foreach (array('price_values', 'commission', 'discounts', 'prices') as $key) {
                
            if( empty($_POST[$key]['value']) ) continue;

            $buslistOpt[$key] = array();
            for ($i=0; $i < count($_POST[$key]['value']); $i++) { 

                $val = str_replace(',', '', $_POST[$key]['value'][$i]);

                $opt = array(
                    // 'name' => isset($_POST[$key]['name'][$i])? $_POST[$key]['name'][$i]: '',
                    'value' => $val,
                );

                if( isset($_POST[$key]['name'][$i]) ){
                    $opt['name'] = $_POST[$key]['name'][$i];
                }

                if( isset($_POST[$key]['key'][$i]) && isset($dataPost[$_POST[$key]['key'][$i]]) ){
                    $dataPost[$_POST[$key]['key'][$i]] = $val;
                    $opt['key'] = $_POST[$key]['key'][$i];
                }

                if( !empty($_POST[$key]['name'][$i]) || !empty($_POST[$key]['key'][$i]) ){

                    if( $key=='discounts' ){
                        if( !empty($val) ){
                            $buslistOpt[$key][] = $opt;
                        }
                    }
                    else{
                        $buslistOpt[$key][] = $opt;
                    }

                    
                }
            }
        }



        // Extra Price
        if( !empty($_POST['extra_price']['value'][0]) ){
            $val = str_replace(',', '', $_POST['extra_price']['value'][0]);
            $dataPost['per_price_4'] = $val;

            $buslistOpt['infant'] = $val;
        }
        if( !empty($_POST['extra_price']['value'][1]) ){
            $val = str_replace(',', '', $_POST['extra_price']['value'][1]);
            $dataPost['per_price_5'] = $val;

            $buslistOpt['joinland'] = $val;
        }

        // single_charge
        if( !empty($_POST['single_charge']['value'][0]) ){
            $val = str_replace(',', '', $_POST['single_charge']['value'][0]);
            $dataPost['single_charge'] = $val;
            $buslistOpt['single_charge'] = $val;
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

            $this->model->query('tour')->period->insert( $dataPost );
            $id = $dataPost['id'];

            if( !empty($id) ){

                $buslistOpt['cancel_mode'] = $dataPost['cancel_mode'];
                $no = 0;
                foreach ($buslist as $key => $value) {
                    $no++;
                    

                    $value['per_id'] = $id;
                    $value['bus_options'] = json_encode( $buslistOpt );
                    $this->model->query('tour')->period->bus->insert( $value );
                }

                // upload file
                foreach ($files as $key => $value) {

                    $value['user_id'] = $this->me['id'];
                    $this->model->query('tour')->period->upload( $id, $value );
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
    }
    
    public function edit($id='', $bus='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        if( empty($id) || empty($bus) || empty($this->me) ) $this->error();

        $item = $this->model->query('tour')->period->get( $id, array('bus'=>$bus) );
        if( empty($item) ) $this->error();

       
        if( !empty($_POST) ){

            // print_r($_POST); die;
            $dataPost = array();

            // set key
            foreach (array('per_price_1', 'per_price_2', 'per_price_3', 'per_price_4', 'per_price_5', 'single_charge', 'per_com_company_agency', 'per_com_agency', 'per_discount') as $key) {
                $dataPost[$key] = '';
            }
                

            foreach (array('price_values', 'commission', 'discounts', 'prices') as $key) {
                
                if( empty($_POST[$key]['value']) ) continue;

                $buslistOpt[$key] = array();
                for ($i=0; $i < count($_POST[$key]['value']); $i++) { 

                    $val = str_replace(',', '', $_POST[$key]['value'][$i]);

                    $opt = array(
                        // 'name' => isset($_POST[$key]['name'][$i])? $_POST[$key]['name'][$i]: '',
                        'value' => $val,
                    );

                    if( isset($_POST[$key]['name'][$i]) ){
                        $opt['name'] = $_POST[$key]['name'][$i];
                    }

                    if( isset($_POST[$key]['key'][$i]) && isset($dataPost[$_POST[$key]['key'][$i]]) ){
                        $dataPost[$_POST[$key]['key'][$i]] = $val;
                        $opt['key'] = $_POST[$key]['key'][$i];
                    }

                    if( !empty($_POST[$key]['name'][$i]) || !empty($_POST[$key]['key'][$i]) ){

                        if( $key=='discounts' ){
                            if( !empty($val) ){
                                $buslistOpt[$key][] = $opt;
                            }
                        }
                        else{
                            $buslistOpt[$key][] = $opt;
                        }

                        
                    }
                }
            }

            // Extra Price
            if( !empty($_POST['extra_price']['value'][0]) ){
                $val = str_replace(',', '', $_POST['extra_price']['value'][0]);
                $dataPost['per_price_4'] = $val;

                $buslistOpt['infant'] = $val;
            }
            if( !empty($_POST['extra_price']['value'][1]) ){
                $val = str_replace(',', '', $_POST['extra_price']['value'][1]);
                $dataPost['per_price_5'] = $val;

                $buslistOpt['joinland'] = $val;
            }

            // single_charge
            if( !empty($_POST['single_charge']['value'][0]) ){
                $val = str_replace(',', '', $_POST['single_charge']['value'][0]);
                $dataPost['single_charge'] = $val;
                $buslistOpt['single_charge'] = $val;
            }

            $buslistOpt['cancel_mode'] = $_POST['cancel_mode'];
            $dataPost['cancel_mode'] = $_POST['cancel_mode'];

            try {
                $form = new Form();
                $form   ->post('bus_qty');

                $form->submit();
                $bus = $form->fetch();


                if( isset($_POST['bus_status']) ){
                    $bus['bus_status'] = $_POST['bus_status'];
                }


                if( empty($arr['error']) ){

                    $bus['bus_options'] = json_encode( $buslistOpt );
                    // print_r($bus); die;

                    if(  $item['bus']['no']==1 ){
                        $this->model->query('tour')->period->update( $item['id'], $dataPost );
                    }
                    $this->model->query('tour')->period->bus->update( $item['bus']['id'], $bus );

                    $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                    $arr['actions'] = array(
                        'call' => "refreshProfile",
                    );

                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            // print_r($item); die;
            $this->view->setData('item', $item);

            $this->view->setData('dataOpt', array(
                'period' => $id,
                'bus' => $bus,
            ));
            $this->view->setData('auto_cancelList', $this->model->query('tour')->period->bus->_defaultAutoCancel());
            $this->view->setData('statusList', $this->model->query('tour')->period->bus->_defaultStatus());
            
            $this->view->setPage('path', 'Themes/admin/forms/tour/period');
            $this->view->render("edit");
        }
    }

    public function tabs($id='', $tab='')
    {
        // $this->view->render("tabs/");
        $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        $item = $this->model->query('tour')->period->get( $id, array('bus'=>$bus) );


        if( $tab=='booking' ){
            $booking = $this->model->query('booking')->lists( array('period'=>$id, 'bus'=>$bus, 'sort'=>'create_date', 'dir'=>'ASC') );
            $this->view->setData( 'booking', $booking );

            $this->view->setData( 'status', $this->model->query('system')->booking_status() );

        }
        

        $this->view->setData( 'item', $item );
        $this->view->render("tour/period/tabs/{$tab}");
    }



    public function changedate($id='', $bus='')
    {

        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->query('tour')->period->get( $id, array('bus'=>$bus) );
        // print_r($item); die;
        if( empty($item) ) $this->error();


        if( !empty($_POST) ){

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

                    $dateStart = new DateTime();
                    $start = explode('/', trim($date[0]));
                    $dateStart->setDate($start[2], $start[1], $start[0]);
                    $postData['per_date_start'] = $dateStart->format('Y-m-d');

                    $dateEnd = new DateTime();
                    $end = explode('/', trim($date[1]));
                    $dateEnd->setDate($end[2], $end[1], $end[0]);
                    $postData['per_date_end'] = $dateEnd->format('Y-m-d');

                    if( strtotime($postData['per_date_end'])<strtotime($postData['per_date_start']) ){
                        $arr['error']['date'] = 'วันที่เดินทางไม่ถูกต้อง';
                    }
                }
            }

            if( empty($arr['error']) ){

                $postData['update_user_id'] = $this->me['id'];
                $postData['update_date'] = date('c');
                $this->model->query('tour')->period->update( $id, $postData );
                
                $arr['message'] = 'แก้ไขข้อมูลเรียบร้อย';

                $arr['actions'] = array(
                    'update' => array("[item-id={$id}]", array(
                        'date_str' => $this->fn->q('time')->eventDate( $postData['per_date_start'], $postData['per_date_end'] )
                    )),
                );
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
        
            $this->view->setPage('path', 'Themes/admin/forms/period');
            $this->view->render("changedate");
        }
    }

    public function del($id='', $bus='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        $bus = isset($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->query('tour')->period->get( $id, array('bus'=>$bus) );
        if( empty($item) ) $this->error();
        // print_r($item); die;

        if( !empty($_POST) ){

            $period = $this->model->query('tour')->period->get( $id );
            // print_r($period); die;
            $seq = 0;

            foreach ($period['busList'] as $key => $value) {

                if( $bus==$value['no'] ){
                    $this->model->query('tour')->period->bus->delete($value['id']);
                }
                else{
                    $seq++;
                    $this->model->query('tour')->period->bus->update($value['id'], array('bus_no'=>$seq));
                }                
            }

            if( $seq==0 ){

                if(!empty($period['url_word'])  ){
                    $this->model->query('tour')->period->removeFile($id, $period['url_word'], 'per_url_word');
                }

                if(!empty($period['url_pdf'])  ){
                    $this->model->query('tour')->period->removeFile($id, $period['url_pdf'], 'per_url_pdf');
                }

                $this->model->query('tour')->period->delete($id);
            }
            

            $arr['message'] = "ลบข้อมูลเรียบร้อย";

            $arr['actions'] = array(
                'call' => "refreshProfile",
            );
            echo json_encode($arr);

        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Themes/admin/forms/period');
            $this->view->render("del");
        }
    }


    public function addBus($id='')
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->query('tour')->period->get( $id );
        if( empty($item) ) $this->erorr();

        if( !empty($_POST) ){

            // print_r($item); die;
            $dataPost = array();

            // set key
            foreach (array('per_price_1', 'per_price_2', 'per_price_3', 'per_price_4', 'per_price_5', 'single_charge', 'per_com_company_agency', 'per_com_agency', 'per_discount') as $key) {
                $dataPost[$key] = '';
            }

            foreach (array('price_values', 'commission', 'discounts', 'prices') as $key) {
                
                if( empty($_POST[$key]['value']) ) continue;

                $buslistOpt[$key] = array();
                for ($i=0; $i < count($_POST[$key]['value']); $i++) { 

                    $val = str_replace(',', '', $_POST[$key]['value'][$i]);

                    $opt = array(
                        'value' => $val,
                    );

                    if( isset($_POST[$key]['name'][$i]) ){
                        $opt['name'] = $_POST[$key]['name'][$i];
                    }

                    if( isset($_POST[$key]['key'][$i]) && isset($dataPost[$_POST[$key]['key'][$i]]) ){
                        $opt['key'] = $_POST[$key]['key'][$i];
                    }

                    if( !empty($_POST[$key]['name'][$i]) || !empty($_POST[$key]['key'][$i]) ){

                        if( $key=='discounts' ){
                            if( !empty($val) ){
                                $buslistOpt[$key][] = $opt;
                            }
                        }
                        else{
                            $buslistOpt[$key][] = $opt;
                        }

                        
                    }
                }
            }

            // Extra Price
            if( !empty($_POST['extra_price']['value'][0]) ){
                $val = str_replace(',', '', $_POST['extra_price']['value'][0]);
                $buslistOpt['infant'] = $val;
            }
            if( !empty($_POST['extra_price']['value'][1]) ){
                $val = str_replace(',', '', $_POST['extra_price']['value'][1]);
                $buslistOpt['joinland'] = $val;
            }

            // single_charge
            if( !empty($_POST['single_charge']['value'][0]) ){
                $val = str_replace(',', '', $_POST['single_charge']['value'][0]);
                $buslistOpt['single_charge'] = $val;
            }

            $buslistOpt['cancel_mode'] = $_POST['cancel_mode'];

            try {
                $form = new Form();
                $form   ->post('bus_qty');

                $form->submit();
                $bus = $form->fetch();


                if( isset($_POST['bus_status']) ){
                    $bus['bus_status'] = $_POST['bus_status'];
                }

                if( empty($arr['error']) ){


                    $bus['per_id'] = $id;
                    $bus['bus_no'] = count($item['busList']) + 1;
                    $bus['bus_options'] = json_encode( $buslistOpt );
                    $this->model->query('tour')->period->bus->insert( $bus );

                    $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                    $arr['actions'] = array(
                        'call' => "refreshProfile",
                    );

                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('auto_cancelList', $this->model->query('tour')->period->bus->_defaultAutoCancel());
            $this->view->setData('statusList', $this->model->query('tour')->period->bus->_defaultStatus());
            $this->view->setData('item', $item );

            $this->view->setPage('path', 'Themes/admin/forms/tour/period');
            $this->view->render("addBus");
        }
        
    }
}