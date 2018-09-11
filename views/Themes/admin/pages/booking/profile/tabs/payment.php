<?php

/**/
/* summary */
/**/
$balance = $this->item['receipt']-$this->item['amountgrandtotal'];
$summaryList[] = array('id'=>'','label'=>'Deposit', 'value'=>$this->item['master_deposit'], 'cls'=>'fc-blue', 'countdown' => $this->item['due_date_deposit']!='0000-00-00 00:00:00'? $this->item['due_date_deposit']: '');
$summaryList[] = array('id'=>'','label'=>'Full Payment', 'value'=>$this->item['master_full_payment'], 'cls'=>'fc-blue', 'countdown' => in_array($this->item['status'], array(0, 10)) && $this->item['due_date_full_payment']!='0000-00-00 00:00:00'? $this->item['due_date_full_payment']: '' );
$summaryList[] = array('id'=>'','label'=>'Total', 'value'=>$this->item['amountgrandtotal'], 'cls'=>'fc-blue');
$summaryList[] = array('id'=>'','label'=>'Amount Receive', 'value'=>$this->item['receipt'], 'cls'=>'fc-blue');
$summaryList[] = array('id'=>'','label'=>'Total Balance', 'value'=>$balance, 'cls'=>$balance<0? 'fc-red':'fc-blue');


/**/
/* Booking List */
/**/
$booking_fieldList = array();
$booking_fieldList[] = array('id'=>'_seq','label'=>'#', 'cls'=>'td-seq');
$booking_fieldList[] = array('id'=>'book_list_name','label'=>'รายการ', 'cls'=>'td-name');
$booking_fieldList[] = array('id'=>'book_list_qty','label'=>'จำนวน', 'cls'=>'td-qty');
$booking_fieldList[] = array('id'=>'book_list_price','label'=>'ราคา', 'cls'=>'td-price', 'type'=>'number');
$booking_fieldList[] = array('id'=>'book_list_total','label'=>'รวม', 'cls'=>'td-total', 'type'=>'number');


/**/
/* Extra List  */
/**/
$ExtraList_fieldList = array();
$ExtraList_fieldList[] = array('id'=>'_seq','label'=>'#', 'cls'=>'td-seq');
$ExtraList_fieldList[] = array('id'=>'name','label'=>'รายการ', 'cls'=>'td-name');
$ExtraList_fieldList[] = array('id'=>'value','label'=>'จำนวน', 'cls'=>'td-qty');
$ExtraList_fieldList[] = array('id'=>'price','label'=>'ราคา', 'cls'=>'td-price', 'type'=>'number');
$ExtraList_fieldList[] = array('id'=>'total','label'=>'รวม', 'cls'=>'td-total', 'type'=>'number');

$ExtraList = $this->extraList;

/**/
/* Payment List */
/**/
$paymentList_fieldList = array();
$paymentList_fieldList[] = array('id'=>'_seq', 'label'=>'#');
$paymentList_fieldList[] = array('id'=>'status', 'label'=>'สถานะ');
// $paymentList_fieldList[] = array('id'=>'', 'label'=>'Invoice No.');
$paymentList_fieldList[] = array('id'=>'file', 'label'=>'ไฟล์อ้างอิง', '');
$paymentList_fieldList[] = array('id'=>'bank_name', 'label'=>'ธนาคาร');
$paymentList_fieldList[] = array('id'=>'bankbook_branch', 'label'=>'สาขาบัญชี');
$paymentList_fieldList[] = array('id'=>'bankbook_code', 'label'=>'เลขที่บัญชี');
$paymentList_fieldList[] = array('id'=>'received', 'label'=>'จำนวนเงิน', 'cls'=>'td-price', 'type'=>'number');
$paymentList_fieldList[] = array('id'=>'date', 'label'=>'วันที่โอน', 'type'=>'date');
$paymentList_fieldList[] = array('id'=>'time', 'label'=>'เวลาที่โอน', 'cls'=>'td-time');
// $paymentList_fieldList[] = array('id'=>'create_name', 'label'=>'ผู้ทำรายการ');
$paymentList_fieldList[] = array('id'=>'create_date', 'label'=>'วันที่ทำรายการ', 'type'=>'date');
$paymentList_fieldList[] = array('id'=>'book_status', 'label'=>'สถานะการชำระเงิน');				
$paymentList_fieldList[] = array('id'=>'_action', 'label'=>'Action');				

