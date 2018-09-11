<?php

$form = new Form();
$form = $form->create()
	// set From
	->url( 'http://admin.probookingcenter.com/admin/report/export_com_grosssale_agent.php' )
        ->method('post')
        ->style( 'horizontal' );

$form   ->field("closedate")
        ->label('ช่วงวันที่')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('');

$form   ->field("com_agency_id")
        ->label('บริษัทเอเจนซี่')
        ->autocomplete('off')
        // ->addClass('inputtext')
        ->select( $this->agencyList )
        // ->attr()
        ->placeholder('');

$form   ->field("agency_id")
        ->label('ชื่อเอเจนซี่')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->select( array() )
        ->placeholder('');

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value( '<i class="icon-print"></i><span class="mls">Export to Excel</span>' );
        
echo $form->html();