<?php

$priceVals = array();

$items = array();
$items[] = array('name'=>'1', 'labelValue'=>'Seat');
$priceVals[] = array('label'=>'บัส', 'items'=>$items, 'name'=>'buslist');


$_defaultPrices = array();
$_defaultPrices[] = array('name'=>'Adult', 'labelValue'=>'Price', 'key'=>'per_price_1');
$_defaultPrices[] = array('name'=>'Child', 'labelValue'=>'Price', 'key'=>'per_price_2');
$_defaultPrices[] = array('name'=>'Chlid No bed.', 'labelValue'=>'Price', 'key'=>'per_price_3');
$priceVals[] = array('label'=>'ราคาขาย', 'items'=>$_defaultPrices, 'name'=>'price_values');


/* extra_price */
$extra_price = array();
$extra_price[] = array('name'=>'Infant', 'labelValue'=>'Price', 'key'=>'per_price_4');
$extra_price[] = array('name'=>'Joinland', 'labelValue'=>'Price', 'key'=>'per_price_5');
$priceVals[] = array('label'=>'', 'items'=>$extra_price, 'name'=>'extra_price', 'actions'=>'disabled');

/* single_charge */
$single_charge = array();
$single_charge[] = array('name'=>'Single Charge', 'labelValue'=>'Price', 'key'=>'single_charge');
$priceVals[] = array('label'=>'', 'items'=>$single_charge, 'name'=>'single_charge', 'actions'=>'disabled');


$_defaultCommission = array();
$_defaultCommission[] = array('name'=>'Com Agency', 'labelValue'=>'Price', 'key'=>'per_com_company_agency');
$_defaultCommission[] = array('name'=>'Com Sales', 'labelValue'=>'Price', 'key'=>'per_com_agency');
$priceVals[] = array('label'=>'คอมมิชชั่น', 'items'=> $_defaultCommission, 'name'=>'commission');


$discount = array();
$discount[] = array('name'=>'โปรไฟไหม้', 'labelValue'=>'Price', 'key'=>'per_discount');
$priceVals[] = array('label'=>'ส่วนลด', 'items'=> $discount, 'name'=>'discounts');


/*$roomTypes = array();
$roomTypes[] = array('name'=>'Twin', 'value'=>2, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Double', 'value'=>2, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Triple(Twin)', 'value'=>3, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Triple', 'value'=>3, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Single', 'value'=>1, 'labelValue'=>'Pax');

$priceVals[] = array('label'=>'Room of Type', 'items'=> $roomTypes, 'name'=>'roomoftypes');
*/

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

    ->field("remark")
        ->label(Translate::Val('รายละเอียด'))
        ->autocomplete('off')
        ->addClass('inputtext')

        ->type('textarea')
        ->value('')

    ->hr('<div class="ui-hr-text white"><span style="background-color: #eee;">ไฟล์เตรียมตัวเดินทาง</span></div>')
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

    // ->hr('<div class="ui-hr-text white"><span></span></div>')
    
    ->field("cancel_mode")
        ->label( 'กำหนดการ ยกเลิกการจองอัตโนมัติ' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-name', 'cancel_mode')
        ->select( $this->cancelmodeList, 'id', 'name', false )


    /*->field("per_discount")
        ->label( 'ส่วนลด (โปรไฟไหม้)' )
        ->autocomplete('off')
        ->addClass('inputtext')*/

        
->html();


$form = new Form();
$formBus = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert clearfix')

    ->field("buslist")
    ->text( '<table class="table-pricevalues-form" data-plugin="pricevalues" data-options="'.Fn::stringify( array('items'=>$priceVals)  ).'"></table>' )


    /*->field("single_charge")
        ->label( 'Single Charge' )
        ->autocomplete('off')
        ->addClass('inputtext')
    */

->html();


# title
$arr['title']= 'เพิ่มพีเรียด';

# set form
$arr['form'] = '<form class="form-period" data-form-action="submit" method="post" action="'.URL. 'period/save" enctype="multipart/form-data"></form>';
$arr['hiddenInput'][] = array('name'=>'ser_id','value'=>$this->item['id']);


# body
$arr['body'] = '<div style="margin: -20px;">
    <table class="table-period-form">
        <tr>
            <td style="width:220px;padding: 2px;vertical-align: top;"><div style="min-height: 520px;padding: 20px;background-color: #eee;">'.$formInfo.'</div></td>
            
            <td style="vertical-align: top;position: relative;">
                <div style="height: 520px;padding: 20px;overflow-y: auto;">'.$formBus.'</div>
            </td>
        </tr>
    </table>

</div>';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';
$arr['close'] = true;
$arr['width'] = 960;


echo json_encode($arr);