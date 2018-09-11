<?php


$optForm = array();
if( !empty($this->item['city_id']) ){
    $optForm['city_id'] = $this->item['city_id'];
}

$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert form-series');
/*->style('horizontal')*/

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

$formLeft = $form->html();


$form = new Form();
$form = $form->create()->elem('div')->addClass('form-insert');

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

    '<div class="clearfix pvm phl" style="background-color: #f2f2f2;">'.
        '<div class="rfloat">'.
            '<button type="submit" class="btn btn-blue">Save Changes</button>'.
        '</div>'.
    '</div>'.

'</form>';