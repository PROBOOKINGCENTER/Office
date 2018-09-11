<?php

$prices = array();
$prices[] = array('id'=>'per_price_1', 'name'=>'Adult');
$prices[] = array('id'=>'per_price_2', 'name'=>'Child');
$prices[] = array('id'=>'per_price_3', 'name'=>'Chlid No bed.');
$prices[] = array('id'=>'per_price_4', 'name'=>'Infant');
$prices[] = array('id'=>'per_price_5', 'name'=>'Joinland');
$prices[] = array('id'=>'single_charge', 'name'=>'Single Charge');
$priceTR ='';
foreach ($prices as $key => $value) {

    $value['value'] = isset($value['value'])? $value['value']: '';

    $priceTR .='<tr data-name="'.$value['id'].'">'.
        '<td class="name"><label for="'.$value['id'].'">'.$value['name'].'</label></td>'.
        '<td class="price"><input type="name" data-plugin="input__num" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$value['value'].'" class="inputtext" autocomplete="off"></td>'.
    '</tr>';
}



$expenses = array();
$expenses[] = array('id'=>'per_com_company_agency', 'name'=>'Agency Com');
$expenses[] = array('id'=>'per_com_agency', 'name'=>'Sales Com');
$expenses[] = array('id'=>'per_cost', 'name'=>'ราคาต้นทุน');
$expenses[] = array('id'=>'per_expenses', 'name'=>'ค่าใช้จ่ายอื่น ๆ');
$expensesTR ='';
foreach ($expenses as $key => $value) {

    $value['value'] = isset($value['value'])? $value['value']: '';

    $expensesTR .='<tr>'.
        '<td class="name"><label for="'.$value['id'].'">'.$value['name'].'</label></td>'.
        '<td  class="price"><input type="name" data-plugin="input__num" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$value['value'].'" class="inputtext" autocomplete="off"></td>'.
    '</tr>';
}


$form = new Form();
$formDetail = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert clearfix')

    ->field("bus_qty")
        ->label('จำนวนที่นั่ง')
        ->maxlength(2)
        ->autocomplete('off')
        ->attr('data-plugin', 'input__num')
        ->addClass('inputtext')

    ->field("price")
        ->label(Translate::Val('Price'))
        ->text(

        '<table class="form-table-peried-price" role="tableprice">'.
            '<thead>'.
                '<tr class="title1"><th class="name">รายการ</th><th class="price">ราคา</th></tr>'.
            '</thead>'.
            '<tbody>'.$priceTR.'</tbody>'.
        '</table>'
    )

    ->field("expenses")
        ->label('ค่าใช้จ่ายอื่น ๆ*')
        ->text('<table class="form-table-peried-price"><tbody><tr><th class="name">รายการ</th><th class="price">ราคา</th></tr>'.$expensesTR.'</tbody></table>')


->html();



$form = new Form();
$formHost = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert clearfix')

    ->field("cancel_mode")
        ->label('Booking Auto Cancel Mode*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->type('select')
        ->select( $this->auto_cancel_mode, 'id', 'name', false )

    ->field("per_discount")
        ->label('ส่วนลด (โปรไฟไหม้)*')
        // ->maxlength(30)
        ->autocomplete('off')
        ->attr('data-plugin', 'input__num')
        ->addClass('inputtext')


->html();

# set form
$arr['form'] = '<form class="form-buslist" data-action="inlineSubmit" method="post" action="'.URL.'period/save" enctype="multipart/form-data"></form>';
$arr['hiddenInput'][] = array('name'=>'ser_id','value'=>$this->tour['id']);


# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal" style="vertical-align: top;">'.
        '<div class="uiBoxWhite bottomborder pam"  style="margin:-20px -20px 10px;border-bottom-style: dotted;"><div class="pam uiBoxYellow"><ul class="uiListStandard">'.
            '<li>Date: '.$this->item['date_str'].'</li>'.
            '<li>Bus: '.$this->dataOpt['bus'].'</li>'.
        '</ul></div></div>'.
        $formDetail.
    '</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formHost.'</td>'.
'</tr></tbody></table></div>';


// $arr['summary'] = '<div style="margin:-10px -20px; -9px" class="uiBoxWhite bottomborder pam"></div>';

$arr['title']= "Edit Peried";


$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';
$arr['close'] = true;

$arr['width'] = 660;
echo json_encode($arr);