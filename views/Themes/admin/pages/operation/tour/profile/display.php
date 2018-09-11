<section id="profile" class="section has-leftCol clearfix" data-plugin="main2" data-options="<?=Fn::stringify( $this->opt )?>">
	
	<aside class="section-side-left">
		
		<div class="profile-left-header" style="padding:0 0 0;">
		
	        <div class="avatar" style="width:260px;height:260px;background-color: #b8b8b8">
	        	<?php

	        	if( !empty($this->item['image_url']) ){
	        		echo '<img src="'.$this->item['image_url'].'">';
	        	}
	        	?>
	        </div>
		</div>

	    <div class="profile-left-content" style="padding-top: 0">
		    <div class="profile-left-main" style="">
			
		   		<section >
			        <table class="table-dataInfo">
			            <tbody>
			            <tr>
			                <td class="label">Code:</td>
			                <td class="data">
			                    <div class=""><span data-profile="code" class="top-code" style="overflow: hidden;text-overflow: ellipsis;max-width: 100px;white-space: nowrap;"><?=$this->item['code']?></span></div>
			                </td>
			            </tr>

			            <tr>
			                <td class="label"><?=Translate::Val('Status')?>:</td>
			                <td class="data">

			                	<?php
						            $dropdownList = array();

						            if( $this->item['status'] == 0 ){

						            	$dropdownList[] = array(
							                'text' => 'เปิดการใช้งาน',
							                'href' => URL.'tour/status/',
							                'attr' => array('data-plugin'=>'lightbox'),
							            );
						            }
						            

						           /* $dropdownList[] = array(
						                'text' => 'ปิดการใช้งาน',
						                'href' => URL.'tour/files/pdf/'.$this->item['id'].'/del/',
						                'attr' => array('data-plugin'=>'lightbox'),
						            );*/

						            echo '<div class="group-btn">';

							            echo '<button class="btn btn-small" style="background-color:'.$this->item['status_background'].';color:#fff"><span class="fwn">'.$this->item['status_name'].'</span></button>';
							            if( !empty($dropdownList) ){
							            	echo '<button data-plugin="dropdown2" class="btn btn-small" style="background-color:'.$this->item['status_background'].';color:#fff;width: 20px;padding: 0;" data-options="'.Fn::stringify( array( 'select' => $dropdownList, 'axisX'=> 'right', 'container'=> '.profile-left-main', ) ).'"><i class="icon-ellipsis-v"></i></button>';
							            }
						            echo '</div>';
						        ?>
			                    <!-- <label class="switch t"><input type="checkbox" name="status" <?=$this->item['status_id']==1 ? ' checked': '' ?>><span class="slider"></span></label> -->
			                </td>
			            </tr>

			            <tr>
			                <td class="label"><?=Translate::Val('Country')?>:</td>
			                <td class="data">
			                    <span class="" style="vertical-align: top;display: inline-block;"><span data-profile="country_name"><?=$this->item['country_name']?></span></span> <span data-profile="flag" data-type="flag" style="vertical-align: top;display: inline-block;"></span>
			                </td>
			            </tr>

			            <tr>
			                <td class="label"><?=Translate::Val('City')?>:</td>
			                <td class="data">
			                    <span class="" style="vertical-align: top;display: inline-block;"><span data-profile="city_name"><?=$this->item['city_name']?></span></span> <span data-profile="flag" data-type="flag" style="vertical-align: top;display: inline-block;"></span>
			                </td>
			            </tr>

			            <tr>
			                <td class="label"><?=Translate::Val('Airline')?>:</td>
			                <td class="data">
			                    <div><span class="fss" data-profile="air_name"><?=$this->item['air_name']?></span></div>
			                </td>
			            </tr>

			            <tr>
			                <td class="label">Created Date:</td>
			                <td class="data"><span data-profile="create_date_str"><?=$this->item['create_date_str']?></span><div class="check-hide" style="font-size: 11px;">by <a data-profile="create_by_name"><?=$this->item['create_by_name']?></a></div></td>
			            </tr>

			            <tr>
			                <td class="label">Last Update:</td>
			                <td class="data"><span data-profile="update_date_str"><?=$this->item['update_date_str']?></span><div class="check-hide" style="font-size: 11px;">by <a data-profile="update_by_name"><?=$this->item['update_by_name']?></a></div></td>
			            </tr>

			        </tbody></table>
			    </section>

		    </div>
	    <!-- end: main -->

		    <div class="profile-left-footer gb-btn-block">

		    	<a type="button" class="btn btn-blue" data-plugin="lightbox" href="<?=URL?>tour/period/add/<?=$this->item['id']?>"><i class="icon-plus mrs"></i>Create Period</a>

		        <?php
		            $dropdownList = array();
		            $dropdownList[] = array(
		                'text' => 'อัพโหลดเวอร์ชันใหม่',
		                'href' => URL.'tour/files/word/'.$this->item['id'].'/revision/',
		                'attr' => array('data-plugin'=>'lightbox'),
		            );

		            $dropdownList[] = array(
		                'text' => 'ลบ',
		                'href' => URL.'tour/files/pdf/'.$this->item['id'].'/del/',
		                'attr' => array('data-plugin'=>'lightbox'),
		            );

		        ?>

		        <span class="btn-group">
		            <a type="button" class="btn" target="_blank" href="<?=DOWNLOAD?>document/word/<?=$this->item['id']?>"><i class="icon-file-word-o mrs"></i><span>ดาวน์โหลด Word</span></a>
		            <button type="button" data-plugin="dropdown2" class="btn" style="width:30px" data-options="<?=Fn::stringify( array( 'select' => $dropdownList, 'axisX'=> 'right', 'container'=> '.profile-left-footer', ) )?>"><i class="icon-ellipsis-v"></i></button>
		        </span>
		        

		        <?php
		            $dropdownList = array();
		            $dropdownList[] = array(
		                'text' => 'ดาวน์โหลด',
		                'href' =>  DOWNLOAD.'document/pdf/'.$this->item['id'],
		                'attr' => array('target'=>'_blank'),
		            );

		            $dropdownList[] = array(
		                'text' => 'อัพโหลดเวอร์ชันใหม่',
		                'href' => URL.'tour/files/pdf/'.$this->item['id'].'/revision/',
		                'attr' => array('data-plugin'=>'lightbox'),
		            );

		            $dropdownList[] = array(
		                'text' => 'ลบ',
		                'href' => URL.'tour/files/pdf/'.$this->item['id'].'/del/',
		                'attr' => array('data-plugin'=>'lightbox'),
		            );

		        ?>
		        <span class="btn-group">
		            <a type="button" class="btn" target="_blank" href="<?=DOCS?>tour/pdf/<?=$this->item['id']?>"><i class="icon-file-pdf-o mrs"></i><span>PDF</span></a>
		            <button data-plugin="dropdown2" class="btn" style="width:30px" data-options="<?=Fn::stringify( array( 'select' => $dropdownList, 'axisX'=> 'right', 'container'=> '.profile-left-footer', ) )?>"><i class="icon-ellipsis-v"></i></button>
		        </span>


		        <?php
		            $dropdownList = array();
		            $dropdownList[] = array(
		                'text' => 'ดาวน์โหลด',
		                'href' => DOWNLOAD.'document/banner/'.$this->item['id'],
		                'attr' => array('target'=>'_blank'),
		            );

		            $dropdownList[] = array(
		                'text' => 'อัพโหลดเวอร์ชันใหม่',
		                'href' => URL.'tour/files/banner/'.$this->item['id'].'/revision/',
		                'attr' => array('data-plugin'=>'lightbox'),
		            );

		            $dropdownList[] = array(
		                'text' => 'ลบ',
		                'href' => URL.'tour/files/pdf/'.$this->item['id'].'/del/',
		                'attr' => array('data-plugin'=>'lightbox'),
		            );

		        ?>
		        <span class="btn-group">
		            <a type="button" class="btn" target="_blank" href="<?=PHOTO.'tour/banner/'.$this->item['id']?>"><i class="icon-image mrs"></i><span>แบนเนอร์</span></a>
		            <button data-plugin="dropdown2" class="btn" style="width:30px" data-options="<?=Fn::stringify( array( 'select' => $dropdownList, 'axisX'=> 'right', 'container'=> '.profile-left-footer', ) )?>"><i class="icon-ellipsis-v"></i></button>
		        </span>


		        <hr>
		        
		        <!-- <a type="button" class="btn" data-plugin="lightbox" href="<?=URL?>tour/clone/<?=$this->item['id']?>"><i class="icon-clone mrs"></i><span><?=Translate::Val('Clone Serie')?></span></a>
		        <hr> -->
		        
		        <a type="button" class="btn btn-red" data-plugin="lightbox" href="<?=URL?>tour/del/<?=$this->item['id']?>"><i class="icon-trash-o mrs"></i><?=Translate::Val('Delete')?></a>

		        
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


<script type="text/javascript">
	
	function refreshProfile( d ) {
		
		var self = $('#profile').data('main2');
		if( !self ) return false;


		var actionTab = d.actionTab || "period";

		var tab = self.$el.$tabs.find('[data-tab-action='+ actionTab +']');
		// var $tab = 
		// var active = '';


		if( tab.length==1 ){

			if( tab.parent().hasClass('active') ){
				self.updateTap( self.tab.current, tab.attr('href'), tab.attr('title') );
			}
			else{
				tab.trigger('click');
			}
		}


		
		// console.log('refreshProfile:', d);
	}
</script>