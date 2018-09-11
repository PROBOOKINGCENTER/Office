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
        ->value( !empty($this->item['fname'])? trim($this->item['fname']):'' )

     ->field("user_lname")
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('นามสกุล')
        ->value( !empty($this->item['lname'])? trim($this->item['lname']):'' )

     ->field("user_nickname")
        ->label(Translate::Val('Nickname'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อเล่น')
        ->value( !empty($this->item['nickname'])? trim($this->item['nickname']):'' )

     ->field("user_email")
        ->label( 'Email' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['email'])? $this->item['email']:'' )

     ->field("user_tel")
        ->label($this->lang->translate('Phone'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['tel'])? $this->item['tel']:'' )

     ->field("user_line_id")
        ->label($this->lang->translate('Line'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['line_id'])? $this->item['line_id']:'' )

->html();


$form = new Form();
$formRigth = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-emp')
    
    ->field("status")
        ->type( 'radio' )
        ->items( $this->statusList )
        ->checked( isset($this->item['status']) ? $this->item['status']: 1 )

     ->field("group_id")
        ->type( 'select' )
        ->label( Translate::Val('Role').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->rolesList )
        ->value( !empty($this->item['role_id'])? trim($this->item['role_id']):'' )


     ->field("user_name")
        ->label(Translate::Val('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['username'])? trim($this->item['username']):'' )

->html();

# set form
$arr['hiddenInput'][] = array('name'=>'id', 'value'=> $this->item['id']);
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'users/edit"></form>';

# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal" style="vertical-align: top;">'.$formLeft.'</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formRigth.'</td>'.
'</tr></tbody></table></div>';

# title
$arr['title']= 'แก้ไขผู้ใช้';

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';
$arr['width'] = 750;

echo json_encode($arr);