<?php

$form = new Form();
$formLeft = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert form-emp')->style( 'horizontal' )

    ->field("user_fname")
        ->label(Translate::Val('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อ')
        ->attr('autofocus', '1')

    ->field("user_lname")
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('นามสกุล')

    ->field("user_nickname")
        ->label(Translate::Val('Nickname'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อเล่น')

    ->field("user_email")
        ->label( 'Email' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['email'])? $this->item['email']:'' )

    ->field("user_tel")
        ->label($this->lang->translate('Phone'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['phone'])? $this->item['phone']:'' )

    ->field("user_line_id")
        ->label($this->lang->translate('Line'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['phone'])? $this->item['phone']:'' )

->html();


$form = new Form();
$formRigth = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-emp')

   ->field("group_id")
        ->type( 'select' )
        ->label( Translate::Val('Role').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->rolesList )

    ->field("user_name")
        ->label(Translate::Val('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')

    ->field("password")
        ->label(Translate::Val('Password').'*')
        ->type('password')
        ->maxlength(30)
        ->autocomplete('off')
        ->addClass('inputtext')

    ->field('auto_password')
        ->text('<div class="fsm"><label class="checkbox" for="auto_password"><input type="checkbox" id="auto_password" name="auto_password" checked><span>สร้างรหัสผ่านอัตโนมัติ</span></label></div>')


    ->field('reset_password')
        ->text('<div class="fsm"><label class="checkbox" for="reset_password"><input type="checkbox" id="reset_password" name="reset_password"><span>ขอให้เปลี่ยนรหัสผ่านในการลงชื่อเข้าใช้ครั้งต่อไป</span></label></div>')

->html();


# set form
$arr['form'] = '<form class="form-emp-add" method="post" action="'.URL. 'users/save"></form>';

# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal" style="vertical-align: top;">'.$formLeft.'</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formRigth.'</td>'.
'</tr></tbody></table></div>';


# title
$arr['title']= 'New create user';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';
$arr['width'] = 750;

echo json_encode($arr);