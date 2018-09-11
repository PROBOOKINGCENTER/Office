<?php

$arr['title'] = 'ยืนยันการลบข้อมูล';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

$arr['form'] = '<form class="js-submit-form" action="'.URL.'settings/extralists/del'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);


$arr['body'] = "คุณต้องการจะลบ <span class=\"fwb\">\"{$this->item['name']}\"</span> หรือไม่?";

$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';

$arr['bg'] = 'red';


echo json_encode($arr);