<?php

$this->formUrl = URL.'manage/business/';
$form = new Form();
$form = $form->create()
		->url($this->formUrl."?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("seo_title")
		->label($this->lang->translate('Page title on search engines?'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['seo_title']) ? $this->system['seo_title']:'');
		
$form  	->field("seo_description")
		->type('textarea')
		->label($this->lang->translate('Page about? (Page Description)'))
		->addClass('inputtext')
		->autocomplete("off")
		->attr('data-plugins', 'autosize')
		->value( !empty($this->system['seo_description']) ? $this->system['seo_description']:'');

$form  	->field("seo_keyword")
		->type('textarea')
		->label($this->lang->translate('Page Keyword?'))
		->addClass('inputtext')
		->autocomplete("off")
		->attr('data-plugins', 'autosize')
		->value( !empty($this->system['seo_keyword']) ? $this->system['seo_keyword']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

?>

<div class="settings-header clearfix">
	<div class="settings-title">SEO (Search Engine Optimization)</div>
	<div class="settings-suptitle">SEO is how you optimize your site so that it can be easily found and ranked by search engines like Google and more.</div>
</div>

<div id="business-settings" class="content">


	<div class="box user-box">
		<div class="box-title">
			<div class="title">SEO Status</div>
			<p>To get traffic from search engines to your site, this feature must be on.</p>
		</div>
		<div class="box-content"><?=$form->html(); ?></div>
	</div>

</div>