<?php

$f = new Form();
$form = $f->create(); 

    // attr, options
$form   ->addClass('login-form-container form-insert form-large')
        ->method('post')
        ->url( $this->redirect );

    // set field
$form   ->field("email")
        ->label('<i class="icon-user"></i>')
        ->placeholder("Username")
        ->addClass('inputtext')
        ->required(true)
        ->autocomplete("off");

if( (!empty($this->post['email']) && !empty($this->error['email'])) || empty($this->post['email']) ){
$form   ->attr('autofocus', '1');
}

$form   ->value( !empty($this->post['email'])? $this->post['email'] : '' )
        ->notify( !empty($this->error['email']) ? $this->error['email'] : '' );

$form   ->field("pass")
        ->label('<i class="icon-key"></i>')
        ->type('password')
        ->required(true);

if( (!empty($this->post['email']) && empty($this->error['email'])) ){
$form   ->attr('autofocus', '1');
}

$form   ->addClass('inputtext')
        ->placeholder("Password")
        ->notify( !empty($this->error['pass']) ? $this->error['pass'] : '' );


if( !empty($this->captcha) ){

    $form->field("captcha")
    ->text('<div class="g-recaptcha" data-sitekey="'.RECAPTCHA_SITE_KEY.'"></div>')
    ->notify( !empty($this->error['captcha']) ? $this->error['captcha'] : '' );

}

$form->hr( !empty($this->next) ? '<input type="hidden" autocomplete="off" value="'.$this->next .'" name="next">': '' )


->submit()
->addClass('btn btn-blue btn-large')
->value('Sign In');


// $title = $this->getPage('title');
// $name = $this->getPage('name');
$image = $this->getPage('image_x1');


?>

<div class="bgs">
    <!-- <div class="bg" style="background-image: url(&quot;<?=IMAGES?>/Backgrounds/0.jpg?x=a5dbd4393ff6a725c7e62b61df7e72f0&quot;);"></div> -->
   
    <div class="bg bg-sticky">
        <img src="<?=IMAGES?>illustrations/skyline.png" class="bg-sticky-image">
    </div>
</div>

<div class="section"><div class="middle">
    <div class="content-wrapper<?=!empty($this->captcha)? ' has-captcha':''?>">

        <!-- end: login-header -->
        <div class="login-header-bar login-logo">
            <div class="text">
                <?php if( !empty($image) ){ ?><div class="pic"><img src="<?=$image?>" style="max-width:200px" ></div><?php } ?>
                <!-- <h2><?= !empty( $title ) ? $title :''?></h2> -->
            </div>

            <!-- <div class="subtext mvm"></div> -->
        </div>

        <div class="login-container-wrapper auth-box">
            <div class="login-container">
                <div class="login-title"><span class="fwb">Sign in to your account</span></div>
                <?=$form->html()?>
            </div>

        </div>
        <!-- end: login-container-wrapper -->

        <div class="login-footer-text">
            <!-- <a href="<?=URL?>"><i class="icon-home mrs"></i><span>Back To Home</span></a><span class="mhm">·</span> -->
            <!-- <a href="<?=URL?>account/forgot_password" class="forgot_password"><span>Forgot password?</span></a> -->
        </div>
        <!-- end: login-footer -->
        
    </div>
    <!-- end: content-wrapper -->

</div></div>
<!-- /section -->


<div id="footer" class="footer default" role="contentinfo">
    <div class="footerNode">
        <span class="copyright"><?=COPYRIGHT?></span>
    </div>
</div>