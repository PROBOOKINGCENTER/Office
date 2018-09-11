<?php

$caleranOpt = array(
    'showButtons'=> true,
    'format'=> 'DD/MM/YYYY',
);

$date = '';
if( !empty($this->item) ){
    // $date = date('d/m/Y', strtotime($this->item['start_date']))." - ".date('d/m/Y', strtotime($this->item['end_date']));

    $caleranOpt['startDate'] = date('d/m/Y', strtotime($this->item['start_date']));
    $caleranOpt['endDate'] = date('d/m/Y', strtotime($this->item['end_date']));
}

$imageCoverOpt = array(
    'name' => 'file_image',

);

if( !empty($this->item['image_url']) ){
    $imageCoverOpt['src'] = $this->item['image_url'];
}


$form = new Form();
$formInfo = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert formInfo clearfix')

    ->field("pro_image")
        ->text( $this->fn->q('form')->imageCover( $imageCoverOpt ) )
    

    ->field("pro_name")
        ->label('ชื่อ*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('ชื่อ')
        ->attr('autofocus', '1')
        ->value( !empty($this->item['name']) ? $this->item['name']: ''  )

    ->field("pro_date")
    	->label('ระยะเวลากิจกรรม*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'caleran')
        ->attr('data-options', Fn::stringify( $caleranOpt ))
        ->placeholder('')
        // ->value( $date )

    /*->field("pro_discount_type")
        ->label(Translate::Val('ประเภทส่วนลด'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')*/

    ->field("pro_discount")
        ->label(Translate::Val('ส่วนลด'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'input__num')
        ->placeholder('')
        ->value( !empty($this->item['discount']) ? $this->item['discount']: '' )

    ->field("pro_description")
        ->label(Translate::Val('Description'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->type('textarea')
        // ->attr('data-plugin', 'autosize')
        ->placeholder('')
        ->value( !empty($this->item['description']) ? $this->item['description']: '' )

->html();


$opt = array(
    'items' => !empty($this->item['items']) ? $this->item['items']: array()
);

$form = new Form();
$formHost = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-promotion clearfix')


    ->field("pro_items")
        // ->label(Translate::Val('ราการที่ต้องการลดราคา'))
        ->text('<div class="promotion-select-item" data-plugin="promotionSelectItem" data-options="'.Fn::stringify( $opt ).'"><table class="promotion-table-item"><tbody><tr><td class="country"><h3>Country</h3><ul role="country"></ul></td><td class="series"><h3>Series</h3><ul role="series"></ul></td><td class="period"><h3 class="clearfix"><span class="lfloat">พีเรียด</span><span class="rfloat fwn period-action"><a data-period-action="all">เลือกทั้งหมด</a> / <a data-period-action="cancel">ยกเลิกทั้งหมด</a></span></h3><div class="table"><table><thead><tr><th class="tal"></th><th class="tal">วันเดินทาง</th><th class="tar">Bus/Seat</th></tr></thead><tbody role="period"></tbody></table></div></td><td class="preview"><h3 class="clearfix"><span class="lfloat">สรุป</span><span class="rfloat fwn"><a data-action="cancel">ยกเลิกทั้งหมด</a></span></h3><div class="items" role="preview"></div></td></tr></tbody></table></div>')

->html();
# set form
$arr['form'] = '<form class="form-promotion js-submit-form" method="post" action="'.URL.'promotion/save/" enctype="multipart/form-data"></form>';

# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td style="width:270px;vertical-align: top;">'.$formInfo.'</td>'.
    '<td style="vertical-align: top;">'.$formHost.'</td>'.
'</tr></tbody></table></div>';

if( !empty($this->item) ){
    $arr['title']= "Edit Promotion";
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= "Create Promotion";
}

$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 1050;
echo json_encode($arr);