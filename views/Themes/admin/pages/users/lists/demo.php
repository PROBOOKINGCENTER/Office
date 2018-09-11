<?php


$nav = array();

$items = array();
$items[] = array('key'=>'sales', 'id'=>'payment', 'name'=>'Admin', 'link'=>URL.'reports/sales/payment', 'title'=>'รายงาน รับโอนเงินจากเอเจ้นท์');
$items[] = array('key'=>'sales', 'id'=>'payment', 'name'=>'Manager', 'link'=>URL.'reports/sales/payment', 'title'=>'รายงาน รับโอนเงินจากเอเจ้นท์');
$items[] = array('key'=>'sales', 'id'=>'commission', 'name'=>'Op', 'link'=>URL.'reports/sales/commission', 'title'=>'รายงาน ค่าคอมมิชชั่นพนักงาน');
$items[] = array('key'=>'sales', 'id'=>'commission', 'name'=>'Op Subpor', 'link'=>URL.'reports/sales/commission', 'title'=>'รายงาน ค่าคอมมิชชั่นพนักงาน');
$items[] = array('key'=>'sales', 'id'=>'commission', 'name'=>'Actionting', 'link'=>URL.'reports/sales/commission', 'title'=>'รายงาน ค่าคอมมิชชั่นพนักงาน');
$nav[] = array('id'=>'sales', 'name'=>'Admin Role', 'items'=>$items);
// 

$items = array();
$items[] = array('key'=>'summary', 'id'=>'country', 'name'=>'Theme 1', 'link'=>URL.'reports/summary/country', 'title'=>'รายงาน สรุปยอดขาย เรียงตามประเทศ');
$items[] = array('key'=>'summary', 'id'=>'agency', 'name'=>'Theme 2', 'link'=>URL.'reports/summary/agency', 'title'=>'รายงาน สรุปยอดขาย เรียงตามเอเจนซี่');
$items[] = array('key'=>'summary', 'id'=>'sales', 'name'=>'Theme 3', 'link'=>URL.'reports/summary/sales', 'title'=>'รายงาน สรุปยอดขาย เรียงตามพนักงาน');
$nav[] = array('id'=>'', 'name'=>'Sales', 'items'=>$items);


$items = array();
$nav[] = array('id'=>'', 'name'=>'Setting');

$this->section = '';

?><style type="text/css">
	
	.two-columns{
		position: relative;
    	height: 100%;
	}
	.two-columns .secondary-content{
		position: fixed;
	    left: 50px;
	    bottom: 0;
	    top: 50px;
	    /* background-color: #fff; */
	    z-index: 1;
	    overflow-x: hidden;
	    overflow-y: auto;
	    /* box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16); */
	    /*transition: left .3s;*/
	    width: 220px;
	    background-color: #f6f6f6
	}
	.two-columns .primary-content {
	    margin-left: 220px;
	    /* background-color: #fff; */
	    min-height: calc(100vh - 50px);
	    position: relative;
	    z-index: 1
	}

	body.is-pushed-left .two-columns .secondary-content{
		left: 260px;
	}

	.navigation-main{background-color: #ebebeb}
	.navigation-list>li.active>a{
		background: #f6f6f6;
	}

	.secondary-content .sidebar{
		padding-bottom: 36px;padding-top: 20px;
	}
	.sidebar-widget-header {
	    position: relative;
	    cursor: pointer;
	}
	.sidebar-widget-list {
	    display: none;
	}
	
	.sidebar-widget-item {
	    color: #888;
	    padding-left: 25px;
	    display: block;
	    padding: 2px 20px 2px 25px;
	}
	

	.sidebar-widget-item:hover, .active.sidebar-widget-item {
	    color: #286efa;
	    text-decoration: none;
	}

	.sidebar-widget-header .sidebar-widget-item {
	    color: #222;
	    display: block;
	    padding: 4px 20px;
	    text-decoration: none;
	    font-weight: bold;
	    font-size: 13px;
	}
	.sidebar-widget-header .arrow {
	    transition: .14s transform;
	    display: inline-block;
	    transform: rotate(-90deg);
	    font-size: 9px;
	}

	.sidebar-widget.is-open .sidebar-widget-list {
	    display: block;
	}

	.sidebar-widget.is-open .sidebar-widget-header .arrow {
    	transform: rotate(0deg);
    }
}


</style>

<div style="" class="two-columns clearfix">

	<div class="secondary-content narrow">
		
		<aside class="sidebar">

			<?php foreach ($nav as $key => $value) { 

				$is_item = !empty($value['items']);
			?>
			<div class="sidebar-widget">

				<?php if( !empty($value['name']) ){ ?>
				<div class="sidebar-widget-header">
					<a class="sidebar-widget-item" data-nav="toggle"><span><?=$value['name']?></span>

						<?php if( $is_item ){ ?>
						<i class="arrow icon-chevron-down mls"></i>
						<?php } ?>
					</a>
				</div>
				<?php } ?>

				<?php if( $is_item ){ ?>
				<ul class="sidebar-widget-list">
				<?php foreach ($value['items'] as $i => $item) {
					
					$active = "{$item['key']}_{$item['id']}"== $this->section ? ' active':'';
					$link = !empty($item['link']) ? ' href="'.$item['link'].'"':'';

					echo '<li><a'.$link.' class="sidebar-widget-item'.$active.'" data-key="'.$item['key'].'" data-section-action="'.$item['id'].'">'.$item['name'].'</a></li>';
				} ?>
				</ul>
				<?php } ?>

			</div>
			<?php } ?>
		</aside>

	</div>
	<div class="primary-content wide">
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
													echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
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
</div>