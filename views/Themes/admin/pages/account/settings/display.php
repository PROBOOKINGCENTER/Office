<div class="maestro pal">

	<!-- <header class="maestro-header"><h1>Personal account</h1></header> -->

	<div class="account-page" style="max-width: 750px;">
		
		<div class="mc-tabbed-header hidden_elem">
			<ul role="tablist" class="mc-tabbed-header-list">
				<li class="mc-tabbed-header-item active"><a>General</a></li>

				<li class="mc-tabbed-header-item"><a>Security</a></li>

				<li class="mc-tabbed-header-item"><a>Notifications</a></li>
				<!-- <li class="mc-tabbed-header-item"><a>Connected apps</a></li> -->
			</ul>
		</div>

		<div class="account-page-tab account-page-general">
			
			<!-- Basic -->
			<div class="account-page-block">
				<div class="general-page-header">Basic</div>

				<!-- <div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Photo</span>
						<span class="settings-listValue fcg"></span>
						<span class="settings-listActions">
							<button type="button"><i class="icon-pencil mrs"></i><span>Edit</span></button>
							<button type="button"><i class="icon-trash mrs"></i><span>Delete</span></button>
						</span>
					</div>
				</div> -->

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Name</span>
						<span class="settings-listValue fcg" data-ref="fullname"><?=$this->me['name']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/basic"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Address</span>
						<span class="settings-listValue fcg" data-ref="address"><?=$this->me['address']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/basic"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>
				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Phone</span>
						<span class="settings-listValue fcg" data-ref="tel"><?=$this->me['tel']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/basic"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>
				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Line ID</span>
						<span class="settings-listValue fcg" data-ref="line_id"><?=$this->me['line_id']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/basic"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>
			</div>
			<!-- end: Basic -->

			<!-- account -->
			<div class="account-page-block">
				<div class="general-page-header">Preferences</div>

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Username</span>
						<span class="settings-listValue fcg" data-ref="username"><?=$this->me['username']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/account"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Email</span>
						<span class="settings-listValue fcg" data-ref="email"><?=$this->me['email']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/account"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Language</span>
						<span class="settings-listValue fcg" data-ref="lang_str"><?=$this->me['lang_str']?></span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/account"><i class="icon-pencil mrs"></i><span>Edit</span></button>
						</span>
					</div>
				</div>

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listLabel fcg fwb fsm">Theme</span>
						<span class="settings-listValue"><?php

						$form = new Form();
						$form = $form->create()->elem('div');

						$theme = array();
						$theme[] = array('id'=>'light', 'name'=>'Default');
						$theme[] = array('id'=>'dark', 'name'=>'Dark');
						$theme[] = array('id'=>'blue', 'name'=>'Blue');
						$theme[] = array('id'=>'green', 'name'=>'Super Sparkle Happy');
						// $theme[] = array('id'=>'sky', 'name'=>'Sky');
						/*
						
						$theme[] = array('id'=>'darkblue', 'name'=>'Dark Blue');*/

						$form   ->field("user_mode")->type('radio')->items( $theme )->checked( $this->me['mode'] );

						echo $form->html();


						?></span>
						<span class="settings-listActions"></span>
					</div>

				</div>	
			</div>
			<!-- end: account -->


			<!-- Security -->
			<div class="account-page-block">
				<div class="general-page-header">Security</div>

				<div class="settings-listItem">
					<div class="settings-listLink">
						<span class="settings-listValue">
							<strong>Password</strong>
							<div class="fcg fsm">Set a unique password to protect your personal account.</div>
						</span>
						<span class="settings-listActions">
							<button type="button" data-plugins="lightbox" href="<?=URL?>me/edit/password"><i class="icon-pencil mrs"></i><span>Change password</span></button>
						</span>
					</div>

				</div>
			</div>
			<!-- end: Security -->
		</div>
	</div>

</div>

<script src="<?=JS?>plugins/lightbox.js"></script>
<script type="text/javascript">
$(function () {

	$('[name=user_mode]').change(function() {
		
		var val = $(this).val();

		$.post( app.getUri('account/update'), { 'name': 'user_mode', value: val }, function (res) {

			$('body')
					.removeClass('light')
					.removeClass('dark')
					.removeClass('green')
					.removeClass('blue')
					.removeClass('sky')
					.removeClass('darkblue')
					.addClass( val );

			Event.dock({
				text: 'Theme Updated.',
				auto: true,
				key: 'theme'
			});

		}, 'json' );
	});

	$('body').delegate('.form-reset-password input[name=password_auto]', 'change', function () {

		var $form = $('.form-reset-password'),
			is = $(this).prop('checked');


		if( is ){
			$form.find('#password_new, #password_confirm').val('123456').prop('disabled', true).addClass('disabled');
		}
		else{
			$form.find('#password_new, #password_confirm').val('').prop('disabled', false).removeClass('disabled');
		}
	});

	var password = '';
	$('body').delegate('form.form-reset-password', 'submit', function (e) {
		e.preventDefault();

		var $form = $(this);
		Event.inlineSubmit( $form ).done(function( resp ){

			Event.processForm($form, resp);

			if( resp.error ){
				return false;
			}

			password = resp.password;
			$.lightbox({
				'title': 'Reset password successful',
				'body': '<div class="form-vertical">'+

					'<fieldset class="control-group">' + 
						'<label class="control-label">Password</label>' +
						'<div class="controls">'+
							
							'<span id="show-password">●●●●●●●●</span>'+
							'<a class="show-password-toggle mlm">แสดงรหัสผ่าน</a> | <a data-password-action="copy" class="fsm">Click to copy password</a>'+
						'</div>'+ 

					'</fieldset>' +

				'</div>',
				'bottom_msg' : ''+
					'<span style="position:absolute;opacity:0;pointer-events: none;"><input data-password-action="input" value="'+ password +'"></span>' + 
					// '<a type="button" ><span class="btn-text">Send to email</span></a>' + 
					'',
				'button': '<button type="button" data-action="close" class="btn"><span class="btn-text">Done</span></button>',
			});
		});
	});


	$('body').delegate('.show-password-toggle', 'click', function () {

		var $this = $(this),
			box = $('#show-password');

		if( box.hasClass('show') ){
			$this.text('แสดงรหัสผ่าน');
			box.removeClass('show').text('●●●●●●●●');
		}
		else{

			$this.text('ซ่อนรหัสผ่าน');
			box.addClass('show').text( password );
		}

	});

	
	$('body').delegate('[data-password-action=copy]', 'click', function () {

		var $target = $('[data-password-action=input]').val( password );
		var target = $target[0];

		// select the content
		var currentFocus = document.activeElement;
		target.focus();
		target.setSelectionRange(0, target.value.length);

		// copy the selection
		var succeed;
		try {
	    	succeed = document.execCommand("copy");
	    	Event.showMsg( {text: 'Password copide to clipboard.', auto: true, load: true} );
	    } catch(e) {
	        succeed = false;
	    }

	    // restore original focus
	    /*if (currentFocus && typeof currentFocus.focus === "function") {
	        currentFocus.focus();
	    }*/


	});
	
	

	
});

</script>

