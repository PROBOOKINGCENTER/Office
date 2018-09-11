<div class="table-period-wrap phl pbl" style="overflow: initial;">

	<div class="clearfix pbm">
		<div class="lfloat">
			<!-- <form class="form-search" data-action="form"><input class="inputtext search-input" type="text" id="search-query" placeholder="ค้นหา..." name="q" autocomplete="off"><span class="search-icon"><button type="submit" class="icon-search nav-search" tabindex="-1"></button></span></form> -->
			<!-- <h2 style="line-height: 30px;"><i class="icon-calendar-o mrs"></i><span class="pls">Period</span></h2> -->
		</div>
		<div class="rfloat"><a class="btn btn-blue" data-plugin="lightbox" href="<?=URL?>tour/period/add/<?=$this->item['id']?>"><i class="icon-plus "></i><span class="pls">Add</span></a></div>
	</div>
	<table class="table-period">
		<thead>
			<tr>
				<th rowspan="2">#</th>
				<th rowspan="2" class="td-status">Status</th>
				<th rowspan="2">Date</th>

				<th rowspan="2">Bus</th>
				<th rowspan="2">Seat</th>

				<th colspan="6">Price</th>

				<th rowspan="2" class="td-sub" style="text-align: right;"><div style="font-size: 10px;">Com</div>Agency</th>
				<th rowspan="2" class="td-sub" style="text-align: right;"><div style="font-size: 10px;">Com</div>Sales</th>
				<th rowspan="2" class="td-sub">ส่วนลด</th>
				<th rowspan="2" class="td-action">Action</th>
			</tr>
			
			<tr>
				<th class="td-price">ผู้ใหญ่</th>
				<th class="td-price">เด็กมีเตียง</th>
				<th class="td-price">เด็กไม่มีเตียง</th>
				<th class="td-price">Infant</th>
				<th class="td-price">จอยแลนด์</th>
				<th class="td-price">พักเดี่ยว</th>
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

				$dropdownList = array();
		        
				$dropdownList[] = array(
		            'text' => 'เปลี่ยนวันเดินทาง',
		            'href' => URL.'users/del/',
		            'attr' => array('data-plugin'=>'lightbox'),
		        );
		        $dropdownList[] = array(
		            'text' => 'เพิ่ม Bus',
		            'href' => URL.'users/del/',
		            'attr' => array('data-plugin'=>'lightbox'),
		        );

		        $dropdownList[] = array('type' => 'separator');

		        $dropdownList[] = array(
		            'text' => 'ดาวน์โหลดใบตรียมตัว(Word)',
		            'href' => URL.'users/del/',
		            'attr' => array('data-plugin'=>'lightbox'),
		        );

		        $dropdownList[] = array(
		            'text' => 'ดาวน์โหลดใบตรียมตัว(PDF)',
		            'href' => URL.'users/del/',
		            'attr' => array('data-plugin'=>'lightbox'),
		        );

		        $dropdownList[] = array('type' => 'separator');
		        
		        $dropdownList[] = array(
		            'text' => 'Clone Period',
		            'href' => URL.'users/del/',
		            'attr' => array('data-plugin'=>'lightbox'),
		        );


		        $dropdownList[] = array('type' => 'separator');
		        $dropdownList[] = array( 'text' => 'เปิดจอง', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );
		        $dropdownList[] = array( 'text' => 'ปิดทัวร์', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );
		        $dropdownList[] = array( 'text' => 'ระงับการใช้งาน', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );
		        $dropdownList[] = array( 'text' => 'ตัดตั๋ว', 'href' => URL.'users/del/',  'attr' => array('data-plugin'=>'lightbox'), );


		        $dropdownList[] = array('type' => 'separator');
		        $dropdownList[] = array(
		            'text' => Translate::Val('Delete'),
		            'href' => URL.'users/del/',
		            'attr' => array('data-plugin'=>'lightbox'),
		        );


				$busEq = 0;
				foreach ($item['busList'] as $key => $bus) {
					$busEq++;
				?>
				<tr class="color-<?=$item['status_arr']['color']?>">
					<?php if( $busEq==1 ){ ?>
					<td<?=$rowspan?> class="td-no"><?=$eq?></td>
					<td<?=$rowspan?> class="td-status"><span class="ui-status color-<?=$item['status_arr']['color']?>"><?=$item['status_arr']['name']?></span></td>
					<td<?=$rowspan?> class="td-date">
						
						<?=$this->fn->q('time')->str_event_date( $item['date_start'], $item['date_end'] )?>
						<div class="fcg" style="font-size: 10px">Auto Cancel: <?=$item['auto_cancel_mode']['name']?></div>
					</td>
					<?php } ?>

					<td class="td-number"><?=$busEq?></td>
					<td class="td-number"><?=number_format($bus['seat'])?></td>

					<td class="td-price fc-blue"><?=number_format($item['price_1'])?></td>
					<td class="td-price"><?=number_format($item['price_2'])?></td>
					<td class="td-price"><?=number_format($item['price_3'])?></td>
					<td class="td-price"><?=number_format($item['price_4'])?></td>
					<td class="td-price"><?=number_format($item['price_5'])?></td>
					<td class="td-price"><?=number_format($item['single_charge'])?></td>
					
					<td class="td-sub td-price"><?=number_format($item['com_company_agency'])?></td>
					<td class="td-sub td-price"><?=number_format($item['com_agency'])?></td>

					<td class="td-sub td-price discount"><?php

						if( $discount>0 ){
							echo number_format($discount);
							echo ' <span class="fss fcg">('.(number_format( (100*$discount) / $price )).'%)</span>';
						}
						else{
							echo '-';
						}

					?></td>

					<td class="td-action">
						<div class="whitespace group-btn"><?php 

							echo '<a class="btn" title="Edit" data-plugin="lightbox" href="'.URL.'tour/period/edit/'.$item['ser_id'].'/'.$item['id'].'/'.$busEq.'"><i class="icon-pencil"></i></a>';

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
				<td colspan="15" class="td-empty" style="padding: 50px;text-align: center;color: #999;">No Period, <a data-plugin="lightbox" href="<?=URL?>tour/period/add/<?=$this->item['id']?>">Create Period</a></td>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>