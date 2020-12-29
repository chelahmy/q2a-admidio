<?php
// q2a-admidio-avatar.php
// User avatar for Question2Answer-Admidio Single Sign-on
// for use in qa_avatar_html_from_userid()
// By Abdullah Daud, chelahmy@gmail.com
// 18 December 2020

/*
	Notes:
	- User avatar will be taken from Admidio user photo.
	- The Admidio user photo is expected to be kept in the *usr_photo* field
	  in the *adm_users* table. Even though Admidio has an option to keep
	  a user photo in a file but it will be ignored for now.
	- *usr_id* parameter must be stated in the query string.
	- *size* parameter is optional.
*/

define('QA_VERSION', '1.8.5'); // required to include qa-*.php

require_once "../qa-include/qa-base.php";
require_once "../qa-include/qa-db.php";
require_once "q2a-admidio-helper.php";

$img_size = 0;
$im = false;

if (isset($_GET['size'])) {
	$size = intval($_GET['size']);
	if ($size > 0)
		$img_size = $size;
}

if (isset($_GET['usr_id'])) {
	$uid = intval($_GET['usr_id']);
	if ($uid > 0) {
		$photo = get_admidio_user_photo($uid);
		if ($photo !== false)
			$im = imagecreatefromstring($photo);
	}
}

if ($im === false)
	$im = imagecreatefrompng('no_profile_pic.png');

if ($im !== false && $img_size > 0) { // resize image
	$w = imagesx($im);
	$h = imagesy($im);
	if ($w != $img_size && $h != $img_size) { 
		if ($w > $h)
			$perc = $img_size / $w;
		else
			$perc = $img_size / $h;
		$nw = $w * $perc;
		$nh = $h * $perc;
		$im2 = imagecreatetruecolor($nw, $nh);
		imagecopyresized($im2, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
		imagedestroy($im);
		$im = $im2;
	}
}

if ($im !== false) {
	header('Content-Type: image/png');
	imagepng($im);
	imagedestroy($im);
}

?>

