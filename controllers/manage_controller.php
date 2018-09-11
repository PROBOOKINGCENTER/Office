<?php

class Manage_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->error();
    }

    public function ticket()
    {
    	$this->view->setPage('on', 'manage_ticket');
        // $this->view->render('manage/ticket/lists/display');
    	$this->view->render('coming-soon');
    }

    public function tour($id=null, $tab='period')
    {
        $this->view->setPage('on', 'manage_tour');
        // print_r($_SERVER); die;
        Session::init();
        $pageOpt = Session::get('manage_tour');
        if( empty($pageOpt) ) $pageOpt = array();

        if( !empty($id) ){

            $item = $this->model->query('tour')->get( $id, array('_field'=>'series.remark') );
            if( empty($item) ) $this->error();

            // print_r($item); die;
            $pageOpt['id'] = $id;

            // print_r( $this->model->query('tour')->categoryList( $id ) ); die;
            // print_r($this->model->query('tour')->period->find( array('series'=>$id) )); die;
            // print_r($pageOpt); die;

            if( $this->format=='json' ){

                // sleep(5);
                if( $tab=='setting' ){

                    $this->view->setData( 'airlineList', $this->model->query('tour')->airlineList() );
                    $this->view->setData( 'statusList', $this->model->query('tour')->status() );
                    $this->view->setData( 'suggestList', $this->model->query('tour')->suggestList() );
                    $this->view->setData( 'countryList', $this->model->query('tour')->countryList() );


                    $categoryList = $this->model->query('tour')->category->lists();
                    $category = $this->model->query('tour')->categoryList( $id );
                    foreach ($categoryList as $i => $value) {
                        if( in_array($value['id'], $category) ){
                            $categoryList[$i]['checked'] = true;
                        }
                    }
                    $this->view->setData( 'categoryList', $categoryList );

                }
                elseif( $tab=='period' ){

                    $this->view->setData( 'periodList', $this->model->query('tour')->period->lists( array('series'=>$id) ) );
                }
                else{
                    $this->error();
                }

                $pageOpt['tab'] = $tab;
                Session::set('manage_tour', $pageOpt); 

                $this->view->render('manage/tour/profile/tabs/'.$tab, array(
                    'item' => $item
                ));
            }
            else{

                $this->view ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                            ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )
                            ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );
                            

                // print_r($this->model->query('tour')->period->lists( array('series'=>$id) )); die;
                
                $tabs = array();
                $tabs[] = array('id'=>'setting', 'name'=>'ข้อมูลทั่วไป', 'link'=>URL."manage/tour/{$id}/setting");
                // $tabs[] = array('id'=>'cost', 'name'=>'ต้นทุน', 'link'=>URL."manage/tour/{$id}/setting");
                $tabs[] = array('id'=>'period', 'name'=>'พีเรียด', 'link'=>URL."manage/tour/{$id}/period");
                // $tabs[] = array('id'=>'plan', 'name'=>'รายละเอียดการเดินทาง', 'link'=>URL."manage/tour/{$id}/period");
                // $tabs[] = array('id'=>'note', 'name'=>'หมายเหตุ', 'link'=>URL."manage/tour/{$id}/period");

                $dropdownList = $this->fn->q('listbox')->manageTourActions( $item );

                // if( !in_array($tab, $tabs) ) $this->error();
                $this->view->setData( 'opt', array(
                    'id' => $id,
                    'title' => "{$item['code']} - {$item['name']}",
                    'controls' => array(
                          '<a class="btn" title="Refresh List" data-control-action="refresh"><i class="icon-refresh"></i></a>'
                        , ( !empty($dropdownList)
                            ? '<button type="button" data-plugin="dropdown2" class="btn" style="width:30px" data-options="'.Fn::stringify( array( 'select' => $dropdownList, 'axisX'=> 'right', 'container'=> '#profile', ) ).'"><i class="icon-ellipsis-v"></i></button>'
                            : '' )
                    ),
                    'tab' => array(
                        'current' => $tab,
                        'items' => $tabs
                    )
                ) );

                // $this->view->setData('pageOpt', $pageOpt );

                $this->view->setData('tab', $tab);
                $this->view->setData('tabs', $tabs );
                $this->view->render('manage/tour/profile/display', array(
                    'item' => $item
                ));
            }
        }
    	else{

            // print_r($this->model->query('tour')->find()); die;

            if( isset($_GET['debug']) ){
                print_r($this->model->query('tour')->find()); die;
            }

            /*$results = $this->model->query('tour')->find();

            $results['$items'] = $this->fn->q('listbox')->manageTourRows($results['items'], $results['options']);        
            echo json_encode($results);
            die;*/

            if( $this->format=='json' ){


                $results = $this->model->query('tour')->find();

                $results['$items'] = $this->fn->q('listbox')->manageTourRows($results['items'], $results['options']);        
                echo json_encode($results);
            }
            else{

                /*$this->view->setPage('icon', 'flag');
                $this->view->setPage('title', Translate::Val('Serie Management'));

                $topnav = array();
                $topnav[] = 'divider';
                $topnav[] = array('type'=>'button', 'link_cls'=>'btn btn-blue', 'text'=> Translate::Val('Add New'), 'link'=>URL.'tour/create', 'plugin'=>'lightbox', 'icon'=>'plus');
                $this->view->setPage('topnav', $topnav);*/

                $filter = array();
                $filter[] = array('key'=>'q', 'type'=>'search');
                $filter[] = array('key'=>'airline', 'type'=>'change', 'items'=>$this->model->query('tour')->airlineList(), 'label'=>'สายการบิน' );
                $filter[] = array('key'=>'country', 'type'=>'change', 'items'=>$this->model->query('tour')->countryList(), 'label'=>'ประเทศ' );
                $filter[] = array('key'=>'status', 'type'=>'change', 'items'=>$this->model->query('tour')->status(), 'label'=>'Status' );

                $this->view->setData('listOpt', array(
                    'title' => Translate::Val('Serie Management'),
                    'icon' => 'flag',
                    'datatable' => $this->fn->q('listbox')->manageTourColumn(),

                    'url' => URL.'manage/tour',
                    'is_float' => true,

                    'controls' => array(

                          '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                        , '<div class="divider"></div>'
                        , '<a class="btn btn-blue" href="'.URL.'tour/create" data-plugin="lightbox" title="Add">'.Translate::Val('Add New').'</a>'
                    ),

                    'filter' => $filter
                ) );

                /*$this->view->setData( 'airlineList', $this->model->query('tour')->airlineList() );
                $this->view->setData( 'countryList', $this->model->query('tour')->countryList() );
                $this->view->setData( 'statusList', $this->model->query('tour')->status() );*/

                // $this->view->setData('_options', $_options);

                
                $this->view->render('manage/tour/lists/display');
            }
    	}
    }


    public function cost()
    {
    	$this->view->setPage('on', 'manage_cost');
        $this->view->render('coming-soon');
    	// $this->view->render('manage/cost/lists/display');
    }    
}