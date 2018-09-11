<?php


echo '<div id="mainContainer" data-plugins="main">';

	echo '<div role="content"><div role="main"><div class="pal mhl">';
		
		echo '<div id="business-settings" class="admin-settings">';

			echo '<div class="settings-header clearfix">';
			  echo '<div class="settings-title">Business Info</div>';
			  echo '<div class="settings-suptitle">Provide visitors with essential information about your business.</div>';
			echo '</div>';

			echo '<div id="business-settings" class="content">';
				echo '<div class="boxes-container">';
					include_once 'sections/info.php';
					include_once 'sections/basic.php';
					include_once 'sections/address.php';
				echo '</div>';
				
			echo '</div>';
		echo '</div>';

		// 
	echo '</div></div></div>';

echo '</div>';