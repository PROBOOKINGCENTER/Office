<?php

$arr['title'] = 'ยืนยันการลบ';
$key = $this->type. '_url';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

// if( !empty($this->item['permit']['del']) ){

	$arr['form'] = '<form class="js-submit-form" action="'.URL.'tour/files/'.$this->type.'/'.$this->item['id'].'/remove'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "คุณแน่ใจแล้วใช่ไหม ที่จะลบไฟล์นี้อย่างถาวร?";

	$arr['body'] .= '<div class="mtm fcg">สิ่งที่ควรทราบ:</div>';
	$arr['body'] .= '<ul class="uiList uiListStandard">'.
		'<li>คุณจะไม่สามารถกู้คืนไฟล์นี้ได้อีก</li>'.
		( !empty($this->item[$key]) ? '<li><a target="_blank" href="'.$this->item[$key].'">ดาวน์โหลดไฟล์</a></li>': '').
	'</ul>';

	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
	$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';
	
/*}
else{

	$arr['body'] = "ไม่สามารถลบ <span class=\"fwb\">\"{$this->item['name']}\"</span> ได้";
	$arr['button'] = '<a href="#" class="btn btn-cancel" data-action="close"><span class="btn-text">'.Translate::Val('Close').'</span></a>';
}*/
$arr['bg'] = 'red';


echo json_encode($arr);
