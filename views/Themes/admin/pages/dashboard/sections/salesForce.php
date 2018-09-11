<?php


$datatable = $this->fn->q('listbox')->table_tour_col();

								

?>
<section class="pvm phl">

	<div class="page-title pvm">
		<h1 class="title"><i class="icon-flag mrs"></i>โปรดันขาย</h1>
	</div>

	
			
	<div class="tb" data-plugin="datatable3" data-options="<?=Fn::stringify( $this->tourListOpt )?>" style="">

		<nav class="tb-filter" role="filter">
			<ul>
				<li class="filter-item">
					<label for="search-query" class="label">ค้นหา</label>
					<form class="form-search" data-action="formsearch">
					<input class="inputtext search-input" type="text" id="search-query" placeholder="" name="q" autocomplete="off">
					<span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span>
				</form></li>

				<li class="filter-item">
					<label for="airline" class="label">สายการบิน</label>
					<select  id="airline" name="airline" class="inputtext" data-action="change">
						<option value="">-- ทั้งหมด -- </option>
						<?php foreach ($this->airlineList as $key => $value) {
							echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
						}
						?>
					</select>
				</li>

				<li class="filter-item">
					<label for="country" class="label">ประเทศ</label>
					<select  id="country" name="country" class="inputtext" data-action="change">
						<option value="">-- ทั้งหมด -- </option>
						<?php foreach ($this->countryList as $key => $value) {
							echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
						}
						?>
					</select>
				</li>

				<li class="filter-item hidden_elem">
					<label for="city" class="label"><?=Translate::Val('City')?></label>
					<select data-action="change" class="inputtext" id="city" name="city" disabled></select>
				</li>
				<!-- <li class="filter-item hidden_elem">
					<label for="series" class="label"><?=Translate::Val('Series')?></label>
					<select data-action="change" class="inputtext" id="series" name="series" disabled></select>
				</li> -->
				
				<!-- <li class="filter-item daterange">
					<label for="closedate" class="label">Period Date</label>
					<input data-action="caleran" id="closedate" type="text" name="closedate" class="inputtext">
					<button class="daterange-clear" type="button" data-action="cleardate"><i class="icon-remove"></i></button>
				</li> -->
			</ul>
		</nav>

		<div style="">
			<table class="tb-table">
				<thead>
				<?php foreach ($datatable as $key => $value) {
					echo '<th class="'.$value['cls'].'">'.$value['label'].'</th>';
				}	
				?></thead>
				<tbody role="listsbox"></tbody>
				<tbody>
					<tr>
						<td class="td-alert" colspan="<?=count($datatable)?>">
							<div class="tb-alert">
								<div class="tb-loading"><div class="loader-spin-wrap"><div class="loader-spin"></div></div><p>Loading...</p></div>
								<div class="tb-empty">
									<div class="empty-icon"><i class="icon-flag"></i></div>
						        	<div class="empty-title">No Results Found.</div>
								</div>
								<div class="tb-error">Don't connected, <a type="button" data-action="tryagain">Try again</a></div>
								<!-- <div class="tb-more"><a type="button" class="btn btn-large" data-action="more">More</a></div> -->
							</div>
						</td>
					</tr>
				</tbody>
			</table>


			<footer class="tb-footer clearfix">
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

						<li class="hidden_elem" role="countVal"><label class="label">ลำดับที่:</label><span class="start">1</span>-<span class="end">6</span> จาก <span class="total">6</span></li>
						<li><div class="group-btn"><button class="btn" data-page-action="prev"><i class="icon-chevron-left"></i></button><button class="btn" data-page-action="next"><i class="icon-chevron-right"></i></button></div></li>
					</ul>
				</nav>
			</footer>

		</div>

	</div>
</section>

<script type="text/javascript">
	
	$(function () {

		var $filterCountry = $('[role=filter] #country');
		var $filterCity = $('[role=filter] #city');
		$filterCountry.change(function() {
			
			var val = $(this).val();
			$filterCity.parent().toggleClass('hidden_elem', val=='');
			if( val != '' ){

				Location.cityList({country: val}).done(function (resp) {

					$filterCity.empty();
					$filterCity.append( $('<option>', {value: '', text: '-' }) );

					if( val=='' || resp.length==0 ){
						$filterCity.prop('disabled', true).addClass('disabled').parent().addClass('hidden_elem');
					}
					else{
						$filterCity.prop('disabled', false).removeClass('disabled').parent().removeClass('hidden_elem');
						$.each(resp, function(key, value) {
							$filterCity.append( $('<option>', {value: value.id, text: value.name }) );
						});
					}

				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			}
		});

	});
</script>