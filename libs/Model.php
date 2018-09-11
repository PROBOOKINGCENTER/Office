<?php

class Model {

    function __construct() {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $this->fn = new Fn();

        $this->lang = new Langs();

        Session::init();
        $lang = Session::get('lang');

        if( isset($lang) ){
            $this->lang->set( $lang );
        }
    }

    // private query protected
    private $_query = array();

    // Public query
    public function query( $table=null ){

        // echo $this->lang->getCode(); die;

        $path = "models/{$table}_model.php";
        
        if(!array_key_exists($table, $this->_query) && file_exists($path)){

            require_once $path;
            $modelName = $table . '_Model';
            $this->_query[$table] = new $modelName();
            
        }

        return $this->_query[$table];
        
    }
    protected function limited($limit=0, $pager=1, $del=0){
        return "LIMIT ".((($pager*$limit)-$limit)-$del) .",". $limit;
    }

    protected function orderby($sort, $dir='DESC'){
        return "ORDER BY ".( $dir=='rand'  ? "rand()": "{$sort} {$dir}" );
    }
    protected function __cutPrefixField($search, $results)  {
        $data = array();
        foreach ($results as $key => $value) {
            $data[ str_replace($search, '', $key) ] = $value;
        }
        return $data;
    }
    
}