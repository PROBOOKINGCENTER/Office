<?php


$priceVals = array();

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


$form = new Form();
$formBus = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert clearfix')

    ->field("buslist")
    ->text( '<table class="table-pricevalues-form" data-plugin="pricevalues" data-options="'.Fn::stringify( array('items'=>$priceVals)  ).'"></table>' )

->html();


$statusList = '';
$statusListActive = !empty($this->_bus['status']) ? $this->_bus['status']: 1;
foreach ($this->statusList as $key => $value) {
	if( !empty($value['display']) ){

		$checked = $statusListActive==$value['id'] ? ' checked':'';

		$statusList .= '<label class="radio" for="bus_status_'.$value['id'].'"><input'.$checked.' id="bus_status_'.$value['id'].'" type="radio" name="bus_status" value="'.$value['id'].'"><span>'.$value['name'].'</span></label>';
		
	}
}

$form = new Form();
$formInfo = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert clearfix')


    ->field("bus_qty")
        ->label( 'Seat' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'number_format')

    ->field("cancel_mode")
        ->label( 'กำหนดการ ยกเลิกการจองอัตโนมัติ' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-name', 'cancel_mode')
        ->select( $this->auto_cancelList, 'id', 'name', false )

    ->field("bus_status")
        ->label( 'Status' )
        // ->type( 'radio' )
        ->text( $statusList )
        // ->checked( !empty($this->_bus['status']) ? $this->_bus['status']: 1 )

->html();


# title
$arr['title']= 'เพิ่มบัส';

# set form
$arr['form'] = '<form class="form-period" data-form-action="submit" method="post" action="'.URL. 'period/addBus"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);

# body
$arr['body'] = '<div style="margin: -20px;">

	<div></div>
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
$arr['width'] = 860;


echo json_encode($arr);