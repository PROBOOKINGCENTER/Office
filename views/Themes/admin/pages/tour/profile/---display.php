<?php


$priceVals = array();

$pricesOpt = array();
$pricesOpt[] = array('id'=>'', 'name'=>'Pax');


$prices = array();
$prices[] = array('name'=>'Adult', 'labelValue'=>'Price');
$prices[] = array('name'=>'Child', 'labelValue'=>'Price');
$prices[] = array('name'=>'Chlid No bed.', 'labelValue'=>'Price');
$prices[] = array('name'=>'Infant', 'labelValue'=>'Price');
$prices[] = array('name'=>'Joinland', 'labelValue'=>'Price');
$prices[] = array('name'=>'Single Charge', 'labelValue'=>'Price');
$priceVals[] = array('label'=>'ราคา', 'items'=>$prices, 'name'=>'prices');


$discount[] = array('name'=>'Com Agency', 'labelValue'=>'Price');
$discount[] = array('name'=>'Com Sales', 'labelValue'=>'Price');
$priceVals[] = array('label'=>'ส่วนลด', 'items'=> $discount, 'name'=>'discounts');


$roomTypes = array();
$roomTypes[] = array('name'=>'Twin', 'value'=>2, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Double', 'value'=>2, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Triple(Twin)', 'value'=>3, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Triple', 'value'=>3, 'labelValue'=>'Pax');
$roomTypes[] = array('name'=>'Single', 'value'=>1, 'labelValue'=>'Pax');

$priceVals[] = array('label'=>'Room of Type', 'items'=> $roomTypes, 'name'=>'roomoftypes');


/*$roomTypeStr = '<tr>'.
	'<th class="td-name">ประเภทห้อง</th>'.
	'<th class="td-pax">รับได้</th>'.
	'<th class="td-status">สถานะ</th>'.
'</tr>';
foreach ($roomTypes as $key => $value) {
	$roomTypeStr .= '<tr>'.
		'<td class="td-name"><div class="input-field"><input type="text" name="roomoftype[value][]" class="dirty" value="'.$value['name'].'"><label>Name</label></div></td>'.
		'<td class="td-pax"><div class="input-field"><input data-plugin="number_format" maxlength="3" type="text" name="roomoftype[value][]" class="dirty" value="'.$value['pax'].'"><label>Pax</label></div></td>'.
		'<td class="td-status"><label class="switch"><input type="checkbox" name="roomoftype[display][]" value="1"'.( !empty($value['display']) ? ' checked':'' ).'><span class="slider round"></span></label></td>'.
	'</tr>';
	
}*/

$form = new Form();
$formInfo = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert clearfix')


    ->field("date")
        ->label(Translate::Val('วันที่เดินทาง').'*')
        ->autocomplete('off')
        ->addClass('inputtext')

        ->attr('data-plugin', 'caleran')
        ->attr('data-options', Fn::stringify( array(
            'continuous'=> true
        ) ))
        ->value('')
        // ->placeholder('')

    ->field("note")
    	->label(Translate::Val('รายละเอียด'))
        ->autocomplete('off')
        ->addClass('inputtext')

       	->type('textarea')
        ->value('')

    /*->hr('<div class="ui-hr-text white"><span>'.Translate::Val('Bus').'</span></div>')
    ->field("bus")->text(
        '<table class="form-table-peried-price"><tr><th class="name">รายการ</th><th class="price">ที่นั้ง</th><th class="action"></th></tr><thead></thead><tbody role="buslist"></tbody></table>'.
        '<div class="mts tar"><a data-bus-action="add">เพิ่ม bus</a></div>'
    )*/

    ->hr('<div class="ui-hr-text white"><span>ไฟล์เตรียมตัวเดินทาง</span></div>')
    ->field("source")
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

        '</tbody></table></div>' )
    
->html();


$form = new Form();
$formBus = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert clearfix')

    ->field("seat")
        ->label(Translate::Val('จำนวนที่นั่ง').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value('')
        ->attr('data-plugin', 'number_format')

    ->field("pricevalues")
        ->text( '<table class="table-pricevalues-form" plugin="pricevalues"></table>' )
   

->html();
?>

<form action="<?=URL?>tour/period/save" method="post" class="pal">

	<div class="mal" style="padding: 2px;border: 1px solid #ccc; border-radius: 2px" data-plugin="periodForm" data-options="<?=Fn::stringify( array(
		'buslist' => array(),
		'busform' => $formBus,
		'busformOpt' => array('items'=>$priceVals),
	) )?>">
		
		<table class="table-period-form">
			<tr>
				<td style="width:220px;padding: 20px;background-color: #eee;vertical-align: top;"><?=$formInfo?></td>

				<td style="vertical-align: top;position: relative;">

					<div style="width: 680px;">
						<div style="padding:20px; /*max-height: 520px;overflow-y: auto;*/padding-right: 140px">
							<ul class="list-listbus" role="buslist"></ul>
						</div>
					</div>

					<div style="position: absolute;top: 20px;left: 560px;">
						<ul class="nav-overview" role="buslistnav"></ul>
						<div class="list-bus-plus"><a data-buslist-action="add">+ เพิ่มบัส</a></div>
					</div>
				</td>

			</tr>
			
		</table>

	</div>


	<button type="submit" class="btn">Ok</button>
</form>