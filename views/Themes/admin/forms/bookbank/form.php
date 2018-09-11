<?php

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert')->style('horizontal');

$form   ->field("bank_name")
        ->label('ธนาคาร')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['bank_name']) ? $this->item['bank_name']: '' );

$form   ->field("bankbook_branch")
        ->label( 'สาขาธนาคาร' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['branch']) ? $this->item['branch']: '' );


$form   ->field("bankbook_code")
        ->label( 'เลขบัญชี' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['code'])? $this->item['code']:'' );


$form   ->field("bankbook_name")
        ->label( 'ชื่อบัญชี' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

# set form
$arr['form'] = '<form class="form-bookbank" data-form-action="submit" method="post" action="'.URL. 'settings/bookbank/save"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ) {
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
    $arr['title']= 'Edit Book Bank';
}
else{
    $arr['title']= 'New create Book Bank';
}


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


echo json_encode($arr);