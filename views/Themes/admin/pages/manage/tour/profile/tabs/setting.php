<?php


$optForm = array();
if( !empty($this->item['city_id']) ){
    $optForm['city_id'] = $this->item['city_id'];
}

$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert form-series');
/*->style('horizontal')*/

$form   ->field("status")
        ->autocomplete('off')
        ->label( 'Status' )
        ->text('<label class="switch"><input type="checkbox" name="status" value="1"'.($this->item['status']==1? ' checked': '').'><span class="slider round"></span></label>');

$form   ->field("ser_code")
        ->label( 'Code' )
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( $this->item['code'] );

$form   ->field("ser_name")
        ->label( Translate::Val('Series Name') )
        ->addClass('inputtext')
        ->autocomplete('off')
        ->placeholder('Add name')
        ->value( $this->item['name'] );

$form   ->field("country_id")
        ->type( 'select' )
        ->label( 'Country' )
        ->addClass('inputtext')
        ->select( $this->countryList )
        ->value( $this->item['country_id'] );

$form   ->field("city_id")
        ->type( 'select' )
        ->label( 'City' )
        ->addClass('inputtext')
        ->select( array() );

$form   ->field("remark")
        ->type( 'textarea' )
        ->label( Translate::Val('ไฮไลท์') )
        ->addClass('inputtext')
        ->autocomplete('off')
        ->placeholder('')
        ->attr('data-plugins', 'autosize')
        ->value( $this->item['remark'] );

$form   ->hr('<div class="ui-hr-text"><span>Travel</span></div>');

$form   ->field("air_id")
        ->type( 'select' )
        ->label( 'Airline' )
        ->addClass('inputtext')
        ->select( $this->airlineList )
        ->value( $this->item['air_id'] );

$form   ->field("flight")
        ->label( 'Flight' )
        ->text( $this->fn->q('form')->table_flight($this->item) );


$dropdownList_word = array();
$dropdownList_word[] = array(
    'text' => 'อัพโหลดเวอร์ชันใหม่',
    'href' => URL.'tour/files/word/'.$this->item['id'].'/revision/',
    'attr' => array('data-plugin'=>'lightbox'),
);

$dropdownList_word[] = array(
    'text' => 'ลบ',
    'href' => URL.'tour/files/word/'.$this->item['id'].'/del/',
    'attr' => array('data-plugin'=>'lightbox'),
);


          
$dropdownList_PDF = array();
// $dropdownList_PDF[] = array(
//     'text' => 'ดาวน์โหลด',
//     'href' =>  DOWNLOAD.'document/pdf/'.$this->item['id'],
//     'attr' => array('target'=>'_blank'),
// );

$dropdownList_PDF[] = array(
    'text' => 'อัพโหลดเวอร์ชันใหม่',
    'href' => URL.'tour/files/pdf/'.$this->item['id'].'/revision/',
    'attr' => array('data-plugin'=>'lightbox'),
);

$dropdownList_PDF[] = array(
    'text' => 'ลบ',
    'href' => URL.'tour/files/pdf/'.$this->item['id'].'/del/',
    'attr' => array('data-plugin'=>'lightbox'),
);



