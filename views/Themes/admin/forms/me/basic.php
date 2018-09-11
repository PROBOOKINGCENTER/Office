<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("user_fname")
        ->label( Translate::Val('First name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('autofocus', 1)
        ->value( $this->me['fname'] );

$form   ->field("user_lname")
        ->label( Translate::Val('Last name') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( $this->me['lname'] );

$form   ->field("user_nickname")
        ->label( Translate::Val('Nickname') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( $this->me['nickname'] );

$form   ->field("user_address")
        ->label( Translate::Val('Address') )
        ->autocomplete('off')
        ->type( 'textarea' )
        ->attr('data-plugins', 'autosize')
        ->addClass('inputtext')
        ->value( $this->me['address'] );

$form   ->field("user_tel")
        ->label( Translate::Val('Phone') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( $this->me['tel'] );    

$form   ->field("user_line_id")
        ->label( Translate::Val('Line ID') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( $this->me['line_id'] );  


# set form
$arr['form'] = '<form data-form-action="submit" method="post" action="'.URL. 'me/save/basic"></form>';

# body
$arr['body'] = $form->html();

# title
$arr['title']= 'Basic';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


echo json_encode($arr);