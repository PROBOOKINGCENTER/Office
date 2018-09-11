<?php

class Settings_Controller extends Controller {

    public function __construct() {
        parent::__construct();

        $this->parent = 'system';
        $this->section = '';
        $this->tab = '';
    }

    public function run(){


        // $nav = array();

        // /* accounting */
        // $items = array();
        // $items[] = array('key'=>'sales', 'id'=>'payment', 'name'=>'Book Bank', 'link'=>URL.'reports/sales/payment', 'title'=>'Book Bank');
        // // $items[] = array('key'=>'status', 'id'=>'payment', 'name'=>'Status', 'link'=>URL.'reports/sales/payment', 'title'=>'สถานะ');
        // $nav[] = array('id'=>'accounting', 'name'=>'Accounting', 'items'=>$items);


        // /* booking */
        // $items = array();
        // $items[] = array('key'=>'sales', 'id'=>'payment', 'name'=>'สายการบิน', 'link'=>URL.'reports/sales/payment', 'title'=>'Book Bank');
        // $items[] = array('key'=>'sales', 'id'=>'payment', 'name'=>'Country', 'link'=>URL.'reports/sales/payment', 'title'=>'Book Bank');
        // $items[] = array('key'=>'status', 'id'=>'payment', 'name'=>'City', 'link'=>URL.'reports/sales/payment', 'title'=>'สถานะ');
        // $nav[] = array('id'=>'tour', 'name'=>'Tour', 'items'=>$items);

        // /* tour */
        // $items = array();
        // $items[] = array('key'=>'status', 'id'=>'payment', 'name'=>'Extra List', 'link'=>URL.'reports/sales/payment', 'title'=>'สถานะ');
        // // $items[] = array('key'=>'status', 'id'=>'payment', 'name'=>'Status', 'link'=>URL.'reports/sales/payment', 'title'=>'สถานะ');
        // $nav[] = array('id'=>'tour', 'name'=>'Booking', 'items'=>$items);


        $tabs = array();
        $tabs[] = array('id'=>'bookbank', 'name'=>'Book Bank', 'link'=>URL."settings/accounting/bookbank");
        
        $tabs[] = array('id'=>'extralists', 'name'=>'Extra Lists', 'link'=>URL."settings/booking/extralists");

        $tabs[] = array('id'=>'airline', 'name'=>'สายการบิน', 'link'=>URL."settings/tour/airline");
        $tabs[] = array('id'=>'country', 'name'=>'ประเทศ', 'link'=>URL."settings/tour/location/country");
        $tabs[] = array('id'=>'city', 'name'=>'เมือง', 'link'=>URL."settings/tour/location/city");


        /*$has = false;
        foreach ($tabitems as $key => $value) {
            if( $this->section==$value['id'] ){
                $has = true; break;
            }
        }
        if( !$has ) $this->error();*/


        if( $this->format=='json' ){

            $path = "settings/{$this->parent}/{$this->section}";
            if( !empty($this->tab) ){
                $path.="/{$this->tab}";
            }

            $this->view->render($path);
        }
        else{

            $current = $this->section;
            if( !empty($this->tab) ){
                $current = $this->tab;
            }

            $this->view->setPage('on', 'settings' );
            $this->view->setPage('icon', 'cogs');
            $this->view->setPage('title', 'Settings');

            $this->view->setData('pageOpt', array(
                'title' => 'Settings',
                'icon' => 'cogs',

                'tab' => array(
                    'current' => $current,
                    'items' => $tabs
                ),

                /*'nav' => array(
                    'current' => $this->section,
                    'items' => $nav
                )*/
            ));

            // $this->view->setPage('toolbar', $this->model->query('system')->navSettings($this->me) );
            // $this->view->elem('body')->addClass('has-toolbar');

            $this->view->setData('section', $this->section);
            $this->view->setData('tab', $this->tab);
            $this->view->render("settings/display");
        }
    }

    public function index() {
        header('location:' .URL.'settings/tour/location/country');
    }


