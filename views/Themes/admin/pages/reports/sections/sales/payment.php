<?php

$bankbookList = array();
foreach ($this->bankbookList as $key => $value) {
    $bankbookList[] = array(
        'id' => $value['id'],
        'name' => $value['name'] ." ({$value['bank_name']})",
    );
}

$form = new Form();
$form = $form->create()
	// set From
	// ->elem('div')
        ->url( 'http://admin.probookingcenter.com/admin/report/export_payment.php' )
        ->method('post')
    	->style( 'horizontal' );

$form   ->field("closedate")
        ->label('ช่วงวันที่')
        ->autocomplete('off')
        ->addClass('inputtext input-closedate')
        ->placeholder('');

$form   ->field("bankbook")
        ->label('สมุดธนาคาร')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $bankbookList, 'id', 'name', 'ทั้งหมด' )
        ->placeholder('');

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value( '<i class="icon-print"></i><span class="mls">Export to Excel</span>' );  

echo $form->html();