$InvoiceList = array();


function tablePayment( $data, $options=array() )
{
	$thead = '';
	foreach ($options['fields'] as $field) {
		$thead .= '<th>'.$field['label'].'</th>';
	}

	if( !empty($thead) ){
		$thead = '<thead><tr>'.$thead.'</tr></thead>';
	}

	$sums = !empty($options['sum'])? $options['sum']: array();
	$sum = array();
	

	$li = '';
	$_seq = 0;
	foreach ($data as $item) {

		$trCls = isset($item['cls'])? $item['cls']: '';


		$trCls = !empty($trCls)? ' class="'.$trCls.'"':'';
		$li .= '<tr'.$trCls.'>';

		$_seq++;
		foreach ($options['fields'] as $field) {

			$cls = isset($field['cls'])? $field['cls']: '';
			$type = isset($field['type'])? $field['type']: 'text';

			if( $field['id']=='_seq' ){
				$val = $_seq;

				$cls .= !empty($cls)? ' ':'';
				$cls .= 'td-seq';
			}
			elseif($field['id']=='_action'){
				$val = $options['actions'];
			}
			else{
				$val = !empty($item[$field['id']]) ? $item[$field['id']]: '';
			}

			if( in_array($field['id'], $sums) && is_numeric($val) ){

				if( empty($sum[ $field['id'] ]) ) $sum[ $field['id'] ] = 0;
				$sum[ $field['id'] ] += $val;
			}

			if( $type=='number' ){
				$val = number_format($val);
			}

			$cls = !empty($cls)? ' class="'.$cls.'"':'';

			$sub = '';
			if( isset($item['items']) && isset($field['items']) ){

				$subItem = '';
				foreach ($item['items'] as $key => $value) {
					
					$subItem .= '<tr>';
					foreach ($field['items'] as $fid) {

						$_cls = isset($fid['cls'])? $fid['cls']: '';

						if( $fid['id']=='_seq' ){
							$_val = '-';

							$_cls .= !empty($_cls)? ' ':'';
							$_cls .= 'td-seq';
						}
						else{
							$_val = !empty($value[$fid['id']]) ? $value[$fid['id']]: '';
						}

						$_cls = !empty($_cls)? ' class="'.$_cls.'"':'';
						$subItem .= '<td'.$_cls.'>'.$_val.'</td>';
					}
					$subItem .= '</tr>';
				}

				$sub = '<table class="table-inner">'.$subItem.'</table>';
			}



			$li .= '<td'.$cls.'>'.$val.$sub.'</td>';
		}
		
		$li .= '</tr>';
	}


	$summary = '';

	if( empty($data) ){
		$li = '<tr class="tr-empty"><td colspan="'.count($options['fields']).'">No result found.</td></tr>';
	}

	else{
		
		foreach ($options['fields'] as $field) {

			if( isset( $sum[ $field['id'] ] ) ){
				$summary .= '<td class="td-total fc-blue">'.number_format($sum[ $field['id'] ]).'</td>';
			}
			else{
				$summary .= '<td></td>';
			}
			
		}
	}


	return '<div class="mbl">'.
		'<div style="padding: 5px 10px;border:1px solid #ccc;border-bottom-width: 0;border-radius: 3px 3px 0 0;background-color: #fff;"><h3>'.$options['title'].'</h3></div>'.
		'<table class="table-payment">'.
			$thead.
			'<tbody>'.$li.'</tbody>'.
			( !empty($summary) ? '<tbody><tr class="tr-summary">'.$summary.'</tr></tbody>':'' ).
		'</table>'.
	'</div>';

} // end: fun: tablePayment;
?>

