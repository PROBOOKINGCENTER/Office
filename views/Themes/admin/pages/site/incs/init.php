<?php

$this->pageURL = URL.'admin/site/';
$this->count_nav = 5;
$menu = array();


$sub = array();
$sub[] = array('key'=>'font', 'text'=>'Font', 'url' => $this->pageURL.'font');
$sub[] = array('key'=>'colors', 'text'=>'Colors', 'url' => $this->pageURL.'colors');
$sub[] = array('key'=>'localization', 'text'=>'Language & Region', 'url' => $this->pageURL.'localization');
$sub[] = array('key'=>'favicon', 'text'=>'Favicon', 'url' => $this->pageURL.'favicon');
$sub[] = array('key'=>'copyright', 'text'=>'Copyright', 'url' => $this->pageURL.'copyright');
$this->count_nav+=count($sub);
$menu[] = array('text' => 'Design', 'url' => '', 'sub' => $sub);


$sub = array();
// $sub[] = array('key'=>'alert', 'text'=>'Site Alert', 'url' => $this->pageURL.'site_alert');
$sub[] = array('key'=>'slide', 'text'=>'Slide Deck Strip', 'url' => $this->pageURL.'slide');
$sub[] = array('key'=>'banner', 'text'=>'Banner', 'url' => $this->pageURL.'banner');

$this->count_nav+=count($sub);
$menu[] = array('text' => 'Module', 'url' => '', 'sub' => $sub);



$sub = array();
$sub[] = array('key'=>'social_google', 'text'=>'Google+', 'url' => $this->pageURL.'social_google');
$sub[] = array('key'=>'social_feacbook', 'text'=>'Feacbook', 'url' => $this->pageURL.'social_feacbook');

$this->count_nav+=count($sub);
$menu[] = array('text' => 'Social Setup', 'url' => '', 'sub' => $sub);


/* System */
$sub = array();
// $sub[] = array('key'=>'overview', 'text'=>'Overview', 'url' => $this->pageURL.'overview');
// $sub[] = array('key'=>'domain', 'text'=>'Domain', 'url' => $this->pageURL.'domain');
// $sub[] = array('key'=>'favicon', 'text'=>'Favicon', 'url' => $this->pageURL.'favicon');
$sub[] = array('key'=>'seo', 'text'=>'SEO', 'url' => $this->pageURL.'seo');
$sub[] = array('key'=>'analytics', 'text'=>'Google Analytics', 'url' => $this->pageURL.'analytics');


$this->count_nav+=count($sub);
$menu[] = array('text' => 'Search Engine', 'url' => '', 'sub' => $sub);

