<?php

$nav = array();

$items = array();
$items[] = array('key'=>'sales', 'id'=>'payment', 'name'=>'รับโอนเงินจากเอเจ้นท์', 'link'=>URL.'reports/sales/payment', 'title'=>'รายงาน รับโอนเงินจากเอเจ้นท์');
$items[] = array('key'=>'sales', 'id'=>'commission', 'name'=>'ค่าคอมมิชชั่นพนักงาน', 'link'=>URL.'reports/sales/commission', 'title'=>'รายงาน ค่าคอมมิชชั่นพนักงาน');
$nav[] = array('id'=>'sales', 'name'=>'Sales', 'items'=>$items);
// 

$items = array();
$items[] = array('key'=>'summary', 'id'=>'country', 'name'=>'เรียงตามประเทศ', 'link'=>URL.'reports/summary/country', 'title'=>'รายงาน สรุปยอดขาย เรียงตามประเทศ');
$items[] = array('key'=>'summary', 'id'=>'agency', 'name'=>'เรียงตามเอเจนซี่', 'link'=>URL.'reports/summary/agency', 'title'=>'รายงาน สรุปยอดขาย เรียงตามเอเจนซี่');
$items[] = array('key'=>'summary', 'id'=>'sales', 'name'=>'เรียงตามพนักงาน', 'link'=>URL.'reports/summary/sales', 'title'=>'รายงาน สรุปยอดขาย เรียงตามพนักงาน');
$items[] = array('key'=>'summary', 'id'=>"invoice", 'name'=>'ใบ Invoice', 'link'=>URL.'reports/invoice', 'title'=>'รายงาน สรุปยอด Invoice');
// $items[] = array('id'=>'', 'name'=>'สรุป Invoice');
$nav[] = array('id'=>'', 'name'=>'สรุปยอดขาย', 'items'=>$items);


$items = array();
$items[] = array('id'=>'', 'name'=>'Activity By User');
// $nav[] = array('id'=>'', 'name'=>'Activity', 'items'=>$items);


?><style type="text/css">
	.l-reports {
    position: relative;
    height: 100%;
}

.l-reportsNav {
    position: fixed;
    left: 50px;
    bottom: 0;
    top: 50px;
    /*background-color: #fff;*/
    z-index: 1;
    overflow-x: hidden;
    overflow-y: auto;
    /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16);*/
    transition: left .3s;
    width: 220px;
}

.is-pushed-left .l-reportsNav{
	left: 260px
}
.l-main {
    margin-left: 220px;
    /*background-color: #fff;*/
    min-height: calc(100vh - 50px);
    /*border-top-left-radius: 20px;*/
    position: relative;
    z-index: 10;
    
    overflow-x: auto;

    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    background-color: #ffff;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 1px 6px rgba(0,0,0,0.12);
    padding: 2px;
    -webkit-transition: opacity .25s cubic-bezier(0.4,0.0,0.2,1),visibility 0s linear 0s;
    transition: opacity .25s cubic-bezier(0.4,0.0,0.2,1),visibility 0s linear 0s;
    position: relative;
}

.ReportNav {
	/*padding: 0 12px;*/
    /*margin: 20px 0;*/
    /*padding: 0 20px;*/
}
.ReportNav_header {
    position: relative;
    cursor: pointer;
}
.ReportNav_header .ReportNav_item{
	color: #222;
	display: block;
    padding: 4px 20px;
    text-decoration: none;
    font-weight: bold;

    font-size: 13px;
}
.ReportNav_item {
    color: #888;
    padding-left: 25px;
    display: block;

    padding: 2px 20px 2px 25px;
}
.ReportNav_header .arrow{
	transition: .14s transform;
    display: inline-block;
    transform: rotate(-90deg);
    font-size: 9px;
}
.ReportNav_item:hover, .active.ReportNav_item{
	color: #286efa;
	text-decoration: none;
}

.l-bg{
	position: fixed;
	left: 270px;
	top: 50px;
	height: calc(100vh - 50px);
	background-color:#fff;
	right: 0;
	z-index: 0;
	border-top-left-radius: 10px;
	transition: left .23s;

	/*transition: left .23s cubic-bezier(.165,.84,.44,1);*/
	/*box-shadow: 0 0 0 1px rgba(0,0,0,.05), 0 4px 24px 2px rgba(0,0,0,.1);*/
}
.is-pushed-left .l-bg{
	left: 480px;
}

.ReportNav_list{
	display: none;
}

.ReportNav.is-open .ReportNav_list{
	display: block;
}
.ReportNav.is-open .ReportNav_header .arrow{
	transform: rotate(0deg);
}


.l-toolbar{
	padding: 20px;
	border-bottom: 1px solid #ebebeb;
}
.l-content{
	padding: 20px;
}

.u-section{
	display: none;
}


.input-closedate, #closedate_fieldset .inputtext {
	min-width: 200px
}
.selectize-control{
	display: inline-block;
	width: 255px;
}

</style>


