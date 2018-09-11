<?php

$this->pageURL = URL;
$this->permit = $this->getPage('auth');
$nav = $this->getPage('navigation');
if( empty($nav) ) $nav = array();


$image = '<div class="avatar lfloat no-avatar mrs"><div class="initials"><i class="icon-user"></i></div></div>';


echo '<div class="navigation-main-bg navigation-trigger"></div>';
echo '<a class="navigation-btn-trigger navigation-trigger"><span></span></a>';

echo '<nav class="navigation-main" role="navigation">';

// echo '<div class="navigation-main-header"><div class="anchor clearfix">'.$image.'<div class="content"><div class="spacer"></div><div class="massages"><div class="fullname">'.$this->me['name'].'</div><div class="subname">'.$this->me['role_name'].'</div></div></div></div></div>';


echo '<div class="navigation-main-content">';



foreach ($nav as $items) {
	
	$n = 0;
	foreach ($items as $key => $value) {
		if( is_array($value) ){
			if( empty($this->permit[$value['key']]['view']) ){
				unset($items[$key]);
			}
			else{ $n++; }
		}
	}

	if( !empty($items) && $n>0 ){
		echo $this->fn->manage_nav($items, $this->getPage('on'));
	}
}

echo '</div>';

	echo '<div class="navigation-main-footer">';


echo '<ul class="navigation-list">'.

	'<li class="clearfix">'.
		'<div class="navigation-main-footer-cogs">'.
			'<a data-plugins="lightbox" href="'.$this->pageURL.'logout/admin/"><i class="icon-power-off"></i><span class="visuallyhidden">Log Out</span></a>'.
			// '<a href="'.URL.'logout/admin"><i class="icon-cog"></i><span class="visuallyhidden">Settings</span></a>'.
		'</div>'.
		'<div class="navigation-brand-logo clearfix"><span class="fsss fcg">'.COPYRIGHT.'</span></div>'.
	'</li>'.
'</ul>';

echo '</div>';


echo '</nav>';
