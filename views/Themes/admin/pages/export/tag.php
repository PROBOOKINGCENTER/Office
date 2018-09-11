<?php

function item($data)
{
	return '<td class="v"><table class="outer">'.

		/*  */
		'<tr class="top"><td style="padding: 0">'.

			'<table class="pon" style="background: #fff;"><tr>'.
				'<td class="bg number">'.$data.'</td>'.
				'<td class="traveler">'.
					'<div class="txt f-en">Puchong TourDD Monkey</div>'.
					'<div class="txt">ขณิณฐา ดาทาพาลงรถไฟ</div>'.
				'</td>'.
			'</tr></table>'.
		'</td></tr>'.


		/* mate */
		'<tr><td class="hr bg" style="padding: 2px"></td></tr>'.

		'<tr><td class="title title fc-blue fwb">JMM01A พม่า-มัณฑะเลย์-มิงกุน-อมรปุระ 3วัน2คืน</td></tr>'.

		'<tr><td style="padding: 0;background: #fff;">'.
			'<table style="background: #fff;"><tr>'.
				'<td style="width: 120px;padding-left: 4px"><img class="img-flag" src="'.IMAGES.'demo/9.png"></td>'.
				'<td style="padding: 10px">19-21 Aug 2017</td>'.
				'<td style="width: 48px;"><img class="img-airline" src="'.IMAGES.'demo/AirAsia_New_Logo.png"></td>'.
			'</tr></table>'.

		'</td></tr>'.
		
		'<tr><td class="title title fc-blue fwb">FD244(11.10-12.25) - FD245(12.55-15.10)</td></tr>'.
		'<tr><td class="hr bg" style="padding: 10px"></td></tr>'.
	
	'</table></td>';
}


?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Demo</title>

	<style type="text/css">
		body{font-size:13px;line-height:1.28}
		table{width:100%;border-spacing:0;border-collapse:collapse;}
		/*.table{width:100%;border-spacing:0;border-collapse:collapse}*/
		td{padding: 0;vertical-align:middle;text-align: center;font-weight: bold;}


		.table td.v{width: 50%;padding:14px 8px 11px 6px}
		.table tr.first td.v{padding-top: 0}
		.table tr.last td.v{padding-bottom: 0}

		/*.header td{padding: 10px}*/
		.bg{background-color: #000032;} /**/
		.bg{background-color: #e32526} /**/
		.bg{background-color: #f19dd8} /*แดง*/
		.bg{background-color: #9C27B0} /*ม่วง*/
		.bg{background-color: #f6921e} /*ส้ม*/
		.bg{background-color: #4413e6} /*ส้ม*/

		.outer .inner{padding: 2px;border-width: 0}

		.lists>.item{width: 50%;display: inline-block;}
		.hr{padding: 5px}
		.txt{padding:4px 0}
		.title{background-color: #f2f2f2; padding: 5px;}
		.traveler{padding: 5px 12px;text-align:center;font-size: 18;font-weight: bold;}

		.fc-blue{color:#4200b7}
		.fc-red{color:#c52d22}
		.fc-green{color:#459a49}
		.fwb{font-weight: bold;}
		
		.table .number{
			font-size: 38px;
			color: #fff;
			width: 75px;
			text-align: center;
			line-height: 70px;
			padding: 0;
			font-weight: bold;
		}
		.img-flag{
			width: 120px;
			height: 48px;
			background: #fff;
			padding-left: 2px
		}
		.img-airline{
			width: 48px;
			height: 48px;
			background: rgb(227, 37, 38);
		}
		.f-en{font-size: 22px}
	</style>
</head>

<body>
	<?php

	$lenght = 0;
	$page = 0;
	$no = 0;
	for ($i=1; $i <= 50; $i++) {	
		$lenght++;

		$no = $i;
		

		$cls = '';
		if( $lenght==1 ){
			$page++;

			if( $page>1){
				echo '<pagebreak></pagebreak>';
			}

			echo '<table class="table">';

			
		}

		if($lenght==1){
			$cls = 'first';
		}

		if( $lenght==5 ){
			$cls = 'last';
		}

		echo '<tr class="'.$cls.'">';
			echo item($no);

			$no = $i+5;

			echo item($no);
		echo '</tr>';


		if( $lenght==5 ){
			echo '</table>';
			
			$lenght = 0;
		}

	}

	if( $lenght!=10 ){
		echo '</table>';
	}
	?>
</body>
</html>