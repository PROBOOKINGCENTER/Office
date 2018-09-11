<?php

# title
$title = 'Country';

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

$form   ->field("country_code")
        ->label( 'Name*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'selectflag')
        ->attr('data-options', Fn::stringify( array('data'=>$this->flagList) ) )
        ->value( !empty($this->item['code'])? $this->item['code']:'' );

$form 	->field("country_name")
    	->label( '&nbsp;' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("country_description")
        ->type( 'textarea' )
        ->label( 'Description' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
         ->attr('data-plugin', 'autosize')
        ->value( !empty($this->item['description'])? $this->item['description']:'' );


# set form
$arr['form'] = '<form class="js-submit-form form-country" method="post" action="'.URL. 'location/save/country/"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
