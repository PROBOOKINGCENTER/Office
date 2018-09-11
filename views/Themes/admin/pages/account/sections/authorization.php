<?php

$url = '';

?><div id="account-authorization" class="admin-settings">

	<div class="settings-header clearfix">
	  <div class="settings-title">Roles & Permissions</div>
	</div>

	<div class="content">
		<table class="settings-table admin"><tbody>
			<tr>
				<th class="name"><?=Translate::Val('Name')?></th>
				<th class="status">บทบาท</th>
				<th class="date">เข้าสู่ระบบครั้งล่าสุด</th>
				<th style="width: 24px;"></th>
				<th style="width: 24px;"></th>
				<th style="width: 30px;"></th>
			</tr>

			<?php foreach ($this->data as $key => $item) { 


				if( !empty($item['is_owner']) ) continue;
			?>
			<tr data-id="<?=$item['id']?>">
				<td class="name"><?php

				echo '<div class="anchor clearfix"><div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div><div class="content"><div class="spacer"></div><div class="massages"><div class="fullname">';

				echo $item['name'];
				/*if( empty($item['is_owner']) || $item['id']==$this->me['id'] ){
					echo '<a href="'.$url.'edit/'.$item['id'].'" data-plugins="dialog">'.$item['name'].'</a>';
				}
				else{
					echo $item['name'];
				}*/

				echo ' <span class="subname fwb fcg">@'.$item['login'].'</span>';

				echo '</div> </div></div></div>';

				?></td>

				<td class="status"><?php 



					echo '<select class="inputtext"'.(!empty($item['is_owner'])? ' disabled':'').' data-action="selector" name="user_role_id">';

					foreach ($this->roles as $val) {

						if( empty($item['is_owner']) && empty($val['display']) ) continue;

						$see = $item['role_id']==$val['id'] ? ' selected':'';
						echo '<option'.$see.' value="'.$val['id'].'">'.$val['name'].'</option>';
					}

					echo '</select>';

				?></td>

				<td class="date"><?php

					if( !empty($item['lastvisit']) ){

						echo $this->fn->q( 'time' )->stamp($item['lastvisit']);
					}
				?></td>

				<td><a class="fcg link-hover-opacity" href="<?=$url?>change_password/<?=$item['id']?>" data-plugins="dialog"><img  src="<?=IMAGES?>reset-password-24.svg"></a></td>
				<td><a class="fcg link-hover-opacity" href="<?=$url?>edit/<?=$item['id']?>" data-plugins="dialog"><img src="<?=IMAGES?>edit-person-24.svg"></a></td>
				<td class="whitespace">
					<?php

					$dropdown = array();

					if( empty($item['is_owner']) ){

						if( !empty($item['enabled'])  ){
							$dropdown[] = array(
				                'text' => 'ปิดการใช้งาน',
				                'href' => $url.'change_enabled/'.$item['id'].'/0',
				                'attr' => array('data-plugins'=>'dialog'),
				                // 'icon' => 'remove'
				            );
						}
						else{
							$dropdown[] = array(
				                'text' => 'เปิดการใช้งาน',
				                'href' => $url.'change_enabled/'.$item['id'].'/1',
				                'attr' => array('data-plugins'=>'dialog'),
				                // 'icon' => 'remove'
				            );
						}
						

						$dropdown[] = array(
			                'text' => Translate::Val('Delete'),
			                'href' => $url.'del/'.$item['id'],
			                'attr' => array('data-plugins'=>'dialog'),
			                // 'icon' => 'remove'
			            );

		            }

		            if( !empty($dropdown) ){
		            
						echo '<a data-plugins="dropdown" class="btn btn-no-padding" data-options="'.$this->fn->stringify( array(
	                        'select' => $dropdown,
	                        'settings' =>array(
	                            'axisX'=> 'right',
	                            'parent'=>'.setting-main'
	                        ) 
	                    ) ).'"><i class="icon-ellipsis-v"></i></a>';

					}


					?>
						
				</td>

			</tr>
			<?php } ?>
		</tbody></table>
	</div>
</div>