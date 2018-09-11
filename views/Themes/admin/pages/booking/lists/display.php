<div style="" class="">
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
				<nav class="tb-filter" role="filter">
					<ul class="clearfix">
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

					<ul class="clearfix" style="margin-top: 2px;"><?php
						if( !empty($this->statusList) ){

						echo '<li>';

						foreach ($this->statusList as $key => $value) {

							if( isset($value['disabled']) ) continue;

							$name = isset($value['desc']) ? $value['desc']: $value['name']; 

							echo '<label class="checkbox mrs"><input type="checkbox" name="status" value="'.$value['id'].'" data-action="multichecked"><span class="fwb">'.$name.'</span></label>';
						}

						echo '</li>';

						}
						
					?></ul>
				</nav>
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