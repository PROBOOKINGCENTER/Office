<?php

class Booking_Fn extends Fn
{
	
	public function form($options=array()){


		$dataBooking = isset($options['booking']) ? $options['booking']: array();

		if( !empty($dataBooking['pax_total']) ){
			$options['data']['bus']['wanted'] += $dataBooking['pax_total']; 
		}

		$optForm = array(
		    'seat'=> $options['data']['bus']['seat'],
			'deposit' => !empty($options['datePayment']['deposit']['value']) ? $options['datePayment']['deposit']['value']: 0,
			'extraList' => !empty($options['extraList'])? $options['extraList']: array(),
			'extraListData' => !empty($options['extraListData'])? $options['extraListData']: array(),

			'wanted' => intval($options['data']['bus']['wanted']),
			'booking' => $dataBooking,
		);		


		$priceTable = array();
		$priceTable[] = array('key'=>'adult', 'name'=>'Adult', 'value'=> round($options['data']['price_1']));
		$priceTable[] = array('key'=>'child', 'name'=>'Child', 'value'=> round($options['data']['price_2']));
		$priceTable[] = array('key'=>'childNoBed', 'name'=>'Child No bed', 'value'=> round($options['data']['price_3']));
		$priceTable[] = array('key'=>'infant', 'name'=>'Infant', 'value'=> round($options['data']['price_4']));
		$priceTable[] = array('key'=>'joinland', 'name'=>'Joinland', 'value'=> round($options['data']['price_5']));
		$priceTable[] = array('key'=>'room_single', 'name'=>'Sing Charge', 'value'=> round($options['data']['single_charge']));


		$travelerTable = array();
		$travelerTable[] = array('key'=>'adult', 'label'=>'Adult', 'cls'=>'input-value-pax', 'name'=>'traveler[adult]', 'value'=>!empty($dataBooking['traveler']['adult'])? $dataBooking['traveler']['adult']: 0);
		$travelerTable[] = array('key'=>'child', 'label'=>'child', 'cls'=>'input-value-pax', 'name'=>'traveler[child]', 'value'=>!empty($dataBooking['traveler']['child'])? $dataBooking['traveler']['child']: 0);
		$travelerTable[] = array('key'=>'childNoBed', 'label'=>'Child No bed', 'cls'=>'input-value-pax', 'name'=>'traveler[childNoBed]', 'value'=>!empty($dataBooking['traveler']['childnobed'])? $dataBooking['traveler']['childnobed']: 0);
		$travelerTable[] = array('key'=>'infant', 'label'=>'Infant', 'name'=>'traveler[infant]', 'value'=>!empty($dataBooking['traveler']['infant'])? $dataBooking['traveler']['infant']: 0);
		$travelerTable[] = array('key'=>'joinland', 'label'=>'Joinland', 'cls'=>'input-value-pax', 'name'=>'traveler[joinland]', 'value'=>!empty($dataBooking['traveler']['joinland'])? $dataBooking['traveler']['joinland']: 0);


		$roomOfTypeTable = array();
		$roomOfTypeTable[] = array('key'=>'room_twin', 'label'=>'Twin', 'type'=>'room', 'name'=>'room[twin]', 'value'=>!empty($dataBooking['room_twin'])? $dataBooking['room_twin']: 0, 'quota'=>2);
		$roomOfTypeTable[] = array('key'=>'room_double', 'label'=>'Double', 'type'=>'room', 'name'=>'room[double]', 'value'=>!empty($dataBooking['room_double'])? $dataBooking['room_double']: 0, 'quota'=>2);
		$roomOfTypeTable[] = array('key'=>'room_triple', 'label'=>'Triple', 'type'=>'room', 'name'=>'room[triple]', 'value'=>!empty($dataBooking['room_triple'])? $dataBooking['room_triple']: 0, 'quota'=>3);
		$roomOfTypeTable[] = array('key'=>'room_tripletwin', 'label'=>'Triple(Twin)', 'type'=>'room', 'name'=>'room[tripletwin]', 'value'=>!empty($dataBooking['room_tripletwin'])? $dataBooking['room_tripletwin']: 0, 'quota'=>3);
		$roomOfTypeTable[] = array('key'=>'room_single', 'label'=>'Single', 'type'=>'room', 'name'=>'room[single]', 'value'=>!empty($dataBooking['room_single'])? $dataBooking['room_single']: 0, 'quota'=>1);


		$discountList = array();
		$discountList[] = array('name'=>'Agency Com', 'price'=>$options['data']['com_company_agency'], 'value'=>'auto', 'total'=>0);
		$discountList[] = array('name'=>'Sales Com', 'price'=>$options['data']['com_agency'], 'value'=>'auto', 'total'=>0);

		if( $options['data']['discount']>0  ){
			$discountList[] = array('name'=>'โปรไฟไหม้', 'price'=>$options['data']['discount'], 'value'=>'auto', 'total'=>0);
		}

		// if( !empty($options['data']['discount_extra']) ){
			$discountList[] = array('name'=>'ลดพิเศษ', 'price'=>0, 'value'=>1, 'total'=>0, 'is_input'=>1);
		// }




		$form = new Form();
		$contactForm = $form->create()->elem('div')->addClass('form-insert')

		    ->field( 'sales' )->label( 'Sales Contact' )->select( $options['salesList'] )->autocomplete('off')->value( !empty($dataBooking['user_id']) ? $dataBooking['user_id']:'' )
		    ->field( 'company' )->label( 'Agent' )->select( $options['agencyList'] )->autocomplete('off')->value( !empty($dataBooking['company_id']) ? $dataBooking['company_id']:'' )
		    ->field( 'agent' )->label( 'Sales Agent' )->addClass('inputtext')->select()->autocomplete('off')
		    ->field( 'book_comment' )->label( 'คำสั่งพิเศษ' )->addClass('inputtext')->type( 'textarea' )->attr('data-plugin', "autosize")->autocomplete('off')->value( !empty($dataBooking['comment']) ? $dataBooking['comment']:'' )
		    ->field( 'book_cus_name' )->label( 'ชื่อลูกค้า' )->addClass('inputtext')->autocomplete('off')->value( !empty($dataBooking['cus_name']) ? $dataBooking['cus_name']:'' )
		    ->field( 'book_cus_tel' )->label( 'เบอร์โทรลูกค้า' )->addClass('inputtext')->autocomplete('off')->value( !empty($dataBooking['cus_tel']) ? $dataBooking['cus_tel']:'' )

		->html();

		$travelerTR = '';
		foreach ($priceTable as $key => $value) {
		    $travelerTR .= '<tr data-summary="'.$value['key'].'">
		        <td class="label">'.$value['name'].'</td>
		        <td class="data"><span class="value" data-value="'.$value['value'].'">'. number_format($value['value']) .'</span> <span class="x">x</span> <span class="count">0</span> <span>=</span> <span class="sum">0</span></td>
		    </tr>';
		}

		$depCountdownStr = '';
		if( !empty($options['datePayment']['deposit']['date']) ){
		    $depCountdownStr = '<tr><td colspan="2" style="font-size: 14px;padding-right: 8px;">';

		    $depCountdownStr .= date('j/m/Y H:i', strtotime($options['datePayment']['deposit']['date']));

		    /*if( !empty($options['datePayment']['deposit']['countdown_str']) ){
		        $depCountdownStr .= " ({$options['datePayment']['deposit']['countdown_str']})";
		    }*/

		    $depCountdownStr .= '</td></tr>';
		}

		$fulCountdownStr = '';
		if( !empty($options['datePayment']['fullpayment']['date']) ){
		    $fulCountdownStr = '<tr><td colspan="2" style="font-size: 14px;padding-right: 8px;">';
		    $fulCountdownStr .= date('j/m/Y H:i', strtotime($options['datePayment']['fullpayment']['date']));

		    /*if( !empty($options['datePayment']['fullpayment']['countdown_str']) ){
		        $fulCountdownStr .= " ({$options['datePayment']['fullpayment']['countdown_str']})";
		    }*/

		    $fulCountdownStr .= '</td></tr>';
		}


		$bookingInfo = '';
		if( !empty($dataBooking) ){

			if( !empty($dataBooking['agen_id']) ){
				$bookingInfo .= '<li><span class="fwb">'.$dataBooking['agen_name'].'</span>'.( !empty($dataBooking['agen_tel'])? " <a href=\"tel:{$dataBooking['agen_tel']}\">{$dataBooking['agen_tel']}</a>":'' ).'</li>';
			}

			if( !empty($dataBooking['company_name']) ){
				$bookingInfo .= '<li style="font-size:11px;">'.$dataBooking['company_name'].( !empty($dataBooking['company_location_province_name'])? " ({$dataBooking['company_location_province_name']})":'' ).'</li>';
			}

			$bookingInfo .= '<li class="mts" style="font-size:11px;white-space:nowrap;"><span class="fwb">Create Date:</span> '.date('j M Y H:i', strtotime($dataBooking['create_date'])).' - '.$dataBooking['createby_name'].'</li>';
			$bookingInfo .= '<li style="font-size:11px;white-space:nowrap;"><span class="fwb">Last Update:</span> '.date('j M Y H:i', strtotime($dataBooking['update_date'])).( !empty($dataBooking['updateby_nickname'])? ' - '.$dataBooking['updateby_nickname']:'' ).'</li>';
		}


		$html = '<div data-plugin="bookingform" data-options="'.Fn::stringify($optForm).'">'.
		    
		    '<div class="uiBoxYellow">
		        <table>
		            <tr>'.

		            	( !empty($dataBooking) 
		                	? '<td class="pam" style="width: 249px;vertical-align: top;">'.
			                	'<h2>'.
			                		'<i class="icon-address-card-o"></i><span class="mls">ผู้จอง</span>'.
			                		// '<span class="ui-status" style="vertical-align: top;margin-left: 8px;margin-top: 2px;background-color:'.$dataBooking['status_arr']['color'].'">'.$dataBooking['status_arr']['name'].'</span>'.
			                	'</h2>'.
			                    '<ul class="bookingTitleList">'.$bookingInfo.'</ul>'.
			                '</td>'
			                : ''
			            ).

		                '<td class="pam" style="background:#ffee91;vertical-align: top;">
		                    <div class="ui-code-wrap" style="white-space: nowrap;">
		                        <span class="ui-code c">'.$options['data']['ser_code'].'</span><span class="ui-bus"><span>Bus</span><strong>'.$options['data']['bus']['no'].'</strong></span>
		                    </div>
		                    <div style="font-size: 10px;line-height: 1;margin-top: 10px;">Seat</div>
		                    <div style="font-size: 30px;line-height: 1">'.
		                    	($options['data']['bus']['wanted']<=0?'เต็ม':$options['data']['bus']['wanted']).'<span style="font-size:11px">/ '.$options['data']['bus']['seat'].'</span></div>
		                </td>'.

		                '<td class="pvm prm" style="background:#ffee91;vertical-align: top;">
		                	<h2><i class="icon-plane"></i><span class="mls">แพคเกจทัวร์</span></h2>
		                    <ul class="uiListStandard">
		                        <li>'.$options['data']['ser_name'].'</li>
		                        <li>'.$options['data']['date_str'].'</li>
		                    </ul>
		                </td>'.

		            '</tr>
		        </table>
		    </div>'.

		    ( $options['data']['bus']['wanted']<=0
		        ? '<div class="pam uiBoxRed mam fwb">*เนื่องจากมีจำนวนการจองที่นั่งเต็มจำนวนแล้ว คุณจะสามาจองทัวร์นี้ได้ในสถานะ Waiting List เท่านั้น</div>'
		        : '' 
		    ).

		    '<div class="booking-form-table-wrap" style="border-width: 1px 0 0"><table class="booking-form-table"><tbody>'.

		        '<tr>'.
		            '<td class="td-contact">'. 
		                '<header>Contact</header>'.
		                $contactForm.
		            '</td>'.


		            '<td>
		            	
		                <table>
		                    <tr>
		                        <td class="" style="padding: 10px;vertical-align: top;width: 50%">

		                        	<fieldset id="traveler_fieldset" class="control-group">
		                            <header>Traveler Info</header>'.

		                            $this->inputValueNumber($travelerTable).

		                            '<div class="notification"></div>'.
	                				'</fieldset>'.
		                        '</td>
		                        <td class="" style="padding: 10px;vertical-align: top;">
		                            <header>Room Type</header>'.
		                            $this->inputValueNumber($roomOfTypeTable).
		                        '</td>
		                    </tr>
		                </table>
		                	
		            </td>'.


		            '<td rowspan="2" class="" style="padding: 10px;padding-bottom: 190px;width: 300px;vertical-align: top;border-left: 1px dashed #ccc;position: relative;background-color: #fff;">'.

		                '<table class="booking-table-calculatePrice" role="summary">'.

		                    '<tbody><tr><td class="head frist" colspan="2">Price</td></tr></tbody>'.

		                    '<tbody data-summary-section="traveler">'.
		                        $travelerTR.
		                        '<tr><td colspan="2" class="subtotal">0</td></tr>'.
		                    '</tbody>'.

		                    '<tbody class="extralist-head"><tr>
		                        <td colspan="2" class="head">
		                            <div class="clearfix">
		                                <span class="lfloat title">รายการเพิ่มเติม</span>
		                                <span class="rfloat js-extralist-total" data-value="0" style="color: #465cd8;">0</span>
		                            </div>
		                        </td>
		                    </tr></tbody>'.

		                    '<tbody class="extralist-list" data-summary-section="extralist" style="display:none;"></tbody>'.

		                    '<tbody>'.
		                        '<tr><td colspan="2" style="padding-top:20px;border-width: 0"></td></tr>'.
		                        '<tr><td class="head" colspan="2">ส่วนลด</td></tr>'.
		                    '</tbody>'.


		                    $this->discountTable( $discountList ).
		                '</table>'.

		                '<div style="position: absolute;bottom: 2px;right: 2px;text-align: right;left: 2px">

		                    <div style="padding: 8px;">
		                        <table data-summary-section="total">
		                            <tr>
		                                <td style="padding-right: 10px;">
		                                    <div style="font-size: 11px;line-height: 1">Pax</div>
		                                    <div style="font-size: 20px;font-weight: bold;color: #4CAF50" class="pax">0</div>
		                                </td>
		                                <td style="width: 100%"></td>
		                                <td style="padding-right: 10px;">
		                                    <div style="font-size: 11px;line-height: 1">Discount</div>
		                                    <div style="font-size: 20px;font-weight: bold;color: red" class="discount">0</div>
		                                </td>
		                                <td style="padding-left: 10px;border-left: 1px solid #ccc">
		                                    <div style="font-size: 11px;line-height: 1">Total</div>
		                                    <div style="font-size: 20px;font-weight: bold;color: #3f51b5" class="total">0</div>
		                                </td>
		                            </tr>
		                        </table>
		                    </div>
		                    
		                    <div style="background-color: #f0f0f0">
		                        <table data-summary-section="pay">
		                            <tr>
		                                <td style="font-weight: bold;padding-top: 14px">Deposit:</td>
		                                <td style="text-align: right;padding-bottom: 10px;padding: 8px 8px 0">
		                                    <div style="font-size: 24px;font-weight: bold;" class="deposit">0</div>
		                                </td>
		                            </tr>'.

		                            $depCountdownStr.

		                            '<tr><td colspan="2" style="padding: 4px;"></td></tr>'.

		                            '<tr>
		                                <td style="font-weight: bold;padding-top: 14px;white-space: nowrap;width: 30px;padding-left: 8px;border-top: 1px solid #fff;">Full Payment:</td>
		                                <td style="text-align: right;padding: 8px 8px 0;border-top: 1px solid #fff;">
		                                    <div style="font-size: 24px;font-weight: bold;" class="fullpayment">0</div>
		                                </td>
		                            </tr>'.

		                            $fulCountdownStr.

		                            '<tr><td colspan="2" style="padding: 4px;"></td></tr>
		                        </table>

		                    </div>
		                </div>'.
		            '</td>'.
		        '</tr>'.


		        '<tr>'.
		            '<td colspan="2" style="padding: 10px;border-top: 1px solid #ddd;background: #fff;">'.
		                '<div class="table-booking-extra-wrap" role="extralist">'.
		                    '<table class="table-booking-extra">
		                        <thead>
		                            <tr>
		                                <th class="td-no">#</th>
		                                <th class="td-name">รายการเพิ่มเติม</th>
		                                <th class="td-price">ราคา</th>
		                                <th class="td-qty">จำนวน</th>
		                                <th class="td-sum">รวม</th>
		                                <th class="td-actions"></th>
		                            </tr>
		                        </thead>
		                        <tbody role="listsbox"></tbody>
		                        <tfoot>
		                                <tr>
		                                    <td colspan="4" style="text-align: right"></td>
		                                    <td class="td-sum"><span style="font-weight: bold;font-size: 18px;" data-ref="total">-</span></td>
		                                    <td></td>
		                                </tr>
		                            </tfoot>
		                        </table>'.
		                '</div>'.


		                '<header>Remark</header><div><textarea class="inputtext" name="remark" data-plugin="autosize" style="width: 100%"></textarea></div>'.
		            '</td>'.
		        '</tr>'.
		    '</tbody></table></div>'.
		'</div>';


		return $html;
	}

