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

if( isset($_POST['fb_name']) && $_POST['fb_name']=="twoclick") {

  if (!preg_match("~^(?:f|ht)tps?://~i", $_POST['fb_twoclick_lnk']) && !empty($_POST['fb_twoclick_lnk']) ) {
        $_POST['fb_twoclick_lnk'] = "http://" . $_POST['fb_twoclick_lnk'];
  }

  $plugin_fb['data'] = array(
  	'fb_id' => intval($_POST['fb_id']),
  	'fb_changed'	=> date('Y-m-d H:i:s'),
  	'fb_name'		=> clean_slweg($_POST['fb_name']),
  	'fb_status'		=> empty($_POST['fb_status']) ? 0 : 1
	);

  $plugin_fb['data']['values'] = array(

    'fb_twoclick_lnk'	=> empty($_POST['fb_twoclick_lnk']) ? '' : clean_slweg($_POST['fb_twoclick_lnk']),
    'fb_twoclick_txt_but'	=> empty($_POST['fb_twoclick_txt_but']) ? '' : clean_slweg($_POST['fb_twoclick_txt_but']),
    'fb_twoclick_txt_set'	=> empty($_POST['fb_twoclick_txt_set']) ? '' : clean_slweg($_POST['fb_twoclick_txt_set']),
    'fb_twoclick_lbl_set'	=> empty($_POST['fb_twoclick_lbl_set']) ? '' : clean_slweg($_POST['fb_twoclick_lbl_set']),
    'fb_twoclick_perm_fb'	=> empty($_POST['fb_twoclick_perm_fb']) ? 0 : 1,
    'fb_twoclick_perm_tw'	=> empty($_POST['fb_twoclick_perm_tw']) ? 0 : 1,
    'fb_twoclick_perm_go'	=> empty($_POST['fb_twoclick_perm_go']) ? 0 : 1
	);

  $regex = "^((ht|f)tp(s?))\:\/\/([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$";
	if(!preg_match("/^$regex$/", $plugin_fb['data']['values']['fb_twoclick_lnk']) && !empty($plugin_fb['data']['values']['fb_twoclick_lnk'])) {
		$plugin_fb['error']['fb_twoclick_lnk'] = 1;
	}


		if( empty($plugin_fb['error'] )) {
		
			// Update
			if( $plugin_fb['data']['fb_id'] ) {

				$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_modfb SET ';
				$sql .= "fb_changed = '".aporeplace( $plugin_fb['data']['fb_changed'])."', ";
				$sql .= "fb_name = '".aporeplace($plugin_fb['data']['fb_name'])."', ";
				$sql .= "fb_values = '".aporeplace(serialize( $plugin_fb['data']['values'] ))."', ";
				$sql .= "fb_status = ".$plugin_fb['data']['fb_status']." ";
				$sql .= "WHERE fb_id = " . $plugin_fb['data']['fb_id'];
				_dbQuery($sql, 'UPDATE');
			
			// INSERT
			} else {
				$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_modfb (';
				$sql .= 'fb_created,fb_changed,fb_status,fb_name,fb_cat,fb_values';
				$sql .= ') VALUES (';
				$sql .= "'".aporeplace( $plugin_fb['data']['fb_changed'] )."', ";
				$sql .= "'".aporeplace( $plugin_fb['data']['fb_changed'] )."', ";
				$sql .= $plugin_fb['data']['fb_status'].", ";
				$sql .= "'".aporeplace($plugin_fb['data']['fb_name'])."', ";
				$sql .= "'twoclick', ";
				$sql .= "'".aporeplace(serialize( $plugin_fb['data']['values'] ))."'";
				$sql .= ')';
				$result = _dbQuery($sql, 'INSERT');
				
				if( !empty($result['INSERT_ID']) ) {
					$plugin_fb['data']['fb_id']	= $result['INSERT_ID'];
				}

			}

			// save and back to listing mode
				headerRedirect( fb_map_url('controller=twoclick', '') );

		}
}
?>