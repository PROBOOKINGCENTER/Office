<?php

$li = '';
foreach ($this->periodList as $key => $value) {
	$li .= '<li><label class="checkbox"><input type="checkbox" value="'.$value['id'].'" name="pe[]"><span>'. $value['date_str'] .'</span></label></li>';
}

$arr['title'] = 'คัดลอกข้อมูลแพคเกจทัวร์';
$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

$arr['form'] = '<form class="js-submit-form" action="'.URL.'tour/clone'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['body'] = "<strong>{$this->item['name']}</strong>";
/*$arr['body'] .= '<div class="mtm fcg">เลือกวันเดินทาง:</div>';
$arr['body'] .= '<div style="max-height: 500px;overflow-y: auto;margin:0 -20px -20px;padding: 20px;"><ul class="uiList">'.$li.'</ul></div>';*/

// 
	
$arr['button'] = '<button type="submit" class="btn btn-blue btn-submit"><span class="btn-text">'.Translate::Val('Clone').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';

// $arr['width'] = 890;

echo json_encode($arr);