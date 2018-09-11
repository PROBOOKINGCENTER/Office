<?php

$form = new Form();
$form = $form->create()
	// set From
	// ->elem('div')

        ->url( 'http://admin.probookingcenter.com/admin/report/export_com_sale.php' )
        ->method('post')
        ->style( 'horizontal' );

$form   ->field("closedate")
        ->label('ช่วงวันที่')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('');

$form   ->field("user_id")
        ->label('ชื่อพนักงาน')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->salesList , 'id', 'name', 'ทั้งหมด' )
        ->placeholder('');

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value( '<i class="icon-print"></i><span class="mls">Export to Excel</span>' );

echo $form->html();