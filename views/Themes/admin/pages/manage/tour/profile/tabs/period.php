<div class="table-period-wrap phl pbl" style="overflow: initial;">

	<div class="clearfix pbm">
		<div class="lfloat">
			<!-- <form class="form-search" data-action="form"><input class="inputtext search-input" type="text" id="search-query" placeholder="ค้นหา..." name="q" autocomplete="off"><span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span></form> -->
			<!-- <h2 style="line-height: 30px;"><i class="icon-calendar-o mrs"></i><span class="pls">Period</span></h2> -->
		</div>
		<div class="rfloat"><a class="btn btn-blue" data-plugin="lightbox" href="<?=URL?>period/add/<?=$this->item['id']?>"><i class="icon-plus "></i><span class="pls">Add</span></a></div>
	</div>
	<table class="table-period">
		<thead>
			<tr>
				<th rowspan="2" class="td-no">#</th>
				<th rowspan="2" class="td-status">Status</th>
				<th rowspan="2" class="td-date">Date</th>

				<th rowspan="2">Bus</th>
				<th rowspan="2">Seat</th>
				<th rowspan="2" class="td-status">Status</th>

				<th colspan="4">ราคาขาย</th>

				<th rowspan="2">คอมมิชชั่น</th>

				<th rowspan="2" class="td-price">ส่วนลด</th>
				<th rowspan="2" class="td-status">Auto Cancel</th>
				
				<th rowspan="2" class="td-action">Action</th>
			</tr>
			
			<tr>
				<th class="td-price">ราคา + ตั๋ว</th>
				<!-- <th class="td-price">เด็กมีเตียง</th> -->
				<!-- <th class="td-price">เด็กไม่มีเตียง</th> -->
				<th class="td-price">Infant</th>
				<th class="td-price">จอยแลนด์</th>
				<th class="td-price">พักเดี่ยว</th>
				<!-- <th class="td-price">อื่นๆ</th> -->

				<!-- <th rowspan="2" class="td-sub" style="text-align: right;"><div style="font-size: 10px;">Com</div>Agency</th> -->
				<!-- <th rowspan="2" class="td-sub" style="text-align: right;"><div style="font-size: 10px;">Com</div>Sales</th> -->
			</tr>
			
		</thead>
		<tbody>
		<?php 

			$eq = 0;
			foreach ($this->periodList as $i => $item) {

				$eq++;
				$price = 25000;
				$discount = $item['discount'];

				$rowspan = ''; $busListTotal = count($item['busList']);
				if( $busListTotal>1 ){
					$rowspan = ' rowspan="' .$busListTotal. '"';
				}

				$busEq = 0;
				foreach ($item['busList'] as $key => $bus) {
					$busEq++;


					$dropdownList = array();
					$dropdownList[] = array(
			            'text' => 'เปลี่ยนวันเดินทาง',
			            'href' => URL.'period/changedate/'.$item['id'].'/'.$bus['no'],
			            'attr' => array('data-plugin'=>'lightbox'),
			        );
			        $dropdownList[] = array(
			            'text' => 'เพิ่ม Bus',
			            'href' => URL.'period/addBus/'.$item['id'].'/',
			            'attr' => array('data-plugin'=>'lightbox'),
			        );

			        if( !empty($item['word_url']) || !empty($item['pdf_url']) ){

				        $dropdownList[] = array('type' => 'separator');
				        if( !empty($item['word_url']) ){
				            $dropdownList[] = array(
				                'text' => 'ดาวน์โหลดใบตรียมตัว(Word)',
				                'href' => $item['word_url'],
				                'target'=> "_blank"
				            );
				        }

				        if( !empty($item['pdf_url']) ){
				            $dropdownList[] = array(
				                'text' => 'ดาวน์โหลดใบตรียมตัว(PDF)',
				                'href' => $item['pdf_url'],
				                'target'=> "_blank"
				            );
				        }
			        }

			        /*$dropdownList[] = array('type' => 'separator');
			        $dropdownList[] = array(
			            'text' => 'Clone Period',
			            'href' => URL.'users/del/',
			            'attr' => array('data-plugin'=>'lightbox'),
			        );*/


			        /*$dropdownList[] = array('type' => 'separator');
			        $dropdownList[] = array( 'text' => 'เปิดจอง', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );
			        $dropdownList[] = array( 'text' => 'ปิดทัวร์', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );
			        $dropdownList[] = array( 'text' => 'ระงับการใช้งาน', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );
			        $dropdownList[] = array( 'text' => 'ตัดตั๋ว', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );*/


			        $dropdownList[] = array('type' => 'separator');
			        $dropdownList[] = array(
			            'text' => Translate::Val('Delete'),
			            'href' => URL.'period/del/'.$item['id'].'/'.$bus['no'],
			            'attr' => array('data-plugin'=>'lightbox'),
			        );


				?>
				<tr class="color-<?=$item['status_arr']['color']?>" item-id="<?=$this->item['id']?>">
					<?php if( $busEq==1 ){ ?>
					<td<?=$rowspan?> class="td-no"><?=$eq?></td>
					<td<?=$rowspan?> class="td-status"><span class="ui-status" style="background-color:<?=$item['status_arr']['color']?>"><?=$item['status_arr']['name']?></span></td>
					<td<?=$rowspan?> class="td-date"><span ref="date_str"><?=$item['date_str']?></span></td>
					<?php } ?>

					<td class="td-number"><?=$busEq?></td>
					<td class="td-number"><?=number_format($bus['seat'])?></td>
					<td class="td-status"><?php

					if( !empty($bus['status_arr']) ){

						echo '<span class="ui-status" style="background-color:'.$bus['status_arr']['color'].'">'.$bus['status_arr']['name'].'</span>';
					}

					?></td>

					<!-- price -->
					<?php 
					// foreach ($bus['price_values'] as $i => $val) {
					// 	echo '<td class="td-price">'.(!empty($val['value'])? number_format( intval($val['value']) ): '').'</td>';
					// }
					?>
					<td class="td-price" style="vertical-align: top">
						
						<?php if( !empty($bus['options']['price_values']) ){ ?>
						<table class="sub-table">
							<?php foreach ($bus['options']['price_values'] as $name => $value) {

								$val = number_format( intval($value['value']) );
								$name = isset($value['name']) ? $value['name']: '';
							?>
							<tr>
								<td class="name"><?=$name ?></td>
								<td class="value fc-blue"><?= $val ?></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</td>

					<td class="td-price"><?=!empty($bus['options']['infant']) ? number_format( intval($bus['options']['infant']) ): '' ?></td>

					<td class="td-price"><?=!empty($bus['options']['joinland']) ? number_format( intval($bus['options']['joinland']) ): '' ?></td>

					<td class="td-price"><?=!empty($bus['options']['single_charge']) ? number_format( intval($bus['options']['single_charge']) ): '' ?></td>
					<!-- end: price -->
					

					<td class="td-price" style="vertical-align: top">
						
						<?php if( !empty($bus['options']['commission']) ){ ?>
						<table class="sub-table">
							<?php foreach ($bus['options']['commission'] as $name => $value) {

								$val = number_format( intval($value['value']) );
								$name = isset($value['name']) ? $value['name']: '';

							?>
							<tr>
								<td class="name"><?= $name ?></td>
								<td class="value fc-blue"><?= $val ?></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</td>

					

					<td class="td-price" style="vertical-align: top">
						
						<?php if( !empty($bus['options']['discounts']) ){ ?>
						<table class="sub-table">
							<?php foreach ($bus['options']['discounts'] as $name => $value) { 

								$name = isset($value['name']) ? $value['name']: '';
								?>
							<tr>
								<td class="name"><?=$name?></td>
								<td class="value fc-blue"><span><?php
									echo number_format( intval($value['value']) );
								?></span></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</td>


					<!-- <td class="td-price" style="vertical-align: top">
						
						<?php if( !empty($bus['options']['room_of_types']) ){ ?>
						<table class="sub-table">
							<?php foreach ($bus['options']['room_of_types'] as $name => $value) { ?>
							<tr>
								<td class="name"><?=$name?></td>
								<td class="value fc-blue"><i class="icon-check"></i></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</td> -->
					<td class="td-status"><?php

					if( !empty($bus['autocancel_arr']) ){

						echo '<span class="ui-status">'.$bus['autocancel_arr']['name'].'</span>';
					}

					?></td>


					<td class="td-action">
						<div class="whitespace group-btn"><?php 

							echo '<a class="btn" title="Edit" data-plugin="lightbox" href="'.URL.'period/edit/'.$item['id'].'/'.$busEq.'"><i class="icon-pencil"></i></a>';

							echo '<a data-plugin="dropdown2" class="btn" data-options="'.$this->fn->stringify( array(
			                    'select' => $dropdownList,
			                    'axisX'=> 'right',
			                    'container'=> '.table-period-wrap',
			                ) ).'"><i class="icon-ellipsis-v"></i></a>';
						?>
						</div>
					</td>
				</tr>
			<?php } ?>

		<?php } ?>
		</tbody>

		<?php if( count($this->periodList)==0 ){?>

		<tfoot>
			<tr>
				<td colspan="17" class="td-empty" style="padding: 50px;text-align: center;color: #999;">No Period, <a data-plugin="lightbox" href="<?=URL?>period/add/<?=$this->item['id']?>">Create Period</a></td>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>