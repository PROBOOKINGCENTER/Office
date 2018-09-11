<?php


$form = new Form();
$formInfo = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert form-agency-company clearfix')

    ->field("agen_com_name_th")
        ->label('ชื่อภาษาไทย')
        ->addClass('inputtext')
        // ->required(true)
        ->autocomplete("off")
        ->value( !empty($this->item['name_th']) ? $this->item['name_th']:'' )

    ->field("agen_com_name")
        ->label('ชื่อภาษาอังกฤษ*')
        ->addClass('inputtext')
        // ->required(true)
        ->autocomplete("off")
        ->value( !empty($this->item['name']) ? $this->item['name']:'' )

    ->field("agen_com_code")
        ->label("Auto Code (3 ตัวอักษร)")
        ->addClass("inputtext")
        ->value( !empty($this->item['code']) ? $this->item['code'] :'' )

    ->field("license_number")
        ->label('เลขที่ใบอนุญาติ')
        ->addClass('inputtext')
        // ->required(true)
        ->autocomplete("off")
        ->value( !empty($this->item['license_number']) ? $this->item['license_number']:'' )

    ->field("license_type")
        ->label('ประเภทการจดทะเบียน')
        ->addClass('inputtext')
        ->select( $this->license_types )
        ->value( !empty($this->item['license_type']) ? $this->item['license_type']:'' )

    ->field("location_address")
        ->label('ที่อยู่')
        ->type('textarea')
        ->addClass('inputtext')
        // ->attr('data-plugins', 'autosize')
        ->value( !empty($this->item['location_address']) ? $this->item['location_address']:'' )

    ->field("location_city")
        ->label('จังหวัด')
        ->addClass('inputtext')
        ->select( $this->city )
        ->value( !empty($this->item['location_city']) ? $this->item['location_city']:'' )

    ->field("location_zip")
        ->label('รหัสไปรษณีย์')
        ->addClass('inputtext')
        ->value( !empty($this->item['location_zip']) ? $this->item['location_zip']:'' )

    ->field("agen_com_tel")
        ->label('โทรศัพท์')
        ->addClass('inputtext')
        ->autocomplete("off")
        ->value( !empty($this->item['tel']) ? $this->item['tel']:'' )

    ->field("agen_com_email")
        ->label('เมล์')
        ->addClass('inputtext')
        ->autocomplete("off")
        ->value( !empty($this->item['email']) ? $this->item['email']:'' )

    ->field("social_line")
        ->label('Line ID')
        ->addClass('inputtext')
        ->value( !empty($this->item['social_line']) ? $this->item['social_line']:'' )

    ->field("social_facebook")
        ->label('Facebook')
        ->addClass('inputtext')
        ->value( !empty($this->item['social_facebook']) ? $this->item['social_facebook']:'' )

    ->field("agen_com_website")
        ->label('Website')
        ->addClass('inputtext')
        ->autocomplete("off")
        ->value( !empty($this->item['website']) ? $this->item['website']:'' )

/*    ->field("agen_com_website_grade")
        ->label('Website Level')
        ->addClass('inputtext')
        ->select( $this->grade )
        ->value( !empty($this->item['website_grade']) ? $this->item['website_grade']:'' )*/

->html();


$form = new Form();
$formHost = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-company clearfix')

    ->field("guarantee")
        // ->label('Guarantee')
        ->type( 'checkbox' )
        ->items( array(0=>
            array('id'=>1, 'name'=>'<i class="icon-thumbs-up mrs"></i><span>Guarantee</span>')

        ) )
        ->value( !empty($this->item['guarantee']) ? $this->item['guarantee']: 0 )


    ->field("status")
        // ->label('Guarantee')
        ->type( 'radio' )
        ->items( $this->status )
        ->checked( isset($this->item['status']) ? $this->item['status']: 1 )

/*    ->field("agen_com_username")
        ->label('Username')
        ->addClass('inputtext')
        ->required(true)
        ->autocomplete("off")
        ->value( !empty($this->item['username']) ? $this->item['username']:'' )

    ->field("agen_com_theme")
        ->label('Theme')
        ->addClass('inputtext')
        ->select( $this->theme )
        ->value( !empty($this->item['theme']['id']) ? $this->item['theme']['id']:'' )*/

->html();

# set form
$arr['form'] = '<form class="js-submit-form form-companies" method="post" action="'.URL.'agency/save/company"></form>';

# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal">'.$formInfo.'</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formHost.'</td>'.
'</tr></tbody></table></div>';

if( !empty($this->item) ){
    $arr['title']= "Edit Company";
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= "Create Company";
}


$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 1050;
echo json_encode($arr);
