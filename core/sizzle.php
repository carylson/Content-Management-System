<?php

	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('date.timezone', 'America/New_York');

	session_name('Sizzle!_CMS');
	session_start();

	require_once(dirname(__FILE__) .'/BaseController.class.php');
	require_once(dirname(__FILE__) .'/BaseApp.class.php');
	require_once(dirname(__FILE__) .'/BaseApplet.class.php');
	require_once(dirname(__FILE__) .'/BaseModel.class.php');
	require_once(dirname(__FILE__) .'/BaseAppModel.class.php');
	require_once(dirname(__FILE__) .'/BaseAppletModel.class.php');
	require_once(dirname(__FILE__) .'/Sizzle.class.php');
	require_once(dirname(__FILE__) .'/SizzleConfig.class.php');
	require_once(dirname(__FILE__) .'/SizzleDebug.class.php');
	require_once(dirname(__FILE__) .'/SizzleUtils.class.php');
	require_once(dirname(__FILE__) .'/SizzleView.class.php');

	$sizzle = new Sizzle();
	$sizzle->dispatch();

?>