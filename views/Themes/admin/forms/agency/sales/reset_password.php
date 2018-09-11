<?php

$f = new Form();
$form = $f->create()
	
	// set From
	->elem('div')
	->addClass('form-insert clearfix')

	->field("password_auto")
	->text('<label class="checkbox"><input type="checkbox" name="password_auto" id="password_auto">สร้างรหัสผ่านอัตโนมัติ</label>')

	->field("password_new")
		->label($this->lang->translate('New Password'))
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off")

	->field("password_confirm")
		->label($this->lang->translate('Confirm Password'))
		->type('password')
		->addClass('inputtext')
		->maxlength(30)
		->required(true)
		->autocomplete("off")

	->field('reset_password')
        ->text('<div class="fsm"><label class="checkbox" for="reset_password"><input type="checkbox" id="reset_password" name="reset_password"><span>ขอให้เปลี่ยนรหัสผ่านในการลงชื่อเข้าใช้ครั้งต่อไป</span></label></div>');

$arr['hiddenInput'][] = array('name'=>'id', 'value'=> $this->item['id']);
$arr['body'] = $form->html();
$arr['title'] = 'รีเซ็ตรหัสผ่าน';	

$arr['form'] = '<form class="form-reset-password" action="'.URL.'agency/reset_password"></form>';
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


// $arr['width'] = 330;
echo json_encode($arr);