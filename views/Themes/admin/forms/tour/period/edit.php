<?php

$this->_bus = $this->item['bus'];
$this->_options = $this->_bus['options'];

$priceVals = array();


$_defaultPrices = array();
$_defaultPrices['per_price_1'] = array('name'=>'Adult', 'labelValue'=>'Price', 'key'=>'per_price_1');
$_defaultPrices['per_price_2'] = array('name'=>'Child', 'labelValue'=>'Price', 'key'=>'per_price_2');
$_defaultPrices['per_price_3'] = array('name'=>'Chlid No bed.', 'labelValue'=>'Price', 'key'=>'per_price_3');

$prices = array();
foreach ($this->_options['price_values'] as $key => $value) {
	$value['labelValue'] = 'Price';

	if( isset($value['key']) ){
		if( !empty($_defaultPrices[$value['key']]) ){
			$value['name'] = $_defaultPrices[$value['key']]['name'];
			// unset($_defaultPrices[$value['key']]);
		}
	}
	$prices[] = $value;
}
$priceVals[] = array('label'=>'ราคาขาย', 'items'=>$prices, 'name'=>'price_values');


/* extra_price */
$extra_price = array();
$extra_price[] = array('name'=>'Infant', 'labelValue'=>'Price', 'key'=>'per_price_4', 'value'=> !empty($this->_options['infant'])? $this->_options['infant']: '');
$extra_price[] = array('name'=>'Joinland', 'labelValue'=>'Price', 'key'=>'per_price_5', 'value'=> !empty($this->_options['joinland'])? $this->_options['joinland']: '');
$priceVals[] = array('label'=>'', 'items'=>$extra_price, 'name'=>'extra_price', 'actions'=>'disabled');

/* single_charge */
$single_charge = array();
$single_charge[] = array('name'=>'Single Charge', 'labelValue'=>'Price', 'key'=>'single_charge', 'value'=> !empty($this->_options['single_charge'])? $this->_options['single_charge']: '');
$priceVals[] = array('label'=>'', 'items'=>$single_charge, 'name'=>'single_charge', 'actions'=>'disabled');


$_defaultCommission = array();
$_defaultCommission['per_com_company_agency'] = array('name'=>'Com Agency', 'labelValue'=>'Price', 'key'=>'per_com_company_agency');
$_defaultCommission['per_com_agency'] = array('name'=>'Com Sales', 'labelValue'=>'Price', 'key'=>'per_com_agency');

$commission = array();
foreach ($this->_options['commission'] as $key => $value) {
	$value['labelValue'] = 'Price';

	if( isset($value['key']) ){
		if( !empty($_defaultCommission[$value['key']]) ){
			$value['name'] = $_defaultCommission[$value['key']]['name'];
		}		
	}

	$commission[] = $value;
}
$priceVals[] = array('label'=>'คอมมิชชั่น', 'items'=> $commission, 'name'=>'commission', 'labelValue'=>'Price');


$discount = array();
$_defaultDiscount['per_discount'] = array('name'=>'โปรไฟไหม้', 'labelValue'=>'Price', 'key'=>'per_discount');

foreach ($this->_options['discounts'] as $key => $value) {
	$value['labelValue'] = 'Price';

	if( isset($value['key']) ){
		if( !empty($_defaultDiscount[$value['key']]) ){
			$value['name'] = $_defaultDiscount[$value['key']]['name'];
		}
	}

	$discount[] = $value;
}
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
        ->value( $this->_bus['seat'] )

    ->field("cancel_mode")
        ->label( 'กำหนดการ ยกเลิกการจองอัตโนมัติ' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-name', 'cancel_mode')
        ->select( $this->auto_cancelList, 'id', 'name', false )
        ->value( !empty($this->_bus['autocancel_arr']['id']) ? $this->_bus['autocancel_arr']['id']: 0 )

    ->field("bus_status")
        ->label( 'Status' )
        // ->type( 'radio' )
        ->text( $statusList )
        // ->checked( !empty($this->_bus['status']) ? $this->_bus['status']: 1 )

->html();


# title
$arr['title']= 'แก้ไขพีเรียด บัส'.$this->_bus['no'];

# set form
$arr['form'] = '<form class="form-period" data-form-action="submit" method="post" action="'.URL. 'period/edit/save"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'bus','value'=>$this->_bus['no']);

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