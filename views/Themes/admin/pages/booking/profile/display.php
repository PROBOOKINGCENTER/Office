<?php


?><style type="text/css">

	.section{
		min-height: calc(100vh - 50px);
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

	.section-header>.inner{
		padding: 14px 20px 4px;
    	background-color: #f1f1f1;
	}

	.section-tab{
		border-bottom: 1px solid #ccc;
		background-color: #f1f1f1;
		padding-left: 4px;
	}
	.section-tab>.nav>li{
		display: inline-block;
	}
	.section-tab>.nav>li>a{
		display: block;
		padding: 4px 16px;
		color: #999;
		position: relative;
		border-radius: 3px 3px 0 0;
		border-color: transparent;
		border-width: 1px 1px 0;
		border-style: solid;
	}
	.section-tab>.nav>li>a:hover{
		text-decoration: none;
		color: #000
	}
	.section-tab>.nav>li.active>a{
		text-decoration: none;
		color: #1428a0;
		background-color: #fff;
		border-color: #ccc;
	}
	.section-tab>.nav>li.active>a:before{
		position: absolute;
		height: 1px;
		background-color: #fff;
		left: 0;
		right: 0;
		content: '';
		bottom: -1px;
	}
	.section-alert{
		text-align: center;
		color: #999
	}
	.section-alert>div{
		padding: 20px;
		display: none;
	}
	.section-alert .loader-spin-wrap{
		margin: 2px auto;
	}
</style>


<section id="profile" class="section" data-plugin="main2" data-options="<?=Fn::stringify( $this->opt )?>">
	
	<header class="section-header" role="header">
		<div class="inner">
			<!-- toolbar -->
			<div class="toolbar clearfix">
				<h1 class="title lfloat"><i class="icon-<?=$this->listOpt['icon']?> mrs"></i><?=$this->opt['title']?></h1>
				<?php 
				if( !empty($this->opt['controls']) ){

					echo '<nav class="rfloat"><ul class="nav tb-controls">';
					foreach ($this->opt['controls'] as $key => $value) {
						echo '<li class="control-item">'.$value.'</li>';
					}
					echo '</ul></nav>';
				}
				?>
			</div>
			<!-- end: toolbar -->
		</div>
		<?php if( !empty($this->opt['tab']['items']) ) { ?>
		<nav class="section-tab" role="tabs">
			<ul class="nav"><?php

			foreach ($this->opt['tab']['items'] as $key => $value) {

				// $active = $this->tab==$value['id'] ? ' class="active"':'';
				echo '<li><a data-tab-action="'.$value['id'].'" title="'.$value['name'].'" href="'.$value['link'].'">'.$value['name'].'</a></li>';
			}

			?></ul>
		</nav>
		<?php } ?>
	</header>


	<div class="section-main" role="main">

		<div class="section-content" role="content">
			<?php //require_once 'tabs/booking.php'; ?>
		</div>

		<div class="section-alert">
			<div class="section-loading"><div class="loader-spin-wrap"><div class="loader-spin"></div></div><p>Loading...</p></div>
			<div class="section-empty">
				<div class="empty-icon"><i class="icon-check-square-o"></i></div>
	        	<div class="empty-title">No Results Found.</div>
			</div>
			<div class="section-error">Don't connected, <a type="button" data-action="tryagain">Try again</a></div>
			<div class="section-more"><a type="button" class="btn btn-large" data-action="more">More</a></div>
		</div>

	</div>


</section>