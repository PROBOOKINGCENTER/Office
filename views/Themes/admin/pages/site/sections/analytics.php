<?php

$this->formUrl = URL.'manage/business/';
$form = new Form();
$form = $form->create()
		->url($this->formUrl."?run=1")
		->addClass('js-submit-form box-content')
		->method('post');

$form  	->field("google_analytic")
		->label($this->lang->translate('Analytic Code'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['google_analytic']) ? $this->system['google_analytic']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

?>
<div class="settings-header clearfix">
	<div class="settings-title">Google Analytics</div>
	<div class="settings-suptitle">Get key insight into your website’s performance.</div>
</div>

<div id="business-settings" class="content">


	<div class="box user-box"><?php

		echo $form->html();
		
		?>
	</div>

</div>