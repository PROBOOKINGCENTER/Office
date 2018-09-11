<?php


$accept = '';
if( $this->type=='word' ){
	$accept = ' accept=".doc, .docx, application/msword"';
}

if( $this->type=='pdf' ){
	$accept = ' accept="application/pdf"';
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->field("file")
        ->text( '<div class="mbs photoUploadPrompt fwb">เลือกไฟล์จากคอมพิวเตอร์ของคุณ (ขนาดสูงสุด 100 MB)</div><input name="'.$this->fileOpt['key'].'" type="file"'.$accept.'>' );


# set form
$arr['form'] = '<form data-form-action="submit" method="post" action="'.URL. 'tour/upload/'.$this->type.'/'.$this->action.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);


# body
$arr['body'] = $form->html();

# title
$arr['title']= 'อัพโหลดไฟล์เวอร์ชันใหม่';


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';


echo json_encode($arr);