<div class="l-bg"></div>
<div class="l-reports">
	
	<div class="ReportsPage">
		<div class="l-reportsNav">

			<div style="padding-bottom: 36px;padding-top: 20px;">

			<?php foreach ($nav as $key => $value) { ?>
			<div class="ReportNav is-open">

				<?php if( !empty($value['name']) ){ ?>
				<div class="ReportNav_header">
					<a class="ReportNav_item" data-nav="toggle"><span><?=$value['name']?></span><i class="arrow icon-chevron-down mls"></i></a>
				</div>
				<?php } ?>

				<ul class="ReportNav_list">
				<?php foreach ($value['items'] as $i => $item) {
					
					$active = "{$item['key']}_{$item['id']}"== $this->section ? ' active':'';
					$link = !empty($item['link']) ? ' href="'.$item['link'].'"':'';

					echo '<li><a'.$link.' class="ReportNav_item'.$active.'" data-key="'.$item['key'].'" data-section-action="'.$item['id'].'">'.$item['name'].'</a></li>';
				} ?>
				</ul>
			</div>
			<?php } ?>
			</div>
		</div>
		<div class="l-main">

			<?php

			foreach ($nav as $key => $value) {

				foreach ($value['items'] as $i => $item) {

					$path = "sections/{$item['key']}/{$item['id']}.php";

					if( file_exists( dirname(__FILE__).'/'.$path) ){


						$title = isset($item['title']) ? $item['title']:'';

						echo '<section class="u-section" id="'.$item['key'].'_'.$item['id'].'">';
							echo '<div class="l-toolbar"><h2>'.$title.'</h2></div>';
							echo '<div class="l-content">';
								require_once $path;
							echo '</div>';
						echo '</section>';
					}			

				}

			}

			?>
		</div>
	</div>
	
</div>

<script type="text/javascript">

	var curt = $('[data-section-action].active').length ? $('[data-section-action].active'): $('[data-section-action]').first();

	active();
	function active() {

		var key = curt.attr('data-key')+'_'+curt.attr('data-section-action');

		$('.u-section.active').slideUp(200, function () {
			$(this).removeClass('active');
		});
		$('.u-section#'+ key).slideDown(200, function() {
			$(this).addClass('active');
		});

	}

	// console.log( tab, $('[data-section-action]').length );
	
$(function () {
	
	$('[data-nav=toggle]').click(function() {
		
		var box = $(this).closest('.ReportNav');
		box.find('.ReportNav_list').slideToggle(100);

		box.toggleClass('is-open');

	});

	$('[data-section-action]').click(function(evt) {
		evt.preventDefault();

		if( $(this).hasClass('active') ) return false;
		$('[data-section-action].active').removeClass('active');
		$(this).addClass('active');

		var title = $(this).attr('data-title') || '';
		history.pushState('', title, $(this).attr('href'));
        document.title = title;

		curt = $(this);
		active();
	});


	var ranges = [];
	var today = new Date();
	// var m = ;
	// console.log( m );

	var i = 0;
	var mArr = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	for (var m = today.getMonth(); m >= 1; m--) {
		
		ranges.push({
			title: mArr[i],
			startDate: moment().subtract(m,"months").startOf("month"),
			endDate: moment().subtract(m,"months").endOf("month")
		});
		i++;
	}

	ranges.push({
		title: mArr[i],
		startDate: moment().startOf("month"),
		endDate: moment().endOf("month")
	});

	$(':input[name=closedate]').caleran({
		format: 'DD/MM/YYYY',
		showButtons: true,
		startOnMonday: true,
		ranges: ranges,
		onbeforemonthchange: function(caleran, month, direction){

			console.log( caleran, month, direction );
		},
		onaftermonthchange: function (caleran, month) {
			console.log( 'onaftermonthchange' );
		},
		onredraw: function(caleran){
			console.log( 'onredraw' );
		},
		startDate: moment().startOf("month"),
		endDate: moment().endOf("month"),

		onafterselect: function (caleran, startDate, endDate) {
			caleran.$elem.parent().addClass('has-date');
		}
	});

	$('#country_id').change(function() {
		
		$.get(app.getUri('ajax_lists/series'),{country: $(this).val()}, function( res ) {

			var $select = $('#ser_id');
			$select.empty();

			$select.append( $('<option>', {value: '', text: 'ทั้งหมด'}) );

			$.each( res, function(i, obj) {

				$select.append( $('<option>', {value: obj.id}).text( obj.code + " - "+ obj.name ) );
			});
		}, 'json');
	});

	Event.setPlugin( $('#com_agency_id'), 'selectize', {
		onChange: function (id) {

			$.get(app.getUri('ajax_lists/agency'), {company: id}, function( res ) {

				var $select = $('#agency_id');
				$select.empty();

				$select.append( $('<option>', {value: '', text: 'ทั้งหมด'}) );

				$.each( res, function(i, obj) {

					$select.append( $('<option>', {value: obj.id}).text( obj.name ) );
				});
			}, 'json');
		}
	}); 
})
	
</script>