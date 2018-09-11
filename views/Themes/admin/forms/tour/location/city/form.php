<?php

# title
$title = 'City';

if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);

    $is_country = false;
    foreach ($this->countryList as $key => $value) {
        if( $value['id']==$this->item['country_id'] ){
            $is_country = true;
            break;
        }
    }

    if( !$is_country ){
        $this->countryList[] = array(
            'id' => $this->item['country_id'],
            'name' => $this->item['country_name'] .' - ปิดการใช้งาน' ,
        );
    }

}
else{
    $arr['title']= $title;
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("city_country_id")
        ->label( 'Country*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->countryList, 'id', 'name', false )
        ->value( !empty($this->item['country_id'])? $this->item['country_id']:'' );


$form   ->field("city_name")
        ->label( 'Name*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );
        
$typeList = array();
$typeList[] = array('id'=>'1', 'name'=>'City');
$typeList[] = array('id'=>'2', 'name'=>'Capital');
$typeList[] = array('id'=>'3', 'name'=>'Island');
$form   ->field("city_type")
        ->type('radio')
        ->items( $typeList )
        ->checked( !empty($this->item['type'])? $this->item['type']:1 );


$form   ->field("city_description")
        ->type('textarea')
        ->label( 'Description' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('data-plugin', 'autosize')
        ->value( !empty($this->item['description'])? $this->item['description']:'' );


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'tour/location/city/save"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
