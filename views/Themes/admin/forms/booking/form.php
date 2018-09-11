<?php

# set form data-action="inlineSubmit" 
$arr['form'] = '<form data-plugins="inlineSubmit" class="form-booking" method="post" action="'.URL. 'booking/save"></form>';

# body
$arr['body'] = '<div style="margin:-20px;">'.$this->fn->q('booking')->form( $this->settingForm ).'</div>';

# title
$arr['title'] = !empty($this->item['ser_name']) ? $this->item['ser_name']: 'Booking';

$arr['hiddenInput'][] = array('name'=>'per_id','value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'ser_id','value'=>$this->item['ser_id']);
$arr['hiddenInput'][] = array('name'=>'bus_no','value'=>$this->item['bus']['no']);
$arr['hiddenInput'][] = array('name'=>'confirm','value'=> false);


# fotter: button
$arr['button'] = ''.
	'<span class="mrm"><label class="checkbox"><input type="checkbox" name="book_is_guarantee" value="1"><span class="mls">การันตี</span></label></span>'.
	'<button type="submit" class="btn btn-primary btn-submit btn-jumbo"><span class="btn-text">'.Translate::val('Booking').'</span></button>';
$arr['bottom_msg'] = '<button type="button" class="btn btn-jumbo" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></button>';

// $arr['bottom_msg'] = '<button type="button" type="button" class="btn btn-warning"><i class="icon-print mrs"></i>Print Invoice</button>';
$arr['width'] = 960;
$arr['close'] = true;
// $arr['overflowY'] = true;

echo json_encode($arr);