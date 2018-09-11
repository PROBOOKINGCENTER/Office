<?php

$arr['title'] = 'ยืนยันการลบข้อมูล';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

if( !empty($this->item['permit']['del']) ){
	
	$arr['form'] = '<form class="js-submit-form" action="'.URL.'agency/del/company'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "คุณต้องการจะลบ <span class=\"fwb\">\"{$this->item['name']}\"</span> หรือไม่?";
	
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
	$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';
}
else{

	$arr['body'] = "ไม่สามารถลบ <span class=\"fwb\">\"{$this->item['name']}\"</span> ได้";	
	$arr['button'] = '<a href="#" class="btn btn-cancel" data-action="close"><span class="btn-text">'.Translate::Val('Close').'</span></a>';
}

$arr['bg'] = 'red';


echo json_encode($arr);