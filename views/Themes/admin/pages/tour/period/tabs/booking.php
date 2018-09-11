<?php

$keys = array();
$keys[] = array('label'=>'#', 'key'=>'seq', 'cls'=>'td-no');
$keys[] = array('label'=>'สถานะ', 'key'=>'status', 'ui'=>'booking_status', 'cls'=>'td-status' );
$keys[] = array('label'=>'Booking No.', 'key'=>'book_code', 'cls'=>'td-code');
$keys[] = array('label'=>'จำนวนคน', 'key'=>'book_total_pax', 'cls'=>'td-qty');
$keys[] = array('label'=>'ยอดรวม', 'key'=>'book_total', 'type'=>'int', 'cls'=>'td-price td-bg-lightred');
$keys[] = array('label'=>'ส่วนลด', 'key'=>'book_discount', 'type'=>'int', 'cls'=>'td-price td-bg-sky');
$keys[] = array('label'=>'ยอดสุทธิ', 'key'=>'book_amountgrandtotal', 'type'=>'int', 'cls'=>'td-price td-bg-green');
$keys[] = array('label'=>'วันที่จอง', 'key'=>'create_date', 'type'=>'date', 'cls'=>'td-date td-bg-yellow');
$keys[] = array('label'=>'วันหมดอายุ', 'key'=>'book_due_date_full_payment', 'type'=>'date', 'cls'=>'td-date td-bg-red', 'ui'=>'booking_dateCancel');
$keys[] = array('label'=>'ชื่อบริษัท', 'key'=>'company_name', 'cls'=>'td-company');
$keys[] = array('label'=>'Booking By', 'key'=>'user_name', 'cls'=>'td-content');
$keys[] = array('label'=>'Sales contact', 'key'=>'agen_name', 'cls'=>'td-content', 'desc'=>"agen_position");
$keys[] = array('label'=>'Amount Receive', 'key'=>'book_receipt', 'type'=>'int', 'cls'=>'td-price td-bg-gary');
$keys[] = array('label'=>'', 'key'=>'action', 'cls'=>'td-action', 'context'=>'<div class=""><a class="btn btn-small"><i class="icon-cogs"></i><span class="mls">จัดการ</span></a></div>');



?><div class="pal" style="overflow-x: auto;">
	

	<table class="table-booking">
		<thead>
			<tr><?php 

			foreach ($keys as $key => $value) {

				$cls = '';
				if( !empty($value['cls']) ){
					$cls .= !empty($cls)? ' ':'';
					$cls .= $value['cls'];
				}

				$cls = !empty($cls)? ' class="'.$cls.'"':'';
				echo '<th'.$cls.'>'.$value['label'].'</th>';
			}
				
			?></tr>
		</thead>
		<tbody><?php 

			if( !empty($this->booking) ){

			
				$seq = 0;
				foreach ($this->booking as $data) {
					$seq++;

				echo '<tr>';
				foreach ($keys as $key => $value) {

					if( isset($value['context']) ){
						$val = $value['context'];

					}elseif( $value['key']=='seq' ){
						$val = $seq;

					}elseif( isset($value['ui']) ){

						if( $value['ui']=='booking_status' ){
							if( !empty($this->status[$data[$value['key']]]) ){
								$status = $this->status[$data[$value['key']]];
								$val = '<span class="ui-status" style="background-color: '.$status['color'].'">'.$status['name'].'</span>';
							}
							else{
								$val = '-';
							}
							
						}
						elseif( $value['ui']=='booking_dateCancel' ){

							if ($data['status'] >= 25 && $data['status'] != 40 ){
								$val = $data['book_due_date_full_payment'];
							}
							else{

								if( $data['book_master_deposit']==0 ){
									$val = $data['book_due_date_full_payment'];
								}
								else{
									$val = $data['book_due_date_deposit'];
								}
							}

							$time = strtotime($val);

							$val = date('j', $time) . ' '. $this->fn->q('time')->month( date('n', $time) ) . ' ' .date('Y', $time);
							$val .= '<div class="fsm fcg">'.date('H:i', $time).'</div>';

						}

						

						// $val = $this->ui->frame( $value['ui'] )->fetch();
					}else{

						$type = isset($value['type'])? $value['type']: 'text';
						$empty = isset($value['empty'])? $value['empty']: '-';

						$val = !empty($data[$value['key']])? $data[$value['key']]:$empty;
						if( $type=='int' && !empty($val) ){
							$val = number_format($val);
						}
						elseif( $type=='date' && !empty($val) ){


							if( $val=='0000-00-00 00:00:00' ){
								$val = $empty;
							}
							else{
								$time = strtotime($val);

								$val = date('j', $time) . ' '. $this->fn->q('time')->month( date('n', $time) ) . ' ' .date('Y', $time);
								$val .= '<div class="fsm fcg">'.date('H:i', $time).'</div>';

							}
						}
					}


					if( isset($value['desc']) ){
						if( !empty($data[$value['desc']]) ){
							$val .=  '<div class="fsm fcg">'.$data[$value['desc']].'</div>';
						}
					}

					$cls = '';
					if( !empty($value['cls']) ){
						$cls .= !empty($cls)? ' ':'';
						$cls .= $value['cls'];
					}

					$cls = !empty($cls)? ' class="'.$cls.'"':'';
					echo '<td'.$cls.'><span data-ref="'.$value['key'].'">'.$val.'</span></td>';
				}
				echo '</tr>';

				}

			}
			else{

				echo '<tr><td colspan="'.count($keys).'" style="padding: 60px;text-align: center;color: #999;background-color: #f7f7f7;">ยังไม่มีการจองในพีเรียดนี้, <a>จอง</a></td></tr>';
			}
		?></tbody>
	</table>
</div>