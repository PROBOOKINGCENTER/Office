<?php require_once 'init.php';  ?>

<!-- container -->
<div id="tourslist" class="datatable__container on clearfix" data-plugin="toursList" data-options="<?=Fn::stringify( $this->listOpt )?>">

	<!-- topbar -->
	<div role="topbar" class="datatable__header" style="box-shadow: 0 1px 0 rgba(12,13,14,0.1), 0 1px 6px rgba(59,64,69,0.1);transition: box-shadow cubic-bezier(.165, .84, .44, 1) .25s;z-index: 10">
		<div class="inner">

			<!-- filter -->
		<div class="clearfix" role="filter">

			<div class="lfloat">
				<ul class="datatable-actions clearfix">
					<!-- <li><label class="label">&nbsp;</label><button type="button" style="vertical-align:top;padding: 0;" class="btn btn-icon js-refresh" title="refresh"><i class="icon-refresh"></i></button></li> -->
					<li>
						<label class="label"><?=Translate::Val('Search')?></label>
						<form class="form-search" action="#">
							<input class="inputtext search-input" type="text" id="search-query" placeholder="<?=Translate::Val('Search')?>..." name="q" autocomplete="off">
							<!-- <span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span> -->
						</form>
					</li>
					
					<li>
						<label for="country" class="label"><?=Translate::Val('Country')?></label>
						<select ref="selector" id="country" name="country" class="inputtext"><?php

							echo '<option value="">-- '.Translate::Val('All').' --</option>';

							foreach ($this->countryList as $key => $value) {
								echo '<option value="'.$value['id'].'" data-items="'.Fn::stringify($value['items']).'">'.$value['name'].'</option>';
							}
						?></select>
					</li>

					<li class="hidden_elem">
						<label for="city" class="label"><?=Translate::Val('City')?></label>
						<select ref="selector" class="inputtext" id="city" name="city" disabled></select>
					</li>

					<li class="hidden_elem">
						<label for="code" class="label"><?=Translate::Val('Code')?></label>
						<select ref="selector" class="inputtext" id="code" name="code" disabled></select>
					</li>

					<li class="daterange">
						<label class="label"><?=Translate::Val('Date range')?></label>
						<input type="text" name="" class="js-daterange daterange-input inputtext" style="width: 200px;">
						<button class="daterange-clear" type="button" data-action="cleardate"><i class="icon-remove"></i></button>
					</li>
				</ul>
			</div>

			<div class="rfloat">
				<ul class="datatable-actions clearfix">
					
					<li>
						<label for="sort" class="label"><?=Translate::Val('Sort')?></label>
						<select ref="selector" id="sort" name="sort" class="inputtext"><?php

							echo '<option value="series.ser_code ASC, period.per_date_start ASC">'.Translate::Val('Code').'</option>';
							echo '<option value="period.per_date_start ASC, series.ser_code ASC">วันเดินทาง</option>';

						?></select>
					</li>
				</ul>
			</div>
		</div>
		<!-- end: filter -->
		
		</div>
	</div>
	<!-- end: topbar -->

	<!-- content -->
	<div role="content" style="background-color:rgb(235, 235, 235);min-height: calc(100vh - 50px);"><div role="main" class="pal tour-item-wrap">

		<table class="table-tour" role="listsbox"></table>
		<div class="tour-item-lists" role="listsbox"></div>


		<div class="tour-item-footer">
			<div class="tour-item-footer__loader">
				<div class="loader-spin-wrap"><div class="loader-spin"></div></div>
				<div class="loader-text">Loading...</div>
			</div>
			<div class="tour-item-footer__empty"><div class="empty-icon"><i class="icon-flag"></i></div><div class="empty-title">No results found.</div></div>
			<div class="tour-item-footer__error">Don't connected, <a type="button" data-action="tryagain">Try again</a></div>
			<div class="tour-item-footer__more"><a type="button" class="btn btn-small" data-action="more">More</a></div>
		</div>

		<div class="u-scrolltop" data-action="scrolltop" style="position: fixed;"><i class="icon-arrow-up"></i></div>
	</div><!-- end: main --></div>
	<!-- end: content -->

</div>
<!-- end: container -->

<style type="text/css">
	.navigation-list>li.active>a{
		border-radius: 0 15px 15px 0;
	}
</style>
<script type="text/javascript">


	function nBook(a, b, c) {

		var data = $('#tourslist').data('toursList');
		console.log('data');
		data.updateBooking( a.id, a.bus.no, a.bus );
	}

	/*$('body').delegate('form.form-booking', 'submit', function(event) {
		event.preventDefault();

		var $form = $(this);
		var confirm = parseInt($form.find(':input[name=confirm]').val()) || 0;

		if( confirm==0 ){
			bookConfirm();
		}
		else{

			alert( confirm );
		}
	});*/

	function bookConfirm() {

		$.lightbox({
			'title': 'ยืนยันการจอง',
			'body': '',
			
			'button': '<button type="button" data-action="close" class="btn btn-link"><span class="btn-text">ยกเลิก</span></button><button type="button" class="btn btn-blue"><span class="btn-text">ยืนยัน</span></button>',
		});
	}
</script>