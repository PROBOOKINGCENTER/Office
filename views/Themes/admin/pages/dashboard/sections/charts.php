<section class="dashboard-charts pvm phl">

	<div class="page-title pvm">
		<h1 class="title"><i class="icon-area-chart mrs"></i>Dashboard</h1>
	</div>

	<div class="clearfix card-outer" style="margin: -10px;margin-bottom: 15px;"><?php
		foreach ($cardList as $key => $value) {

			$cls = '';

			if( isset($value['bg']) ){
				$cls .= " card-{$value['bg']}";
			}

			$ico = '';
			if( isset($value['icon']) ){
				$cls .= " has-icon";
				$ico = '<i class="stat-ico icon-'.$value['icon'].'"></i>';
			}
			
			echo '<div class="card card-col-4'.$cls.'"><div class="inner">'.
				'<div class="stat clearfix pal">'.
					$ico.
					'<div class="stat-message">'.
						'<div class="stat-title">'.$value['name'].'</div>'.
						'<div class="stat-value">'.$value['value'].'</div>'.
					'</div>'.
				'</div>'.


			'</div></div>';

		}
	?></div>


	<div class="card" style="margin-bottom: 25px;">
		<div class="inner pal">
			<div data-plugin="charts" data-options="<?=Fn::stringify( $this->incomeYearlyOpt )?>"></div>
		</div>
	</div>


	<div class="clearfix card-outer" style="margin: -10px;margin-bottom: 15px;"><?php
		foreach ($chartsList as $key => $opt) {

			echo '<div class="card card-col-3"><div class="inner pal">'.
				'<div class="clearfix" data-plugin="charts" data-options="'.Fn::stringify( $opt ).'"></div>'.
			'</div></div>';

		}
	?></div>
	</section>