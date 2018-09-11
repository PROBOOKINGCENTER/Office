<div class="settings-header clearfix">
	<div class="settings-title">Language & Region</div>
	<div class="settings-suptitle">Set your language & region preferences here.</div>
</div>

<div id="business-settings" class="content">


	<div class="box user-box">
		<div class="title-box">
			<div class="title">Language</div>
			<p>Select your language & region.</p>
		</div>
		<div class="box-content"><?php

			$this->formUrl = URL.'manage/business/';
			$form = new Form();
			$form = $form->create()
					->url($this->formUrl."?run=1")
					->addClass('js-submit-form')
					->method('post');

			$form  	->field("seo_title")
					->addClass('inputtext')
					->autocomplete("off")
					->select( array() );

			echo $form->html();

		?></div>
	</div>

	<div class="box user-box">
		<div class="title-box">
			<div class="title">Time Zone</div>
			<p>Choose your nearest city.</p>
		</div>

		<div class="box-content"><?php

			$this->formUrl = URL.'manage/business/';
			$form = new Form();
			$form = $form->create()
					->url($this->formUrl."?run=1")
					->addClass('js-submit-form')
					->method('post');

			$form  	->field("seo_title")
					->addClass('inputtext')
					->autocomplete("off")
					->select( array() );

			echo $form->html();

		?></div>
	</div>

</div>