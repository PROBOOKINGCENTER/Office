<?php

if( isset($_REQUEST['next']) ){
	$arr['hiddenInput'][] = array('name'=>'next','value'=>$_REQUEST['next']);
}

$arr['hiddenInput'][] = array('name'=>'ref','value'=>'mb');
$arr['hiddenInput'][] = array('name'=>'h','value'=>'AfcbKa6ETzqgrsz2');

$arr['title'] = 'Confirm Log Out';
$arr['body'] = "Do you want to Log Out?";


$arr['form'] = '<form action="'.URL.'account/logout/" method="post">';
$arr['button'] = '<button type="button" data-action="close" class="btn btn-link btn-cancel"><span class="btn-text">Cancel</span></button>';
$arr['button'] .= '<button type="submit" class="btn btn-blue">Log Out</button>';

echo json_encode($arr);