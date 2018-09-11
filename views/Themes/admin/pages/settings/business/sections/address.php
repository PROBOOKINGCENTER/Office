<div class="box user-box">
	<div class="box-title">
		<div class="title">Business Address</div>
		<p>If your business has a physical address, fill in the following:</p>
	</div>
	<?php
		$form = new Form();
		$form = $form->create()
			// ->style('horizontal')
			->attr('data-plugins', 'changeForm')
			->addClass('form-insert box-content');


		$form   ->field("business_location")
		        ->label( "Street Address" )
		        ->autocomplete('off')
		        ->addClass('inputtext')
		        ->placeholder('Enter your street address')
		        ->value( '' );

		$form   ->field("business_location_city")
		        ->label('City')
		        ->autocomplete('off')
		        ->placeholder('Enter your city')
		        ->addClass('inputtext');

		$form   ->field("business_location_country")
		        ->label('Country')
		        ->autocomplete('off')
		        ->placeholder('Enter your country')
		        ->addClass('inputtext');

		$form   ->field("business_location_state")
		        ->label('State')
		        ->autocomplete('off')
		        ->placeholder('Enter your state')
		        ->addClass('inputtext');

		$form   ->field("business_location_zip")
		        ->label('Zip Code')
		        ->autocomplete('off')
		        ->addClass('inputtext');


		$form   ->submit()
				->addClass('btn btn-blue btn-submit')
		        ->value('Save Changes');

		echo $form->html();
	?>
</div>