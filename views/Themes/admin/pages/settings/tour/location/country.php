<?php

$this->direction = URL.'tour/location/country/';

/* data-contents="true" */
?>

<div class="datatree-wrap" style="display: none;">
	
	<ul class="datatree-list n">
		<li class="title">
			<div class="input">
				<span class="input-text">ประเทศ</span>
				<span class="input-actions">
					<a type="button" class="action" data-plugins="lightbox" href="<?=$this->direction?>add"><i class="icon-plus"></i></a>
				</span>
			</div>

			<ul class="datatree-list">

				<?php foreach ($this->dataList as $key => $item) { 


					$dropdown = array();

					$dropdown[] = array(
		                'text' => Translate::Val('Edit'),
		                'href' => $this->direction.'del/'.$item['id'],
		                'attr' => array('data-plugins'=>'lightbox'),
		                // 'icon' => 'remove'
		            );
					
					$dropdown[] = array(
		                'text' => Translate::Val('Enabled'),
		                'href' => $this->direction.'del/'.$item['id'],
		                'attr' => array('data-plugins'=>'lightbox'),
		                // 'icon' => 'remove'
		            );

		            $dropdown[] = array(
		                'text' => Translate::Val('Disabled'),
		                'href' => $this->direction.'del/'.$item['id'],
		                'attr' => array('data-plugins'=>'lightbox'),
		                // 'icon' => 'remove'
		            );

		            $dropdown[] = array(
		                'text' => Translate::Val('Delete'),
		                'href' => $this->direction.'del/'.$item['id'],
		                'attr' => array('data-plugins'=>'lightbox'),
		                // 'icon' => 'remove'
		            );

				?>
					
				<li>
					<div class="input">
						<span class="input-text"><?=$item['name']?></span>
						<span class="input-actions">
							<button type="button" class="action t" title="เพิ่มเมือง">+ เพิ่มเมือง</button>
							<?php 

							echo '<a data-plugins="dropdown" class="action" data-options="'.$this->fn->stringify( array(
		                        'select' => $dropdown,
		                        'settings' =>array(
		                            'axisX'=> 'right',
		                            'parentElem'=>'.setting-main'
		                        )
		                    ) ).'"><i class="icon-ellipsis-v"></i></a>';

							?>
						</span>
					</div>
					<!-- <div class="text-desc">dasfsdf sdfsd4fds f4ds4 fd4sf4dsf4 ds4f</div> -->
					<ul class="datatree-list mtm hidden_elem">
						<li><div class="input"><input type="text" name="" class="inputtext" ></div></li>
						<li><div class="input"><input type="text" name="" class="inputtext" ></div></li>
						<li><div class="input"><input type="text" name="" class="inputtext" ></div></li>
					</ul>

					<a href="" class="mvm hidden_elem">แสดงเมือง</a>
				</li>
				<?php } ?>

				<!-- <li><div class="input"><input type="text" name="" class="inputtext" ></div></li> -->
			</ul>
		</li>

	</ul>
</div>

<div class="datatable-wrap">

	<div class="datatable-header clearfix">
		
		<ul class="datatable-actions">
			<li><h2 class="datatable-title"><i class="icon-map-o"></i><span class="mls">Country</span></h2></li>
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
				<!-- <th class="status">Flag</th> -->
				<th class="image">Image</th>
				<th class="name">Country Name</th>
				<th class="status">Status</th>
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
				<!-- <td class="status"></td> -->
				<td class="image" style="width: 170px">
					<?php if( !empty($item['image_url']) ){ ?>
					<div class="pic" style="width: 170px;padding-top:102px;"><img src="<?=$item['image_url']?>"></div>
					<?php } ?>
				</td>
				<td class="name"><?php

					echo '<div class="anchor clearfix">';
					
						echo '<div class="lfloat">';
							echo !empty($item['code'])? '<i class="flag icoflag-'.strtolower($item['code']).' mrs"></i>':'';
						echo '</div>';

						echo '<div class="content"><div class="spacer"></div><div class="massages">';

							echo '<a class="fwb" href="'.$this->direction.'edit/'.$item['id'].'" data-plugins="lightbox" ref="air_name">'. $item['name'].'</a>';

							if( !empty($item['description']) ){
								echo '<div class="fsm fcg">'.$item['description'].'</div>';
							}

						echo '</div></div>';

					echo '</div>';
				?></td>


				<td class="status">
					<?=(!empty($item['status'])? '<span class="ui-status" style="display:block;background-color:#8bc34a">ใช้งาน</span>':'<span class="ui-status" style="display:block;background-color:#d30000">ปิด</span>' )?>
					<!-- <label class="switch"><input type="checkbox" data-action-update="checked" name="status"<?=( !empty($item['status'])? ' checked':'' )?>><span class="slider round"></span></label>				 -->
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
		                // 'icon' => 'remarkove'
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