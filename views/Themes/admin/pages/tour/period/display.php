<style type="text/css">
	.section.has-leftCol .section-main{
		/*margin-left: 220px;*/
		width: calc(100% - 220px);
	}
	.section-side-left{
		width: 220px;
		transition: left .3s;
		border-right: 2px solid #fff;
		height: calc(100vh - 54px);
		float: left;
		/*position: fixed;*/
		/*background-color: #f2f2f2*/
	}

	.section.has-leftCol {
		display: flex;
	}

	#profile.section.has-leftCol .section-main{
		width: calc(100% - 262px);
	}
	#profile .section-side-left{
		width: 262px;
	}

</style>

<section id="profile" class="section has-leftCol clearfix" data-plugin="main2" data-options="<?=Fn::stringify( $this->opt )?>">
	
	<aside class="section-side-left">
		
		<div class="profile-left-header" style="padding:0 0 0;">
		
	        <div class="avatar" style="width:260px;height:260px;background-color: #b8b8b8">
	            <!-- <img src="<?=UPLOADS?>download/document/banner/<?=$this->tour['id']?>"> -->
	        </div>
		</div>

	    <div class="profile-left-content" style="padding-top: 0">
		    <div class="profile-left-main" style="">
			
		    <?php 

		    $lists = array();

		    $a = array();
		    $a[] = array('id'=>'code', 'name'=>'Code');
		    $a[] = array('id'=>'country_name', 'name'=>Translate::Val('Country'));
		    $a[] = array('id'=>'city_name', 'name'=>Translate::Val('City'));
		    $a[] = array('id'=>'air_name', 'name'=>Translate::Val('Airline'));
		    $lists[] = array('title'=>'ข้อมูลซี่รีย์', 'items'=> $a, 'model'=>$this->tour);


		    $a = array();
		    $a[] = array('id'=>'date_str', 'name'=>'วันเดินทาง');
		    $a[] = array('id'=>'bus_str', 'name'=>'Bus');
		    $a[] = array('id'=>'bus_str', 'name'=>'จำนวนที่นั่ง');
		    $a[] = array('id'=>'bus_str', 'name'=>'ราคา');
		    $a[] = array('id'=>'bus_str', 'name'=>'คอมมิชชั่น');
		    $a[] = array('id'=>'bus_str', 'name'=>'สถานะ');
		    $lists[] = array('title'=>'ข้อมูลพีเรียด', 'items'=> $a, 'model'=>$this->item);


		    $a = array();
		    $a[] = array('id'=>'date_str', 'name'=>'ยอดเงินรวม');
		    $a[] = array('id'=>'bus_str', 'name'=>'ยอดเงินที่ได้รับ');
		    $a[] = array('id'=>'bus_str', 'name'=>'คงเหลือ');
		    $lists[] = array('title'=>'การชำระเงิน', 'items'=> $a, 'model'=>$this->item);


		    foreach ($lists as $key => $value) {

		        echo '<section class="profile-left-section">';

		            echo isset($value['title']) ? '<h3>'.$value['title'].'</h3>' : '';

		            echo '<article>';

		            echo '<table class="table-dataInfo"><tbody>';

		            foreach ($value['items'] as $i => $val) {
		                    
		                echo '<tr>'.
		                    '<td class="label">'.$val['name'].'</td>'.
		                    '<td class="data">'.( !empty($value['model'][$val['id']]) ? $value['model'][$val['id']]: '-' ).'</td>'.
		                '</tr>';
		            }


		            echo '</tbody></table>';

		            echo '</article>';

		        echo '</section>';
		    }

		    ?>

		    </div>
	    <!-- end: main -->


		    <div class="profile-left-footer gb-btn-block" style="margin:0 0;padding-bottom: 20px">
		        
		        <a type="button" class="btn btn-green" target="_blank" href="http://admin.probookingcenter.com/admin/report/export_roomlist.php?per_id=<?=$this->item['id']?>&bus_no=<?=$this->item['bus']['no']?>"><i class="icon-print mrs"></i><span>ข้อมูลผู้เดินทาง</span></a>

		        <a type="button" class="btn btn-green" target="_blank" href="http://admin.probookingcenter.com/admin/print/export_tag_bag.php?per_id=<?=$this->item['id']?>&bus_no=<?=$this->item['bus']['no']?>"><i class="icon-print mrs"></i><span>Tag กระเป๋า</span></a>


		        <a type="button" class="btn btn-green" target="_blank" href="http://admin.probookingcenter.com/admin/print/immigration_form_japan.php?per_id=<?=$this->item['id']?>&bus_no=<?=$this->item['bus']['no']?>"><i class="icon-print mrs"></i><span>ใบต.ม.</span></a>

		        <a type="button" class="btn btn-green" target="_blank" href="http://admin.probookingcenter.com/admin/print/declare_japan.php?per_id=<?=$this->item['id']?>&bus_no=<?=$this->item['bus']['no']?>"><i class="icon-print mrs"></i><span>ใบดีแคร์</span></a>

		    </div>

	    </div>
	</aside>

	<div class="section-main" role="main">

		<header class="section-header" role="header">
			<div class="inner">
				<!-- toolbar -->
				<div class="toolbar clearfix">
					<h1 class="title lfloat"><i class="icon-<?=$this->listOpt['icon']?> mrs"></i><?=$this->opt['title']?></h1>
					<?php 
					if( !empty($this->opt['controls']) ){

						echo '<nav class="rfloat"><ul class="nav tb-controls">';
						foreach ($this->opt['controls'] as $key => $value) {
							echo '<li class="control-item">'.$value.'</li>';
						}
						echo '</ul></nav>';
					}
					?>
				</div>
				<!-- end: toolbar -->
			</div>
			<?php if( !empty($this->opt['tab']['items']) ) { ?>
			<nav class="section-tab" role="tabs">
				<ul class="nav"><?php

				foreach ($this->opt['tab']['items'] as $key => $value) {

					// $active = $this->tab==$value['id'] ? ' class="active"':'';
					echo '<li><a data-tab-action="'.$value['id'].'" title="'.$value['name'].'" href="'.$value['link'].'">'.$value['name'].'</a></li>';
				}

				?></ul>
			</nav>
			<?php } ?>
		</header>

		<div class="section-content" role="content"></div>

		<div class="section-alert">
			<div class="section-loading"><div class="loader-spin-wrap"><div class="loader-spin"></div></div><p>Loading...</p></div>
			<div class="section-empty">
				<div class="empty-icon"><i class="icon-check-square-o"></i></div>
	        	<div class="empty-title">No Results Found.</div>
			</div>
			<div class="section-error">Don't connected, <a type="button" data-action="tryagain">Try again</a></div>
			<div class="section-more"><a type="button" class="btn btn-large" data-action="more">More</a></div>
		</div>
	</div>


</section>