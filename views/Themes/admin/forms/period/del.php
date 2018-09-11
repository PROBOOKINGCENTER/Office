<?php



$arr['title'] = 'ยืนยันการลบข้อมูล';

$next = isset($_REQUEST['next']) ? '?next='.$_REQUEST['next']:'';

$arr['form'] = '<form class="js-submit-form" action="'.URL.'period/del'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'bus','value'=>$this->item['bus']['no']);
$arr['body'] = '<div class="pam uiBoxGray"><table>
	<tr><td>Code</td><td>'.$this->item['ser_code'].'</td></tr>
	<tr><td>Name</td><td>'.$this->item['ser_name'].'</td></tr>
	<tr><td>Date</td><td>'.$this->item['date_str'].'</td></tr>
	<tr><td>Bus</td><td>'.$this->item['bus']['no'].'</td></tr>
</table></div>';

$arr['body'] .= '<div class="mtm fcg">สิ่งที่ควรทราบ:</div>';
$arr['body'] .= '<ul class="uiList uiListStandard">'.
	'<li>คุณจะไม่สามารถกู้คืนข้อมูลได้อีก</li>'.
	// '<li>ระบบจะเก็บข้อมูลของผู้ใช้รายนี้ไว้ แต่จะถูกถอนสิทธิ์ในการเข้าใช้งานทั้งหมด</li>'.
	// '<li>สามารถกู้คืนผู้ใช้ได้ หากยังไม่</li>'.
'</ul>';

// 
	
$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">'.Translate::Val('Delete').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';


$arr['bg'] = 'red';

echo json_encode($arr);