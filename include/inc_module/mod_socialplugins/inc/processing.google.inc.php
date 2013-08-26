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

// check if form should be closed only -> and back to listing mode
if( isset($_POST['close']) ) {
	headerRedirect( fb_map_url('controller=google', '') );
}
if(isset($_GET['edit'])) {
	$cm['fb_id']		= intval($_GET['edit']);
} else {
	$cm['fb_id']		= 0;
}

if(isset($_POST['fb_name'])) {

  if (!preg_match("~^(?:f|ht)tps?://~i", $_POST['fb_google_url']) && !empty($_POST['fb_google_url']) ) {
        $_POST['fb_google_url'] = "http://" . $_POST['fb_google_url'];
  }

  $plugin_fb['data'] = array(
  	'fb_id' => intval($_POST['fb_id']),
  	'fb_changed'	=> date('Y-m-d H:i:s'),
  	'fb_name'		=> clean_slweg($_POST['fb_name']),
  	'fb_status'		=> empty($_POST['fb_status']) ? 0 : 1
	);


  $plugin_fb['data']['values'] = array(
    'fb_google_domain'		=> empty($_POST['fb_google_domain']) ? 0 : 1,
    'fb_google_url'		=> empty($_POST['fb_google_url']) ? '' : clean_slweg($_POST['fb_google_url']),
    'fb_google_size'	=> empty($_POST['fb_google_size']) ? 'standard' : clean_slweg($_POST['fb_google_size']),
    'fb_google_title'		=> empty($_POST['fb_google_title']) ? 0 : intval($_POST['fb_google_title']),
    'fb_google_titletxt'		=> empty($_POST['fb_google_titletxt']) ? '' : clean_slweg($_POST['fb_google_titletxt']),
    'fb_google_locale'		=> empty($_POST['fb_google_locale']) ? 'en-US' : clean_slweg($_POST['fb_google_locale']),
    'fb_google_annotation'		=> empty($_POST['fb_google_annotation']) ? 'none' : clean_slweg($_POST['fb_google_annotation']),
    'fb_id_img' => empty($_POST['fb_id_img']) ? '' : intval($_POST['fb_id_img']),
    'fb_name_img'		=> empty($_POST['fb_name_img']) ? '' : clean_slweg($_POST['fb_name_img'])
	);

	if(empty($plugin_fb['data']['fb_name'])) {
		$plugin_fb['error']['fb_name'] = 1;
	}
  $regex = "^((ht|f)tp(s?))\:\/\/([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$";
	if(!preg_match("/^$regex$/", $plugin_fb['data']['values']['fb_google_url']) && !empty($plugin_fb['data']['values']['fb_google_url'])) {
		$plugin_fb['error']['fb_google_url'] = 1;
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
				$sql .= "'google', ";
				$sql .= "'".aporeplace(serialize( $plugin_fb['data']['values'] ))."'";
				$sql .= ')';
				$result = _dbQuery($sql, 'INSERT');
				
				if( !empty($result['INSERT_ID']) ) {
					$plugin_fb['data']['fb_id']	= $result['INSERT_ID'];
				}

			}

			// save and back to listing mode
			if( isset($_POST['save']) ) {
				headerRedirect( fb_map_url('controller=google', '') );
			} else {
				headerRedirect( fb_map_url( array('controller=google', 'edit='.$plugin_fb['data']['fb_id']), '') );
			}	

		}
}
	
// try to read entry from database
if($cm['fb_id'] && !isset($plugin_fb['error'])) {

		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . $cm['fb_id'] . ' LIMIT 1';
		$plugin_fb['data'] = _dbQuery($sql);

		if( isset($plugin_fb['data'][0]) ) {
			$plugin_fb['data'] = $plugin_fb['data'][0];
			$plugin_fb['data']['values'] = unserialize($plugin_fb['data']['fb_values']);

      $plugin_fb['data']['values']['fb_id_img'] 		= empty($plugin_fb['data']['values']['fb_id_img']) ? '' : $plugin_fb['data']['values']['fb_id_img'];
      $plugin_fb['data']['values']['fb_name_img']			= empty($plugin_fb['data']['values']['fb_name_img']) ? '' : $plugin_fb['data']['values']['fb_name_img'];

    } else {
			headerRedirect( fb_map_url('controller=google', '') );
		}

}

if($action == 'status') {
  $plugin_fb['data'] = array();
	list($plugin_fb['data']['fb_id'], $plugin_fb['data']['fb_status']) = explode( '-', $_GET['verify'] );
	$plugin_fb['data']['fb_id']		= intval($plugin_fb['data']['fb_id']);
	$plugin_fb['data']['fb_status']	= empty($plugin_fb['data']['fb_status']) ? 1 : 0;
}

if($action == 'delete') {
  $plugin_fb['data'] = array();
	$plugin_fb['data']['fb_del'] = intval($_GET['delete']);
}

// default values
if(empty($plugin_fb['data'])) {

	$plugin_fb['data'] = array(
	  'fb_id' => 0,
    'fb_created'	=> '',
    'fb_changed'	=> date('Y-m-d H:i:s'),
  	'fb_cat'		=> 'google',
  	'fb_name'		=> '',
  	'fb_status'		=> 0
	);
	$plugin_fb['data']['values'] = array(
    'fb_google_domain'	=> 0,
    'fb_google_url'		=> '',
    'fb_google_size'	=> 'standard',
    'fb_google_title'	=> 0,
    'fb_google_titletxt'	=> '',
    'fb_google_locale'		=> 'en-US',
    'fb_google_annotation'		=> 'none',
    'fb_id_img' 		=> '',
    'fb_name_img'			=> ''
	);
}
?>