<?php

$btns = array();
$btns[] = array('icon'=>'print','text'=>'Print Invoice', 'link'=>URL."booking/cancel/{$this->item['id']}", 'plugin'=> 'lightbox', 'cls'=>'btn-green');
$btns[] = array('icon'=>'paper-plane-o','text'=>'Send Invoice', 'link'=>URL."booking/send_invoice/{$this->item['id']}", 'plugin'=> 'lightbox', 'cls'=>'btn-green');
$btns[] = array('divider'=>1);
$btns[] = array('text'=>'แจ้งการชำระเงิน', 'link'=>URL."booking/payment/{$this->item['id']}", 'plugin'=> 'lightbox', 'cls'=>'btn-blue');
$btns[] = array('divider'=>1);
$btns[] = array('text'=>'ยกเลิกการจอง', 'link'=>URL."booking/cancel/{$this->item['id']}", 'plugin'=> 'lightbox', 'cls'=>'btn-red');


?>
<div style="padding: 20px;max-width: 1336px">

	<div class="row-fluid clearfix mbl">

		<div class="span10">
			<!-- form -->
			<form class="form-booking" method="post" action="<?=URL?>booking/update">
				<input class="hiddenInput" type="hidden" autocomplete="off" name="id" value="<?=$this->item['id']?>">
				<input class="hiddenInput" type="hidden" autocomplete="off" name="ser_id" value="">
				<input class="hiddenInput" type="hidden" autocomplete="off" name="per_id" value="<?=$this->item['per_id']?>">
				<input class="hiddenInput" type="hidden" autocomplete="off" name="bus_no" value="<?=$this->item['bus_no']?>">
				<input class="hiddenInput" type="hidden" autocomplete="off" name="token" value="">

				<div style="background-color: #fff"><?php

				echo $this->fn->q('booking')->form( $this->settingForm );

				?></div>
					
				<div class="clearfix pam" style="border-top:1px solid #ccc">
					<!-- <div class="lfloat">
						<button type="submit" class="btn btn-red btn-jumbo" href="<?=URL?>booking/cancel/<?=$this->item['id']?>" data-plugin="lightbox">ยกเลิก</button>
					</div> -->
					<div class="rfloat">
						<span class="mrm"><label class="checkbox"><input type="checkbox" name="book_is_guarantee" value="1"<?=!empty($this->item['is_guarantee'])? ' checked':'' ?>><span class="mls">การันตี</span></label></span>
						<button type="submit" class="btn btn-blue btn-jumbo">บันทึก</button>
					</div>
				</div>
			</form>
			<!-- end: form -->
		</div>

		<div class="span2">
			

			<?php 
			if( $this->item['status']!=40 ) { 

				echo '<ul class="mbl ui-list-btn">';

				foreach ($btns as $key => $value) {
					
					$li = '';

					if( isset($value['divider']) ){
						$li = '<li class="divider"></li>';
					}
					else{

						$cls = 'btn';
						if( !empty($value['cls']) ){
							$cls .= " {$value['cls']}";
						}

						$plugin = '';
						if( !empty($value['plugin']) ){
							$plugin .= " data-plugin=\"{$value['plugin']}\"";
						}

						$link = '';
						if( !empty($value['link']) ){
							$link .= " href=\"{$value['link']}\"";
						}


						$li = '<li><a class="'.$cls.'"'.$link.$plugin.'>'.$value['text'].'</a></li>';
					}

					echo $li;
				}

				echo '</ul>';

			} ?>


            <div class="mbl">
            	<ul class="ui-list-timeline">
            		<?php for ($i=0; $i < 3; $i++) { ?>
            		<li>
            			
        				<div class="meta fcg fsm"><?=date('j M Y H:i', strtotime($this->item['cancel_date']))?> - ชง</div>
        				<div class="post">Booking</div>
            			
            			<!-- <header>
            				<div class="anchor anchor32 clearfix"><div class="avatar lfloat size32 no-avatar mrs"><div class="initials"><i class="icon-user"></i></div></div><div class="content"><div class="spacer"></div><div class="massages"><div class="tal">เปิ้ล</div><div class="whitespace fcg fss tal">กมลชนก   มนตรี</div></div></div></div>
            			</header> -->
            		</li>
            		<?php } ?>
            	</ul>
            </div>
		</div>
	</div>
	<!-- end: row -->
	
</div>