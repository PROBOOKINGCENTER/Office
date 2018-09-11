<?php

class Promotion_Controller extends Controller {

    function __construct() {
        parent::__construct();

        $this->pathForm = 'Themes/admin/forms/promotion';
    }

    public function index()
    {
        if($this->format=='json'){


            $results = $this->model->query('promotion')->find();

            $results['$items'] = $this->fn->q('listbox')->promotionRows($results['items'], $results['options']);        
            echo json_encode($results);
        }
        else{
            $this->view->setPage('on', 'promotion');

            $filter = array();
            $filter[] = array('key'=>'q', 'type'=>'search');
            // $filter[] = array('key'=>'airline', 'type'=>'change', 'items'=>$this->model->query('tour')->airlineList(), 'label'=>'สายการบิน' );
            // $filter[] = array('key'=>'country', 'type'=>'change', 'items'=>$this->model->query('tour')->countryList(), 'label'=>'ประเทศ' );
            // $filter[] = array('key'=>'status', 'type'=>'change', 'items'=>$this->model->query('tour')->status(), 'label'=>'Status' );

            $this->view->setData('listOpt', array(
                'title' => Translate::Val('Promotion'),
                'icon' => 'tags',
                'datatable' => $this->fn->q('listbox')->promotionColumn(),

                'url' => URL.'promotion',
                'is_float' => true,

                'controls' => array(

                      '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                    , '<div class="divider"></div>'
                    , '<a class="btn btn-blue" href="'.URL.'promotion/add" data-plugin="lightbox" title="Add">'.Translate::Val('Add New').'</a>'
                ),

                'filter' => $filter
            ) );
            /*$nav = array();
            $nav[] = 'divider';
            $nav[] = array('type'=>'button', 'link_cls'=>'btn btn-blue', 'text'=> Translate::Val('Add New'), 'link'=>URL.'promotion/add', 'plugin'=>'lightbox', 'icon'=>'plus');
            $this->view->setPage('topnav', $nav);*/
            
            /*$this->view 
                ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )
                
                ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );*/

            /*$item = $this->model->query('promotion')->get( 2 );

            $this->model->query('promotion')->deleteImage( $item['file_image'] );
            print_r( $item ); die;*/
            $this->view->render('promotion/lists/display');
        }
        
    }

    public function add()
    {
    	if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', $this->pathForm);
    	$this->view->render('form');
    }

    public function edit($id='')
    {
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$item = $this->model->get( $id );
    	if( empty($item) ) $this->error();

    	$this->view->setData('item', $item );
    	$this->add();
    }

