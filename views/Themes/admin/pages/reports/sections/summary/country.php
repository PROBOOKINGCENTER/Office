<?php

$form = new Form();
$form = $form->create()
	// set From
	->url( 'http://admin.probookingcenter.com/admin/report/export_com_grosssale.php' )
        ->method('post')
	->style( 'horizontal' );

$form   ->field("closedate")
        ->label('ช่วงวันที่')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('');

$form   ->field("country_id")
        ->label('ประเทศ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->countryList, 'id', 'name', 'ทั้งหมด' )
        ->placeholder('');

$form   ->field("ser_id")
        ->label('รหัส ซี่รี่ทัวร์')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( array(), 'id', 'name', 'ทั้งหมด' )
        ->placeholder('');

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value( '<i class="icon-print"></i><span class="mls">Export to Excel</span>' );
        
echo $form->html();