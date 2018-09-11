<?php

$arr['title'] = 'Confirm for Delete';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

if( !empty($this->item['permit']['del']) ){

	$arr['form'] = '<form class="js-submit-form" action="'.URL.'location/del/country'.$next.'"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "Are you sure you want to delete <span class=\"fwb\">\"{$this->item['name']}\"</span>?";

	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
	$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';
	$arr['bg'] = 'red';
}
else{

	$arr['body'] = "{$this->lang->translate('You can not delete')} <span class=\"fwb\">\"{$this->item['name']}\"</span>";
	$arr['button'] = '<a href="#" class="btn btn-cancel" data-action="close"><span class="btn-text">'.Translate::Val('Close').'</span></a>';
}


echo json_encode($arr);
