<?php 

$this->topnav = $this->getPage('topnav');
if( empty($this->topnav) ) $this->topnav = array();


$pageNav = '';
foreach ($this->topnav as $key => $value) {

	if( $value=='divider' ){

		$pageNav .= '<li class="divider"></li>';
		continue;
	}


	$type = isset($value['type']) ? $value['type']: '';


	if( isset($value['html']) ){
		$pageNav .= '<li>'.$value['html'].'</li>';
	}
	else{

		$cls = isset($value['cls']) ? $value['cls']: '';

		if( isset($value['id']) ){
			if( $this->getPage('on')==$value['id'] ){
				$cls .= !empty($cls) ? ' ':'';
				$cls .= 'active';
			}
		}
		
		$countVal = '';
		if( !empty($value['count']) ){
			$cls .= !empty($cls) ? ' ':'';
			$cls .= 'hasCount';

			$countVal = $value['count'];
		}

		$cls = !empty($cls) ? ' class="'.$cls.'"':'';

		// href="'.$value['url'].'"
		$dialog = '';
		if( isset($value['plugin']) ){
			$dialog =' data-plugin="'.$value['plugin'].'"';
		}

		$target = isset($value['target']) ? ' target="'.$value['target'].'"' : '';
		$icon = isset($value['icon']) ? ' <i class="icon-'.$value['icon'].' mrs"></i>' : '';


		
		$link_cls = isset($value['link_cls']) ? ' class="'.$value['link_cls'].'"': '';
		
		$pageNav .= '<li'.$cls.'><a'.$link_cls.' href="'.$value['link'].'"'.$dialog.$target.'>'.$icon.'<strong>'.$value['text'].'</strong>'.'<span class="mls countVal">'.$countVal.'</span>'.'</a></li>';
	}
}


$pageNavR = '';

/*$pageNavR .= '<li class="headerClock a2 tar fwb">'.
	'<div class="headerClock-inner" data-plugins="oclock" data-options="'.$this->fn->stringify( array('lang'=>$this->lang->getCode() ) ).'">'.
		'<div ref="time" class="time fullname"></div>'.
		'<div ref="date" class="date subname"></div>'.
	'</div>'.
'</li>';*/

// $pageNavR .= '<li class="divider a2"></li>';

// $pageNavR .= '<li class="a2"><a class="btn btn-green" href="'.URL.'tour"><i class="icon-check-square-o mrs"></i><span>Booking Now</span></a></li>';
// $pageNavR .= '<li><a class="link fwb">Explore</a></li>';



$imageAvatar = '';
if( !empty($this->me['image_url']) ){
	$imageAvatar = '<div class="avatar lfloat size32 headerAvatar mrs"><img class="img" src="'.$this->me['image_url'].'"></div>';
	$imageAvatarBig = '<div class="avatar lfloat headerAvatar mrm"><img class="img" src="'.$this->me['image_url'].'"></div>';
}
else{
	$imageAvatar = '<div class="avatar lfloat size32 no-avatar mrs"><div class="initials"><i class="icon-user"></i></div></div>';
	$imageAvatarBig = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
}

$pageNavR .= '<li class="uiToggle headerAvatarWrap a2">'.
    '<a data-plugins="toggleLink" class="anchor anchor32 clearfix link">'.$imageAvatar.'<div class="content"><div class="spacer"></div><div class="massages">'.
    	'<div class="fullname">'.$this->me['name'].'</div>'.
    	// '<div class="subname">'.$this->me['role_name'].'</div>'.
    '</div></div></a>'.

    '<div class="uiToggleFlyout uiToggleFlyoutRight uiToggleFlyoutPointer" id="accountSettingsFlyout"><ul role="menu" class="uiMenu">'.

        // '<li class="menuItem head"><a class="itemAnchor" href="'.URL.'account/settings"><span class="itemLabel"><div class="clearfix"><div class="anchor"><div class="clearfix">'.$imageAvatarBig.'<div class="content"><div class="spacer"></div><div class="massages"><div class="fullname">'.$this->me['fullname'].'</div>'.$this->me['role_name'].'</div></div></div></div></div></span></a></li>'.

        /*<li class="menuItemDivider" role="separator"></li>
        <li class="menuItem"><a class="itemAnchor" href="http://localhost/events/manage/index.php"><span class="itemLabel">จัดการระบบ</span></a></li>*/
        // '<li class="menuItemDivider" role="separator"></li>'.
        
        
        // '<li class="menuItem"><a class="itemAnchor" href="'.URL.'allactivity"><span class="itemLabel">'.Translate::Val('Activity Log').'</span></a></li>'.
        '<li class="menuItem"><a class="itemAnchor" href="'.URL.'account/settings"><span class="itemLabel">'.Translate::Val('Settings').'</span></a></li>'.

        '<li class="menuItem"><a class="itemAnchor" data-plugins="lightbox" href="'.URL.'logout/admin"><span class="itemLabel">'.Translate::Val('Log Out').'</span></a></li>'.
    '</ul></div>'.

