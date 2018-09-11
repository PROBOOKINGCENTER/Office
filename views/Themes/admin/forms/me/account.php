<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("user_name")
        ->label( Translate::Val('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('autofocus', 1)
        ->value( $this->me['username'] );

$form   ->field("user_email")
        ->label( Translate::Val('Email') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( $this->me['email'] );

$form   ->field("user_lang")
        ->label( Translate::Val('Language') )
        ->addClass('inputtext')
        ->select( array(0=>
              array('id'=>'th','name'=>'ภาษาไทย - Thai')
            , array('id'=>'en','name'=>'English (United States)') //อังกฤษ
        ), 'id', 'name', '' )
        ->value( !empty($this->me['lang']) ? $this->me['lang']:'en' );


# set form
$arr['form'] = '<form data-form-action="submit" method="post" action="'.URL. 'me/save/account"></form>';

# body
$arr['body'] = $form->html();

# title
$arr['title']= 'Account';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


echo json_encode($arr);