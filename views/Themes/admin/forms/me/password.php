<?php

$f = new Form();
$form = $f->create()
	
	// set From
	->elem('div')
	->addClass('form-insert clearfix')

	->field("password_auto")
	->text('<label class="checkbox"><input type="checkbox" name="password_auto" id="password_auto">'.Translate::val('Create an auto password').'</label>')

	->field("password_new")
		->label(Translate::Val('New Password'))
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off")

	->field("password_confirm")
		->label(Translate::Val('Confirm Password'))
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off");

$arr['body'] = $form->html();
$arr['title'] = Translate::val('Reset password');	

$arr['form'] = '<form class="form-reset-password" action="'.URL.'me/save/password"></form>';
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


// $arr['width'] = 330;
echo json_encode($arr);