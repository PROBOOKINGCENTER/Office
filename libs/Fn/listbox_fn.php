<?php

class Listbox_Fn extends Fn
{

	public function ul_anchor($data, $options=array(), $item_options=array()) {

		$options = array_merge( array(
			'addClass' => 'ui-lists'
		), $options);

		$cls = !empty($options['addClass']) ? ' class="'.$options['addClass'].'"':'';

		$li = '';
		foreach ($data as $key => $value) {
			$li .= $this->li_anchor( $value, $item_options );
		}

		return '<ul'.$cls.'">'.$li.'</ul>';
	}

	public function li_anchor($data, $options=array() ){

		$options = array_merge(array(
			'icon' => '',
			'addClass' => 'ui-item',
			'size' => ''
		), $options);

		$anchorCls = '';
		// is_array('ui-bucketed',explode(' ', $options['addClass'])) || 
		// 
		if( !empty($options['size']) ){
			$anchorCls = ' anchor'.$options['size'];
		}

		$cls = !empty($options['addClass']) ? ' class="'.$options['addClass'].'"':'';

		$li = '<li'.$cls.'><div class="anchor'.$anchorCls.' clearfix">'.
	        
	        '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>'.
	        
	        '<div class="content"><div class="spacer"></div><div class="massages">'.

	            (!empty($data['text'])?'<div class="text">'.$data['text'].'</div>':'').
	            (!empty($data['subtext'])?'<div class="subtext">'.$data['subtext'].'</div>':'').
	            (!empty($data['category'])?'<div class="category">'.$data['category'].'</div>':'').
	            (!empty($data['meta'])?'<div class="meta">'.$data['meta'].'</div>':'').
	            
	        '</div></div>'.
	    '</div></li>';

	    return $li;
	}



