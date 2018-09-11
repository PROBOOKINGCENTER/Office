<?php

$form = new Form();
$form = $form->create()
	// set From
	->url( 'http://admin.probookingcenter.com/admin/report/export_excel_invoice_sale.php' )
    ->method('post')
	->style( 'horizontal' );

$form   ->field("closedate")
        ->label('ช่วงวันที่')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('');

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value( '<i class="icon-print"></i><span class="mls">Export to Excel</span>' );

echo $form->html();