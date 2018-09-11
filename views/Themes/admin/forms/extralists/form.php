<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
    ->style( 'horizontal' )
	->addClass('form-insert');

$form   ->field("name")
        ->label(Translate::Val('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', '1')
        ->value( !empty($this->item['name']) ? $this->item['name']: '' );

$form   ->field("price")
        ->type('number')
        ->label(Translate::Val('Price').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['price']) ? round($this->item['price']): '' );

/*$form   ->field("description")
        ->type('textarea')
        ->label( Translate::Val('Description') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['description'])? $this->item['description']:'' );*/

# set form
$arr['form'] = '<form data-form-action="submit" method="post" action="'.URL. 'settings/extralists/save"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ) {
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
    $arr['title']= 'Edit extra lists';
}
else{
    $arr['title']= 'Create extra lists';
}


# fotter: button
$arr['button'] = '<a class="btn btn-link" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a><button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
// $arr['bottom_msg'] = '';


echo json_encode($arr);