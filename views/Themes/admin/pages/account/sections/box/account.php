<div class="box user-box">
	<div class="box-title"><div class="title">Account</div></div>
	<?php
		$form = new Form();
		$form = $form->create()
			->style('horizontal')
			->attr('data-plugins', 'changeForm')
			->addClass('box-content');


		$form   ->field("user_name")
		        ->label( 'Username' )
		        ->autocomplete('off')
		        ->addClass('inputtext')
		        ->placeholder('')
		        ->value( '' );
		        // ->note( "*Changing your username will also change your site's free web address (i.e. username.wixsite.com/sitename). If you connected your domain, there will be no change." );

		$form   ->field("user_login")
		        ->label('Email')
		        ->autocomplete('off')
		        ->addClass('inputtext');

		$form   ->field("user_lang")
		        ->label( Translate::Val('Language') )
		        ->addClass('inputtext')
		        ->select( array(0=>
		              array('id'=>'th','name'=>'ภาษาไทย - Thai')
		            , array('id'=>'en','name'=>'English') //อังกฤษ
		        ), 'id', 'name', '' )
		        ->value( !empty($this->me['lang']) ? $this->me['lang']:'en' );


		$a = array();
		$a[] = array('id'=>'light', 'name'=>'Light');
		$a[] = array('id'=>'dark', 'name'=>'Dark');
		$a[] = array('id'=>'blue', 'name'=>'Blue');

		if( empty($this->me['mode']) ){
		    $this->me['mode'] = 'light';
		}

		$mode = '';
		if( empty($this->me['mode']) ) $this->me['mode'] = 'word';
		foreach ($a as $key => $value) {
		    
		    $check = $this->me['mode']==$value['id'] ? ' checked="1"':'';
		    $mode .= '<li class="mrm" style="display: inline-block;"><label class="radio"><input type="radio" name="user_mode" value="'.$value['id'].'"'.$check.' />'.$value['name'].'</label></li>';
		}

		$form   ->field("user_mode")
		        ->label( Translate::Val('Mode') )
		        ->text( '<ul>'.$mode.'</ul>' );


		$form   ->submit()
				->addClass('btn btn-blue btn-submit')
		        ->value('Save Changes');

		echo $form->html();
	?>
</div>