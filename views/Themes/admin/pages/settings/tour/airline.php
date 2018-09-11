<?php

$this->direction = URL.'tour/airline/';

?>
<div class="datatable-wrap" style="-max-width: 750px;">

	<div class="datatable-header clearfix">
		
		<ul class="datatable-actions">
			<li><h2 class="datatable-title"><i class="icon-plane"></i><span class="mls">Airline</span></h2></li>
			<li class="divider"></li>
			<li><a class="btn btn-blue" data-plugins="lightbox" href="<?=$this->direction?>add/"><i class="icon-plus"></i><span class="mls"><?=Translate::Val('Add New')?></span></a></li>
			<li class="r"><form class="form-search" action="#">
				<input class="inputtext search-input" type="text" id="search-query" placeholder="ค้นหา..." name="q" autocomplete="off">
				<span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span>
			</form></li>
		</ul>
	</div>


	<table class="datatable">
		<thead>
			<tr>
				<th class="no">#</th>
				<th class="image" style="width: 48px">Logo</th>
				<th class="status">Code</th>
				<th class="name">Airline Name</th>
				<th class="status">Enabled</th>
				<th class="actions"></th>
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
				<td class="image" style="width: 48px">
					<?php if( !empty($item['image_url']) ){ ?>
					<div class="pic" style="width: 48px;padding-top:48px;"><img src="<?=$item['image_url']?>"></div>
					<?php } ?>
				</td>
				<td class="status"><span ref="air_code"><?=$item['code']?></span></td>
				<td class="name"><?php

					echo '<a class="fwb" href="'.$this->direction.'edit/'.$item['id'].'" data-plugins="lightbox" ref="air_name">'. $item['name'].'</a>';

					if( !empty($item['description']) ){
						echo '<div class="mts fsm fcg">'.$item['description'].'</div>';
					}
				?></td>



				<td class="status">
					<!-- <label class="switch"><input type="checkbox" data-action-update="checked" name="status"<?=( !empty($item['status'])? ' checked':'' )?>><span class="slider round"></span></label>					 -->
					<?=(!empty($item['status'])? '<span class="ui-status" style="display:block;background-color:#8bc34a">ใช้งาน</span>':'<span class="ui-status" style="display:block;background-color:#d30000">ปิด</span>' )?>
				</td>

				<td class="actions whitespace">
					<?php

					echo '<div class="group-btn">';

					echo '<a class="btn" data-plugin="lightbox" href="'.$this->direction.'edit/'.$item['id'].'"><i class="icon-pencil"></i><span class="mls">'.Translate::Val('Edit').'</span></a>';

					$dropdown = array();

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
		</tbody>
	</table>
</div>