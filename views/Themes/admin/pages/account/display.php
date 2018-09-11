<?php


echo '<div id="mainContainer" data-plugins="main">';

	echo '<div role="content"><div role="main"><div class="pal mhl">';
		
		if( !empty($this->section) ){
			require_once "sections/{$this->section}.php";
		}

	echo '</div></div></div>';

echo '</div>';

