<?php

$this->direction = URL.'settings/bookbank/';

?>
<div class="datatable-wrap" style="-max-width: 750px;">

	<div class="datatable-header clearfix">
		
		<ul class="datatable-actions">
			<li><h2 class="datatable-title"><i class="icon-money"></i><span class="mls">Book Bank</span></h2></li>
			<li class="divider"></li>
			<li><a class="btn btn-blue" data-plugins="lightbox" href="<?=$this->direction?>form/"><i class="icon-plus"></i><span class="mls"><?=Translate::Val('Add New')?></span></a></li>
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
				<th class="status" style="text-align: left;">Bank</th>
				<th class="name">Account</th>
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
				<td class="whitespace">
					<strong ref="bank_name"><?=$item['bank_name']?></strong>
					<div class="fsm">สาขา: <span ref="bankbook_branch"><?=$item['branch']?></span></div>
				</td>
				<td class="name"><?php
					echo '<div style="overflow: hidden;text-overflow: ellipsis;width: 170px"><a ref="bankbook_code" class="fwb" href="'.$this->direction.'form/'.$item['id'].'" data-plugins="lightbox">'. $item['code'].'</a></div>';

					echo '<div class="fsm"><span ref="bankbook_name">'.$item['name'].'</span></div>';
				?></td>

				<td class="status">
					<label class="switch"><input type="checkbox" data-action-update="checked" name="status"<?=( !empty($item['status'])? ' checked':'' )?>><span class="slider round"></span></label>
				</td>

				<td class="actions whitespace">
					<?php

					echo '<div class="group-btn">';

					echo '<a class="btn" data-plugin="lightbox" href="'.$this->direction.'form/'.$item['id'].'"><i class="icon-pencil"></i><span class="mls">'.Translate::Val('Edit').'</span></a>';

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