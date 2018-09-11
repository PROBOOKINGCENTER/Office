<?php


$fields = array();
$fields[] = array('id'=>'_seq', 'name'=>'','label'=>'#', 'cls'=>'td-seq');
// $fields[] = array('id'=>'_room', 'name'=>'','label'=>'Room');
$fields[] = array('type'=>'fullname', 'id'=>'', 'name'=>'','label'=>'Name (EN)*');
$fields[] = array('type'=>'name', 'id'=>'', 'name'=>'','label'=>'Name (TH)*');
$fields[] = array('type'=>'radio', 'id'=>'', 'name'=>'','label'=>'Gender*', 'items' => array(0=>array('id'=>1, 'name'=>'<i class="icon-male"></i>'), array('id'=>2, 'name'=>'<i class="icon-female"></i>') ), 'cls'=>'td-radio');
$fields[] = array('id'=>'', 'name'=>'','label'=>'Country*');
$fields[] = array('id'=>'', 'name'=>'','label'=>'National*');
$fields[] = array('id'=>'', 'name'=>'','label'=>'Address in Thailand');
$fields[] = array('type'=>'date', 'id'=>'', 'name'=>'','label'=>'Date of birth', 'cls'=>'td-date');
$fields[] = array('id'=>'', 'name'=>'','label'=>'จัดหวัดที่เกิด');
$fields[] = array('id'=>'', 'name'=>'','label'=>'อาชีพ');


$fields[] = array('id'=>'', 'name'=>'','label'=>'Passport No.');
$fields[] = array('type'=>'date', 'id'=>'', 'name'=>'','label'=>'Expire');
// $fields[] = array('id'=>'', 'name'=>'','label'=>'File');
// $fields[] = array('id'=>'', 'name'=>'','label'=>'Upload');
$fields[] = array('id'=>'', 'name'=>'','label'=>'สถานที่ออก PP');
$fields[] = array('type'=>'date', 'id'=>'', 'name'=>'','label'=>'วันที่ออก PP', 'cls'=>'td-date');
$fields[] = array('id'=>'', 'name'=>'','label'=>'Remark');
// , 'plugin'=>'datepicker', 'pluginOpt'=>Fn::stringify(array('format'=>'short'))


$rooms = array();
$rooms[] = array('quota'=>2, 'id'=>'room_twin', 'label'=>'Twin');
$rooms[] = array('quota'=>2, 'id'=>'room_double', 'label'=>'Double');
$rooms[] = array('quota'=>3, 'id'=>'room_triple', 'label'=>'Triple');
$rooms[] = array('quota'=>3, 'id'=>'room_tripletwin', 'label'=>'Triple Twin');
$rooms[] = array('quota'=>1, 'id'=>'room_single', 'label'=>'Single');


?><style type="text/css">

	
	.traveler-wrap{
		/*border-radius: 3px;
		background-color: #fff;
		border:1px solid #ddd;*/



		/*padding: 10px*/
	}
	.table-traveler{
		/*background-color: #fff;*/
	}

	.table-traveler th, .table-traveler td{
		border: 1px solid ;
		border-color: #ccc #ddd;
		background-color: #fff;
	}
	.table-traveler th{
		font-size: 10px;
		white-space: nowrap;
		padding: 3px 4px;
		background-color: #dddddd;
		text-align: left;
	}
	.table-traveler td{
		padding: 2px 0
	}

	.table-traveler .td-input .inputtext{
		width: 100%;
		box-shadow: none;
		border-width: 0;
		font-size: 13px;
		height: auto;
	}

	.table-traveler .td-head{
		/*background-color: #eaeaea;*/
		background-color: transparent;
		padding: 6px 12px 2px;
		border-width: 0;
		font-weight: bold;
	}
	.table-traveler .td-seq{
		width: 10px;
		padding-left: 4px;
		padding-right: 4px;
		text-align: center;
		white-space: nowrap;
	}
	.table-traveler .td-date{
		width: 60px;
	}
	.table-fullname td{
		border-width: 0 1px;
		padding: 0
	}
	.table-fullname tr td:first-child{
		border-left-width: 0
	}
	.table-fullname tr td:last-child{
		border-right-width: 0
	}
	.table-traveler .td-radio{
		white-space: nowrap;
	}
	.table-traveler .td-radio .radio{
		margin: 5px;
	}

	.ui-state{
		border: 1px solid #ccc;
	    border-radius: 2px;
	    padding: 2px 2px 0;
	    background-color: #fff;
	}
	.ui-state li{
		display: inline-block;
		padding:6px 12px;
		float: left;
		position: relative;
	}
	.ui-state span{

	}
	.ui-state strong{
		display: block;
		font-size: 22px;
		text-align: right;
	}
	.ui-state strong span{
		font-size: 11px;
	}
</style>

