<?php


$contact = array();
$contact[] = array('id'=>'agen_email');
// $contact[] = array('id'=>'agen_tel');


$li = '';
foreach ($contact as $key => $value) {

    if( !empty( $this->item[$value['id']]) ){

        $li .= '<li><label class="checkbox"><input type="checkbox" name="'.$value['id'].'" value="1" checked><span>'. $this->item[$value['id']].'</span></label></li>';
    }

}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert')

    ->field("remark")
    	->type('textarea')
        ->label('ข้อความพิเศษ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( '' )

->html();


$arr['title'] = 'Send Invoice';


$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';
// if( !empty($item['permit']['del']) ){
	
	$arr['form'] = '<form data-plugins="inlineSubmit" method="post" action="'.URL.'booking/cancel"></form>';

	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);


	$arr['body'] = 

        '<div class="">'.$this->item['agen_name']. (!empty($this->item['agen_position'])? "({$this->item['agen_position']})": '').'</div>'. 
        '<ul>'.$li.'</ul>'.

    $form;
	
	$arr['button'] = '<button type="submit" class="btn btn-blue btn-submit disabled" disabled><span class="btn-text">Send Invoice</span></button>';
	$arr['bottom_msg'] = '<button type="button" class="btn" data-action="close"><span class="btn-text">ปิด</span></button>';
	
/*}
else{

	$arr['body'] = "Can't delete contant name ";
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">Close</span></a>';
}
*/

$arr['close'] = true;

header('Content-type: application/json');
echo json_encode($arr);
