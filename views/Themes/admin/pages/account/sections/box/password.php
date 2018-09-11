<div class="box user-box">
	<div class="box-title">
		<div class="title">Password</div>
		<p>Change your password or recover your current one.</p>
	</div>
	<?php
		$form = new Form();
		$form = $form->create()
			->style('horizontal')
			->attr('data-plugins', 'changeForm')
			->addClass('box-content');


		$form   ->field("current_password")
		        ->label( 'Current Password' )
		        ->autocomplete('off')
		        ->addClass('inputtext')
		        ->placeholder('');

		$form   ->field("user_password")
		        ->label('New Password')
		        ->autocomplete('off')
		        ->addClass('inputtext');

		$form   ->field("user_password_confirmation")
		        ->label('Verify password')
		        ->autocomplete('off')
		        ->addClass('inputtext');

		$form   ->submit()
				->addClass('btn btn-blue btn-submit')
		        ->value('Save Changes');

		echo $form->html();
	?>
</div>