<div class="traveler-wrap" style="padding: 20px;">
	
	<div class="clearfix">
		
		<ul class="ui-state clearfix">
			<li style="background-color: #1010da;color: #fff">
				<span>จำนวนผู้เดินทาง</span>
				<strong><?=$this->item['pax_total']?></strong>
			</li>

			<li style="background-color: #ddd">
				<span>อัปโหลดพาสปอร์ตแล้ว</span>
				<button type="button" class="btn btn-blue btn-small" style="position: absolute;bottom: 6px;left: 12px;width: 25px;height: 25px;border-radius: 50%;padding: 0;font-size: 12px" title="อัพโหลดไฟล์พาสปอร์ต"><i class="icon-upload"></i></button>
				<strong>0<span>/<?=$this->item['pax_total']?></span></strong>
			</li>

			<li style="background-color: #f0f0f0">
				<span>การพิมพ์ข้อมูล</span>
				<strong>0<span>/<?=$this->item['pax_total']?></span></strong>
			</li>

			<?php foreach ($rooms as $k => $v) { 

				$val = !empty($this->item[$v['id']]) ? $this->item[$v['id']]: 0;
			?>
				<li style="background-color: #fff">
					<span><?=$v['label']?></span>
					<strong><?=$val>0? number_format($val).'<span>/'.($val*$v['quota']).'</span>':'-' ?></strong>
				</li>

			<?php } ?>

			<li style="background-color: #fff7b1">
				<span>ชื่อลูกค้า</span>

				<strong><?=!empty($this->item['cus_name'])? $this->item['cus_name']:'-' ?></strong>
			</li>
			<li style="background-color: #fff7b1">
				<span>เบอร์โทรลูกค้า</span>
				<strong><a><?=!empty($this->item['cus_tel'])? $this->item['cus_tel']:'-' ?></a></strong>
			</li>

			
			<?php if( $this->item['pax_total'] > 0 ){ ?>
			<li style="float: right;">
				<button type="submit" class="btn btn-blue btn-jumbo">บันทีก</button>
			</li>
			<?php } ?>
		</ul>
	</div>
	<table class="table-traveler">		
			<?php 

			$_seq = 0;

			foreach ($rooms as $k => $v) {

				// 

				$val = !empty($this->item[$v['id']]) ? $this->item[$v['id']]: 0;

				$_room = 0;
				for ($room=0; $room < $val; $room++) { 
					$_room ++;

					echo '<tbody class="room-'.$v['id'].'">';
					echo '<tr><td class="td-head" colspan="'.count($fields).'">'.$v['label'].' ' .$_room .'</tr>';

					echo '<tr>';
					foreach ($fields as $key => $value) {
						echo '<th>'.$value['label'].'</th>';
					}

					echo '</tr>';

					for ($i=0; $i < $v['quota']; $i++) { 

						$_seq++; 



						echo '<tr>';
						foreach ($fields as $key => $value) {
							
							$is_input = true;
							$input = ''; $rowspan = '';

							$type = isset($value['type'])? $value['type']: 'text';

							if( $value['id']=='_seq' ){
								$input = $_seq;
							}
							elseif( $value['id']=='_room' ){

								if( $i==0 ){
									$rowspan = ' rowspan="'.$v['quota'].'"';

									$input = "{$v['label']} {$_room}";
								}
								else{
									$is_input = false;
								}
								// $input = $_seq;
							}
							else{

								$plugin = isset($value['plugin']) ? ' data-plugin="'.$value['plugin'].'"':'';
								if( !empty($value['pluginOpt']) ){
									$plugin .= ' data-options="'.$value['pluginOpt'].'"';
								}


								if( $type=='fullname' ){

									$input = '<table class="table-fullname"><tr>'.
										'<td style="width:50px"><input type="text" name="" class="inputtext" autocomplete="off" placeholder="Prefix"></td>'.
										'<td><input type="text" name="" class="inputtext" autocomplete="off" placeholder="First name"></td>'.
										'<td><input type="text" name="" class="inputtext" autocomplete="off" placeholder="Last name"></td>'.
									'</tr></table>';
								}
								elseif( $type=='radio' ){

									foreach ($value['items'] as $radio) {
										$input .= '<label class="radio"><input type="radio" name="" value="'.$radio['id'].'" autocomplete="off"><span>'.$radio['name'].'</span></label>';
									}
								}
								else{
									$input = '<input'.$plugin.' type="'.$type.'" name="" class="inputtext" autocomplete="off" _placeholder="'.$value['label'].'">';
								}

								
							}

							$cls = 'td-input';
							if( isset($value['cls']) ){
								$cls .= ' '.$value['cls'];
							}


							$cls = !empty($cls)? ' class="'.$cls.'"':'';
							if( $is_input ){
								echo '<td'.$rowspan.$cls.'>'.$input.'</td>';
							}
						}


						echo '</tr>';
					}

					echo '</tbody>';
				}

			}

			?>
		
	</table>

	<?php if( $_seq>0 ) { ?>
	<div class="clearfix mtl">
		<div class="rfloat">
			<button type="submit" class="btn btn-blue btn-jumbo">บันทีก</button>
		</div>
	</div>
	<?php } ?>

</div>
