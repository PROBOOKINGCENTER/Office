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
        '<td class="name"><label>'.$value['name'].'</label></td>'.
        // '<td  class="price"><input type="name" data-plugin="input__num" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$value['value'].'" class="inputtext" autocomplete="off"></td>'.
        // '<td  class="price"><input type="name" data-plugin="input__num" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$value['value'].'" class="inputtext" autocomplete="off"></td>'.
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



// ->style( 'horizontal' )
$form = new Form();
$formInfo = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert clearfix')


    ->field("date")
        ->label(Translate::Val('วันที่เดินทาง').'*')
        ->autocomplete('off')
        ->addClass('inputtext')

        ->attr('data-plugin', 'caleran')
        ->attr('data-options', Fn::stringify( array(
            'continuous'=> true
        ) ))
        ->value('')
        // ->placeholder('')

    ->hr('<div class="ui-hr-text white"><span>'.Translate::Val('Bus').'</span></div>')
    ->field("bus")->text(
        '<table class="form-table-peried-price"><tr><th class="name">รายการ</th><th class="price">ที่นั้ง</th><th class="action"></th></tr><thead></thead><tbody role="buslist"></tbody></table>'.
        '<div class="mts tar"><a data-bus-action="add">เพิ่ม bus</a></div>'
    )


    ->hr('<div class="ui-hr-text white"><span>ไฟล์เตรียมตัวเดินทาง</span></div>')
    ->field("source")
        ->text( '<div class="table-document-wrap">'.

            // '<div class="phm pvs uiBoxYellow">Select a file on your computer (Max size 100 MB)</div>'.

            '<table class="table-document"><tbody>'.

                '<tr>'.
                    '<td class="td-label">'.
                        '<div class="i-s2 word"></div>'.
                    // Word
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_word_fieldset" class="control-group">'.
                            '<input type="file" name="file_word" accept=".doc, .docx, application/msword">'.
                            '<div class="notification"></div>'.
                        '</fieldset>'.
                    '</td>'.
                    '<td class="td-action"></td>'.
                '</tr>'.

                '<tr>'.
                    '<td class="td-label">'.
                        // 'PDF'.
                        '<div class="i-s2 pdf"></div>'.
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_pdf_fieldset" class="control-group">'.
                            '<input type="file" name="file_pdf" accept="application/pdf">'.
                            '<div class="notification"></div>'.
                        '</fieldset>'.
                        
                    '</td>'.
                    '<td class="td-action"></td>'.
                '</tr>'.

        '</tbody></table></div>' )

    /*->hr('<div class="ui-hr-text white"><span>แนบไฟล์ใบต้นทุน(Cost)</span></div>')
    ->field("file_cost")
        ->text( '<div class="table-document-wrap">'.

            '<table class="table-document"><tbody>'.

                '<tr>'.
                    '<td class="td-label">'.
                        '<div class="i-s2 word"></div>'.
                    // Word
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_word_fieldset" class="control-group">'.
                            '<input type="file" name="file_cost" accept="application/pdf">'.
                            '<div class="notification"></div>'.
                        '</fieldset>'.
                    '</td>'.
                    '<td class="td-action"></td>'.
                '</tr>'.

        '</tbody></table></div>' )*/

->html();


$form = new Form();
$formMiddle = $form->create()->elem('div')->addClass('form-insert clearfix')


    ->field("price")
        ->label(Translate::Val('Price'))
        ->text(

        '<table class="form-table-peried-price" role="tableprice">'.
            '<thead>'.
                '<tr class="title1"><th class="name" rowspan="2">รายการ</th><th class="price" colspan="2">ที่นั้ง</th></tr>'.
                '<tr class="title2"><th class="bus">Bus 1</th><th class="bus">Bus 2</th></tr>'.
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
    ->addClass('form-insert form-agency-sales-right clearfix')

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
$arr['form'] = '<form class="form-create-peried"  data-action="inlineSubmit" method="post" action="'.URL.'period/save" data-plugin="peried__form"  enctype="multipart/form-data"></form>';
$arr['hiddenInput'][] = array('name'=>'ser_id','value'=>$this->tour['id']);


# body
$arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
    '<td class="pal" style="vertical-align: top;">'.$formInfo.'</td>'.
    '<td style="width:400px;vertical-align: top;border-left: 1px dotted #dedede;" class="pal">'.$formMiddle.'</td>'.
    '<td style="width:270px;background-color: #eee;vertical-align: top;" class="pal">'.$formHost.'</td>'.
'</tr></tbody></table></div>';

$arr['title']= "Create Peried";


$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 960;
echo json_encode($arr);