'</li>';
// $pageNavR .= '<li><a class="link" data-global-action="bell"><i class="icon-bell-o"></i></a></li>';


$pageTitle = $this->getPage('pageTitle');
$pageImage = $this->getPage('pageImage');
// $icon = $this->getPage('icon');
// $breadcrumps = $this->getPage('breadcrumps');

/*if( empty($pageTitle) ){
	$pageTitle = $this->getPage('title');
}
*/



echo '<div id="page-topbar" class="page-topbar">';
	
	// if( !empty($pageImage) ){
		echo '<h1 class="brand-logo topbar-etched"><img src="'.IMAGES.'logo/top-logo.svg" alt=""><span class="visuallyhidden"></span></h1>';
	// }
				
	// hreder
	echo '<div class="global-nav clearfix">';
			
			

			echo '<div class="global-nav-left'.(!empty($breadcrumps)? ' has-breadcrump':'').'">';

				

				if( !empty($breadcrumps) ){
					$breadcrumpLi = '<li><a><i class="icon-home"></i></a></li>';
					foreach ($breadcrumps as $key => $value) {
						$tap = isset($value['link']) ? 'a':'span';
						$href = isset($value['link']) ? ' href="'.$value['link'].'"':'';

						$breadcrumpLi .= '<li><'.$tap.$href.'>'.$value['text'].'</'.$tap.'></li>';
					}

					echo '<nav class="global-breadcrumps"><ul>'.$breadcrumpLi.'</ul></nav>';

				}
				
				if( !empty($pageTitle) ){
					echo '<h1 class="global-title">'.( !empty($icon)? '<i class="icon-'.$icon.' mrs"></i>':'' ).'<span class="title">'.$pageTitle.'</span></h1>';
				}

				if( !empty($pageNav) ){
					echo '<ul class="nav clearfix">'.$pageNav.'</ul>';
				}

			echo '</div>';

			echo '<div class="global-nav-rigth clearfix rfloat"><ul class="nav">'.$pageNavR.'</ul></div>';

	echo '</div>';


	// $toolbar = $this->getPage('toolbar');
	if( !empty($toolbar) ){

		$li = '';
		foreach ($toolbar as $key => $value) {

			$cls = ''; $sub = '';

			$tab = !empty($this->section) ? $this->section: '';

			if( isset($value['sub']) ){

				$sub_li = '';
				foreach ($value['sub'] as $i => $val) {

					$sub_cls = '';
					if( !empty($val['key']) ){
						if( $tab==$val['key'] ){
							$sub_cls .= !empty($sub_cls)? ' ':'';
							$sub_cls .= 'active';
						}
					}

					$href = isset($val['link']) ? ' href="'.$val['link'].'"':'';
					$sub_cls = !empty($sub_cls) ? ' class="'.$sub_cls.'"':'';
					$sub_li .= '<li'.$sub_cls.'><a'.$href.' class="">'.$val['text'].'</a></li>';
				}

				$sub = '<ul class="page-toolbar-sub">'.$sub_li.'<ul>';

				$cls = 'has-sub';

				if( isset($this->tab) ){
					$tab = $this->tab;
				}
			}


			$tap = isset($value['link']) ? 'a':'span';
			$href = isset($value['link']) ? ' href="'.$value['link'].'"':'';

			if( !empty($value['key']) ){
				if( $tab==$value['key'] ){
					$cls .= !empty($cls)? ' ':'';
					$cls .= 'active';
				}
			}
			
			$cls = !empty($cls) ? ' class="'.$cls.'"':'';
			$li .= '<li'.$cls.'><'.$tap.$href.' class="page-toolbar-link">'.$value['text'].'</'.$tap.'>'.$sub.'</li>';
		}

		echo '<nav class="page-toolbar-wrap">';

			echo '<ul class="page-toolbar-tabs">'.$li.'</ul>';
			// echo '<span class="page-toolbar-active-bar"></span>';
		echo '</nav>';
	}

echo '</div>';

// echo '<div id="nprogress"><div class="bar" role="bar" style="transform: translate3d(-0.6%, 0px, 0px); transition: all 500ms ease;"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div></div>';