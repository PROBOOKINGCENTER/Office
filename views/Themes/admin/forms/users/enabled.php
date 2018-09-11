<?php

$arr['title'] = 'อนุญาตให้ผู้ใช้ใช้งานได้';
$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';


$arr['form'] = '<form class="js-submit-form" action="'.URL.'users/enabled'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['body'] = "ชื่อผู้ใช้: <strong>{$this->item['name']}</strong>";
$arr['body'] .= '<div class="mtm fcg">แน่ใจไหมว่าต้องการเปิดใช้งานผู้ใช้นี้</div>';


$arr['button'] = ''.
	'<button type="button" class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></button>'.
	'<button type="submit" class="btn btn-blue btn-submit"><span class="btn-text">เปิดใช้งาน</span></button>';


echo json_encode($arr);