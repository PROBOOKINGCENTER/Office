<?php

// ->style( 'horizontal' )
$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert clearfix')

    ->field("date")
        ->label(Translate::Val('วันที่เดินทาง').'*')
        ->autocomplete('off')
        ->addClass('inputtext')

        ->attr('data-plugin', 'caleran')
        ->attr('data-options', Fn::stringify( array(
            'continuous'=> true,
            'format' => 'DD/MM/YYYY',
            'startDate' => date('d/m/Y', strtotime($this->item['date_start'])),
            'endDate' => date('d/m/Y', strtotime($this->item['date_end'])),
        ) ))
->html();


# set form
$arr['form'] = '<form data-action="inlineSubmit" method="post" action="'.URL.'period/changedate/"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'bus','value'=>$this->item['bus']['no']);

# body
$arr['body'] = $form;

$arr['title']= "เปลี่ยนวันเดินทาง";


$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">ยกเลิก</span></a>';
$arr['close'] = true;

echo json_encode($arr);
