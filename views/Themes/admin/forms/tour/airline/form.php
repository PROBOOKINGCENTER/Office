<?php

$imageCoverOpt = array(
    'name' => 'file_image',
    'width' => 128,
    'height' => 128,
);


# title
if( !empty($this->item) ) {
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
    $arr['title']= 'Edit Airline';

    if( !empty($this->item['image_url']) ){
        $imageCoverOpt['src'] = $this->item['image_url'];
    }
}
else{
    $arr['title']= 'Create airline';
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("file_image")
        ->label('Logo')
        ->text( $this->fn->q('form')->imageCover( $imageCoverOpt ) );

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

$form   ->field("status")
        ->label( 'สถานะ*')
        ->text( '<label class="switch"><input type="checkbox" name="status"'.( !empty($this->item['status'])? ' checked':'').' value="1"><span class="slider round"></span></label>' );
        
# set form
$arr['form'] = '<form class="form-airline" data-form-action="submit" method="post" action="'.URL. 'tour/airline/save"></form>';

# body
$arr['body'] = $form->html();




# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


echo json_encode($arr);