<style type="text/css">
	.ui-list-card{margin: -10px;}
	.ui-list-card>li{}
	.ui-list-card .outer{margin: 10px;background: #fff;border-radius: 3px;padding: 10px;border:1px solid #ccc;}
	.ui-list-card .inner{display:table;width: 100%}
	.ui-list-card .label{font-weight: normal;display: table-cell;vertical-align: bottom;}
	.ui-list-card .value{font-size: 22px;font-weight: bold;display: table-cell;text-align: right;}
	.ui-list-card .countdown{font-size: 11px;color: #666;display: block;font-weight: normal;}

	.table-payment th, .table-payment td{border:1px solid #ccc;padding: 3px 4px;}
	.table-payment th{font-size: 11px;background-color: #eee;white-space: nowrap;}
	.table-payment td{background-color: #fff}

	.table-payment .td-seq{text-align: center;width: 10px;white-space: nowrap;vertical-align: top;padding-left: 12px;padding-right: 12px}
	.table-payment .td-qty{text-align: center;width: 10px;white-space: nowrap}
	.table-payment .td-price,.table-payment .td-number{text-align: right;width: 80px;white-space: nowrap;vertical-align: bottom;padding-left: 10px}
	.table-payment .td-total{text-align: right;width: 100px;white-space: nowrap;vertical-align: bottom;font-weight: bold;font-size: 110%;padding-left: 10px}
	.table-payment .td-date{width: 30px;white-space: nowrap;}
	.table-payment .td-action{width: 40px;white-space: nowrap;}
	.table-payment .td-status{width:20px;white-space: nowrap;}
	.table-payment .td-status .ui-status{display:block;text-align: center;}
	.table-payment .tr-empty td{padding: 10px;background-color: #ddd;color: #999;text-align: center}
	.table-payment .tr-summary td{border-width: 0;text-align: right;font-weight: bold;background-color: transparent;}


	.table-inner td{border-width: 0 0 1px;border-color: #eee}
	.tr-extralist .table-inner td{color: #999;font-size: 90%;}


	
</style>

<div style="padding-top: 15px">

	<div class="container pvl">
		
		<div class="row-fluid clearfix mbl">

			<div class="span3">
				
				<ul class="ui-list-card"><?php

					foreach ($summaryList as $item) {

						$countdown = '';
						if( !empty($item['countdown']) ){

							$date = date('j M Y H:i', strtotime($item['countdown']));

							$countdown = '<div class="countdown tar">'.$date.' (<span data-plugin="countdownDate" data-date="'.$item['countdown'].'"></span>)</div>';
						}

						
						echo '<li><div class="outer">'.

							'<div class="inner">'.
								'<strong class="label">'.$item['label'].'</strong>'.
								'<span class="value '.$item['cls'].'">'. 
									($item['value']!=0? number_format($item['value']): '-') .
								'</span>'.
							'</div>'.
							$countdown.
						'</div></li>';
					}


				?></ul>
			</div>

			<div class="span7">

			<?php 

			echo tablePayment($this->item['detail'], array(
				'fields' => $booking_fieldList,
				'title' => 'Booking',
				'sum' => array('book_list_total'),
			));


			echo tablePayment($ExtraList, array(
				'fields' => $ExtraList_fieldList,
				'title' => 'Extra List',
				'sum' => array('total'), 
			));


			echo tablePayment($this->discountList, array(
				'fields' => $ExtraList_fieldList,
				'title' => 'Discount',
				'sum' => array('total'), 
			));

			?>
			</div>


			<div class="span2">
				<ul class="pbl ui-list-btn">
					<?php if( $this->item['status']!=40 ) { ?>
	                <li><a class="btn btn-green"><i class="icon-print"></i><span class="mls">Print Invoice</span></a></li>
	                <li><a class="btn btn-green"><i class="icon-paper-plane-o"></i><span class="mls">Send Invoice</span></a></li>
	                <li class="divider"></li>
	                <li><a class="btn btn-blue">แจ้งการชำระเงิน</a></li>
	                <li class="divider"></li>
	                <li><a class="btn btn-red" href="<?=URL?>booking/cancel/<?=$this->item['id']?>" data-plugin="lightbox">ยกเลิกการจอง</a></li>
	            	<?php } ?>
	            </ul>
			</div>


		</div>

		<div>

			<?php

			$thead = '';
			foreach ($paymentList_fieldList as $key => $value) {
				$thead .= '<th>'.$value['label'].'</th>';
			}


			$li = ''; $seq = 0;
			foreach ($this->paymentList as $i => $item) {
				$seq++;

				$li .= '<tr>'; 

				foreach ($paymentList_fieldList as $key => $value) {

					$type = isset($value['type']) ? $value['type']: 'text';

					if( $value['id']=='_seq' ){
						$li .= '<td class="td-seq" style="vertical-align: middle;">'.$seq.'</td>';
					}
					elseif($value['id']=='_action'){
						$li .= '<td class="td-action"><div class="group-btn">'.
							'<button type="button" class="btn btn-small btn-blue"><i class="icon-check"></i><span class="mls">อนุมัติ</span></button>'.
							'<button type="button" class="btn btn-small btn-red"><i class="icon-close"></i><span class="mls">ไม่อนุมัติ</span></button>'.
						'</div></td>';
					}
					else{

						$val = '';
						$cls = '';

						if( $value['id']=='status' ){
							$cls = 'td-status';
							$val = '<span class="ui-status" style="background-color:'.$item['status_arr']['color'].'">'.$item['status_arr']['name'].'</span>';
						}
						else if( $value['id']=='file' ){
							$cls = 'td-status';
							$val = '<a href="#"><span class="ui-status"><i class="icon-image"></i><span class="mls">ไฟล์อ้างอิง</span></span></a>';
						}
						else if( $value['id']=='create_date' ){
							$cls = 'td-date';

							$val = date('j M Y', strtotime($item['create_date']));
							$val .= '<div class="fsm fcg">'. date('H:i', strtotime($item['create_date'])) .' - '.$item['createby_name'].'</div>';
						}
						else if( $value['id']=='book_status' ){

							$cls = 'td-status';
							$val = '<span class="ui-status" style="background-color:'.$item['book_status_arr']['color'].'">'.$item['book_status_arr']['name'].'</span>';
						}
						else{


							$val = !empty($item[$value['id']]) ? $item[$value['id']]: '';

							if( !empty($val) ){
								if( $type=='number' ){
									$cls = 'td-number';

									$val = number_format($val);
									$val = $val==0?'-': '<span class="fc-blue">'.$val.'</span>';
								}
								elseif($type=='date') {
									$cls = 'td-date';
									$val = date('j M Y', strtotime($val));
								}
							}

							
						}

						$li .= '<td class="'.$cls.'">'.$val.'</td>';
					}

					
				}

				$li .= '</tr>';
			}

			?>
			<div class="mbl">
				<div style="padding: 5px 10px;border:1px solid #ccc;border-bottom-width: 0;border-radius: 3px 3px 0 0;background-color: #fff;"><h3>Payment</h3></div>
				<table class="table-payment">
					<thead><tr><?=$thead?></tr></thead>
					<tbody><?=$li?></tbody>
					<!-- ( !empty($summary) ? '<tbody><tr class="tr-summary">'.$summary.'</tr></tbody>':'' ). -->
				</table>
			</div>

			<?php




			

			/*echo tablePayment($this->paymentList, array(
				'fields' => $paymentList_fieldList,
				'title' => 'Payment',

				'actions' => '<div>'.
					'<button type="button" class="btn btn-small btn-blue"><i class="icon-check"></i><span class="mls">อนุมัติ</span></button>'.
					'<button type="button" class="btn btn-small btn-danger"><i class="icon-close"></i><span class="mls">ไม่อนุมัติ</span></button>'.
				'</div>',
			));*/


			?>
		</div>
		
	</div>

</div>