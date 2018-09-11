<div class="box user-box">
	<div class="box-title">
		<div class="title">Basic Info</div>
		<p>Let people know how they can contact your business.</p>
	</div>
	<?php
		$form = new Form();
		$form = $form->create()
			// ->style('horizontal')
			->attr('data-plugins', 'changeForm')
			->addClass('form-insert box-content');

		$form   ->field("business_logo")
		        ->label('Add Your Logo')
		        ->text('<div class="anchor anchor96 clearfix image-wrapper"><div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div><div class="content"><div class="spacer"></div><div class="massages"><div class="fsm fcg">For best results, upload a high resolution image</div></div></div></div>');

		$form   ->field("business_name")
		        ->label( "Business Name" )
		        ->autocomplete('off')
		        ->addClass('inputtext')
		        ->placeholder('Enter your business name')
		        ->value( '' );
		        // ->note( "*Changing your username will also change your site's free web address (i.e. username.wixsite.com/sitename). If you connected your domain, there will be no change." );


		$form   ->field("business_email")
		        ->label('Business Email Address')
		        ->autocomplete('off')
		        ->placeholder('Enter your email')
		        ->addClass('inputtext');

		$form   ->field("business_phone")
		        ->label('Business Phone')
		        ->autocomplete('off')
		        ->placeholder('Enter your phone number')
		        ->addClass('inputtext');




		$form   ->submit()
				->addClass('btn btn-blue btn-submit')
		        ->value('Save Changes');

		echo $form->html();
	?>
</div>