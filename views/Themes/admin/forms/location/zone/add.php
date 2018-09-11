<?php

# title
$title = 'Zone';

if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("zone_country_id")
        ->label( 'Province*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->countryList, 'id', 'name', false )
        ->value( !empty($this->item['country_id'])? $this->item['country_id']:'' );

$form   ->field("zone_province_id")
        ->label( 'Province*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->provinceList )
        ->value( !empty($this->item['province_id'])? $this->item['province_id']:'' );

$form   ->field("zone_name")
        ->label( 'Name*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("zone_postcode")
    	->label( 'Post Code' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['postcode'])? $this->item['postcode']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'location/save/zone/"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
