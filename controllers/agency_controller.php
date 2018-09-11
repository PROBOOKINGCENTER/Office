<?php

class Agency_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->error();
    }


    public function company()
    {
        if( isset($_GET['debug']) ){
            print_r($this->model->company->find()); die;
        }
        

        if( $this->format == 'json' ){


            $results = $this->model->company->find();
            // print_r($results); die;

            $results['$items'] = $this->fn->q('listbox')->agencyCompanyRows($results['items'], $results['options']);        
            // $this->view->render("tour/ajax/items");
            echo json_encode($results);
        /*
            $this->view->setData( 'results', $this->model->company->find() );
            $this->view->render("agency/company/lists/json");*/

        }
        else{

            $filter = array();
            $filter[] = array('key'=>'q', 'type'=>'search');
            $filter[] = array('key'=>'status', 'type'=>'change', 'items'=>$this->model->company->status(), 'label'=>'Status' );

            $this->view->setData('listOpt', array(
                'title' => 'Agency Company',
                'icon' => 'building-o',
                'datatable' => $this->fn->q('listbox')->agencyCompanyColumn(),

                'url' => URL.'agency/company',
                'is_float' => true,

                'controls' => array(

                      '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                    , '<div class="divider"></div>'
                    , '<a class="btn btn-blue" href="'.URL.'agency/add/company" data-plugin="lightbox" title="Add">'.Translate::Val('Add New').'</a>'
                ),

                'filter' => $filter
            ) );

            /*$this->view->setPage('icon', 'building-o');
            $this->view->setPage('title', 'Agency Company');*/


            /*$nav = array();
            $nav[] = 'divider';
            $nav[] = array('type'=>'button', 'link_cls'=>'btn btn-blue', 'text'=> Translate::Val('Add New'), 'link'=>URL.'agency/add/company', 'plugin'=>'lightbox', 'icon'=>'plus');
            $this->view->setPage('nav', $nav);*/


            // $this->view->setData('statusList', $this->model->company->status() );
            $this->view->setPage( 'on', 'agency_company' );
            $this->view->render("agency/company/lists/display");
        }
    }

    public function sales()
    {
        if( isset($_GET['debug']) ){
            print_r($this->model->sales->find()); die;
        }

        if( $this->format == 'json' ){

            /*$this->view->setData( 'results', $this->model->sales->find() );
            $this->view->render("agency/sales/lists/json");*/

            $results = $this->model->sales->find();
            // print_r($results); die;

            $results['$items'] = $this->fn->q('listbox')->agencySalesRows($results['items'], $results['options']);        
            // $this->view->render("tour/ajax/items");
            echo json_encode($results);

        }
        else{

            $this->view->setPage('icon', 'user-circle-o');
            $this->view->setPage('title', 'Agency');

            $filter = array();
            $filter[] = array('key'=>'q', 'type'=>'search');
            $filter[] = array('key'=>'status', 'type'=>'change', 'items'=>$this->model->sales->status(), 'label'=>'Status' );

            $this->view->setData('listOpt', array(
                'title' => 'Agency',
                'icon' => 'user-circle-o',
                'datatable' => $this->fn->q('listbox')->agencySalesColumn(),

                'url' => URL.'agency/sales',
                'is_float' => true,

                'controls' => array(

                      '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                    , '<div class="divider"></div>'
                    , '<a class="btn btn-blue" href="'.URL.'agency/add/sales" data-plugin="lightbox" title="Add">'.Translate::Val('Add New').'</a>'
                ),

                'filter' => $filter
            ) );


            /*$this->view->setData('statusList', $this->model->sales->status() );
            $this->view->setData('roleList', $this->model->sales->admin_roles() );
            $this->view->setData('companyList', $this->model->sales->companyList() );*/

            $this->view->setPage( 'on', 'agency_sales' );
            $this->view->render("agency/sales/lists/display");
        }
    }



    /* opareter */
    public function add( $action='' ) {

        if( empty($this->me) || $this->format!='json' || empty($action) ) $this->error();

        if( $action=='company' ){
            $this->view->setData('status', $this->model->company->status() );
            $this->view->setData('city', $this->model->company->city() );
            $this->view->setData('license_types', $this->model->company->license_types() );
        }

        if($action=='sales'){
            $this->view->setData('status', $this->model->sales->status() );
            $this->view->setData('roleList', $this->model->sales->admin_roles() );
            $this->view->setData('companyList', $this->model->sales->companyList() );
        }

        $this->view->setPage('path', 'Themes/admin/forms/agency/'.$action );
        $this->view->render('add');
    }
    public function edit( $action='', $id=null )
    {
        if( empty($this->me) || $this->format!='json' || empty($action) ) $this->error();

        $item = $this->model->{$action}->get($id);
        if( empty($item) ) $this->error();
        $this->view->setData('item', $item);

        if( $action=='sales' ){

            $this->view->setData('status', $this->model->sales->status() );
            $this->view->setData('roleList', $this->model->sales->admin_roles() );
            $this->view->setData('companyList', $this->model->sales->companyList() );

            $this->view->setPage('path', 'Themes/admin/forms/agency/'.$action );
            $this->view->render('edit');
        }
        else{
            $this->add( $action );
        }
        
    }
    public function save( $action='' ) {

        if( empty($this->me) || $this->format!='json' || empty($_POST) ) $this->error();


        /* Save: company  */
        if( $action=='company' ) {

            $id = isset($_POST['id']) ? $_POST['id']: null;
            if( !empty($id) ){
                $item = $this->model->{$action}->get($id);
                if( empty($item) ) $this->error();
            }

            try {
                $form = new Form();
                $form   ->post('agen_com_name_th')
                        ->post('agen_com_name')->val('is_empty')
                        ->post('agen_com_code')

                        ->post('license_number')
                        ->post('license_type')

                        ->post('location_address')
                        ->post('location_city')
                        ->post('location_zip')

                        ->post('agen_com_tel')
                        ->post('agen_com_email')
                        ->post('social_line')
                        ->post('social_facebook')

                        ->post('agen_com_website');

                $form->submit();
                $postData = $form->fetch();


                // $postData['status'] = 1;
                $postData['status'] = $_POST['status'];
                $postData['agen_com_guarantee'] = !empty($_POST['guarantee']) ? 1: 0;

                if( empty($arr['error']) ){

                    $postData['update_user_id'] = $this->me['id'];
                    $postData['update_date'] = date('c');
                    $postData['version'] = 2;


                    if( !empty($item) ){
                        $this->model->{$action}->update( $id, $postData );

                        $arr['message'] = 'Company saved.';
                        $arr['actions'] = array(
                            'update' => array("[item-id={$id}]", $this->model->{$action}->get($id)),
                        );

                    }
                    else{

                        $postData['create_user_id'] = $this->me['id'];
                        $postData['create_date'] = date('c');
                        $this->model->{$action}->insert( $postData );
                        $id = $postData['id'];

                        $arr['message'] = 'Company added.';
                        $arr['actions'] = array(
                            'call' => 'refreshList',
                        );
                    }

                    
                    // $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }
        }
        elseif( $action=='sales' ) {
            
            $id = isset($_POST['id']) ? $_POST['id']: null;
            if( !empty($id) ){
                $item = $this->model->{$action}->get($id);
                if( empty($item) ) $this->error();
            }

            try {
                $form = new Form();
                $form   ->post('agency_company_id')->val('is_empty')
                        ->post('agen_position')

                        ->post('agen_fname')->val('is_empty')
                        ->post('agen_lname')
                        ->post('agen_nickname')

                        ->post('agen_email')
                        ->post('agen_tel')
                        ->post('agen_line_id')

                        ->post('agen_role')
                        ->post('agen_user_name')->val('username');

                $form->submit();
                $postData = $form->fetch();

                $postData['agen_user_name'] = strtolower( trim($postData['agen_user_name']) );

                if( !empty($item) ){
                    $postData['status'] = $_POST['status'];

                    if( $this->model->sales->is_user( $postData['agen_user_name'] ) &&  $postData['agen_user_name']!=$item['username'] ){
                        $arr['error']['agen_user_name'] = 'ไม่สามารถใช้ชื่อผู้ใช้นี้ได้';
                    }

                }
                else{
                    $lenPass = 4;
                    if( isset($_POST['auto_password']) ){
                        $arr['password'] = $this->fn->q('user')->generateStrongPassword( 8 );
                    }
                    else if(strlen($_POST['agen_password']) < $lenPass ){
                        $arr['error']['agen_password'] = "รหัสผ่านต้องมากกว่า {$lenPass} ตัว";
                    }
                    else{
                        $arr['password'] = $_POST['password'];
                    }

                    if( $this->model->sales->is_user( $postData['agen_user_name'] ) ){
                        $arr['error']['agen_user_name'] = 'ไม่สามารถใช้ชื่อผู้ใช้นี้ได้';
                    }

                }


                if( !empty($_POST['reset_password']) ) $postData['reset_password'] = 1;

                if( empty($arr['error']) ){

                    $postData['update_user_id'] = $this->me['id'];
                    $postData['update_date'] = date('c');

                    if( !empty($item) ){
                        $this->model->{$action}->update( $id, $postData );

                        $arr['message'] = 'Saved.';
                        $arr['actions'] = array(
                            'update' => array("[item-id={$id}]", $this->model->{$action}->get($id)),
                            'call' => 'resizeList'
                        );

                    }
                    else{

                        $postData['agen_password'] = $this->model->query('users')->createPassword($arr['password']);
                        $postData['create_user_id'] = $this->me['id'];
                        $postData['create_date'] = date('c');
                        $postData['status'] = 1;

                        $this->model->{$action}->insert( $postData );
                        $id = $postData['id'];

                        $arr['data'] = array(
                            'name' => trim("{$postData['agen_fname']} {$postData['agen_lname']}"),
                            'login' => $postData['agen_user_name'],
                        );
                        $arr['message'] = 'Added.';                        
                    }                    
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

        }else{
            $arr['error'] = 400;
        }

        echo json_encode($arr);
    }
    public function del( $action=null, $id=null ) {

        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || $this->format!='json' || empty($action) || empty($id) ) $this->error();

        $item = $this->model->{$action}->get($id);
        if( empty($item) ) $this->error();


        if( !empty($_POST) ){

            if( !empty($item['permit']['del']) ){
                $this->model->{$action}->delete( $id );

                $arr['message'] = 'Deleted.';
                $arr['actions'] = array(
                    'call' => 'refreshList',
                );
            }
            else{
                $arr['error'] = 1;
                $arr['message'] = "Can't Delete";
            }

            echo json_encode( $arr );
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Themes/admin/forms/agency/'.$action );
            $this->view->render('del');
        }
    }
    public function update( $action='', $id=null )
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || $this->format!='json' || empty($id) || empty($action) ) $this->error();

        $name = isset($_REQUEST['name']) ? $_REQUEST['name']: '';
        $value = isset($_REQUEST['value']) ? $_REQUEST['value']: '';

        $dataPost = array();
        $dataPost[ $name ] = trim($value);
        $dataPost['update_date'] = date('c');
        $dataPost['update_user_id'] = $this->me['id'];
        
        $item = $this->model->{$action}->findById($id);
        if( empty($item) ) $this->error();
        $this->model->{$action}->update($id, $dataPost);

        echo json_encode(array('message'=>'Saved.'));  
    }


    public function reset_password($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->sales->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            
            $leg = 4;
            if( !empty( $_POST['password_auto'] ) ){
                $arr['password'] = $this->fn->q('user')->generateStrongPassword( 8 );
            }
            else{

                if( strlen($_POST['password_new']) < $leg ){
                    $arr['error']['password_new'] = "รหัสผ่านต้องมากกว่า {$leg} ตัว";
                }
                else if( $_POST['password_new']!=$_POST['password_confirm'] ){
                    $arr['error']['password_confirm'] = 'รหัสผ่านไม่ตรงกัน';
                }
                else{
                    $arr['password'] = $_POST['password_new'];
                }
            }

            if( empty($arr['error']) ){

                $postData = array(
                    'agen_password' => $this->model->query('users')->createPassword( $arr['password'] )
                );
                if( !empty($_POST['reset_password']) ) $postData['reset_password'] = 1;

                // update
                $this->model->sales->update($item['id'], $postData);
                $arr['message'] = "Saved.";
            }            

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Themes/admin/forms/agency/sales');
            $this->view->render("reset_password");
        }
    }
    public function disabled($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->sales->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->sales->update($item['id'], array( 'status' => 2 ));
            $arr['message'] = "User Disabled.";
            $arr['data'] = $item;
            $arr['actions'] = array(
                'call' => "refreshList",
            );

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Themes/admin/forms/agency/sales');
            $this->view->render("disabled");
        }
    }
    public function enabled($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->sales->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->sales->update($item['id'], array( 'status' => 1 ));
            $arr['message'] = "User Enabled.";
            $arr['data'] = $item;

            $arr['actions'] = array(
                'call' => "refreshList",
            );
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Themes/admin/forms/agency/sales');
            $this->view->render("enabled");
        }
    }
}