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

	// delete
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_modfb SET ';
		$sql .= "fb_status=9 WHERE fb_id=".$plugin_fb['data']['fb_del'];
		_dbQuery($sql, 'UPDATE');

	  headerRedirect( fb_map_url('controller=likeit', '') );
?>