<?php
/**
* phpwcms content management system
*
* @author Oliver Georgi <oliver@phpwcms.de>
* @copyright Copyright (c) 2002-2013, Oliver Georgi
* @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
* @link http://www.phpwcms.de
*
* This script is a module for PHPWCMS
* Module Social Plugins v1.0.5 by breitsch - webrealisierung gmbh 2013
*
**/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// first check if neccessary db exists
if(isset($phpwcms['modules'][$module]['path']) ) {

	// module default stuff
	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
  include_once($phpwcms['modules'][$module]['path'].'inc/fb.functions.inc.php');

  // first check if neccessary db exists
  // ceate main db table
  $sql = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_modfb` (
          `fb_id` int(11) NOT NULL auto_increment,
          `fb_created` datetime NOT NULL,
          `fb_changed` datetime NOT NULL,
          `fb_cat` varchar(255) NOT NULL default '',
          `fb_name` varchar(255) NOT NULL default '',
          `fb_values` text NOT NULL,
          `fb_status` int(1) NOT NULL default '0',
          PRIMARY KEY  (`fb_id`)
          ) ENGINE=MyISAM"._dbGetCreateCharsetCollation();

  if (mysql_fetch_row(mysql_query("SHOW TABLES FROM `" . DB_PREPEND . $GLOBALS['phpwcms']['db_table'] . "` LIKE '%phpwcms_modfb'"))) {

	$plugin_fb = array();
  $sort	= 0;

  if(isset($_GET['edit'])) {
		$action	= 'edit';
	} elseif(isset($_GET['update'])) {
		$action	= 'update';
	} elseif(isset($_GET['verify'])) {
		$action	= 'status';
	} elseif(isset($_GET['delete'])) {
		$action	= 'delete';
  } else {
		$action		= '';
	}
	
	if(isset($_GET['sort'])) {
		$sort	= intval($_GET['sort']);
	}

	$controller	= empty($_GET['controller']) ? 'likeit' : strtolower($_GET['controller']);
	switch($controller) {

		case 'likeit':	$controller	= 'likeit';
    				break;
		case 'fb_activity':	$controller	= 'fb_activity';
    				break;
		case 'fb_recom':	$controller	= 'fb_recom';
    				break;
		case 'fb_comm':	$controller	= 'fb_comm';
    				break;
		case 'fb_share':	$controller	= 'fb_share';
    				break;
		case 'twitterbutton':	$controller	= 'twitterbutton';
    				break;
		case 'tw_hashtag':	$controller	= 'tw_hashtag';
    				break;
		case 'tw_mention':	$controller	= 'tw_mention';
    				break;
		case 'twitterfollow':	$controller	= 'twitterfollow';
						break;
		case 'twitterwidget':	$controller	= 'twitterwidget';
						break;
		case 'google':	$controller	= 'google';
    				break;
		case 'twoclick':	$controller	= 'twoclick';
    				break;
		case 'about':	$controller	= 'about';
						break;
		default:		$controller	= 'likeit';

	// some defaults - unset session vars
	//unset($_SESSION['detail_page'], $_SESSION['list_active'], $_SESSION['list_inactive'], $_SESSION['filter']);
	}

	// processing
	if( $action ) {
		include_once($phpwcms['modules'][$module]['path'].'inc/processing.' . $controller . '.inc.php');
	}

	// header
	include_once($phpwcms['modules'][$module]['path'].'inc/tabs.inc.php');

	// listing
	if($action) {
		include_once($phpwcms['modules'][$module]['path'].'inc/'.$action.'.' . $controller . '.inc.php');
  } else {
		include_once($phpwcms['modules'][$module]['path'].'inc/listing.' . $controller . '.inc.php');
	}

 } else if (_dbQuery($sql, 'CREATE')) { //create table
      echo '<p class="title">Module Social Plugins Setup</p>';
      echo '<p class="">Module Social Plugins setup successful. Please click the module link again to start working with the module!</p>';

 } else { //not good
      	echo '<p>'.@htmlentities(@mysql_error(), ENT_QUOTES, PHPWCMS_CHARSET).'</p>';
  }
}
?>