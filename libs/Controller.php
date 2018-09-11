<?php

class Controller {

    public $format = "html";
    public $pathName = "";


    function __construct() {
        $this->fn = new Fn();
        $this->format =  $this->_httprequestFormat();
        $this->lang = new Langs();
        $this->lang->set( 'en' );
        
        // View
        $this->view = new View();
        $this->view->format = $this->format;
    }
 
    private function _httprequestFormat() {
        $_q = 'html';
        if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) ){
            if( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
                $_q = 'json';
            }
        }

        return $_q;
    }

    /**
     * 
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = 'models/', $_url=array()) {

        $path = $modelPath . $name.'_model.php';
        
        if (file_exists($path)) {
            require $modelPath .$name.'_model.php';
            
            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
        else{
            $this->model = new Model();
        }


        $this->init( $name, $_url );
        $this->handleLogin();

        $this->setDataDefault();
    }

    // return FORM Error
    protected function _getError($err) {
        $err = explode(',', rtrim($err, ','));

        $error = array();
        foreach ($err as $k) {
            $str = explode('=>', $k);
            $error[$str[0]] = $str[1];
        }

        return $error;        
    }

    public $me = null;
    public function handleLogin(){
        Session::init();

        $loggedOn = $this->getPage('loggedOn');
        if ( Cookie::get( COOKIE_KEY_USER ) ) {
            $me = $this->model->query('users')->findById( Cookie::get( COOKIE_KEY_USER ), array('status'=>1) );
        }

        if( !empty($me) ){
            $this->me =  $me;

            if( !empty($this->me['lang']) ){
                Session::init();
                Session::set('lang', $this->me['lang']);
                
                $this->lang->set( $this->me['lang'] );
            }

            
            $this->view->setData('me', $this->me);
            Cookie::set( COOKIE_KEY_USER, $this->me['id'], time() + (3600*24));

            /* -- authorization -- */
            $this->pageOptions['auth'] = $this->model->query('system')->auth( !empty($this->me['access']) ? $this->me['access']: array() );
            $this->view->setPage('auth', $this->pageOptions['auth']);
        }
        else if( !empty( $loggedOn ) ) {
            $this->login();
        }
    }


    // Page Error
    public function error(){
        if( !$this->model ){
            $this->loadModel('error');
        }

        if( $this->format=='json' ){

            echo json_encode(array(
                'error' => 404,
                'message' => 'Page not found'
            ));
        }
        else{
            $this->view->setPage('title', $this->lang->getCode()=='th'?'ไม่พบเพจ': 'Page not found');
            $this->view->elem('body')->addClass('page-errors');
            $this->view->render( 'error' );
        }

        exit;
    }

    // Page Login
    public function login() {
        Session::init();

        $pageOptions = $this->pageOptions;
        if( !empty($pageOptions['url'][1]) ){
            $render = in_array($pageOptions['url'][1], array('forgot_password') )? $pageOptions['url'][1]: '';
        }

        $attempt = Session::get('login_attempt');
        if( isset($attempt) && $attempt>=2 ){
            $this->view->setData('captcha', true);
            $this->view->js('https://www.google.com/recaptcha/api.js', true);
        }
        elseif( empty($attempt) ){
            $attempt = 0;
            Session::set('login_attempt', $attempt);
        }

        $login_mode = isset($_REQUEST['login_mode']) ? $_REQUEST['login_mode']: 'default';

        if( empty($_REQUEST['g-recaptcha-response']) && $attempt>2 ){
            $error['captcha'] = 'คุณป้อนรหัสไม่ถูกต้อง?';
        }
        if( !empty($_POST) && empty($error) ){            
            try {
                $form = new Form();

                $form   ->post('email')->val('is_empty')
                        ->post('pass')->val('is_empty');

                $form->submit();
                $post = $form->fetch();

                $id = $this->model->query('users')->login($post['email'], $post['pass']);

                if( !empty($id) ){

                    Cookie::set( COOKIE_KEY_USER, $id, time() + (3600*24));
                    Session::set('isPushedLeft', 1);

                    if( isset($attempt) ){
                       Session::clear('login_attempt');
                    }

                    $this->model->query('users')->update($id, array('user_lastvisit'=>date('c')));

                    $url = !empty($_REQUEST['next'])
                        ? $_REQUEST['next']
                        : $_SERVER['REQUEST_URI'];

                    header('Location: '.$url);
                }
                else{

                    if(!$this->model->query('users')->is_user($post['email'])){
                        $error['email'] = 'ชื่อผู้ใช้ไม่ถูกต้อง'; 
                    }
                    else{
                        $error['pass'] = 'รหัสผ่านไม่ถูกต้อง';
                    }
                }

                $post['pass'] = "";
                $this->view->setData('post', $post);
            } catch (Exception $e) {
                $error = $this->_getError( $e->getMessage() );
            }            
        }
        if(!empty($error)){

            if( isset($attempt) ){
                $attempt++;
                Session::set('login_attempt', $attempt);
            }

            $this->view->setData('error', $error);
        }

        $redirect = URL;
        $redirect = !empty($render) ? $redirect.$render.'/':'';

        $next = isset($_REQUEST['next']) ? $_REQUEST['next']: '';
        
        if( !empty( $next) ){
            $this->view->setData('next', $next);
        }

        $this->view->setData('redirect', $redirect);
        $title = $this->pageOptions['title'];
        $title = !empty($title)? Translate::Val('Login') . ' - ' . $title: Translate::Val('Login');

        $this->view->setPage('title', $title );
        $this->view->setPage('theme', 'login');

        $this->view->render( !empty($render) ? $render: 'default' );
        exit;
    }

    private $pageOptions = array(
        'elem' => array(),
        'data' => array(),
        'theme_options' => array(),
    );

    /* -- init --*/
    public function init($on, $_url=array()) {


        // Get Data System
        $this->pageOptions = array_merge($this->pageOptions, $this->model->query('system')->get() );
        $this->pageOptions['on'] = $on;

        // print_r($this->pageOptions); die;

        // set Theme

        if( empty($this->pageOptions['theme']) ){

            $this->setPage( array(
                'theme' => 'admin',
                'theme_options' => array(
                    'topbar'=>true,
                    'head' => true,
                    'leftMenu' => true,
                ),
                'loggedOn' => true,
                'url' => $_url,
                // 'render' => !empty($a[1]) ?$a[1]:'',

                'favicon' => IMAGES.'favicon.ico',
                'image_x1' => IMAGES.'image_x1.svg',
                'navigation' => $this->model->query('system')->pageNav( $this->me )
            ) );

        }

        $this->view->page = $this->pageOptions;
        // authorization
    }

    public function setDataDefault()
    {

        $themeName = $this->view->getPage( 'theme' );
        
        if( $themeName=='datacenter' ){

            $this->view->setPage('nav', $this->model->query('system')->pageNav() );
        }

        if( !empty($this->me) ){
            
        }
    }



    public function setPage($key, $val=null) {

        if( is_array($key) ){
            foreach ($key as $key => $val) {
                $this->pageOptions[$key] = $val;
            }
        }
        else{
            $this->pageOptions[$key] = $val;
        }

        return $this;
    }
    public function getPage($key)
    {
        return !empty($this->pageOptions[$key]) ? $this->pageOptions[$key]: null;
    }


    // Permit Page
    public function setPagePermit() {

        $permit = $this->model->query('system')->permit( !empty($this->me['access']) ? $this->me['access']:array() );

        if( !empty($this->me['permission']) ){

            foreach ($permit as $key => $value) {
                
                if( !empty($this->me['permission'][ $key ]) ){
                    $permit[$key] = array_merge($value, $this->me['permission'][ $key ]);
                }
            }

        }

        // print_r($permit); die;
        
        $this->permit = $permit;
        $this->view->setData('permit', $this->permit);

        /*echo 'options page';
        print_r($this->pageOptions); die;

        # Check Permit
        if( !empty($this->pageOptions['data']['permit']) && !empty($this->pageOptions['theme_options']['logged']) ){

            $on = $this->pageOptions['on'];
            if( empty($on) ) $on = $name;

            if( empty($this->pageOptions['data']['permit'][$on][$this->pageOptions['action']]) ){

                if( $this->format=='json' ){
                    echo json_encode(array(
                        'error' => 404,
                        'message' => 'Page not found'
                    )); exit;
                }
                else{
                    $this->setPage('title', 'Page not found');
                    $name = 'error';
                }
                
            }
        }*/
    }

    // set Data Default Page
    public function setupSystem(){

        $url = isset($_GET['url']) ?$_GET['url']:'';
        $title = '';
        if( !empty($url) ){
            $url = trim($url, '/');
            $url = str_replace('-', ' ', $url);
            $title = str_replace('/', ' - ', $url);
        }

        if( empty($title) && !empty($this->system['title']) ){
            $title = $this->system['title'];
        }

        $this->view->setPage('title', $title);

        $on = str_replace(' ', '-', $url);
        if( empty($on) ) $on = 'index';

        $this->view->setPage('on', $on );

        $this->system['url'] = URL.$on;
        $keys = array('site_name', 'type', 'url', 'image', 'keywords', 'color', 'facebook_app_id');
        foreach ($keys as $key) {
            if( !empty($this->system[$key]) ){

                if( $key=='site_name' ){
                    $this->view->setPage('site', $this->system[$key] );
                }

                $this->view->setPage($key, $this->system[$key] );
            }
        }

        if( !empty($this->system['blurb']) || !empty($this->system['description']) ){

            $description  ='';
            if( !empty($this->system['blurb']) ){
                $description = $this->system['blurb'];
            }
            else{
                $description = $this->fn->q('text')->more($this->system['description']);
            }

            $this->view->setPage('description',  $description );
        }  
    
        
        $this->view->setPage('logo', IMAGES.'logo/top-logo.png' );
        if( empty($this->system['image']) ){
            $this->view->setPage( 'image', IMAGES.'logo/top-logo.png' );
        }

        if( !empty($this->system['theme']) ){
            $this->view->setPage('theme',  $this->system['theme'] );
        }
    }


}