	public function table_tour_col()
	{
		
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		// $th[] = array('cls'=>'td-code', 'key'=>'country_name', 'label'=>'ประเทศ');
		$th[] = array('cls'=>'td-code', 'key'=>'code', 'label'=>'รหัสซีรีย์');
		$th[] = array('cls'=>'td-date', 'key'=>'date_str', 'label'=>'เดินทาง');
		$th[] = array('cls'=>'td-number', 'key'=>'bus_no', 'label'=>'BUS');
		$th[] = array('cls'=>'td-price', 'key'=>'price', 'label'=>'ราคา', 'type'=>'price');
		$th[] = array('cls'=>'td-number', 'key'=>'seat', 'label'=>'ที่นั่ง');
		$th[] = array('cls'=>'td-number', 'key'=>'bookingVal', 'label'=>'จอง');
		$th[] = array('cls'=>'td-number', 'key'=>'wantedVal', 'label'=>'รับได้');
		$th[] = array('cls'=>'td-number', 'key'=>'fpVal', 'label'=>'FP');
		$th[] = array('cls'=>'', 'key'=>'', 'label'=>'Booking');
		$th[] = array('cls'=>'', 'key'=>'', 'label'=>'W/L');
		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}
	public function table_tour_rows($data, $options=array())
	{	
		$th = $this->table_tour_col();



		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $key => $value) {
			$__seq ++;

			$li .= '<tr>';
			foreach ($th as $i => $field) {

				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$fpVal = !empty($value['count']['fullpayment']) ? $value['count']['fullpayment']: 0;
					$wantedVal = !empty($value['count']['wanted']) ? $value['count']['wanted']: 0;

					$val = '<div class="group-btn">';
					$val .= '<button type="button" class="btn btn-icon" title="จอง"><i class="icon-plus-circle"></i></button>';
					

					if( $fpVal==$value['seat'] ){
						$val .= '<button type="button" class="btn btn-icon" title="เต็ม"><i class="icon-minus-circle"></i></button>';
					}

					if( $wantedVal<=0 ){
						$val .= '<button type="button" class="btn btn-icon" title="W/L"><i class="icon-list"></i></button>';
					}

					
					$val .= '<button type="button" class="btn btn-icon" title="ดูรายละเอียด"><i class="icon-pencil"></i></button>';
					$val .= '<div>';

				}
				elseif( $field['key']=='bookingVal' ){
					$val = !empty($value['count']['booking']) ? $value['count']['booking']: 0;
				}
				elseif( $field['key']=='fpVal' ){
					$val = !empty($value['count']['fullpayment']) ? $value['count']['fullpayment']: 0;
				}
				elseif( $field['key']=='wantedVal' ){
					$val = !empty($value['count']['wanted']) ? $value['count']['wanted']: 0;

					if( $val<0 ){
						$val = 0;
					}
				}
				elseif( $field['key']=='__seq' ){
					$val = $__seq;
				}
				else{
					
					$val = !empty($value[$field['key']]) ? $value[$field['key']]: '';
					if( $type=='price' ){
						$val = number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'">'.$val.'</td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}


	/**/
	/* booking */
	public function table_booking_col()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-status', 'key'=>'status_arr', 'label'=>'สถานะ', 'type'=>'status');

		$th[] = array('cls'=>'td-date', 'key'=>'create_date_str', 'label'=>'วันที่จอง');
		$th[] = array('cls'=>'td-avatar', 'key'=>'sales', 'label'=>'Sales');
		$th[] = array('cls'=>'td-code', 'key'=>'code', 'label'=>'Booking No.', 'type'=>'status');

		$th[] = array('cls'=>'td-code', 'key'=>'tour_code', 'label'=>'Code');
		$th[] = array('cls'=>'td-date', 'key'=>'period_date', 'label'=>'Period');
		// $th[] = array('cls'=>'td-number', 'key'=>'pax_total', 'label'=>'Pax', 'type'=>'number');
		$th[] = array('cls'=>'td-price', 'key'=>'total', 'label'=>'ยอดสุทธิ', 'type'=>'number');
		$th[] = array('cls'=>'td-price', 'key'=>'deposit', 'label'=>'Dep', 'type'=>'number');
		$th[] = array('cls'=>'td-price', 'key'=>'fullpay', 'label'=>'Full', 'type'=>'number');
		// $th[] = array('cls'=>'td-number', 'key'=>'', 'label'=>'Receipt');
		// $th[] = array('cls'=>'td-number', 'key'=>'', 'label'=>'Balance');
		$th[] = array('cls'=>'td-name', 'key'=>'agency', 'label'=>'Agency');
		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}
	public function booking_tour_rows($data, $options=array())
	{
		$th = $this->table_booking_col();


		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $key => $item) {
			$__seq ++;


			


			$li .= '<tr>';
			foreach ($th as $i => $field) {

				$val = '';
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					// $fpVal = !empty($value['count']['fullpayment']) ? $value['count']['fullpayment']: 0;
					// $wantedVal = !empty($value['count']['wanted']) ? $value['count']['wanted']: 0;

					$val = '<div class="group-btn">';
					// $val .= '<button type="button" class="btn btn-icon" title="จอง"><i class="icon-plus-circle"></i></button>';
					

					/*if( $fpVal==$value['seat'] ){
						$val .= '<button type="button" class="btn btn-icon" title="เต็ม"><i class="icon-minus-circle"></i></button>';
					}

					if( $wantedVal<=0 ){
						$val .= '<button type="button" class="btn btn-icon" title="W/L"><i class="icon-list"></i></button>';
					}*/

					
					$val .= '<a type="button" class="btn btn-icon" title="ดูรายละเอียด" href="'.URL.'booking/'.$item['id'].'/payment"><i class="icon-pencil"></i></a>';
					$val .= '<div>';

				}
				elseif( $field['key']=='create_date_str' ){
					
					$time = strtotime($item['create_date']);

					$val = date('j', $time);
			        $val .= ' '. $this->q('time')->month( date('n', $time) );
			        $val .= ' '.date('Y', $time);
			        
			        $desc = date('H:i', $time);

			        if( !empty($item['createby_name']) ){
			        	$desc .= ' - '. $item['createby_name'];
			        }

			        $val .= '<div class="fss fcg">'.$desc .'</div>';
			        

				}
				elseif( $field['key']=='status_arr' ){

					if( !empty($item['status_arr']) ){
						$val = '<span class="ui-status" style="background-color:'.$item['status_arr']['color'].'">'.$item['status_arr']['name'].'</span>';
					}
				}
				elseif( $field['key']=='sales' ){
					$val = '<div class="anchor anchor32 clearfix link btn-toggle whitespace">'.
						// '<div class="avatar lfloat size32 no-avatar mrs"><div class="initials"><i class="icon-user"></i></div></div>'.

						'<div class="content"><div class="spacer"></div>'.

							'<div class="massages">'.
								'<div style="line-height: 1;">'.$item['user_nickname'].'</div>'.
								'<div class="fcg fss tal">'. trim("{$item['user_fname']}").' '. trim("{$item['user_lname']}").'</div>'.
							'</div>'.
						'</div>'.
					'</div>';

					
				}
				elseif( $field['key']=='code' ){

					$val = '<div class="whitespace">'.
						( $item['is_guarantee'] ? '<i title="การันตี" class="fc-blue icon-thumbs-up"></i>': '<span style="display: inline-block;width: 15px;"></span>' ).
						'<span class="mls">'.$item['code'].'</span>'.
					'</div>';

				}
				elseif($field['key']=='period_date'){
					$time = strtotime($item['end_date']);

					$val =    date('j', strtotime($item['start_date']))
							. '-' 
							. date('j ', $time)
							. $this->q('time')->month( date('n', $time) )
							. date(' Y', $time);
				}
				elseif( $field['key']=='fullpay' ){

					$val = '-';
			        if( $item['master_full_payment']>0 && $item['status']!=40 ){
			            $countdown = $this->q('time')->Countdown($item['due_date_full_payment']);
			            $title = date('j/m/y H:s', strtotime($item['due_date_full_payment']));

			            $desc = '';
			            if( !empty($countdown['days']) ){
			                if( $countdown['days']==1 ){
			                    $desc = '<span title="'.$title.'">วันนี้ '.date('H:s', strtotime($item['due_date_full_payment'])).'</span>';
			                }
			                elseif($countdown['days']==2){
			                    $desc = '<span title="'.$title.'">พรุ่งนี้ '.date('H:s', strtotime($item['due_date_full_payment'])).'</span>';
			                }
			                else{
			                    $desc = $title;
			                }
			            }

			            $val = '<span class="fwb">'.number_format($item['master_full_payment']). '</span><div style="white-space: nowrap;" class="fsm fcg">'.$desc.'</div>';
			        }
				}
				elseif( $field['key']=='deposit' ){

					$val = '-';
			        if( $item['master_deposit']>0 && $item['status']!=40){
			            $countdown = $this->q('time')->Countdown($item['due_date_deposit']);
			            $title = date('j/m/y H:s', strtotime($item['due_date_deposit']));

			            $desc = '';
			            if( !empty($countdown['days']) ){
			                if( $countdown['days']==1 ){
			                    $desc = '<span title="'.$title.'">วันนี้ '.date('H:s', strtotime($item['due_date_deposit'])).'</span>';
			                }
			                elseif($countdown['days']==2){
			                    $desc = '<span title="'.$title.'">พรุ่งนี้ '.date('H:s', strtotime($item['due_date_deposit'])).'</span>';
			                }
			                else{
			                    $desc = $title;
			                }
			            }

			            $val = '<span class="fwb">'.number_format($item['master_deposit']). '</span><div style="white-space: nowrap;" class="fsm fcg">'.$desc.'</div>';
			        }
				}
				elseif( $field['key']=='agency' ){

					$agenName = '-';
			        if( !empty($item['agen_fname']) ){
			            $agenName = $item['agen_fname'].' ('.$item['company_name'].')';
			        }


			        $agenMeta = '';
			        $item['agen_tel'] = trim($item['agen_tel'], '-');
			        if( !empty($item['agen_tel']) ){
			            
			            $agenMeta .= !empty($agenMeta) ? ', ':'';
			            $agenMeta .= '<span><i class="icon-phone mrs"></i><a href="tel:'.$item['agen_tel'].'">'.$item['agen_tel'].'</a></span>';
			        }

			        $item['agen_line_id'] = trim($item['agen_line_id'], '-');
			        $item['agen_line_id'] = trim($item['agen_line_id'], '.');
			        if( !empty($item['agen_line_id']) ){
			            
			            $agenMeta .= !empty($agenMeta) ? ', ':'';
			            $agenMeta .= '<span><svg version="1.1" class="mrs" style="height:12px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-21 23.3 455.7 455.7" style="enable-background:new -21 23.3 455.7 455.7;" xml:space="preserve"><g><path fill="#00C200" d="M432.8,240.4c1-5.5,1.6-10.1,1.8-13.8c0.4-6-0.1-14.9-0.2-17.7C428.8,112.8,329.1,36.3,207,36.3 c-125.7,0-227.6,81.1-227.6,181.1c0,91.9,86.1,167.8,197.6,179.5c6.8,0.7,11.7,6.9,11,13.7l-4.7,42.7c-1.1,9.7,9,16.8,17.7,12.5 c94.2-45.5,150.6-92.3,184.2-132.8c6.1-7.4,26.1-35.4,30.2-42.7C424,274.7,430,257.9,432.8,240.4z"/><g><path fill="#FFFFFF" d="M81.8,254v-76.3c0-6.4-5.2-11.6-11.6-11.6l0,0c-6.4,0-11.6,5.2-11.6,11.6v87.9c0,6.4,5.2,11.6,11.6,11.6h46.6 c6.4,0,11.6-5.2,11.6-11.6l0,0c0-6.4-5.2-11.6-11.6-11.6H81.8z"/><path fill="#FFFFFF" d="M153.6,277.3h-5.1c-5,0-9.1-4.1-9.1-9.1v-93c0-5,4.1-9.1,9.1-9.1h5.1c5,0,9.1,4.1,9.1,9.1v93 C162.7,273.2,158.7,277.3,153.6,277.3z"/><path fill="#FFFFFF" d="M247.7,177.7v53.7c0,0-46.5-60.7-47.2-61.4c-2.2-2.5-5.5-4-9.1-3.9c-6.3,0.2-11.2,5.8-11.2,12.1v87.4 c0,6.4,5.2,11.6,11.6,11.6l0,0c6.4,0,11.6-5.2,11.6-11.6v-53.4c0,0,47.2,61.2,47.9,61.8c2.1,1.9,4.8,3.2,7.9,3.2 c6.5,0.1,11.8-5.7,11.8-12.1v-87.4c0-6.4-5.2-11.6-11.6-11.6l0,0C252.9,166.1,247.7,171.3,247.7,177.7z"/><path fill="#FFFFFF" d="M358.3,177.7L358.3,177.7c0-6.4-5.2-11.6-11.6-11.6h-46.6c-6.4,0-11.6,5.2-11.6,11.6v87.9 c0,6.4,5.2,11.6,11.6,11.6h46.6c6.4,0,11.6-5.2,11.6-11.6l0,0c0-6.4-5.2-11.6-11.6-11.6h-34.9v-20.7h34.9 c6.4,0,11.6-5.2,11.6-11.6l0,0c0-6.4-5.2-11.6-11.6-11.6h-34.9v-20.7h34.9C353.1,189.4,358.3,184.2,358.3,177.7z"/></g></g></svg><a target="_blank" href="http://line.me/ti/p/~'.$item['agen_line_id'].'">'.$item['agen_line_id'].'</a></span>';
			            
			        }

					$val = $agenName. '<div class="fsm fcg">'.$agenMeta.'</div>';

				}
				elseif( $field['key']=='total' ){
					$val = ( $item['discount']>0 
								? '<div style="white-space: nowrap;" class="fsm"><span>'.number_format($item['total']).'</span> - <span class="fc-red">'.number_format($item['discount']).'</span></div>'
								: '').
                		'<span class="fc-blue fwb whitespace">'.number_format($item['total']-$item['discount']).'</span>';
				}
				elseif( $field['key']=='__seq' ){
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'">'.$val.'</td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}



	/**/
	/* paymentColumn */
	/**/
	public function paymentColumn()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-status', 'key'=>'status_arr', 'label'=>'สถานะ', 'type'=>'status');

		$th[] = array('cls'=>'td-status', 'key'=>'file', 'label'=>'ไฟล์อ้างอิง');
		$th[] = array('cls'=>'td-code', 'key'=>'invoice_code', 'label'=>'Invoice No.');
		$th[] = array('cls'=>'td-code', 'key'=>'ser_code', 'label'=>'รหัสซี่รี่ย์');

		$th[] = array('cls'=>'td-date', 'key'=>'period_date', 'label'=>'Period');
		$th[] = array('cls'=>'td-name', 'key'=>'bankbook_code', 'label'=>'เลขที่บัญชี');
		$th[] = array('cls'=>'td-date', 'key'=>'received', 'label'=>'จำนวนเงิน');
		$th[] = array('cls'=>'td-date', 'key'=>'date', 'label'=>'วันที่โอน');
		$th[] = array('cls'=>'td-date', 'key'=>'time', 'label'=>'เวลาที่โอน');
		$th[] = array('cls'=>'td-date', 'key'=>'createby_fname', 'label'=>'ผู้ทำรายการ');
		$th[] = array('cls'=>'td-date', 'key'=>'create_date', 'label'=>'วันที่ทำรายการ');
		$th[] = array('cls'=>'td-status', 'key'=>'book_status_arr', 'label'=>'สถานะการชำระเงิน', 'type'=>'status');

		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}
	public function paymentRows($data, $options=array())
	{
		$th = $this->paymentColumn();

		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $key => $item) {
			$__seq ++;


			$li .= '<tr>';
			foreach ($th as $i => $field) {
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$val = '<div class="group-btn">';					
						$val .= '<button type="button" class="btn btn-small btn-blue"><i class="icon-check"></i><span class="mls">อนุมัติ</span></button>';
						$val .= '<button type="button" class="btn btn-small btn-red"><i class="icon-close"></i><span class="mls">ไม่อนุมัติ</span></button>';
						$val .= '<a href="'.URL.'booking/'.$item['id'].'/payment" class="btn btn-small" title="จัดการ"><i class="icon-pencil"></i></a>';
					$val .= '<div>';
				} elseif( $field['key']=='status_arr' ){

					if( !empty($item['status_arr']) ){
						$val = '<span class="ui-status" style="background-color:'.$item['status_arr']['color'].'">'.$item['status_arr']['name'].'</span>';
					}
					
				} elseif( $field['key']=='book_status_arr' ){

					if( !empty($item['book_status_arr']) ){
						$val = '<span class="ui-status" style="background-color:'.$item['book_status_arr']['color'].'">'.$item['book_status_arr']['name'].'</span>';
					}

				} elseif( $field['key']=='period_date' ) {

					$time = strtotime($item['period_date_end']);

					$val =    date('j', strtotime($item['period_date_start']))
							. '-' 
							. date('j ', $time)
							. $this->q('time')->month( date('n', $time) )
							. date(' Y', $time);

				} elseif( $field['key']=='file' ) {
					$val = '<a href="#" style="text-decoration: none;"><span class="ui-status"><i class="icon-image"></i><span class="mls">ไฟล์อ้างอิง</span></span></a>';

				} elseif( $field['key']=='__seq' ) {
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'">'.$val.'</td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}


	/**/
	/* manageTourColumn */
	/**/
	public function manageTourColumn()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-status', 'key'=>'status', 'label'=>'สถานะ', 'type'=>'status');

		$th[] = array('cls'=>'td-status', 'key'=>'code', 'label'=>'Code');
		$th[] = array('cls'=>'td-name', 'key'=>'name', 'label'=>'Name');
		$th[] = array('cls'=>'td-date', 'key'=>'country_name', 'label'=>'Country');
		$th[] = array('cls'=>'td-date', 'key'=>'air_name', 'label'=>'Airline');
		$th[] = array('cls'=>'td-date', 'key'=>'update_date_str', 'label'=>'Last update');
		$th[] = array('cls'=>'td-date', 'key'=>'create_date_str', 'label'=>'Create Date');

		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}
	public function manageTourRows($data, $options=array())
	{
		$th = $this->manageTourColumn();

		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $key => $item) {
			$__seq ++;


			$li .= '<tr>';
			foreach ($th as $i => $field) {
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$dropdownList = $this->manageTourActions($item);

					$val = '<div class="group-btn">';

						$val .= '<a class="btn btn-small" title="'.Translate::val( 'Edit' ).'" href="'.URL.'manage/tour/'.$item['id'].'" target="_blank"><i class="icon-pencil"></i></a>';

						if( !empty($dropdownList) ){
							$val .= '<a data-plugin="dropdown2" class="btn btn-small" data-options="'.$this->stringify( array(
				                    'select' => $dropdownList,
				                    'axisX'=> 'right',
				                    // 'container'=> '.tb',
				                ) ).'"><i class="icon-ellipsis-v"></i></a>';
						}
					$val .= '<div>';
				} elseif( $field['key']=='status' ){


					if( $item['status']==0 ){
						$val = '<span class="ui-status" style="background-color:#f2f2f2;color:#000">แบบร่าง</span>';
					}
					else if( !empty($item['status_background']) && !empty($item['status_name']) ){
						$val = '<span class="ui-status" style="background-color:'.$item['status_background'].'">'.$item['status_name'].'</span>';
						
					}
					
				}
				elseif( $field['key']=='__seq' ) {
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'">'.$val.'</td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}
	public function manageTourActions($item)
	{
		$lists = array();
        if( !empty($item['word_url']) ){
            $lists[] = array(
                'text' => 'ดาวน์โหลด Word',
                'href' => $item['word_url'],
                'target'=> "_blank"
            );
        }


        if( !empty($item['pdf_url']) ){
            $lists[] = array(
                'text' => 'ดาวน์โหลด PDF',
                'href' => $item['pdf_url'],
                'target'=> "_blank"
            );
        }

        if( !empty($item['banner_url']) ){
            $lists[] = array(
                'text' => 'ดาวน์โหลด แบนเนอร์',
                'href' => $item['banner_url'],
                'target'=> "_blank"
            );
        }

        if( !empty($lists) ) $lists[] = array('type' => 'separator');
        /*$lists[] = array(
            'text' => Translate::Val('Clone Serie'),
            'href' => URL.'tour/'.$item['id'].'/clone/',
            'attr' => array('data-plugin'=>'lightbox'),
        );*/

        $lists[] = array(
            'text' => Translate::Val('Add Period'),
            'href' => URL.'tour/'.$item['id'].'/period/create/',
            'attr' => array('data-plugin'=>'lightbox'),
        );

        /*$lists[] = array('type' => 'separator');
        $lists[] = array(
            'text' => Translate::Val('Enable'),
            'href' => URL.'users/del/',
            'attr' => array('data-plugin'=>'lightbox'),
        );
        $lists[] = array(
            'text' => Translate::Val('Disable'),
            'href' => URL.'users/del/',
            'attr' => array('data-plugin'=>'lightbox'),
        );*/


        $lists[] = array('type' => 'separator');
        $lists[] = array(
            'text' => Translate::Val('Delete'),
            'href' => URL.'tour/del/'.$item['id'],
            'attr' => array('data-plugin'=>'lightbox'),
        );


        return $lists;
	}

	/* -- promotion -- */
	public function promotionColumn()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-status', 'key'=>'status', 'label'=>'สถานะ', 'type'=>'status');

		$th[] = array('cls'=>'td-name', 'key'=>'name', 'label'=>'Name');
		$th[] = array('cls'=>'td-date', 'key'=>'date_str', 'label'=>'ระยะเวลา');
		$th[] = array('cls'=>'td-date', 'key'=>'discount_str', 'label'=>'ส่วนลด');

		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}
	public function promotionRows($data, $options=array())
	{
		$th = $this->promotionColumn();

		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $key => $item) {
			$__seq ++;


			$li .= '<tr>';
			foreach ($th as $i => $field) {
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$val = '<div class="group-btn">';					
						$val .= '<a class="btn" data-plugin="lightbox" href="'.URL.'promotion/edit/'.$item['id'].'">แก้ไข</a>';
                    	$val .= '<a class="btn btn-red" data-plugin="lightbox" href="'.URL.'promotion/del/'.$item['id'].'"><i class="icon-remove"></i></a>';
					$val .= '<div>';
				} elseif( $field['key']=='status' ) {
					$val = '<label class="switch"><input type="checkbox" data-action-update="checked" name="enabled"'. (!empty($item['enabled'])? ' checked':'') .'><span class="slider round"></span></label>';

				} elseif( $field['key']=='__seq' ) {
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'"><span ref="'.$field['key'].'">'.$val.'</span></td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}



	public function agencyCompanyColumn()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-star', 'key'=>'guarantee', 'label'=>'');
		$th[] = array('cls'=>'td-image', 'key'=>'image', 'label'=>'');

		$th[] = array('cls'=>'td-name', 'key'=>'name', 'label'=>'ชื่อ', 'sort'=>'agen_com_name');
		$th[] = array('cls'=>'td-code', 'key'=>'license_number', 'label'=>'License');
		$th[] = array('cls'=>'td-email td-ellipsis', 'key'=>'email', 'label'=>'Email');
		$th[] = array('cls'=>'td-phone td-ellipsis', 'key'=>'tel', 'label'=>'Phone');
		$th[] = array('cls'=>'td-wabsite td-ellipsis', 'key'=>'wabsite', 'label'=>'Wabsite');
		$th[] = array('cls'=>'td-address', 'key'=>'address', 'label'=>'Address');
		$th[] = array('cls'=>'td-status', 'key'=>'status_arr', 'label'=>'Status', 'type'=>'status');
		$th[] = array('cls'=>'td-date', 'key'=>'update_date_str', 'label'=>'Last Updated', 'type'=>'date', 'sort' => 'update_date');

		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}


	public function agencyCompanyRows($data, $options=array())
	{
		$th = $this->agencyCompanyColumn();

		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $i => $item) {
			$__seq ++;

			$cls = $i%2 ? 'even' : "odd";
			$cls .= !empty($item['guarantee']) ? ' has-guarantee':'';

			$li .= '<tr class="'.$cls.'" item-id="'.$item['id'].'">';
			foreach ($th as $field) {
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$dropdownList = array();
			        $dropdownList[] = array(
			            'text' => 'ลบ',//Translate::Val('Delete'),
			            'href' => URL.'agency/del/company/'.$item['id'],
			            'attr' => array('data-plugin'=>'lightbox'),
			        );

					$val = '<div class="group-btn">';					
						$val .= '<a class="btn" title="Edit" data-plugin="lightbox" href="'.URL.'agency/edit/company/'.$item['id'].'"><i class="icon-pencil"></i></a>';
                    	$val .= '<a data-plugin="dropdown2" class="btn" data-options="'.$this->stringify( array(
			                    'select' => $dropdownList,
			                    'axisX'=> 'right',
			                    'container'=> '.entity-list',
			                ) ).'"><i class="icon-ellipsis-v"></i></a>';
					$val .= '<div>';

				} elseif( $field['key']=='status' ) {
					$val = '<label class="switch"><input type="checkbox" data-action-update="checked" name="enabled"'. (!empty($item['enabled'])? ' checked':'') .'><span class="slider round"></span></label>';

				} elseif( $field['key']=='guarantee' ) {

					$val = '<span class="mousehover js-guarantee" data-table-action="guarantee" ref="guarantee_str">'.$item['guarantee_str'].'</span>';
					
				} elseif( $field['key']=='address' ) {

					$address = '';
					$val = '<span ref="address">'.$address.'</span>';

				} elseif( $field['key']=='name' ) {
					$val = '<div class="hdr-text"><span ref="name">'.$item['name'].'</span></div>';
					$val .= '<div class="fcg fsm"><span ref="name_th">'.$item['name_th'].'</span></div>';

				} elseif( $field['key']=='image' ) {
					$val = '<div class="avatar size32 no-avatar"><div class="initials"><i class="icon-building-o"></i></div></div>';

				} elseif( $field['key']=='status_arr' ) {

					$val = '<span ref="status_str" class="ui-status" style="background-color: '.$item['status_arr']['css']['background-color'].'">'.$item['status_arr']['name'].'</span>';

				} elseif( $field['key']=='__seq' ) {
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'"><div class="hdr-text"><span ref="'.$field['key'].'">'.$val.'</span></div></td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}





	public function agencySalesColumn()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-star', 'key'=>'star', 'label'=>'');

		$th[] = array('cls'=>'td-image', 'key'=>'image', 'label'=>'');
		$th[] = array('cls'=>'td-name', 'key'=>'name', 'label'=>'ชื่อ', 'sort'=>'agen_fname');

		$th[] = array('cls'=>'td-position td-ellipsis', 'key'=>'position', 'label'=>'Position');
		$th[] = array('cls'=>'td-company td-ellipsis', 'key'=>'company_name', 'label'=>'Company');


		$th[] = array('cls'=>'td-email td-ellipsis', 'key'=>'email', 'label'=>'Email');
		$th[] = array('cls'=>'td-phone td-ellipsis', 'key'=>'tel', 'label'=>'Phone');
		$th[] = array('cls'=>'td-phone td-ellipsis', 'key'=>'line_id', 'label'=>'Line');

		$th[] = array('cls'=>'td-status', 'key'=>'status_arr', 'label'=>'Status', 'type'=>'status');

		$th[] = array('cls'=>'td-date', 'key'=>'lastvisit_str', 'label'=>'Last sign in', 'type'=>'date', 'sort'=>'lastvisit');
		$th[] = array('cls'=>'td-date', 'key'=>'update_date_str', 'label'=>'Last Updated', 'type'=>'date', 'sort'=>'update_date');

		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');

		return $th;
	}
	public function agencySalesRows($data, $options=array())
	{
		$th = $this->agencySalesColumn();

		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $i => $item) {
			$__seq ++;

			$cls = $i%2 ? 'even' : "odd";
			$cls .= !empty($item['star']) ? ' has-star':'';

			$li .= '<tr class="'.$cls.'" item-id="'.$item['id'].'">';
			foreach ($th as $field) {
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$dropdownList = array();

			        if( $item['status']==1 ){
			            $dropdownList[] = array(
			                'text' => 'ระงับผู้ใช้',
			                'href' => URL.'agency/disabled/'.$item['id'],
			                'attr' => array('data-plugin'=>'lightbox'),
			            );
			        }else{

			            $dropdownList[] = array(
			                'text' => 'เปิดใช้งาน',
			                'href' => URL.'agency/enabled/'.$item['id'],
			                'attr' => array('data-plugin'=>'lightbox'),
			            );
			        }

			        $dropdownList[] = array(
			            'text' => 'ลบ',
			            'href' => URL.'agency/del/sales/'.$item['id'],
			            'attr' => array('data-plugin'=>'lightbox'),
			        );

					$val = '<div class="group-btn">';					
						$val .= '<a class="btn" title="Reset password" data-plugin="lightbox" href="'.URL.'agency/reset_password/'.$item['id'].'"><i class="icon-lock"></i></a>'.
			                '<a class="btn" title="Edit" data-plugin="lightbox" href="'.URL.'agency/edit/sales/'.$item['id'].'"><i class="icon-pencil"></i></a>'.

			                '<a data-plugin="dropdown2" class="btn" data-options="'.$this->stringify( array(
			                    'select' => $dropdownList,
			                    'axisX'=> 'right',
			                    'container'=> '.entity-list',
			                ) ).'"><i class="icon-ellipsis-v"></i></a>';

					$val .= '<div>';
				} elseif( $field['key']=='status' ) {
					$val = '<label class="switch"><input type="checkbox" data-action-update="checked" name="enabled"'. (!empty($item['enabled'])? ' checked':'') .'><span class="slider round"></span></label>';

				} elseif( $field['key']=='star' ) {

					$val = '<span class="mousehover js-star" data-table-action="star">'.( !empty($item['star']) ? '<i class="icon-star"></i>': '<i class="icon-star-o"></i>' ).'</span>';
					

				} elseif( $field['key']=='line_id' ) {

					$val = '';
					if( !empty($item[$field['key']]) ){
						$val = '<div class="hdr-text"><a ref="'.$field['key'].'" target="_blank" href="http://line.me/ti/p/~'.$item[$field['key']].'">'.$item[$field['key']].'</a></div>';
					}

					
				} elseif( $field['key']=='image' ) {
					$val = '<div class="avatar size32 no-avatar"><div class="initials"><i class="icon-user-circle-o"></i></div></div>';

				} elseif( $field['key']=='status_arr' ) {

					$val = '<span ref="status_str" class="ui-status" style="background-color: '.$item['status_arr']['css']['background-color'].'">'.$item['status_arr']['name'].'</span>';

				} elseif( $field['key']=='__seq' ) {
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'"><div class="hdr-text"><span ref="'.$field['key'].'">'.$val.'</span></div></td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}



	public function usersColumn()
	{
		$th = array();
		$th[] = array('cls'=>'td-seq', 'key'=>'__seq', 'label'=>'#');
		$th[] = array('cls'=>'td-star', 'key'=>'star', 'label'=>'');

		$th[] = array('cls'=>'td-image', 'key'=>'image', 'label'=>'');
		$th[] = array('cls'=>'td-name', 'key'=>'name', 'label'=>'ชื่อ');
		$th[] = array('cls'=>'td-position td-ellipsis', 'key'=>'nickname', 'label'=>'ชื่อเล่น');

		$th[] = array('cls'=>'td-position td-ellipsis', 'key'=>'position', 'label'=>'Position');

		$th[] = array('cls'=>'td-email td-ellipsis', 'key'=>'email', 'label'=>'Email');
		$th[] = array('cls'=>'td-phone td-ellipsis', 'key'=>'tel', 'label'=>'Phone');
		$th[] = array('cls'=>'td-phone td-ellipsis', 'key'=>'line_id', 'label'=>'Line');

		$th[] = array('cls'=>'td-status', 'key'=>'status_arr', 'label'=>'Status', 'type'=>'status');

		$th[] = array('cls'=>'td-date', 'key'=>'lastvisit_str', 'label'=>'Last sign in', 'type'=>'date', 'sort'=>'lastvisit');
		$th[] = array('cls'=>'td-date', 'key'=>'update_date_str', 'label'=>'Last Updated', 'type'=>'date', 'sort'=>'update_date');

		$th[] = array('cls'=>'td-action', 'key'=>'action', 'label'=>'Actions');


		return $th;
	}
	public function usersRows($data, $options=array())
	{
		$th = $this->usersColumn();

		$li = ''; $__seq = ($options['page']*$options['limit']) - $options['limit'];
		foreach ($data as $i => $item) {
			$__seq ++;

			$cls = $i%2 ? 'even' : "odd";
			$cls .= !empty($item['star']) ? ' has-star':'';

			$li .= '<tr class="'.$cls.'" item-id="'.$item['id'].'">';
			foreach ($th as $field) {
				$type = isset($field['type']) ? $field['type']: 'text';
				if( $field['key']=='action' ){

					$dropdownList = array();

			        if( $item['status']==1 ){
			            $dropdownList[] = array(
			                'text' => 'ระงับผู้ใช้',
			                'href' => URL.'users/disabled/'.$item['id'],
			                'attr' => array('data-plugin'=>'lightbox'),
			            );
			        }

			        if( $item['status']==2 ){
			            $dropdownList[] = array(
			                'text' => 'เปิดใช้ใหม่',
			                'href' => URL.'users/enabled/'.$item['id'],
			                'attr' => array('data-plugin'=>'lightbox'),
			            );
			        }
			        
			        $dropdownList[] = array(
			            'text' => 'ลบผู้ใช้',//Translate::Val('Delete'),
			            'href' => URL.'users/del/'.$item['id'],
			            'attr' => array('data-plugin'=>'lightbox'),
			        );

					$val = '<div class="group-btn">';					
						$val .= '<a class="btn" title="Change password" data-plugin="lightbox" href="'.URL.'users/change_password/'.$item['id'].'"><i class="icon-lock"></i></a>';
						$val .= '<a class="btn" title="Edit" data-plugin="lightbox" href="'.URL.'users/edit/'.$item['id'].'"><i class="icon-pencil"></i></a>';

                    	$val .= '<a data-plugin="dropdown2" class="btn" data-options="'.$this->stringify( array(
			                    'select' => $dropdownList,
			                    'axisX'=> 'right',
			                    'container'=> '.entity-list',
			                ) ).'"><i class="icon-ellipsis-v"></i></a>';

					$val .= '<div>';

				} elseif( $field['key']=='status_arr' ) {

					$val = '';
			        switch ($item['status']) {
			            case 1: $val = '<span class="ui-status">ใช้งาน</span>'; break;
			            case 2: $val = '<span class="ui-status" style="background-color:#F44336">ระงับ</span>'; break;
			            case 9: $val = '<span class="ui-status" style="background-color:#333">ลบ</span>'; break;
			        }


				} elseif( $field['key']=='star' ) {

					$val = '<span class="mousehover js-star" data-table-action="star">'.( !empty($item['star']) ? '<i class="icon-star"></i>': '<i class="icon-star-o"></i>' ).'</span>';
					

				} elseif( $field['key']=='line_id' ) {

					$val = '';
					if( !empty($item[$field['key']]) ){
						$val = '<div class="hdr-text"><a ref="'.$field['key'].'" target="_blank" href="http://line.me/ti/p/~'.$item[$field['key']].'">'.$item[$field['key']].'</a></div>';
					}

				} elseif( $field['key']=='image' ) {
					$val = '<div class="avatar size32 no-avatar"><div class="initials"><i class="icon-user"></i></div></div>';

				} elseif( $field['key']=='status_arr' ) {

					$val = '<span ref="status_str" class="ui-status" style="background-color: '.$item['status_arr']['css']['background-color'].'">'.$item['status_arr']['name'].'</span>';

				} elseif( $field['key']=='__seq' ) {
					$val = $__seq;
				}
				else{
					
					$val = !empty($item[$field['key']]) ? $item[$field['key']]: '';
					if( $type=='price' || $type=='number' ){
						$val = empty($val) ? 0: number_format($val);
					}
				}

				$li .= '<td class="'.$field['cls'].'"><div class="hdr-text"><span ref="'.$field['key'].'">'.$val.'</span></div></td>';
			}
			$li .= '</tr>';
		}
		
		return $li;
	}
}