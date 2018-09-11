<div>
	<div class="tb" data-plugin="datatable3" data-options="<?=Fn::stringify( $this->listOpt )?>">
		<div class="tb-header" role="header">

			<div class="tb-header-inner">
				<div class="tb-title clearfix">
					<h1 class="title lfloat"><i class="icon-<?=$this->listOpt['icon']?> mrs"></i><?=$this->listOpt['title']?></h1>
					<?php 
					if( !empty($this->listOpt['controls']) ){

						echo '<nav class="rfloat"><ul class="nav tb-controls">';
						foreach ($this->listOpt['controls'] as $key => $value) {
							echo '<li class="control-item">'.$value.'</li>';
						}
						echo '</ul></nav>';
					}
					?>
				</div>

				<?php if( !empty($this->listOpt['filter']) ) { ?>

					<nav class="tb-filter" role="filter">
						<ul class="clearfix">
							<?php foreach ($this->listOpt['filter'] as $key => $value) { 

								$type = isset($value['type']) ? $value['type']: '';
								if( $type =='search' ){
									echo '<li class="filter-item">
										<label for="search-query" class="label">ค้นหา</label>
										<form class="form-search" data-action="formsearch">
										<input class="inputtext search-input" type="text" id="search-query" placeholder="" name="q" autocomplete="off">
										<span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span>
									</form></li>';
								}

								if( $type =='change' ){

									
									echo '<li class="filter-item">'.
										'<label for="'.$value['key'].'" class="label">'.$value['label'].'</label>'.
										'<select  id="'.$value['key'].'" name="'.$value['key'].'" class="inputtext" data-action="change">'.
											'<option value="">-- ทั้งหมด -- </option>';



											foreach ($value['items'] as $i => $item) {

												$active = '';
												if( !empty($this->active[$value['key']]) ){
													$active = $item['id']==$this->active[$value['key']] ? ' selected':'';
												}

												echo '<option'.$active.' value="'.$item['id'].'">'.$item['name'].'</option>';
											}
											
										echo '</select>'.
									'</li>';
								}

							?>
							<?php } ?>
						</ul>
					</nav>

				<?php } ?>
			</div>

		</div>

		<div class="tb-container clearfix">
			
			<div class="entity-list">
				<table class="tb-table">
					<thead role="tabletitle">
					<?php foreach ($this->listOpt['datatable'] as $key => $value) {
						echo '<th class="'.$value['cls'].'" data-col="'.$key.'">'.$value['label'].'</th>';
					}	
					?></thead>
					<tbody role="listsbox"></tbody>
				</table>
			</div>
		</div>

		<div class="tb-alert">
			<div class="tb-loading"><div class="loader-spin-wrap"><div class="loader-spin"></div></div><p>Loading...</p></div>
			<div class="tb-empty">
				<div class="empty-icon"><i class="icon-<?=$this->listOpt['icon']?>"></i></div>
	        	<div class="empty-title">No Results Found.</div>
			</div>
			<div class="tb-error">Don't connected, <a type="button" data-action="tryagain">Try again</a></div>
			<!-- <div class="tb-more"><a type="button" class="btn btn-large" data-action="more">More</a></div> -->
		</div>

		<aside class="list-sidebar">sidebar</aside>

		<footer class="tb-footer">
			<div class="tb-footer-container clearfix" role="footer">
				<nav class="rfloat nav">
					<ul class="tb-pagination">
						
						<li><label>แสดงแถว:</label><select name="limit" class="inputtext" data-page-action="limit">
							<option value="10">10</option>
							<option value="25" selected>25</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="250">250</option>
							<option value="500">500</option>
						</select><label></label></li>

						<li><label>หน้า:</label><input type="text" name="page" class="inputtext" data-page-action="page" style="width: 60px" data-plugin="input__num"></li>

						<li class="hidden_elem" role="countVal"><label class="label">ลำดับ:</label> <span class="start">1</span>-<span class="end">6</span> จาก <span class="total">6</span></li>
						<li><div class="group-btn"><button class="btn" data-page-action="prev"><i class="icon-chevron-left"></i></button><button class="btn" data-page-action="next"><i class="icon-chevron-right"></i></button></div></li>
					</ul>
				</nav>
			</div>
		</footer>
	</div>
</div>
<script type="text/javascript">
	function refreshList() {
		var table = $('.tb').data('datatable');
		
		if( table ){
			table.options.pager = 1;
			table.options.seq = 0;
			table.refresh( 1 );
		}
	}

	$(function () {

		$('body').delegate('#auto_password', 'change', function () {

			var is = $(this).prop('checked'),
				$fieldset = $('.form-emp-add #password_fieldset');

			$fieldset.toggle( !is );

			if( !is ){
				$fieldset.find('.inputtext').focus();
			}
		});

		var password;
		$('body').delegate('.form-emp-add', 'submit', function (e) {
			e.preventDefault();

			var $form = $(this);
			Event.inlineSubmit( $form ).done(function( resp ){

				Event.processForm($form, resp);

				if( resp.error ) return false;

				password = resp.password;

				$.lightbox({
					'title': 'สร้างผู้ใช้ใหม่สำเร็จแล้ว',
					'body': '<div class="form-vertical">'+

						'<i class="icon-check mrs"></i>'+ resp.data.name + ' เป็นผู้ใช้แล้ว <br><br>' + 
						'<fieldset class="control-group lfloat mrm" style="width:50%">'+
							'<label class="control-label">ชื่อผู้ใช้</label>' +
							'<div class="controls">' + resp.data.login + '</div>'+ 
						'</fieldset>'+

						'<fieldset class="control-group">' + 
							'<label class="control-label">รหัสผ่าน</label>' +
							'<div class="controls"><span id="show-password">******</span></div>'+ 
							'<div class="controls fsm"><a class="show-password-toggle">แสดงรหัสผ่าน</a> | <a data-password-action="copy" class="fsm">Copy password</a></div>'+ 
						'</fieldset>' +

					'</div>',
					'bottom_msg' : ''+
						'<span style="position:absolute;opacity:0;pointer-events: none;"><input data-password-action="input" value="'+ password +'"></span>' + 
						// '<a type="button" ><span class="btn-text">Send to email</span></a>' + 
						'',
					'button': '<button type="button" data-action="close" class="btn js-close btn-blue"><span class="btn-text">Done</span></button>',
					'form': '<div class="form-conf">',
				});

			});
		});

		$('body').delegate('.form-conf .js-close', 'click', function () {
			refreshList();
		});

		$('body').delegate('.show-password-toggle', 'click', function () {

			var $this = $(this),
				box = $('#show-password');

			if( box.hasClass('show') ){
				$this.text('แสดงรหัสผ่าน');
				box.removeClass('show').text('******');
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
					'button': '<button type="button" data-action="close" class="btn btn-blue"><span class="btn-text">Done</span></button>',
				});
			});
		});
		
	});

</script>