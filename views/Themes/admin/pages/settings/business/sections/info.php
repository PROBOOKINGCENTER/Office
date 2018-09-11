<div class="box user-box">
	<div class="box-title">
		<div class="title">Site Info</div>
		<p>Let us know a little more about yourself.</p>
	</div>
	<?php
		$form = new Form();
		$form = $form->create()
			// ->style('horizontal')
			->attr('data-plugins', 'changeForm')
			->addClass('form-insert box-content');


		$form   ->field("categories")
		        ->label( "What's your website for?" )
		        ->autocomplete('off')
		        ->addClass('inputtext')
		        ->placeholder('')
		        ->value( '' );
		        // ->note( "*Changing your username will also change your site's free web address (i.e. username.wixsite.com/sitename). If you connected your domain, there will be no change." );

		$form   ->field("sub_categories")
		        ->label('&nbsp;')
		        ->autocomplete('off')
		        ->addClass('inputtext');

		$form   ->field("site_goals")
		        ->label('Why did you create your website?')
		        ->autocomplete('off')
		        ->addClass('inputtext');

		$form   ->submit()
				->addClass('btn btn-blue btn-submit')
		        ->value('Save Changes');

		echo $form->html();
	?>
</div>