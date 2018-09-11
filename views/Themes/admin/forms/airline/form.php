<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("air_name")
        ->label(Translate::Val('Airline Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', '1')
        ->value( !empty($this->item['name']) ? $this->item['name']: '' );


$form   ->field("air_code")
        ->label(Translate::Val('Code'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['code']) ? $this->item['code']: '' );


$form   ->field("remark")
        ->type('textarea')
        ->label( Translate::Val('Description') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['remark'])? $this->item['remark']:'' );

# set form
$arr['form'] = '<form class="form-airline" data-form-action="submit" method="post" action="'.URL. 'settings/airline/save"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ) {
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
    $arr['title']= 'Edit airline';
}
else{
    $arr['title']= 'New create airline';
}


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


echo json_encode($arr);