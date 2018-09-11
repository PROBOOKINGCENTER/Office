<?php

class Export_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
    	$this->error();
    }

    public function tagged_bag($period='', $bus='')
    {
    	/*if( isset($_SERVER['HTTP_REFERER']) ){
    	
	    	$URL_REF = parse_url($_SERVER['HTTP_REFERER']);
	  		$URL_REF_HOST =   $URL_REF['host'];

	  		if( $URL_REF_HOST!='admin.probookingcenter.com' ){
	  			header('location:'.URL);
	  		}
  		}
  		else{
  			header('location:'.URL);
  		}*/

    	$period = !empty($_REQUEST['period']) ? $_REQUEST['period']: $period;
    	$bus = !empty($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
    	if( empty($period) ) $this->error();


    	$plan = $this->model->query('tour')->traveler->plan( $period, $bus );
    	print_r($plan); die;

    	$items = $this->model->query('tour')->traveler->lists( array(
    		'period' => $period,
    		'bus' => $bus,
    	) );

    	
    	$leader = $this->model->query('tour')->traveler->leader( $period, $bus );
    	// print_r($leader); die;
    	if( !empty($leader) ){
    		$leader['is_leader'] = 1;
    		$leader['tagbag_code'] = $plan['tagbag_code'];
    		$items[] = $leader;
    	}

    	$source = "Themes/admin/pages/export/tagbag.php";
        $path = WWW_VIEW.$source;
        if( file_exists($path) ){

	    	// $var = 'Hi';
	    	ob_start();
			include_once $path;
			$var=ob_get_contents(); 
			ob_end_clean();

			echo $var; die;
	    	$this->output( array(
	    		'filename' => "Tag Bag - {$plan['code']} {$plan['title']}.pdf",
	    		'content' => $var,
	    		'margin_left' => 8,
				'margin_right' => 8,
				'margin_top' => 6,
	    	) );
    	}
    	else{
    		$this->error();
    	}
    }

    public function immigration($period='', $bus='')
    {
    	/*if( isset($_SERVER['HTTP_REFERER']) ){
    	
	    	$URL_REF = parse_url($_SERVER['HTTP_REFERER']);
	  		$URL_REF_HOST =   $URL_REF['host'];

	  		if( $URL_REF_HOST!='admin.probookingcenter.com' ){
	  			header('location:'.URL);
	  		}

  		}
  		else{
  			header('location:'.URL);
  		}*/


    	$period = !empty($_REQUEST['period']) ? $_REQUEST['period']: $period;
    	$bus = !empty($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
    	// $country = !empty($_REQUEST['bus']) ? $_REQUEST['bus']: $bus;
    	if( empty($period) ) $this->error();

    	$plan = $this->model->query('tour')->traveler->plan( $period, $bus );
    	// print_r($plan); die;

    	$items = $this->model->query('tour')->traveler->lists( array(
    		'period' => $period,
    		'bus' => $bus,
    	) );
    	
    	$leader = $this->model->query('tour')->traveler->leader( $period, $bus );
    	// print_r($leader); die;
    	if( !empty($leader) ){
    		$leader['is_leader'] = 1;
    		$leader['tagbag_code'] = $plan['tagbag_code'];
    		$items[] = $leader;
    	}
    	// print_r($items); die;
    	
    	$source = "Themes/admin/pages/export/immigration.php";
        $path = WWW_VIEW.$source;
        if( file_exists($path) ){

	    	ob_start();
			include_once $path;
			$var=ob_get_contents(); 
			ob_end_clean();

			// echo $var; die;
	    	$this->output( array(
	    		'filename' => "Tag Bag - {$plan['code']} {$plan['title']}.pdf",
	    		'content' => $var,
	    		'format' => array(150, 207.69),
	    	) );
    	}
    	else{
    		$this->error();
    	}
    }

    public function output($settings=array())
    {
    	if( empty($settings['filename']) && empty($settings['content']) ) header('location:'.URL);
    	// print_r($settings); die;

    	require_once WWW_VENDORS.'/mPDF/mpdf.php';
    	$settings = array_merge( array(
			'title' => '',
			'format' => ' A4', // A4, A4-L, Legal
			'mode' => 'fullpage', // real,

			'font_size' => 12,
			'font' => 'thsarabun',

			'margin_left' => 0,
			'margin_right' => 0,
			'margin_top' => 0,
			'margin_bottom' => 0,
			'margin_header' => 0,
			// 'margin_footer' => 5,

			'filename' => $settings['filename']
		), $settings);

		$mpdf = new mPDF(
		      'th'
		    , $settings['format']
		    , $settings['font_size']
		    , $settings['font'] 

		    , $settings['margin_left']
		    , $settings['margin_right']
		    , $settings['margin_top']
		    , $settings['margin_bottom']
		    , $settings['margin_header']
		    , $settings['margin_footer'] 
		);

		// $mpdf->debug = true;
		// $mpdf->allow_charset_conversion = true;
		/*$mpdf->charset_in = 'iso-8859-4';
		$mpdf->useOnlyCoreFonts = false;*/
		$mpdf->charset_in='UTF-8';
		$mpdf->SetDisplayMode( $settings['mode'] );

		$mpdf->defaultheaderfontsize=10;
		$mpdf->defaultheaderfontstyle='B';
		$mpdf->defaultheaderline=0;
		$mpdf->defaultfooterline=0;

		// $mpdf->WriteHTML('.table{width:100%;border-spacing:0;border-collapse:collapse}',1);

		# render (full page)
		$mpdf->WriteHTML( $settings['content'] );

		# Outputs ready PDF
		$mpdf->Output( $settings['filename'], 'I' );
    }
}