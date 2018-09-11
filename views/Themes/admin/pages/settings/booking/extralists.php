<?php

$this->direction = URL.'settings/extralists/';

?>
<div class="datatable-wrap" style="-max-width: 750px;">

	<div class="datatable-header clearfix">
		
		<ul class="datatable-actions">
			<li><h2 class="datatable-title">Extra Lists</h2></li>
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
				<th class="name">Name</th>
				<th class="price">Price</th>
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
			<td class="name whitespace"><strong ref="name"><?=$item['name']?></strong></td>
			<td class="price"><span ref="price" data-type="int"><?= number_format($item['price'])?></span></td>
			<td class="status">
				<label class="switch"><input type="checkbox" data-action-update="checked" name="enabled"<?=( !empty($item['enabled'])? ' checked':'' )?>><span class="slider round"></span></label>			
			</td>

			<td class="actions whitespace">
				<?php

				echo '<div class="group-btn">';
				echo '<a class="btn" data-plugin="lightbox" href="'.$this->direction.'form/'.$item['id'].'"><i class="icon-pencil"></i></a>';

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
	</tbody></table>
</div>