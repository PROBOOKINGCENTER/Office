<?php

$cardList = array();
$cardList[] = array('id'=>'', 'name'=>'ยอดขาย (บาท) เดือนปัจจุบัน', 'value'=> number_format($this->receipt), 'bg'=>'blue', 'icon'=>'money');
$cardList[] = array('id'=>'', 'name'=>'ยอดขาย (ที่นั่ง) เดือนปัจจุบัน', 'value'=>number_format($this->seat), 'bg'=>'red', 'icon'=>'users');
$cardList[] = array('id'=>'', 'name'=>'จำนวน PERIOD ที่เปิดอยู่', 'value'=>number_format($this->period), 'bg'=>'green', 'icon'=>'list-alt');




$chartsList = array();
$chartsList[] = $this->agencyChartOpt;
$chartsList[] = $this->salesChartOpt;
$chartsList[] = $this->seriesChartOpt;
// $chartsList[] = array('id'=>'', 'name'=>'ยอดขาย (ที่นั่ง) เดือนปัจจุบัน', 'value'=>number_format(123456));
// $chartsList[] = array('id'=>'', 'name'=>'จำนวน PERIOD ที่เปิดอยู่', 'value'=>number_format(123456));
?>
<style type="text/css">
	
.card {
  position: relative;
}
.card .inner{
	-moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;

  background-color: #ffff;

  /*-moz-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
  box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);*/
  
  box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 1px 6px rgba(0,0,0,0.12);
}
.u-boxShadow-2{
	
}

.card.card-default {
    
}
.card-header{
	padding: 6px 12px
}
.card-body{
	padding: 12px
}

/*.page-title{
	padding: 10px 20px
}*/
.page-main{
	/*background-color: #fff;*/

	/*-webkit-box-shadow: 0 1px 4px 0 rgba(0,0,0,.14);
    box-shadow: 0 1px 4px 0 rgba(0,0,0,.14);*/
}

.card-col-3{
	display: inline-block;
	width: 33.3333336%;
	vertical-align: top;
}
.card-col-4{
	display: inline-block;
	width: 25%;
	vertical-align: top;
}


.card-col-3 .inner, .card-col-4 .inner{
	margin: 10px;
}
.card-blue .inner{background-color: #0090D9;color: #fff;}
.card-red .inner{background-color: #C75757;color: #fff;}
.card-green .inner{background-color: #18a689;color: #fff;}
.card-dark .inner{background-color: #2B2E33;color: #fff;}

.stat{
	display: table;
}
.stat>*{
	display: table-cell;
	vertical-align: middle;
}
.stat-ico{
	font-size: 90px;

	/*margin-right: 10px;*/
	opacity: .5;
}
.stat-message{
	padding-left: 20px
}
.stat-title{
	opacity: .7;
    text-transform: uppercase;
    line-height: 1
}
.stat-value{
	line-height: 1;
	font-size: 46px;
}
</style>

<div id="dashboard" class="page">
	
	<div class="page-container">
		<div class="page-main">

			<!-- charts -->
			<?php require_once 'sections/charts.php'; ?>
			<!-- end: charts -->

			<!-- โปรดันขาย -->
			<?php require_once 'sections/salesForce.php'; ?>
			<!-- end: โปรดันขาย -->

		</div>
		<!-- end: page-main -->
	</div>
	<!-- end: page-container -->
</div>
<!-- end: page -->