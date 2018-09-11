<?php

$arr['title'] = 'ระงับผู้ใช้';
$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';


$arr['form'] = '<form class="js-submit-form" action="'.URL.'users/disabled'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['body'] = "ชื่อผู้ใช้: <strong>{$this->item['name']}</strong>";
$arr['body'] .= '<div class="mtm fcg">สิ่งที่ควรทราบ:</div>';
$arr['body'] .= '<ul class="uiList uiListStandard">'.
	'<li>ระบบจะเก็บข้อมูลของผู้ใช้รายนี้ไว้ แต่จะถูกถอนสิทธิ์ในการเข้าใช้งานทั้งหมด</li>'.
	'<li>สามารถกู้คืนผู้ใช้ที่ถูกระงับได้หากยังไม่ลบถาวร</li>'.
'</ul>';

// เมื่อคุณลบผู้ใช้ ข้อมูลทั้งหมดจะถูกลบด้วย

$arr['button'] = ''.
	'<button type="button" class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></button>'.
	'<button type="submit" class="btn btn-blue btn-submit"><span class="btn-text">ระงับ</span></button>';


echo json_encode($arr);