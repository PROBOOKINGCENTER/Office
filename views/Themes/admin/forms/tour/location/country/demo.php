<?php

# title
$title = 'Country';

if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}


$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert form-series');

$form   ->field("file_image")
        ->text( '<div class="uiCoverImageContainer has-empty preview-image" data-width="260" data-height="260" style="width:260px;height:260px">'.
    
    '<div class="uiCoverImage_empty"><i class="icon-image"></i></div>'.
    '<div class="uiCoverImage_image" role="preview"></div>'.
    '<div class="uiCoverImage_action">'.
        '<a data-action="change" onclick="PreviewImage.trigger(this)"><i class="icon-camera"></i></a>'.
        '<a data-action="remove" onclick="PreviewImage.remove(this)"><i class="icon-remove"></i></a>'.
    '</div>'.
    
    '<div class="uiCoverImage_overlay"><input role="button" type="file" accept="image/jpeg,image/png" name="file_image" onchange="PreviewImage.change(this)"></div>'.
    '<div class="uiCoverImage_loaderspin"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></div>'.

'</div>' );

$formLeft = $form->html();



$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("country_code")
        ->label( 'Name*' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugin', 'selectflag')
        ->attr('data-options', Fn::stringify( array('data'=>$this->flagList) ) )
        ->value( !empty($this->item['code'])? $this->item['code']:'' );

$form   ->field("country_name")
        ->label( '&nbsp;' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->attr('autofocus', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("country_description")
        ->type( 'textarea' )
        ->label( 'Description' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
         ->attr('data-plugin', 'autosize')
        ->value( !empty($this->item['description'])? $this->item['description']:'' );

$formRigth =  $form->html();


# body
$arr['body'] = '<div style="margin: -20px;"><table><tbody><tr>'.
    '<td style="vertical-align:top;width:260px;padding: 2px;">'.$formLeft.'</td>'.
    '<td class="pal" style="vertical-align:top;">'.$formRigth.'</td>'.
'</tr></tbody></table></div>';

# set form
$arr['form'] = '<form class="js-submit-form form-country" method="post" action="'.URL. 'tour/location/country/save/"></form>';

# body
// $arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">Cancel</span></a>';
$arr['width'] = 850;

echo json_encode($arr);