    public function save()
    {
    	if( empty($this->me) || $this->format!='json' || empty($_POST) ) $this->error();

    	if( isset($_POST['id']) ){
    		$id = $_POST['id'];
    		$item = $this->model->query('promotion')->get( $id );
    		if( empty($item) ) $this->error();
    	}


        try {
            $form = new Form();
            $form   ->post('pro_name')->val('is_empty')
                    ->post('pro_description')
                    ->post('pro_discount')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            # Date
	        $date = isset($_POST['pro_date']) ? $_POST['pro_date']: '';
	        if( empty($date) ){
	            $arr['error']['pro_date'] = 'ระบุวันที่';
	        }
	        else{
	            $date = explode('-', $date);

	            if( count($date)!=2 ){
	                $arr['error']['pro_date'] = 'วันที่ไม่ถูกต้อง';
	            }
	            else{

	                $now = strtotime(date('Y-m-d 00:00:00'));

	                $date[0] = str_replace('/', '-', $date[0]);
	                $date[1] = str_replace('/', '-', $date[1]);

	                $postData['pro_start_date'] = date('Y-m-d 00:00:00', strtotime( trim($date[0]) ));
	                $postData['pro_end_date'] = date('Y-m-d 00:00:00', strtotime( trim($date[1]) ));

	                if( strtotime($postData['pro_end_date'])<strtotime($postData['pro_start_date']) ){
	                    $arr['error']['pro_date'] = 'วันที่ไม่ถูกต้อง';
	                }
	                else if( $now > strtotime($postData['pro_end_date']) && empty($item) ){
	                	$arr['error']['pro_date'] = 'วันที่ไม่ถูกต้อง';
	                }
	            }
	        }

	        # Items 
	        $items = isset($_POST['items']) ? $_POST['items']: '';
	        if( empty($items) ){
	        	$arr['message'] = 'เลือกพีเรียด';
	            $arr['error']['items'] = 'เลือกพีเรียด';
	        }


	        # Image
            if( !empty($_FILES['file_image']) ){
                
                $err = '';
                if(!$this->fn->q('file')->validate($err, $_FILES['file_image'], array( 'key'=>'file_image', 'type'=>'img', 'field'=>'file_image' )) ){
                    $arr['error']['file_image'] = $err;
                };

                $image = $_FILES['file_image'];
            }


            if( empty($arr['error']) ){

            	$postData['pro_discount'] = str_replace(',', '', $postData['pro_discount']);
            	$postData['update_user_id'] = $this->me['id'];
            	
            	// print_r($postData); die;
            	
            	if( !empty($item) ){

            		$this->model->update( $id, $postData );

                    // items
                    foreach ($item['items'] as $val) {
                        
                        $has = false;
                        foreach ($items as $key => $obj) {

                            $ex = explode('_', $obj);
                            if( $ex[0]==$val['period_id'] && $val['bus']==$ex[1] ){
                                unset($items[$key]); $has=true; break;
                            }
                        }

                        if( !$has ){
                            $this->model->deleteItem( $val['_id'] );
                        }
                    }


                    // image
                    if( (!empty($image)&&!empty($item['file_image'])) || (empty($_POST['_file_image'])&&!empty($item['file_image'])) ){
                        $this->model->deleteImage( $item['file_image'] );
                        $this->model->update($id, array('file_image'=>''));
                    }


                    $postData['id'] = $id;
            		$arr['actions'] = array(
                        'update' => array("[item-id={$id}]", $this->model->query('promotion')->get( $id )),
                        'call' => 'resizeList',
                    );
            		$arr['message'] = 'Updated.';
            	}
            	else{
            		$postData['enabled'] = 1;
            		$postData['create_user_id'] = $this->me['id'];
            		$this->model->insert( $postData );

	                $arr['actions'] = array(
	                    'call' => 'refreshList',
	                );

	                $arr['message'] = 'Saved.';
                }


                if( !empty($postData['id']) ){

                    // items
                    foreach ($items as $key => $value) {

                        $ex = explode('_', $value);
                        $item = array(
                            'period_id' => $ex[0],
                            'bus' => $ex[1],
                            'promotion_id' => $postData['id'],
                        );
                        $this->model->insertItems( $item );
                    }

                    // upload file
                    if( !empty($image) ){
                        $folder = '../upload/promotion/';
                        $directory = WWW_UPLOADS.'promotion/';

                        $source = $image['tmp_name'];
                        $filename = $image['name'];

                        $filename = $this->fn->q('file')->createName($filename, $postData['id'], 'img', $this->me['id'] );

                        if( copy($source, $directory.$filename) ){

                            $post['file_image'] = $folder.$filename;
                            $this->model->update($postData['id'], $post);
                        }
                    }
                }

            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }


    public function update($name='', $value='')
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id']: null;
        if( empty($id) || empty($this->me) || $this->format!='json' || empty($_POST) ) $this->error();

        $name = !empty($_REQUEST['name']) ? $_REQUEST['name']: $name;
        $value = !empty($_REQUEST['value']) ? $_REQUEST['value']: $value;

        $post[$name] = trim($value);
        $this->model->update( $id, $post );

        echo json_encode(array('log'=> array('text'=>'Updated.'), 'post'=>$post));
    }
    public function del($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get( $id );
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {


            // $arr['data'] = $item;
            if ( !empty($item['permit']['del']) ) {

                if( !empty($item['file_image']) ){
                    $this->model->deleteImage($item['file_image']);
                }
                
                $this->model->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            // $arr['url'] = 'refresh';
            $arr['actions'] = array(
                'remove' => "[item-id={$id}]",
            );
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            
            $this->view->setPage('path', $this->pathForm);
            $this->view->render("del");
        } 

    }
}