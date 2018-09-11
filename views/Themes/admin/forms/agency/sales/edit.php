<?php


$form = new Form();
$formInfo = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert form-agency-sales clearfix')->style( 'horizontal' )


    ->field("agen_fname")
        ->label(Translate::Val('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อ')
        ->attr('autofocus', '1')
        ->value( !empty($this->item['fname'])? $this->item['fname']:'' )

    ->field("agen_lname")
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('นามสกุล')
        ->value( !empty($this->item['lname'])? $this->item['lname']:'' )

    ->field("agen_nickname")
        ->label(Translate::Val('ชื่อเล่น'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อเล่น')
        ->value( !empty($this->item['nickname'])? $this->item['nickname']:'' )

    ->field("agency_company_id")
        ->type( 'select' )
        ->label( Translate::Val('Company').'*')
        ->autocomplete('off')
        ->attr('data-plugin', 'selectize')
        ->select( $this->companyList )
        ->value( !empty($this->item['company_id'])? trim($this->item['company_id']):'' )

    ->field("agen_position")
        ->label( Translate::Val('Position') )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['position'])? trim($this->item['position']):'' )
        

    ->field("agen_email")
        ->label( 'Email' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['email'])? $this->item['email']:'' )

    ->field("agen_tel")
        ->label($this->lang->translate('Phone'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['tel'])? $this->item['tel']:'' )

    ->field("agen_line_id")
        ->label($this->lang->translate('Line ID'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['line_id'])? $this->item['line_id']:'' )


->html();


$form = new Form();
$formHost = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-agency-sales-right clearfix')

    ->field("status")
        ->type( 'radio' )
        ->items( $this->status )
        ->checked( isset($this->item['status']) ? $this->item['status']: 1 )

    ->field("agen_role")
        ->type( 'select' )
        ->label( Translate::Val('Role').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->roleList, 'id', 'name', false )
        ->value( !empty($this->item['role_id'])? trim($this->item['role_id']):'' )

    ->field("agen_user_name")
        ->label(Translate::Val('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['username'])? trim($this->item['username']):'' )


    /*->field('reset_password')
        ->text('<div class="fsm"><label class="checkbox" for="reset_password"><input type="checkbox" id="reset_password" name="reset_password"><span>ขอให้เปลี่ยนรหัสผ่านในการลงชื่อเข้าใช้ครั้งต่อไป</span></label></div>')*/

->html();

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'agency/save/sales"></form>';

# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal" style="vertical-align: top;">'.$formInfo.'</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formHost.'</td>'.
'</tr></tbody></table></div>';

$arr['title']= "Edit Agent";
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);



$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 750;
echo json_encode($arr);
