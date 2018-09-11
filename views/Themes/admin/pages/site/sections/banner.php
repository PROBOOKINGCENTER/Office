<?php

$url = URL .'banner/';

?>

				<!-- header -->
				<div class="setting-header cleafix">

					<div class="rfloat"></div>

					<div class="setting-title"><i class="icon-picture-o mrs"></i><?=Translate::Val('Banners')?></div>
				</div>
				<!-- end: header -->


<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name">ชื่อ</th>
			<th class="status">Size</th>
			<th class="actions"></th>

		</tr>

		<?php foreach ($this->bannersList as $key => $item) { ?>
		<tr data-id="<?=$item['id']?>">
			<td class="name fwb">
				
				<?php 

				$fw = 160;
				$fh = ($fw*$item['height'])/$item['width'];

				$img = '';
				if( !empty($item['items'][0]['src']) ){
					$img = '<img src="'.$item['items'][0]['src'].'" style="width:100%">';
				}

				?>
				<a href="<?=$url?>edit/<?=$item['id']?>" data-plugins="dialog" style="background-color: #aaa;float: left;margin-right: 10px;height: <?=$fh?>px;width: <?=$fw?>px">
					<?=$img?>
				</a>
				<div style="overflow: hidden;"><?php echo '<a href="'.$url.'edit/'.$item['id'].'" data-plugins="dialog">'.$item['name'].'</a>'; ?></div>

			</td>
			
			<td class="status"><?=$item['width']?>x<?=$item['height']?></td>

			<td class="actions whitespace">

				<a class="btn" href="<?=$url?>edit/<?=$item['id']?>" data-plugins="dialog"><i class="icon-pencil"></i><span class="mls">แก้ไข</span></a>
					
			</td>

		</tr>
		<?php } ?>
	</tbody></table>

</section>