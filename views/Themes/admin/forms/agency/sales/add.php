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

    ->field("agen_lname")
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('นามสกุล')

    ->field("agen_nickname")
        ->label(Translate::Val('ชื่อเล่น'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อเล่น')


    ->field("agency_company_id")
        ->type( 'select' )
        ->label( Translate::Val('Company').'*')
        ->autocomplete('off')
        // ->addClass('inputtext')
        ->attr('data-plugin', 'selectize')
        ->select( $this->companyList )

    ->field("agen_position")
        ->label( Translate::Val('Position') )
        ->autocomplete('off')
        ->addClass('inputtext')


    ->field("agen_email")
        ->label( 'Email' )
        ->autocomplete('off')
        ->addClass('inputtext')

    ->field("agen_tel")
        ->label($this->lang->translate('Phone'))
        ->autocomplete('off')
        ->addClass('inputtext')

    ->field("agen_line_id")
        ->label($this->lang->translate('Line ID'))
        ->autocomplete('off')
        ->addClass('inputtext')


->html();


$form = new Form();
$formHost = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-agency-sales-right clearfix')


    ->field("agen_role")
        ->type( 'select' )
        ->label( Translate::Val('Role').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->roleList, 'id', 'name', false )

    ->field("agen_user_name")
        ->label(Translate::Val('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')

    ->field("agen_password")
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
$arr['form'] = '<form class="form-agency-sales-wrap" method="post" action="'.URL.'agency/save/sales"></form>';

# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal" style="vertical-align: top;">'.$formInfo.'</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formHost.'</td>'.
'</tr></tbody></table></div>';


$arr['title']= "Create Agent";


$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 750;
echo json_encode($arr);
