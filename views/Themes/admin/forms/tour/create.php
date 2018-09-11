<?php


$optForm = array();
if( !empty($this->item['city_id']) ){
    $optForm['city_id'] = $this->item['city_id'];
}


$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert form-series');
/**/

$form   ->field("ser_code")
        ->label( 'Code' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['code'])? $this->item['code']: '' );

$form   ->field("ser_name")
        ->label( Translate::Val('Series Name') )
        ->addClass('inputtext')
        ->autocomplete('off')
        ->placeholder('Add name')
        ->value( !empty($this->item['name'])? $this->item['name']: '' );

$form   ->field("country_id")
        ->type( 'select' )
        ->label( 'Country' )
        ->addClass('inputtext')
        ->select( $this->countryList )
        ->value( !empty($this->item['country_id'])? $this->item['country_id']: '' );

$form   ->field("city_id")
        ->type( 'select' )
        ->label( 'City' )
        ->addClass('inputtext')
        ->select( array() )
        ->value( !empty($this->item['city_id'])? $this->item['city_id']: '' );

$form   ->field("remark")
        ->type( 'textarea' )
        ->label( Translate::Val('ไฮไลท์') )
        ->addClass('inputtext')
        ->autocomplete('off')
        ->placeholder('')
        ->attr('data-plugins', 'autosize')
        ->value( !empty($this->item['remark'])? $this->item['remark']: '' );

$form   ->hr('<div class="ui-hr-text"><span>Travel</span></div>');

$form   ->field("air_id")
        ->type( 'select' )
        ->label( 'Airline' )
        ->addClass('inputtext')
        ->select( $this->airlineList )
        ->value( !empty($this->item['air_id'])? $this->item['air_id']: '' );

$form   ->field("flight")
        ->label( 'Flight' )
        ->text( $this->fn->q('form')->table_flight( !empty($this->item) ? $this->item: array() ) );
         
$form   ->hr('<div class="ui-hr-text white"><span>Document</span></div>');
$form   ->field("source")
        ->type( 'select' )
        // ->label( 'Documents' )
        ->addClass('inputtext')
        ->text( '<div class="table-document-wrap">'.

            '<div class="phm pvs uiBoxYellow">Select a file on your computer (Max size 100 MB)</div>'.

            '<table class="table-document"><tbody>'.

                '<tr>'.
                    '<td class="td-label">'.
                        '<div class="i-s2 word"></div>'.
                    // Word
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_word_fieldset" class="control-group">'.
                            '<input type="file" name="file_word" accept=".doc, .docx, application/msword">'.
                            '<div class="notification"></div>'.
                        '</fieldset>'.
                    '</td>'.
                    '<td class="td-action"></td>'.
                '</tr>'.

                '<tr>'.
                    '<td class="td-label">'.
                        // 'PDF'.
                        '<div class="i-s2 pdf"></div>'.
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_pdf_fieldset" class="control-group">'.
                            '<input type="file" name="file_pdf" accept="application/pdf">'.
                            '<div class="notification"></div>'.
                        '</fieldset>'.
                        
                    '</td>'.
                    '<td class="td-action"></td>'.
                '</tr>'.

        '</tbody></table></div>' );

$formLeft = $form->html();


$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

$form   ->field("file_image")
        ->label('Banner')
        ->text( '<div class="uiCoverImageContainer has-empty preview-image" data-width="260" data-height="260" style="width:260px;height:260px">'.
    
    '<div class="uiCoverImage_empty"><i class="icon-image"></i></div>'.
    '<div class="uiCoverImage_image" role="preview"></div>'.
    // '<div class="uiCoverImage_loader"><div class="progress-bar mini"><span class="blue"></span></div></div>'.
    '<div class="uiCoverImage_action">'.
        '<a data-action="change" onclick="PreviewImage.trigger(this)"><i class="icon-camera"></i></a>'.
        '<a data-action="remove" onclick="PreviewImage.remove(this)"><i class="icon-remove"></i></a>'.
    '</div>'.
    
    '<div class="uiCoverImage_overlay"><input role="button" type="file" accept="image/jpeg,image/png" name="file_image" onchange="PreviewImage.change(this)"></div>'.
    '<div class="uiCoverImage_loaderspin"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></div>'.

'</div>' );


/*$form   ->field("contact_type")
        ->label( 'Suggest' )
        ->addClass('inputtext')
        // ->autocomplete('off')
        ->text( $this->fn->q('form')->checkboxList( $this->suggestList ) );
*/
$form   ->field("ser_price")
        ->type('number')
        ->autocomplete('off')
        ->label( Translate::Val('ราคาเริ่มต้น') )
        ->addClass('inputtext')
        ->attr('data-plugin', 'input__num')
        ->placeholder('')
        ->value( !empty($this->item['price']) ? round($this->item['price']): '' );

$form   ->field("ser_deposit")
        ->type('number')
        ->autocomplete('off')
        ->label( Translate::Val('เงินมัดจำ/ต่อนั่ง') )
        ->addClass('inputtext')
        ->attr('data-plugin', 'input__num')
        ->placeholder('')
        ->value( !empty($this->item['deposit'])? round($this->item['deposit']):'' );


$formRigth =  $form->html();




# set form
$arr['form'] = '<form class="form-series-wrap" data-action="inlineSubmit" method="post" action="'.URL. 'tour/save" data-plugin="series__form" enctype="multipart/form-data" data-options="'.Fn::stringify( $optForm ).'"></form>';

# body
$arr['body'] = '<div style="margin: -20px;"><table><tbody><tr>'.
    '<td class="pal" style="vertical-align:top;width:550px;">'.$formLeft.'</td>'.
    '<td class="pal" style="vertical-align:top;background: #f2f2f2;">'.$formRigth.'</td>'.
'</tr></tbody></table></div>';

# title
if( $this->action=='clone' ){
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
    $arr['hiddenInput'][] = array('name'=>'action','value'=>$this->action);
    $arr['title']= 'คัดลอกข้อมูลแพคเกจทัวร์';
}
else{
    $arr['title']= 'สร้างแพคเกจทัวร์';
}


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" data-action="close"><span class="btn-text">'.Translate::val('Cancel').'</span></a>';
$arr['width'] = 850;

echo json_encode($arr);