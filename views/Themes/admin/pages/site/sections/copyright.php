<?php

$this->formUrl = URL.'admin/config/';

$form = new Form();
$form = $form->create()
		->url($this->formUrl."?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("copyright")
		->label( 'copyright' )
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->page['copyright']) ? $this->page['copyright']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value( Translate::Val('Save') );

?>
<div class="settings-header clearfix">
	<div class="settings-title">Copyright</div>
	<!-- <div class="settings-suptitle"></div> -->
</div>

<?=$form->html(); ?>
