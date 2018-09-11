<?php


$priceVals = array();

$pricesOpt = array();
$pricesOpt[] = array('id'=>'', 'name'=>'Pax');

$prices = array();
$prices[] = array('name'=>'Adult', 'labelValue'=>'Price', 'key'=>'period_price_1');
$prices[] = array('name'=>'Child', 'labelValue'=>'Price', 'key'=>'period_price_2');
$prices[] = array('name'=>'Chlid No bed.', 'labelValue'=>'Price', 'key'=>'period_price_3');
$prices[] = array('name'=>'Infant', 'labelValue'=>'Price', 'key'=>'period_price_4');
$prices[] = array('name'=>'Joinland', 'labelValue'=>'Price', 'key'=>'period_price_5');
$prices[] = array('name'=>'Single Charge', 'labelValue'=>'Price', 'key'=>'single_charge');
$priceVals[] = array('label'=>'ราคา', 'items'=>$prices, 'name'=>'prices');


$discount[] = array('name'=>'Com Agency', 'labelValue'=>'Price', 'key'=>'per_com_company_agency');
$discount[] = array('name'=>'Com Sales', 'labelValue'=>'Price', 'key'=>'per_com_agency');
$priceVals[] = array('label'=>'ส่วนลด', 'items'=> $discount, 'name'=>'discounts');


$roomTypes = array();
$roomTypes[] = array('name'=>'Twin', 'value'=>2, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Double', 'value'=>2, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Triple(Twin)', 'value'=>3, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Triple', 'value'=>3, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Single', 'value'=>1, 'labelValue'=>'Pax');

$priceVals[] = array('label'=>'Room of Type', 'items'=> $roomTypes, 'name'=>'roomoftypes');


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

    ->hr('<div class="ui-hr-text white"><span style="background: #eee;">ไฟล์เตรียมตัวเดินทาง</span></div>')
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
    

    /*->field("status")
        ->type( 'radio' )
        ->label(Translate::Val('Status'))
        ->items( $this->statusList )*/
        // ->checked( isset($this->item['status']) ? $this->item['status']: 1 )

->html();


$form = new Form();
$formBus = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert clearfix')

    ->field("seat")
        ->label(Translate::Val('จำนวนที่นั่ง').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value('')
        ->attr('data-name', 'seat')
        ->attr('data-plugin', 'number_format')

    ->field("cancel_mode")
        ->label( 'การยกเลิกการจองอัตโนมัติ' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-name', 'cancel_mode')
        ->select( $this->cancelmodeList, 'id', 'name', false )

    ->field("pricevalues")
        ->text( '<table class="table-pricevalues-form" plugin="pricevalues"></table>' )
   

->html();


# title
$arr['title']= 'เพิ่มพีเรียด';


# set form
$arr['form'] = '<form class="form-period" data-form-action="submit" method="post" action="'.URL. 'tour/period/save"></form>';

# body
$arr['body'] = '<div style="margin: -20px;" data-plugin="periodForm" data-options="'.Fn::stringify( array(
    'buslist' => array(),
    'busform' => $formBus,
    'busformOpt' => array('items'=>$priceVals),
) ).'">
    
    <table class="table-period-form">
        <tr>
            <td style="width:220px;padding: 2px;vertical-align: top;"><div style="min-height: 520px;padding: 20px;background-color: #eee;">'.$formInfo.'</div></td>

            <td style="vertical-align: top;position: relative;">

                <div style="width: 680px;">
                    <div style="padding:20px; max-height: 520px;overflow-y: auto;padding-right: 140px">
                        <ul class="list-listbus" role="buslist"></ul>
                    </div>
                </div>

                <div style="position: absolute;top: 20px;left: 560px;">
                    <ul class="nav-overview" role="buslistnav"></ul>
                    <div class="list-bus-plus mts"><a class="btn btn-small" data-buslist-action="add">+ เพิ่มบัส</a></div>
                </div>
            </td>

        </tr>
        
    </table>

</div>';




# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';
$arr['close'] = true;
$arr['width'] = 1112;


echo json_encode($arr);