<?php

class Download_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->error();
    }

    public function document($type='', $id='')
    {

        // $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || !in_array($type, array('word', 'pdf', 'banner')) ) $this->error();

        $item = $this->model->query('tour')->get( $id );
        if( empty($item) ) $this->error();

        // print_r($item); die;

        if( $type=='word' && !empty($item['url_word']) ){
            $filename = $item['url_word'];
        }

        if( $type=='pdf' && !empty($item['url_pdf']) ){
            $filename = $item['url_pdf'];
        }

        if( $type=='banner' && !empty($item['url_img_1']) ){
            $filename = $item['url_img_1'];
        }

        if( empty($filename) ) $this->error();
        $filename = strtolower(strrchr($filename, '/'));

        // echo $filename; die;
        $ext = $this->fn->q('file')->getExtension($filename);

        $local_file =  WWW_UPLOADS.'travel/'.$filename;
        $download_file = "{$item['name']}{$ext}";

        // echo $local_file; die;

        // set the download rate limit (=> 20,5 kb/s)
        $download_rate = 20.5;
        if(file_exists($local_file) && is_file($local_file))
        {
            header('Cache-control: private');
            header('Content-Type: application/octet-stream');
            header('Content-Length: '.filesize($local_file));
            header('Content-Disposition: filename='.$download_file);

            flush();
            $file = fopen($local_file, "r");
            while(!feof($file))
            {
                // send the current file part to the browser
                print fread($file, round($download_rate * 1024));
                // flush the content to the browser
                flush();
                // sleep one second
                // sleep(1);
            }
            fclose($file);
        }
        else {

            $this->error();
            // die('Error: The file '.$local_file.' does not exist!');
        }
    }
}