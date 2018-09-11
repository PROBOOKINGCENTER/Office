<?php

class Me_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->error();
    }

    public function logout()
    {
        if( empty($this->me) || empty($_POST) ){
            $this->error();
        }

        Session::init();
        Session::destroy();

        $redirect = !empty($_REQUEST['next']) ? $_REQUEST['next']: URL;
        Cookie::clear( COOKIE_KEY_USER );
        
        header('location:' . $redirect);
    }
    public function navTrigger() {
        if( $this->format!='json' ) $this->error();
        if( isset($_REQUEST['status']) ){
            Session::init();
            Session::set('isPushedLeft', $_REQUEST['status']);
        }
    }

    public function update($name='', $value='')
    {
        $name = !empty($_REQUEST['name']) ? $_REQUEST['name']: $name;
        $value = !empty($_REQUEST['value']) ? $_REQUEST['value']: $value;

        $post[$name] = trim($value);
        $this->model->query('users')->update( $this->me['id'], $post );

        echo json_encode(array('log'=> array('text'=>'update'), 'post'=>$post));
    }

    public function edit($action='') {
        
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $path = 'Themes/admin/forms/me';
        // $path .= !empty($action) ? "/{$action}":'';
        $this->view->setPage('path', $path);
        $this->view->render( $action );
    }
    public function save($action='')
    {
        if( empty($this->me) || $this->format!='json' || empty($_POST) ) $this->error();

        /* Save: forum  */
        if( $action=='basic' ) {

            try {

                $form = new Form();
                $form   ->post('user_fname')->val('is_empty')
                        ->post('user_lname')
                        ->post('user_nickname')
                        ->post('user_address')
                        ->post('user_tel')
                        ->post('user_line_id');


                $form->submit();
                $postData = $form->fetch();

                if( empty($arr['error']) ){

                    $this->model->query('users')->update( $this->me['id'], $postData );
                    $arr['message'] = 'Saved!';
                    $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }
        }
        elseif( $action=='account' ) {

            try {

                $form = new Form();
                $form   ->post('user_name')->val('username')->val('is_empty')
                        ->post('user_email')->val('email')->val('is_empty')
                        ->post('user_lang');


                $form->submit();
                $postData = $form->fetch();

                if( empty($arr['error']) ){

                    $this->model->query('users')->update( $this->me['id'], $postData );
                    $arr['message'] = 'Saved!';
                    $arr['url'] = !empty($_REQUEST['next']) ? $_REQUEST['next'] : 'refresh';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }
        }
        elseif( $action=='password' ){

            $len = 6;
            if( !empty( $_POST['password_auto'] ) ){
                $arr['password'] = $this->fn->q('user')->generateStrongPassword( 8 );
            }
            else{

                if( strlen($_POST['password_new']) < $len ){
                    $arr['error']['password_new'] = "รหัสผ่านต้องมากกว่า {$len} ตัว";
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
                /*$this->model->query('users')->update($item['id'], array(
                    'user_password' => $this->model->query('users')->createPassword( $arr['password'] )
                ));*/

                // $arr['message'] = "Saved.";
            }

        }
        else{
            $arr['error'] = 400;
        }
        

        echo json_encode($arr);
        
    }
}