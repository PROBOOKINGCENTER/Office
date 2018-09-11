<?php

class Users_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        // $this->view->render('booking/lists/display');
        $this->error();
    }

    public function lists()
    {
        $results = $this->model->find();
        // print_r($results); die;

        $results['$items'] = $this->fn->q('listbox')->usersRows($results['items'], $results['options']);        
        // $this->view->render("tour/ajax/items");
        echo json_encode($results);
        
    	// sleep(10);
    	/*$this->view->setData( 'results',  $this->model->find() );
        $this->view->render("users/lists/json");*/
    }

    public function add() {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('rolesList', $this->model->admin_roles() );
        $this->view->setPage('path', 'Themes/admin/forms/users');
        $this->view->render("add");
    }
    public function edit($id=null) {

        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->findById($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            try {
                $form = new Form();
                $form   ->post('group_id')->val('is_empty')
                        ->post('user_fname')->val('is_empty')
                        ->post('user_lname')
                        ->post('user_nickname')
                        ->post('user_name')->val('is_empty') //->val('username')
                        ->post('user_email') //->val('email')
                        ->post('user_tel')
                        ->post('user_line_id');

                $form->submit();
                $postData = $form->fetch();

                if( $item['username']!=$postData['user_name'] && $this->model->is_user( $postData['user_name'] ) ){
                    $arr['error']['user_name'] = 'ไม่สามารถใช้ชื่อผู้ใช้นี้ได้';
                }

                if( empty($arr['error']) ){

                    $postData['update_user_id'] = $this->me['id'];
                    $this->model->update( $id, $postData );
                    $postData['id'] = $id;
                    
                    $arr['message'] = 'Saved.';

                    $arr['actions'] = array(
                        'update' => array("[item-id={$id}]", $this->model->get( $id )),
                    );
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('statusList', $this->model->status() );
            $this->view->setData('rolesList', $this->model->admin_roles() );
            $this->view->setData('item', $item );
            $this->view->setPage('path', 'Themes/admin/forms/users');
            $this->view->render("edit");
        }        
    }
    public function save() {
        if( empty($_POST) || empty($this->me) || $this->format!='json' ) $this->error();
        
        $id = isset($_POST['id']) ? $_POST['id']: null;

        if( !empty($id) ){
            $item = $this->model->findById($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('group_id')->val('is_empty')
                    ->post('user_fname')->val('is_empty')
                    ->post('user_lname')
                    ->post('user_nickname')
                    ->post('user_name')->val('is_empty') //->val('username')
                    ->post('user_email') //->val('email')
                    ->post('user_tel')
                    ->post('user_line_id');

            $form->submit();
            $postData = $form->fetch();

            /*if( !empty($postData['user_fname']) ){
                if (@ereg("[a-zA-Z0-9]+$", $postData['user_fname'])){
                    $postData['user_fname'] = ucfirst($postData['user_fname']);
                }
            }*/

            $lenPass = 4;
            if( isset($_POST['auto_password']) ){
                $arr['password'] = $this->fn->q('user')->generateStrongPassword( 8 );
            }
            else if(strlen($_POST['password']) < $lenPass ){
                $arr['error']['password'] = "รหัสผ่านต้องมากกว่า {$lenPass} ตัว";
            }
            else{
                $arr['password'] = $_POST['password'];
            }

            if( $this->model->is_user( $postData['user_name'] ) ){
                $arr['error']['user_name'] = 'ไม่สามารถใช้ชื่อผู้ใช้นี้ได้';
            }

            if( empty($arr['error']) ){

                $postData['user_password'] = $arr['password'];
                $postData['create_user_id'] = $this->me['id'];
                $postData['update_user_id'] = $this->me['id'];
                $postData['status'] = 1;
                $this->model->insert( $postData );
                $postData['id'] = $id;
                
                $arr['data'] = array(
                    'name' => trim("{$postData['user_fname']} {$postData['user_lname']}"),
                    'login' => $postData['user_name'],
                );
                $arr['message'] = 'Saved.';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function del($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->findById($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            $this->model->update($id, array( 'status' => 9 ));
            $arr['message'] = "ลบข้อมูลเรียบร้อย";

            // $arr['data'] = $item;
            /*if ( !empty($item['permit']['del']) ) {
                $this->model->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }*/

            // $arr['url'] = 'refresh';
            $arr['actions'] = array(
                'remove' => "[item-id={$id}]",
            );
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            
            $this->view->setPage('path','Themes/admin/forms/users');
            $this->view->render("del");
        } 
    }
    public function change_password($id='') {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->findById($id);
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

                // update
                $this->model->update($item['id'], array(
                    'user_password' => $this->model->createPassword( $arr['password'] )
                ));

                $arr['message'] = "Saved.";
            }            

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Themes/admin/forms/users');
            $this->view->render("change_password");
        }
    }
    public function update($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || $this->format!='json' || empty($id) ) $this->error();

        $item = $this->model->findById($id);
        if( empty($item) ) $this->error();

        $name = isset($_REQUEST['name']) ? $_REQUEST['name']: '';
        $value = isset($_REQUEST['value']) ? $_REQUEST['value']: '';

        $dataPost = array();
        $dataPost[ $name ] = trim($value);

        $this->model->update($id, $dataPost);

        echo json_encode(array('message'=>'Saved.'));
    }
    public function disabled($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->findById($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->update($item['id'], array( 'status' => 2 ));
            $arr['message'] = "User Disabled.";
            $arr['data'] = $item;
            $arr['actions'] = array(
                'call' => "refreshList",
            );

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Themes/admin/forms/users');
            $this->view->render("disabled");
        }
    }

    public function enabled($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->findById($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->update($item['id'], array( 'status' => 1 ));
            $arr['message'] = "User Enabled.";
            $arr['data'] = $item;

            $arr['actions'] = array(
                'call' => "refreshList",
            );
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Themes/admin/forms/users');
            $this->view->render("enabled");
        }
    }

}