    /**/
    /* -- tour -- */
    /**/
    public function tour($section='', $tab='')
    {
        $this->parent = 'tour';
        $this->section = $section;
        $this->tab = $tab;

        if( $this->section=='location' ){

            if( in_array($this->tab, array('country', 'city')) ) {

                $this->view->setData('dataList', $this->model->query('tour')->{$this->tab}->lists() );
            } 
            else{
                $this->error();
            }
        }
        else if( $this->section=='airline' ){

            $results = $this->model->query('tour')->{$section}->lists();
            // print_r($results); die;
            $this->view->setData('dataList', $results );
        }
        else{
            $this->error();
        }

        $this->run();
    }

    public function booking($section='', $tab='')
    {
        $this->parent = 'booking';
        $this->section = $section;
        $this->tab = $tab;

        if( $section == 'extralists' ){
            $results = $this->model->query('system')->{$section}->find();
            $this->view->setData('dataList', $results );
        }
        else{
            $this->error();
        }

        $this->run();
    }

    public function accounting($section='', $tab='')
    {
        $this->parent = 'accounting';
        $this->section = $section;
        $this->tab = $tab;


        if( $section == 'bookbank' ){
            $results = $this->model->query('system')->{$section}->find();
            $this->view->setData('dataList', $results );
        }
        else{
            $this->error();
        }

        $this->run();
    }

    /**/
    /* -- airline -- */
    /**/
    /*public function airline($action='', $id=null)
    {
        $this->section = 'airline';
        $this->__init();*/

        /*if( !empty($action) ){

            $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;

            if( !empty($id) ){
                $item = $this->model->query('system')->{$section}->get( $id );
                if( empty($item) ) $this->error();
            }


            if( !empty($_POST) && $this->format=='json' ){

                if( $action=='save' ){
                    $arr = array();

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

                            if( !empty($item) ){
                                $this->model->query('system')->{$section}->update( $id, $postData );

                                $arr['actions'] = array(
                                    'update' => array("[data-id={$id}]", $postData),
                                );
                            }
                            else{

                                $postData['create_user_id'] = $this->me['id'];
                                $postData['create_date'] = date('c');

                                $this->model->query('system')->{$section}->insert($postData);
                                $arr['redirect'] = 'refresh';
                            }
                            
                            $arr['message'] = 'Saved.';
                        }
                    } catch (Exception $e) {
                        $arr['error'] = $this->_getError($e->getMessage());
                    }

                    echo json_encode($arr); exit;
                }
                else if($action=='del') {
                    
                    $this->model->query('system')->{$section}->delete($id);
                    $arr['message'] = 'ลบข้อมูลเรียบร้อย';

                    $arr['actions'] = array(
                        'remove' => "[data-id={$id}]",
                    );

                    echo json_encode($arr); exit;
                }
                else if( $action=='update' ){
                    $name = isset($_REQUEST['name']) ? $_REQUEST['name']: '';
                    $value = isset($_REQUEST['value']) ? $_REQUEST['value']: '';

                    $postData = array();
                    $postData[ $name ] = trim($value);

                    $arr = array('message'=> 'Updated.');

                    if( $name=='enabled' ){
                        $arr['message'] = $value==1? 'Enabled.':'Disabled.';
                    }

                    $this->model->query('system')->{$section}->update( $id, $postData );
                    echo json_encode($arr);  exit;
                }
            }

            if( in_array($action, array('form', 'del')) && $this->format=='json' ){
                if( !empty($item) ){
                    $this->view->setData('item', $item);
                }

                $this->view->setPage('path', "Themes/admin/forms/{$section}");
                $this->view->render($action); exit;
            }

            $this->error();

        }else{
            $this->__init($section);

            $results = $this->model->query('system')->{$section}->find();
            $this->view->setData('dataList', $results );

            $this->view->render("settings/airline/display");
        }*/
    // }

