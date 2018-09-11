<?php


$this->statusList = array();
$this->statusList[] = array('id'=>'20', 'name'=>'มัดจำ(Deposite)บางส่วน');
$this->statusList[] = array('id'=>'25', 'name'=>'มัดจำ(Deposite)เต็มจำนวน');
$this->statusList[] = array('id'=>'30', 'name'=>'เต็มจำนวน(Full payment)บางส่วน');
$this->statusList[] = array('id'=>'35', 'name'=>'เต็มจำนวน(Full payment)เต็มจำนวน');

$form = new Form();
$formLeft = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert')->style( 'horizontal' )

    ->field("bankbook_id")
        ->label('ธนาคาร*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('data-plugin', 'choose__bookbank')
        ->attr('data-options', Fn::stringify( array('items'=>$this->bookbank) ) )
        ->select( $this->bookbank , 'id', 'fullname')

    ->field("branch")
        ->label('สาขา')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('disabled', 1)

    ->field("name")
        ->label('ชื่อบัญชี')
        ->autocomplete('off')
        ->addClass('inputtext disabled')
        ->placeholder('')
        ->attr('disabled', 1)

    ->field("code")
        ->label( 'เลขที่บัญชี' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( '' )
        ->attr('disabled', 1)

    ->field("pay_date")
        ->label('วันที่ชำระเงิน')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->type('date')
        ->value( date("Y-m-d") )

    ->field("pay_time")
        ->label('เวลา')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->type('time')
        // ->attr('data-plugin', 'inputmask')
        ->value( date("H:i") )

    ->field("pay_received")
        ->label('จำนวนเงินที่ได้รับ')
        ->autocomplete('off')
        ->attr('data-plugin', 'input__num')
        ->addClass('inputtext')

    ->field("pay_url_file")
        ->label('ไฟล์อ้างอิง')
        ->autocomplete('off')
        ->attr('accept', 'image/jpg, image/jpeg, image/png')
        // ->addClass('inputtext')
        ->type('file')

    ->field("remark")
        ->label('หมายเหตุ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'autosize')
        ->type('textarea')

->html();


$form = new Form();
$formRigth = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-emp')

   ->field("status")
        ->type( 'radio' )
        ->label( 'สถานะ Booking*')
        ->autocomplete('off')
        // ->addClass('inputtext')
        ->items( $this->statusList )


->html();



$arr['title'] = 'แจ้งการชำระเงิน';


$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';
// if( !empty($item['permit']['del']) ){
	
	$arr['form'] = '<form class="form-payment" data-plugin="inlineSubmit" method="post" action="'.URL.'booking/payment"></form>';

	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);


	# body
    $arr['body'] = '<div style="margin:-20px;"><table style="width:100%;"><tbody><tr>'.
        '<td class="pal" style="vertical-align: top;">'.$formLeft.'</td>'.
        '<td style="width:320px;background-color: #eee;vertical-align: top;" class="pal">'.$formRigth.'</td>'.
    '</tr></tbody></table></div>';

	
	$arr['button'] = '<button type="submit" class="btn btn-blue btn-submit disabled" disabled><span class="btn-text">บันทึก</span></button>';
	$arr['bottom_msg'] = '<button type="button" class="btn" data-action="close"><span class="btn-text">ปิด</span></button>';
	
/*}
else{

	$arr['body'] = "Can't delete contant name ";
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">Close</span></a>';
}
*/

$arr['close'] = true;
$arr['width'] = 780;

header('Content-type: application/json');
echo json_encode($arr);