	public function inputValueNumber($fields)
	{
	    $field = '';
	    foreach ($fields as $key => $value) {

	        $cls = 'inputtext input-value-number';
	        if( isset($value['cls']) ){
	            $cls .= " {$value['cls']}";
	        }

	        // set attr 
	        $quota = isset($value['quota'])? ' data-quota="'.$value['quota'].'"': '';

	        
	        $name = isset($value['name'])? $value['name']: $value['key'];
	        $type = isset($value['type'])? ' data-type="'.$value['type'].'"': '';

	        $field .=''.
	            '<fieldset id="'.$value['key'].'_fieldset" class="control-group-number control-group">'.
	                '<label for="'.$value['key'].'" class="control-label">'.$value['label'].'</label>'.
	                '<div class="controls touchtime-wrap">'.
	                    '<span class="gbtn"><button type="button" class="btn" data-value-action="minus"><i class="icon-minus"></i></button></span>'.
	                    '<input id="'.$value['key'].'" class="'.$cls.'" autocomplete="off" type="text" name="'.$name.'" data-name="'.$value['key'].'"'.(!empty($value['value'])?' value="'.$value['value'].'"':'').$type.$quota.'>'.
	                    '<span class="gbtn r"><button type="button" class="btn" data-value-action="plus"><i class="icon-plus"></i></button></span>'.
	                    // '<div class="notification"></div>'.
	                    '<div class="leyerNumber list-touchtime"></div>'.
	                '</div>'.
	            '</fieldset>';
	        
	    }
	    return '<div class="form-insert form-vertical">'.$field.'</div>';
	}


	public function discountTable($lists)
	{
		
		$tr = '';
		foreach ($lists as $key => $val) {

			$input = '';
			if( isset($val['is_input']) ){
				$input = '<input id="discount_extra" type="text" name="discount_extra" class="inputtext input-extra-discount" style="width: 100%">';
			}
			else{
				$input = 

				'<input type="hidden" data-discount-name="name" name="discount[name][]" class="inputtext" autocomplete="off" value="'.$val['name'].'">'.
            	'<input type="hidden" data-discount-name="price" name="discount[price][]" class="inputtext" autocomplete="off" value="'.$val['price'].'">'.
            	'<input type="hidden" data-discount-name="value" name="discount[value][]" class="inputtext" autocomplete="off" value="0">'.

				'<span class="value" data-value="'.round($val['price']).'">'.
					number_format($val['price']).'</span> <span class="x">x</span> <span class="count">0</span> <span>=</span> <span class="sum">0</span>';
			}
			
			$is_auto = $val['value']=='auto';

			$tr .= '<tr>
                <td class="label">'.$val['name'].'</td>
                <td class="data">'.

                	$input.
                '</td>
            </tr>';
		}

		return '<tbody class="t-discount" data-summary-section="discount">'.$tr.'</tbody>';
	}



}