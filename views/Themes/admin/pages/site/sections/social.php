<?php

$this->formUrl = URL.'manage/business/';
$form = new Form();
$form = $form->create()
		->url($this->formUrl."?run=1")
		->addClass('js-submit-form box-content')
		->method('post');

$form  	->field("google_id")
		->label($this->lang->translate('Google ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['google_id']) ? $this->system['google_id']:'');

$form  	->field("facebook_id")
		->label($this->lang->translate('Facebook ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['facebook_id']) ? $this->system['facebook_id']:'');

$form  	->field("twitter_id")
		->label($this->lang->translate('Twitter ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['twitter_id']) ? $this->system['twitter_id']:'');

$form  	->field("instagram_id")
		->label($this->lang->translate('Instagram ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['instagram_id']) ? $this->system['instagram_id']:'');

$form  	->field("youtube_id")
		->label($this->lang->translate('Youtube ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['youtube_id']) ? $this->system['youtube_id']:'');

$form  	->field("linkedin_id")
		->label($this->lang->translate('LinkedIn ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['linkedin_id']) ? $this->system['linkedin_id']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

?>

<div class="settings-header clearfix">
	<div class="settings-title">Social Media</div>
	<div class="settings-suptitle">Set info you want to be displayed when your site is shared on social networks.</div>
</div>

<div id="business-settings" class="content">


	<div class="box user-box"><?php

		echo $form->html();
		
		?>
	</div>

</div>