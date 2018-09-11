<?php

date_default_timezone_set("Asia/Bangkok");

// Always provide a TRAILING SLASH (/) AFTER A PATH
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$pathName = '/';
define('URL', $protocol.'://'.$_SERVER['HTTP_HOST'].$pathName);

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
// define('DB_NAME', 'jitwilaitour_dev');
define('DB_NAME', 'probooking_db');
define('DB_USER', 'probooking_dbo');
define('DB_PASS', 'Modest*Bare9PlanningLicence');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('WWW_LIBS', ROOT . DS . "libs" . DS);
define('WWW_APPS', ROOT . DS . "apps" . DS);

define('WWW_DOCS', ROOT . DS . "public". DS. 'docs' . DS);
define('WWW_VIEW', ROOT . DS . 'views' . DS);
define('WWW_IMAGES', ROOT . DS . 'public' . DS. 'images' . DS );
define('WWW_IMAGES_AVATAR', WWW_IMAGES . DS . 'avatar' . DS);
define('WWW_UPLOADS', ROOT . DS . "upload". DS );
// define('WWW_FORMS', WWW_VIEW . 'Forms' .DS);
define('WWW_VENDORS', ROOT . DS . "vendors" . DS);

define('LIBS', 'libs/');
define('DOCS', URL . 'document/');
define('VIEW', URL . 'views/');
define('CSS', URL . 'public/css/');
define('JS', URL . 'public/js/');
define('FONTS', URL . 'public/fonts/');
define('IMAGES', URL . 'public/images/');
define('AVATAR', URL . 'public/images/avatar/');
define('UPLOADS', URL . "upload/");
define('SOUNDS', URL . "public/sounds/");
define('VENDORS', URL. "vendors/");
define('DOWNLOAD', URL. "download/");
define('PHOTO', URL. "media/");

define('COPYRIGHT', "Pro Booking Center © 2018 · Version 2.1.5");
define('VERSION', "2.1.5");

define('LANG', 'th');
define('COOKIE_KEY_USER', 'u_id');

// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'MixitUp200');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'catsFLYhigh2000miles');

define('RECAPTCHA_SITE_KEY', '6LcFM2oUAAAAABdXxSCyS5He0Mk_1wYYxX-k3cM3');
define('RECAPTCHA_SECRET_KEY', '6LcFM2oUAAAAAJXDUw6L9_R1DuZ9dlnG2SGsyg09');


define('GOOGLE_CLIENT_ID', '679105860696-kb2qb19bpqjb0q051tfafdcuias3dcrh.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'aJ_mt7TQM-eRrQJmlc1v3k79');