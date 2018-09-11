<?php

$this->direction = URL.'tour/location/city/';

?>
<div class="datatable-wrap" style="-max-width: 750px;">

	<div class="datatable-header clearfix">
		
		<ul class="datatable-actions">
			<li><h2 class="datatable-title"><i class="icon-map-o"></i><span class="mls">City</span></h2></li>
			<li class="divider"></li>
			<li><a class="btn btn-blue" data-plugins="lightbox" href="<?=$this->direction?>add"><i class="icon-plus"></i><span class="mls"><?=Translate::Val('Add New')?></span></a></li>
			<li class="r"><form class="form-search">
				<input class="inputtext search-input" type="text" id="search-query" placeholder="ค้นหา..." name="q" autocomplete="off">
				<span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span>
			</form></li>
		</ul>
	</div>

	<table class="datatable">
		<thead>
			<tr>
				<th class="no">#</th>
				<th class="name">Name</th>
				<th class="status">Country</th>
				<th class="status">Enabled</th>
				<th class="actions">Action</th>
			</tr>
		</thead>

		<tbody>
		<?php 

			$qe = 0;
			foreach ($this->dataList as $key => $item) { 

				$qe++;
			?>
			<tr data-id="<?=$item['id']?>">

				<td class="no"><?=$qe?></td>
				
				
				<td class="name"><?php
					echo '<div class="fwb"><span ref="name">'.$item['name'].'</span></div>';
					// echo '<div class="fss fcg">City in <span ref="description">'.$item['country_name'].'</span></div>';
					echo '<div class="fsm fcg"><span ref="description">'.$item['description'].'</span></div>';
				?></td>

				<td class="status">
					<?=!empty($item['country_code'])? '<div><i class="flag icoflag-'.strtolower($item['country_code']).'"></i></div>':''?>
					<div><span ref="country_name"><?=$item['country_name']?></span></div>
				</td>

				<td class="status">
					<!-- <label class="switch"><input type="checkbox" data-action-update="checked" name="city_enabled"<?=( !empty($item['enabled'])? ' checked':'' )?>><span class="slider round"></span></label> -->
					<?=(!empty($item['enabled'])? '<span class="ui-status" style="display:block;background-color:#8bc34a">ใช้งาน</span>':'<span class="ui-status" style="display:block;background-color:#d30000">ปิด</span>' )?>
				</td>


				<td class="actions whitespace">
					<?php

					echo '<div class="group-btn">';

					echo '<a class="btn" data-plugin="lightbox" href="'.$this->direction.'edit/'.$item['id'].'"><i class="icon-pencil"></i><span class="mls">'.Translate::Val('Edit').'</span></a>';

					$dropdown = array();

					/*$dropdown[] = array(
		                'text' => Translate::Val('Edit'),
		                'href' => $this->direction.'edit/country/'.$item['id'],
		                'attr' => array('data-plugins'=>'lightbox'),
		                // 'icon' => 'pencil'
		            );*/

					$dropdown[] = array(
		                'text' => Translate::Val('Delete'),
		                'href' => $this->direction.'del/'.$item['id'],
		                'attr' => array('data-plugins'=>'lightbox'),
		                // 'icon' => 'remove'
		            );

		            if( !empty($dropdown) ){


					echo '<a data-plugins="dropdown" class="btn" data-options="'.$this->fn->stringify( array(
	                        'select' => $dropdown,
	                        'settings' =>array(
	                            'axisX'=> 'right',
	                            'parentElem'=>'.setting-main'
	                        )
	                    ) ).'"><i class="icon-ellipsis-v"></i></a>';

					}

					echo '</div>';
					?>

				</td>

			</tr>
			<?php } ?>
	</tbody></table>
</div>