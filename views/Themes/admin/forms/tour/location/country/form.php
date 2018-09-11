<?php

# title
$title = 'Country';

$imageCoverOpt = array(
    'name' => 'file_image',
    'width' => 380,
    'height' => 228,
);

if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);

    if( !empty($this->item['image_url']) ){
        $imageCoverOpt['src'] = $this->item['image_url'];
    }
}
else{
    $arr['title']= $title;
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert')
    ->style('horizontal');


$form   ->field("file_image")
        ->label('Banner')
        ->text( $this->fn->q('form')->imageCover( $imageCoverOpt ) );


$form   ->field("country_code")
        ->label( 'Flag' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'selectflag')
        ->attr('data-options', Fn::stringify( array('data'=>$this->flagList) ) )
        ->value( !empty($this->item['code'])? $this->item['code']:'' );

$form 	->field("country_name")
    	->label( 'Name*' )
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


$form   ->field("status")
        ->label( 'สถานะ*')
        ->text( '<label class="switch"><input type="checkbox" name="status"'.( !empty($this->item['status'])? ' checked':'').' value="1"><span class="slider round"></span></label>' );


# set form
$arr['form'] = '<form class="js-submit-form form-country" method="post" action="'.URL. 'tour/location/country/save/"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">Cancel</span></a>';
$arr['width'] = 560;

echo json_encode($arr);