    /**/
    /* -- bookbank -- */
    /**/
    public function bookbank($action='', $id=null)
    {
        $section = 'bookbank';
        if( !empty($action) ){

            $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;

            if( !empty($id) ){
                $item = $this->model->query('system')->{$section}->get( $id );
                if( empty($item) ) $this->error();
            }


            if( !empty($_POST) && $this->format=='json' ){

                if( $action=='save' ){
                    $arr = array();

                    try {
                        $form = new Form();
                        $form   ->post('bank_name')->val('is_empty')
                                ->post('bankbook_branch')
                                ->post('bankbook_code')->val('is_empty')
                                ->post('bankbook_name')->val('is_empty');

                        $form->submit();
                        $postData = $form->fetch();

                        if( empty($arr['error']) ){

                            if( !empty($item) ){
                                $this->model->query('system')->{$section}->update( $id, $postData );

                                $arr['actions'] = array(
                                    'update' => array("[data-id={$id}]", $postData),
                                );
                            }
                            else{
                                $this->model->query('system')->{$section}->insert($postData);
                                $arr['redirect'] = 'refresh';
                            }
                            
                            $arr['message'] = 'Saved.';
                        }
                    } catch (Exception $e) {
                        $arr['error'] = $this->_getError($e->getMessage());
                    }

                    echo json_encode($arr); exit;
                }
                else if($action=='del') {
                    
                    $this->model->query('system')->{$section}->delete($id);
                    $arr['message'] = 'ลบข้อมูลเรียบร้อย';

                    $arr['actions'] = array(
                        'remove' => "[data-id={$id}]",
                    );

                    echo json_encode($arr); exit;
                }
                else if( $action=='update' ){
                    $name = isset($_REQUEST['name']) ? $_REQUEST['name']: '';
                    $value = isset($_REQUEST['value']) ? $_REQUEST['value']: '';

                    $postData = array();
                    $postData[ $name ] = trim($value);

                    $arr = array('message'=> 'Updated.');

                    if( $name=='status' ){
                        $arr['message'] = $value==1? 'Enabled.':'Disabled.';
                    }

                    $this->model->query('system')->{$section}->update( $id, $postData );
                    echo json_encode($arr);  exit;
                }
            }

            if( in_array($action, array('form', 'del')) && $this->format=='json' ){
                if( !empty($item) ){
                    $this->view->setData('item', $item);
                }

                $this->view->setPage('path', 'Themes/admin/forms/bookbank');
                $this->view->render($action); exit;
            }

            $this->error();

        }else{
            $this->__init($section);
            $this->view->setData('section', $section);


            $results = $this->model->query('system')->{$section}->find();
            $this->view->setData('dataList', $results );

            $this->view->render("settings/bookbank/display");
        }
    }

    /**/
    /* -- extralists -- */
    /**/
    public function extralists($action='', $id=null)
    {
        $section = 'extralists';
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;

        if( !empty($id) ){
            $item = $this->model->query('system')->{$section}->get( $id );
            if( empty($item) ) $this->error();
        }


        if( !empty($_POST) && $this->format=='json' ){

            if( $action=='save' ){
                $arr = array();

                try {
                    $form = new Form();
                    $form   ->post('name')->val('is_empty')
                            ->post('price')->val('is_empty');

                    $form->submit();
                    $postData = $form->fetch();

                    if( empty($arr['error']) ){

                        if( !empty($item) ){
                            $this->model->query('system')->{$section}->update( $id, $postData );
                            $postData['price'] = number_format($postData['price']);

                            $arr['actions'] = array(
                                'update' => array("[data-id={$id}]", $postData),
                            );
                        }
                        else{
                            $this->model->query('system')->{$section}->insert($postData);
                            $arr['redirect'] = 'refresh';
                        }
                        
                        $arr['message'] = 'Saved.';
                    }
                } catch (Exception $e) {
                    $arr['error'] = $this->_getError($e->getMessage());
                }

                echo json_encode($arr); exit;
            }
            else if($action=='del') {
                
                $this->model->query('system')->{$section}->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';

                $arr['actions'] = array(
                    'remove' => "[data-id={$id}]",
                );

                echo json_encode($arr); exit;
            }
            else if( $action=='update' ){
                $name = isset($_REQUEST['name']) ? $_REQUEST['name']: '';
                $value = isset($_REQUEST['value']) ? $_REQUEST['value']: '';

                $postData = array();
                $postData[ $name ] = trim($value);

                $arr = array('message'=> 'Updated.');

                if( $name=='enabled' ){
                    $arr['message'] = $value==1? 'Enabled.':'Disabled.';
                }

                $this->model->query('system')->{$section}->update( $id, $postData );
                echo json_encode($arr);  exit;
            }
        }

        if( in_array($action, array('form', 'del')) && $this->format=='json' ){
            if( !empty($item) ){
                $this->view->setData('item', $item);
            }

            $this->view->setPage('path', 'Themes/admin/forms/extralists');
            $this->view->render($action); exit;
        }

        $this->error();
    
    }
}
