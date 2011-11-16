<?php

	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	require_once('ThumbLib.inc.php');
	
	// Allow querystring to override thumb width & height defaults
    $thumb_src = !empty($_GET['src']) ? $_GET['src'] : '' ;
    $thumb_x = !empty($_GET['x']) && is_numeric($_GET['x']) ? $_GET['x'] : 100 ;
    $thumb_y = !empty($_GET['y']) && is_numeric($_GET['y']) ? $_GET['y'] : 100 ;
    
	// Make sure the original image exists
	$thumb_src = str_replace('//','/',dirname(dirname(dirname(__FILE__))).'/'.$thumb_src);
	if (!file_exists($thumb_src)) {
	     echo 'File "'. $thumb_src .'" not found.';
	     exit();
	}
	
	// Make sure the thumb dimensions aren't bigger than the original image dimensions
	$thumb_dimensions = getimagesize($thumb_src);
	if ($thumb_x > $thumb_dimensions[0]) $thumb_x = $thumb_dimensions[0];
	if ($thumb_y > $thumb_dimensions[1]) $thumb_y = $thumb_dimensions[1];
	
	// Output!
	try {
		$thumb = PhpThumbFactory::create($thumb_src);
		$thumb->adaptiveResize($thumb_x, $thumb_y);
		$thumb->show();
	} catch (Exception $e) {
		echo 'Error.';
		exit();
	}

?>