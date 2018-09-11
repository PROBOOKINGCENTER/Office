<div class="clearfix<?php if( !empty($this->pageOpt['nav']) ) { echo ' two-columns'; } ?>">

	<?php if( !empty($this->pageOpt['nav']) ) { ?>
	<div class="secondary-content narrow">
		<aside class="sidebar">

			<h1 class="sidebar-title"><?php if( !empty($this->pageOpt['icon']) ) { ?><i class="icon-<?=$this->pageOpt['icon']?> mrs"></i><?php } ?><?=$this->pageOpt['title']?></h1>

			<?php foreach ($this->pageOpt['nav']['items'] as $key => $value) { 

				$is_item = !empty($value['items']);
			?>
			<div class="sidebar-widget is-open">

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
	<?php } ?>

	<div class="primary-content wide">
		<section class="section clearfix" data-plugin="main2" data-options="<?=Fn::stringify( $this->pageOpt )?>">

			<div class="section-main" role="main">

				<header class="section-header" role="header">
					<div class="inner">
						<!-- toolbar -->
						<div class="toolbar clearfix">
							<h1 class="title lfloat"><?php if( !empty($this->pageOpt['icon']) ) { ?><i class="icon-<?=$this->pageOpt['icon']?> mrs"></i><?php } ?><?=$this->pageOpt['title']?></h1>
							<?php 
							if( !empty($this->pageOpt['controls']) ){

								echo '<nav class="rfloat"><ul class="nav tb-controls">';
								foreach ($this->pageOpt['controls'] as $key => $value) {
									echo '<li class="control-item">'.$value.'</li>';
								}
								echo '</ul></nav>';
							}
							?>
						</div>
						<!-- end: toolbar -->
					</div>
					<?php if( !empty($this->pageOpt['tab']['items']) ) { ?>
					<nav class="section-tab" role="tabs">
						<ul class="nav"><?php

						foreach ($this->pageOpt['tab']['items'] as $key => $value) {

							// $active = $this->tab==$value['id'] ? ' class="active"':'';
							echo '<li><a data-tab-action="'.$value['id'].'" title="'.$value['name'].'" href="'.$value['link'].'">'.$value['name'].'</a></li>';
						}

						?></ul>
					</nav>
					<?php } ?>
				</header>

				<div class="section-content" role="content"></div>

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
	</div>
</div>