$form   ->hr('<div class="ui-hr-text white"><span>Document</span></div>');
$form   ->field("source")
        ->type( 'select' )
        // ->label( 'Documents' )
        ->addClass('inputtext')
        ->text( '<div class="table-document-wrap">'.

            // '<div class="phm pvs uiBoxYellow">Select a file on your computer (Max size 100 MB)</div>'.
            '<table class="table-document"><tbody>'.

                '<tr>'.
                    '<td class="td-label">'.
                        '<div class="i-s2 word"></div>'.
                    // Word
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_word_fieldset" class="control-group">'.
                            ( !empty($this->item['word_url'])
                                ? '<a type="button" class="" target="_blank" href="'.$this->item['word_url'].'"><span>ดาวน์โหลด</span></a>'
                                : '<a href="'.URL.'tour/files/word/'.$this->item['id'].'/revision/" data-plugin="lightbox">อัพโหลด</a>'
                            ).
                            // '<a type="button" class="" target="_blank" href="'.DOWNLOAD.'tour/word/'.$this->item['id'].'"><span>ดาวน์โหลด</span></a>'.
                            '<div class="notification"></div>'.
                        '</fieldset>'.
                    '</td>'.
                    '<td class="td-action"><button data-plugin="dropdown2" class="btn" style="width:30px" data-options="'.Fn::stringify( array( 'select' => $dropdownList_word, 'axisX'=> 'right', 'container'=> '.table-document-wrap', ) ).'"><i class="icon-ellipsis-v"></i></button></td>'.
                '</tr>'.

                '<tr>'.
                    '<td class="td-label">'.
                        // 'PDF'.
                        '<div class="i-s2 pdf"></div>'.
                    '</td>'.
                    '<td class="td-text">'.
                        '<fieldset id="file_pdf_fieldset" class="control-group">'.
                            ( !empty($this->item['pdf_url'])
                                ? '<a type="button" class="" target="_blank" href="'.$this->item['pdf_url'].'"><span>ดาวน์โหลด</span></a>'
                                : '<a href="'.URL.'tour/files/pdf/'.$this->item['id'].'/revision/" data-plugin="lightbox">อัพโหลด</a>'
                            ).

                            '<div class="notification"></div>'.
                        '</fieldset>'.
                        
                    '</td>'.
                    '<td class="td-action"><button data-plugin="dropdown2" class="btn" style="width:30px" data-options="'.Fn::stringify( array( 'select' => $dropdownList_PDF, 'axisX'=> 'right', 'container'=> '.table-document-wrap', ) ).'"><i class="icon-ellipsis-v"></i></button></td>'.
                '</tr>'.
                '</tr>'.

        '</tbody></table></div>' );


$formLeft = $form->html();


$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');


$imageCoverOpt = array(
    'name' => 'file_image',
    'width' => 260,
    'heigth' => 260
);

if( !empty($this->item['image_url']) ){
    $imageCoverOpt['src'] = $this->item['image_url'];
}

$form   ->field("file_image")
        ->label('Banner')
        ->text( $this->fn->q('form')->imageCover( $imageCoverOpt ) );

$form   ->field("ser_price")
        ->type('number')
        ->autocomplete('off')
        ->label( Translate::Val('ราคาเริ่มต้น') )
        ->addClass('inputtext')
        ->attr('data-plugin', 'input__num')
        ->placeholder('')
        ->value( $this->item['price']==0? '': round($this->item['price']) );

$form   ->field("ser_deposit")
        ->type('number')
        ->autocomplete('off')
        ->label( Translate::Val('เงินมัดจำ/ต่อนั่ง') )
        ->addClass('inputtext')
        ->attr('data-plugin', 'input__num')
        ->placeholder('')
        ->value( $this->item['deposit']==0? '': round($this->item['deposit']) );



$li = '';
foreach ($this->categoryList as $key => $value) {

    // $checked = $this->showonweb
    $li .= '<li><label class="checkbox" style="display: inline-block;"><input type="checkbox" name="category[]" value="'.$value['id'].'"'.(!empty($value['checked'])? ' checked':'').' /><span>'.$value['name'].'</span></label></li>';
}

$form   ->field("category")
        ->autocomplete('off')
        ->label( 'การแสดงผลบนหน้าเว็บไซต์' )
        ->placeholder('')
        ->text( '<ul>'. $li .'</ul>' );


$formRigth =  $form->html();


/*border: 1px solid #dddfe2;border-radius: 4px;*/
# body
echo '

<form style="max-width:850px;" class="form-series-wrap" data-action="inlineSubmit" method="post" action="'.URL. 'tour/save" data-plugin="series__form" data-options="'.Fn::stringify( $optForm ).'" >'.

    '<input type="hidden" name="id" value="'.$this->item['id'].'" autocomplete="off">'.
    '<input type="hidden" name="action" value="update" autocomplete="off">'.

    '<table><tbody><tr>'.
        '<td class="pal" style="vertical-align:top;">'.$formLeft.'</td>'.
        '<td class="pal" style="vertical-align:top;width:300px">'.$formRigth.'</td>'.
        // '<td></td>'.
    '</tr></tbody></table>'.

    '<div class="clearfix pvm phl" style="background-color: #f2f2f2;" data-plugin="hello">'.
        '<div class="rfloat">'.
            '<button type="submit" class="btn btn-blue">Update</button>'.
        '</div>'.
    '</div>'.

'</form>';