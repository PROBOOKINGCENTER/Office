
<style type="text/css">
	
	.list-event-wrap{
		position: relative;
		height: 100%;
	}
	.list-event-dot{
		position: absolute;

		width: 1px;
		top: 0;
		height: 100%;
		left: 85px;
		background-color: #F2D0B2
	}
	.list-event{
		
	}
	.list-event-content{
		padding: 30px 20px;
	}
	.list-event>li{
		position: relative;
		padding-top: 6px;
		padding-left: 80px;
		padding-bottom: 10px;
		padding-right: 0
	}

	/*.list-event>li:before{
		content: '';
		position: absolute;
		left: 80px;
		bottom: 0;
		width: 1px;
		height: 100%;
		background-color: #F2D0B2
	}*/
	.list-event>li:after{
		content: '';
		position: absolute;
		left: 61px;
	    width: 9px;
	    height: 9px;
	    border-radius: 50%;
	    top: 12px;
		background-color: #ffffff;
    	border: 1px solid #fe8a4c;
    	z-index: 1
	}
	.list-event .head{
		padding-top: 30px;
		font-size: 12px;
		font-weight: bold;
		text-transform: uppercase;

		border-top:1px solid #F2D0B2;

	}
	.list-event .head:after{
		background-color:#fe8a4c;
    	border-color:#fe8a4c;
    	top: 34px;
	}
	.list-event>li.head:first-child{
		border-top-width: 0;
		padding-top: 0;
	}
	.list-event>li.head:first-child:after{
		top: 3px;
	}
	

	.list-event .time{
		width: 50px;
		position: absolute;
		left: 0;
		top: 6px;
		color: #666;
		text-align: right;
	}
	.list-event .content{
		/*background-color: #f2f2f2;*/
		padding: 6px;
		border-radius: 2px;
		margin-top: -6px;
		position: relative;

		display: table;
		width: 100%;
/*
		display: -ms-flexbox;
	    display: flex;
	    -ms-flex-align: center;
	    align-items: center;
	    -ms-flex-pack: justify;
	    justify-content: space-between;
*/
	}
	.list-event .content>div{
		display: table-cell;
		vertical-align: middle;
	}
	.list-event .content:hover{
		background-color: #f2f2f2;

	}
	.list-event .message{
		/*display: inline-block;
		vertical-align: middle;*/
	}
	.list-event .action{
		width: 30px;
		/*float: right;*/
		/*display: flex;*/

		 /*-ms-flex-align: right;*/
	    /*align-items: right;*/

	    /*text-align: right;*/

		/*width: 70px;*/
		/*display: -ms-flexbox;
	    display: flex;
	    -ms-flex-align: center;
	    align-items: center;
	    -ms-flex-pack: center;
	    justify-content: center;*/
	}

	.list-event-content{
		margin-right: 340px; 
	}

	.list-event-side{
		width: 320px;
		position: absolute;
		top:20px;
		right: 20px;
		/*background-color: #ddd;*/
		bottom: 0
	}
</style>

<div class="list-event-wrap" style="-max-width: 1024px">

	<div class="list-event-dot"></div>

	<?php

	$a = array();
	$a[] = array('id'=>'', 'title'=>'Application sideber design', 'text'=>'Positionly', 'time'=> '20:33'); 
	$a[] = array('id'=>'', 'title'=>'Creating great biog post content for marketing', 'time'=> '2:02', 'text'=>'Positionly'); 
	$a[] = array('id'=>'', 'title'=>'Week summary', 'text'=>'Positionly', 'time'=> '10:02'); 
	$a[] = array('id'=>'', 'title'=>'Contact HR', 'text'=>'Positionly', 'time'=> '0:10'); 

	$b = array();
	$b[] = array('id'=>'', 'title'=>'New design for skubacz.pl', 'text'=>'Positionly', 'time'=> '2:02'); 
	$b[] = array('id'=>'', 'title'=>'ekino.tv UI prototype', 'time'=> '1.30', 'text'=>'Positionly'); 

	?>
	<div class="list-event-content">
		<ul class="list-event">

		<?php for ($i=0; $i < 2; $i++) { ?>
			<li class="head">Today, 13 May 2018</li>
			
			<?php  foreach ($a as $key => $value) { ?>

			<li>
				<div class="time fwb"><?=$value['time']?></div>

				<div class="content clearfix">
					<div class="message">
						<div class="title fwb"><?=$value['title']?></div>
						<div class="fcg">
							<span class="text "><?=$value['text']?></span>
						</div>

					</div>
					<div class="action">
						<a class="btn btn-blue">แจ้งการชำระเงิน</a>
					</div>
				</div>
			</li>

			<?php }?> 


		<?php }?> 
		</ul>
	</div>

	<div class="list-event-side">

		<div class="list-event-section">
			<div data-plugin="calendarmin"></div>
		</div>
	</div>

</div>
