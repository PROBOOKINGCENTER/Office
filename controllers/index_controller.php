<?php

class Index_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        header('location: '. URL .'dashboard' );
    }

    public function routes($param=null) {

        if( !empty($param[0])  ){

            // 
            if( in_array($param[0], array('calendar', 'business', 'site', 'inbox')) ){
                $this->view->setPage('on', $param[0]);
                $this->view->render('coming-soon');
                exit;
            }


            if( $param[0]=='dashboard' ){

                # Set Page
                $this->view->setPage('on', 'dashboard' );
                $this->view->setPage('icon', 'area-chart');
                $this->view->setPage('title', 'Dashboard');


                
                # Set Chart
                $sums = $this->model->query('insights')->oldSum();
                $this->view->setData('receipt', $sums['sumtotal']);
                $this->view->setData('seat', $sums['sumqty']);
                $this->view->setData('period', $sums['sumperiod']);

                $this->view->setData('incomeYearlyOpt', $this->model->query('insights')->incomeYearly());

                $this->view->setData('agencyChartOpt', $this->model->query('insights')->topAgencyChart());
                $this->view->setData('salesChartOpt', $this->model->query('insights')->topSalesChart());
                $this->view->setData('seriesChartOpt', $this->model->query('insights')->topSeriesChart());


                # Set dataList
                $this->view 

                    ->js( VIEW. 'Themes/admin/assets/js/caleran.min.js', true )
                    ->js( VIEW. 'Themes/admin/assets/js/moment.min.js', true )

                    ->css( VIEW. 'Themes/admin/assets/css/caleran.min.css', true );

                $this->view->setData( 'statusList', $this->model->query('system')->booking_status() );
                $this->view->setData( 'countryList', $this->model->query('tour')->countryList() );
                $this->view->setData( 'airlineList', $this->model->query('tour')->airlineList() );
                $this->view->setData( 'salesList', $this->model->query('booking')->salesList() );

                $this->view->setData('tourListOpt', array(

                    'data' => array(
                        'bookingStatus' => $this->model->query('system')->booking_status()
                    ),

                    'url' => URL.'tour/salesForce',

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



                # view Page
                $this->view->render('dashboard/display');
                exit;
            }

            if( $param[0]=='ualogger' ){

                // event_name  de_account_general_basics_name_edit_clicked
                // extra   {}
                // for_uids    [195625668]
                // is_xhr  true
                // platform    web
                // t   xzCrynTKIwZhAp0-duUz7uFD
                

                // $this->view->render('coming-soon');
            }

            if( $param[0]=='ajax_lists' && $this->format=='json' ){

                if( $param[1] == 'series' ){
                    echo json_encode( $this->model->query('tour')->lists() );
                }
                else if( $param[1] == 'agency' ){
                    echo json_encode( $this->model->query('tour')->booking->agencySalesList() );
                }else if( $param[1] == 'seriesList' ){
                    echo json_encode( $this->model->query('tour')->codeList() );
                }
                else{
                    $this->error();
                }

                exit;
            }

            if( $param[0]=='ajax_pages' && $this->format=='json' ){

            }

            /*if( $param[0]=='business' ){
                $this->view->setPage('on', 'business');
                $this->view->render('settings/business/display');
                exit;
            }*/

            if( $param[0]=='authorization' ){
                $this->view->setPage('on', 'authorization');

                $filter = array();
                $filter[] = array('key'=>'q', 'type'=>'search');
                $filter[] = array('key'=>'role', 'type'=>'change', 'items'=>$this->model->query('users')->admin_roles(), 'label'=>'Role' );
                $filter[] = array('key'=>'status', 'type'=>'change', 'items'=>$this->model->query('users')->status(), 'label'=>'Status' );


                $this->view->setData('listOpt', array(
                    'title' => 'Users',
                    'icon' => 'users',
                    'datatable' => $this->fn->q('listbox')->usersColumn(),

                    'url' => URL.'users/lists',
                    'is_float' => true,

                    'controls' => array(

                          '<a class="btn" title="Refresh List" data-control-action="refreshList"><i class="icon-refresh"></i></a>'
                        , '<div class="divider"></div>'
                        , '<a class="btn btn-blue" data-plugin="lightbox" href="'.URL.'users/add"><i class="icon-plus mrs"></i><span>'.Translate::Val('Add New').'</span></a>'
                    ),

                    'filter' => $filter
                ) );


                /*$this->view->setPage('icon', 'users');
                $this->view->setPage('title', 'Users');*/


                /*$nav = array();
                $nav[] = 'divider';
                $nav[] = array('type'=>'button', 'link_cls'=>'btn btn-blue', 'text'=> Translate::Val('Add New'), 'link'=>URL.'users/add', 'plugin'=>'lightbox', 'icon'=>'plus');
                $this->view->setPage('topnav', $nav);*/

                $this->view->setData('active', array(
                    'status' => 1
                ) );
                // $this->view->setData('roleList', $this->model->query('users')->admin_roles() );
                $this->view->render('users/lists/display');
                exit;
            }

            if( $param[0]=='media' ){

                switch ( count($param) ) {
                    case 6:
                        $this->{$param[0]}($param[1], $param[2], $param[3], $param[4], $param[5]);
                        break;

                    case 5:
                        $this->{$param[0]}($param[1], $param[2], $param[3], $param[4]);
                        break;

                    case 4:
                        $this->{$param[0]}($param[1], $param[2], $param[3]);
                        break;
                    
                    default:
                        $this->error();
                        break;
                }
                exit;
            }


            if( $param[0]=='document' ){

                switch ( count($param) ) {
                    case 4:
                        $this->{$param[0]}($param[1], $param[2], $param[3]);
                        break;
                    
                    default:
                        $this->error();
                        break;
                }
                exit;
            }

                

            if( $param[0]=='tour' && !empty($param[1]) ){
                switch (count($param)) {
                    case 4:
                        $this->tour( $param[1], $param[2], $param[3] );
                        break;

                    case 3:
                        $this->tour( $param[1], $param[2] );
                        break;

                    case 2:
                        $this->tour( $param[1] );
                        break;

                    default:
                        $this->tour( $param[1], $param[2], $param[3], $param );
                        break;
                }
                
                exit;
            }
        }
        
        $this->error();
        
    }



    public function media($model='', $type='', $id='', $w=null, $h=null)
    {
        if( !in_array($model, array('tour', 'promotion')) ) $this->error();

        $item = $this->model->query($model)->get( $id );
        if( empty($item) ) $this->error();

        $folder = $model;

        if( $model=='tour' ){
            if( $type=='banner' && !empty($item['url_img_1']) ){
                $filename = $item['url_img_1'];
            }

            $folder = 'travel';
        }
        else if( $model=='promotion' && $type=='banner' ){
            $filename = $item['file_image'];
        }

        if( empty($filename) ) $this->error();
        $filename = strtolower(strrchr($filename, '/'));

        // echo $filename; die;
        $ext = $this->fn->q('file')->getExtension($filename);

        $source =  WWW_UPLOADS.$folder.$filename;
        $path =  UPLOADS.$folder.$filename;
        $download_file = htmlentities("{$item['name']}{$ext}");

        if( !file_exists($source) ) $this->error();
       

        list($original_width, $original_height, $image_type) = getimagesize($source);

        $set_width = isset($_REQUEST['w']) ? $_REQUEST['w']: $w;
        $set_height = isset($_REQUEST['h']) ? $_REQUEST['h']: $h;

        switch ($image_type)
        {
            case 1: $src = imagecreatefromgif($path); break;
            case 2: $src = imagecreatefromjpeg($path);  break;
            case 3: $src = imagecreatefrompng($path); break;
            default: return '';  break;
        }

        if( $set_width && $set_height ){

            if( $original_width > $original_height && $original_width > $set_width  ){
                
                $width = $set_width;
                $height = round( ( $set_width*$original_height ) / $original_width );

                if( $height < $set_height ){
                    $height = $set_height;
                    $width = round( ( $set_height*$original_width ) / $original_height );
                }

            }
            elseif($original_height > $set_height){

                $height = $set_height;
                $width = round( ( $set_height*$original_width ) / $original_height );

                if( $width < $set_width ){
                    $width = $set_width;
                    $height = round( ( $set_width*$original_height ) / $original_width );
                }

            }
            else{
                $width = $set_width;
                $height = $set_height;
            }

            $dst = array(0,0);
            $dst[0] = 0;
            if( $width > $set_width ){
                $dst[0] = ($width - $set_width)/2;
            }

            $dst[1] = 0;
            if( $height > $set_height ){
                $dst[1] = ($height - $set_height)/2;
            }

            // echo 1; die;
        }
        elseif( $set_width && !$set_height ){
            $width = $set_width;
            $height = ($original_height*$set_width)/$original_width;

            $set_height = $height;

            $dst = array(0,0);
        }
        elseif( !$set_width && $set_height ){

            $height = $set_height;
            $width = ($original_width*$set_height)/$original_height;

            $set_width = $width;
            $dst = array(0,0);
        }
        else{
            $width = $original_width;
            $height = $original_height;

            $set_width = $original_width;
            $set_height = $original_height;
            $dst = array(0,0);
        }

        $tmp = imagecreatetruecolor($set_width, $set_height);

        /* Check if this image is PNG or GIF, then set if Transparent*/
        if(($image_type == 1) OR ($image_type==3))
        {
            imagealphablending($tmp, false);
            imagesavealpha($tmp,true);
            $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
            imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
        }
        imagecopyresampled($tmp,$src, 0, 0, $dst[0], $dst[1], $width, $height, $original_width, $original_height);


        /*
         * imageXXX() only has two options, save as a file, or send to the browser.
         * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
         * So I start the output buffering, use imageXXX() to output the data stream to the browser, 
         * get the contents of the stream, and use clean to silently discard the buffered contents.
         */
        ob_start();

        switch ($image_type)
        {
            case 1: imagegif($tmp); break;
            case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
            case 3: imagepng($tmp, NULL, 0); break; // no compression
            default: echo ''; break;
        }

        // $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename,"."),1));

        switch( $file_extension ) {
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpeg"; break;
            default:
        }

        header("Content-Type: image/jpeg");
        header("Content-Disposition: inline; filename=\"{$download_file}\"");

        imagedestroy($tmp);
    }


    public function document($model='', $type='', $id='')
    {
        if( empty($this->me) || empty($id) || !in_array($type, array('pdf')) ) $this->error();

        if( $model=='tour' ){

            $item = $this->model->query('tour')->get( $id );
            if( empty($item) ) $this->error();

            if( $type=='pdf' && !empty($item['url_pdf']) ){
                $filename = $item['url_pdf'];
            }

            if( empty($filename) ) $this->error();
            $filename = strtolower(strrchr($filename, '/'));

            // echo $filename; die;
            $ext = $this->fn->q('file')->getExtension($filename);

            $local_file =  WWW_UPLOADS.'travel'.$filename;
            $download_file = "{$item['name']}{$ext}";

            if(file_exists($local_file) && is_file($local_file))
            {
                if( $type=='pdf' ){
                    header('Content-type: application/pdf');
                    header('Content-Disposition: inline; filename="' . $download_file . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . filesize($local_file));
                    header('Accept-Ranges: bytes');
                    @readfile($local_file);
                }
            }
            else{
                $this->error();
            }


        }
        else{
            header('location: '.URL);
        }
    }


    public function tour($id, $section='', $action='', $param=array())
    {
        $item = $this->model->query('tour')->get($id);
        if( empty($item) ) $this->error();

        if( $section=='period' ){

            $this->tour = $item;
            $this->period($action, $param);
        }
        else{

            $tab = $section;
            $this->view->setPage('on', 'manage_tour');

            $tabs = array();
            $tabs[] = array('id'=>'period', 'name'=>'Period', 'link'=>URL."manage/tour/{$id}/period");
            $tabs[] = array('id'=>'setting', 'name'=>'Setting', 'link'=>URL."manage/tour/{$id}/setting");

            $dropdownList = $this->model->query('tour')->getActionsMore( $item );

            // $this->view->setData('tab', $tab);
            // $this->view->setData('tabs', $tabs );
            $this->view->render('manage/tour/profile/display', array(
                'item' => $item,
                'opt' => array(
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
                )
            ));
        }
    }
    public function period($id, $options='')
    {
        if( $id=='create' ){

            $this->view->setData('cancelmodeList', $this->model->query('tour')->period->auto_cancel_mode());
            $this->view->setData('statusList', $this->model->query('tour')->period->status() );
            $this->view->setData('item', $this->tour );

            $this->view->setPage('path', 'Themes/admin/forms/tour/period');
            $this->view->render("add");
        }
        else{

            $bus = isset($options[4])? $options[4]: '';
            $action = isset($options[5])? $options[5]: '';
            if( empty($bus) ) $this->error();

            $item = $this->model->query('tour')->period->get($id, array('bus'=>$bus));
            if( empty($item) ) $this->error();

            if( $action=='edit' ){
                $this->view->setData('cancelmodeList', $this->model->query('tour')->period->auto_cancel_mode());
                $this->view->setData('statusList', $this->model->query('tour')->period->status() );

                $this->view->setData('tour', $this->tour );
                $this->view->setData('item', $item );

                $this->view->setPage('path', 'Themes/admin/forms/tour/period');
                $this->view->render("edit");
            }

            else{
                
                $tabs = array();
                $tabs[] = array('id'=>'booking', 'name'=>'รายละเอียดการจอง', 'link'=>URL."tour/period/{$id}/{$bus}/booking");
                $tabs[] = array('id'=>'traveler', 'name'=>'ข้อมูลผู้เดินทาง', 'link'=>URL."tour/period/{$id}/{$bus}/traveler");

                
                $this->view->setData( 'opt', array(
                    'title' => "{$this->tour['code']} - {$this->tour['name']}",
                    'controls' => array(
                          '<a class="btn" title="Refresh List" data-control-action="refresh"><i class="icon-refresh"></i></a>'
                        // , '<a class="btn" title="Show Sidebar" data-control-action="showsidebar">Sidebar</a>'
                    ),
                    'tab' => array(
                        'current' => $action,
                        'items' => $tabs
                    )
                ) );
                

                $this->view->setData('tour', $this->tour );
                $this->view->setData('item', $item );

                $this->view->render("tour/period/display");
            }
        }
    }
}