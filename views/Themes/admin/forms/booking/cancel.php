<?php

$selectCancel = array();
$selectCancel[] = array('id'=>1, 'name'=>'30 วัน ก่อนเดินทาง คืน 100%'); 
$selectCancel[] = array('id'=>2, 'name'=>'10 วัน ก่อนเดินทาง คืน เงินมัดจำ'); 
$selectCancel[] = array('id'=>3, 'name'=>'ไม่คืน'); 

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert')->style( 'horizontal' )

    ->field("status_cancel")
        ->label( 'เงื่อนไข')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $selectCancel, 'id', 'name', false )
        ->value( 3 )

    ->field("refund_deposit")
        ->label( 'จำนวนเงินมัดจำ' )
        ->autocomplete('off')
        ->addClass('inputtext disabled')
        ->attr('data-plugin', 'input__num')
        ->attr('disabled', 1)
        ->value( $this->item['master_deposit'] )

    ->field("refund_full_payment")
        ->label('Full Payment')
        ->autocomplete('off')
        ->addClass('inputtext disabled')
        ->attr('data-plugin', 'input__num')
        ->attr('disabled', 1)
        ->value( $this->item['master_full_payment'] )

    ->field("amount_receive")
        ->label('Amount Receive')
        ->autocomplete('off')
        ->addClass('inputtext disabled')
        ->attr('data-plugin', 'input__num')
        ->attr('disabled', 1)
        ->value( $this->item['receipt'] )

	->field("book_cancel")
        ->label('จำนวนเงินที่คืน')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'input__num')
        ->value( 0 )

    ->field("remark_cancel")
    	->type('textarea')
        ->label('หมายเหตุ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( '' )

->html();


$arr['title'] = 'ยกเลิกการจอง';


$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';
// if( !empty($item['permit']['del']) ){
	
	$arr['form'] = '<form data-plugins="inlineSubmit" method="post" action="'.URL.'booking/cancel"></form>';

	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);


	$arr['body'] = $form;
	
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit disabled" disabled><span class="btn-text">บันทึก</span></button>';
	$arr['bottom_msg'] = '<button type="button" class="btn" data-action="close"><span class="btn-text">ปิด</span></button>';
	
/*}
else{

	$arr['body'] = "Can't delete contant name ";
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">Close</span></a>';
}
*/

$arr['bg'] = 'red';
$arr['close'] = true;

header('Content-type: application/json');
echo json_encode($arr);
