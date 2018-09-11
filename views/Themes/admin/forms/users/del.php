<?php

$arr['title'] = 'ยืนยันการลบข้อมูล';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

$arr['form'] = '<form class="js-submit-form" action="'.URL.'users/del'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['body'] = "ชื่อผู้ใช้: <strong>{$this->item['name']}</strong>";
$arr['body'] .= '<div class="mtm fcg">สิ่งที่ควรทราบ:</div>';
$arr['body'] .= '<ul class="uiList uiListStandard">'.
	'<li>ผู้ใช้ที่ถูกย้ายไปอยู่สถานะถังขยะ</li>'.
	'<li>ระบบจะเก็บข้อมูลของผู้ใช้รายนี้ไว้ แต่จะถูกถอนสิทธิ์ในการเข้าใช้งานทั้งหมด</li>'.
	'<li>สามารถกู้คืนผู้ใช้ได้ หากยังไม่ลบถาวร</li>'.
'</ul>';

// 
	
$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';


$arr['bg'] = 'red';


echo json_encode($arr);