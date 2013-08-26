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
	headerRedirect( fb_map_url('controller=likeit', '') );
}
if(isset($_GET['edit'])) {
	$cm['fb_id']		= intval($_GET['edit']);
} else {
	$cm['fb_id']		= 0;
}

if(isset($_POST['fb_name'])) {

  if (!preg_match("~^(?:f|ht)tps?://~i", $_POST['fb_site_url']) && !empty($_POST['fb_site_url']) ) {
      $_POST['fb_site_url'] = "http://" . $_POST['fb_site_url'];
  }

  $plugin_fb['data'] = array(
  	'fb_id' => intval($_POST['fb_id']),
  	'fb_changed'	=> date('Y-m-d H:i:s'),
  	'fb_name'		=> clean_slweg($_POST['fb_name']),
  	'fb_status'		=> empty($_POST['fb_status']) ? 0 : 1
	);
  $plugin_fb['data']['values'] = array(

    'fb_site'		=> empty($_POST['fb_site']) ? '' : clean_slweg($_POST['fb_site']),
    'fb_site_fix'		=> empty($_POST['fb_site_fix']) ? 0 : 1,
    'fb_title'		=> empty($_POST['fb_title']) ? '' : clean_slweg($_POST['fb_title']),
    'fb_tit_fix'		=> empty($_POST['fb_tit_fix']) ? 0 : 1,
    'fb_site_url_fix'		=> empty($_POST['fb_site_url_fix']) ? 0 : 1,                                                        //enym new
    'fb_site_url'		=> empty($_POST['fb_site_url']) ? '' : clean_slweg($_POST['fb_site_url']),                              //enym new
    'fb_colorscheme'		=> empty($_POST['fb_colorscheme']) ? 'light' : clean_slweg($_POST['fb_colorscheme']),               //enym new
    'fb_output_type'		=> empty($_POST['fb_output_type']) ? 'xfbml' : clean_slweg($_POST['fb_output_type']),               //new
    'fb_width' => empty($_POST['fb_width']) ? 500 : intval($_POST['fb_width']),
    'fb_comm_nr' => empty($_POST['fb_comm_nr']) ? 5 : intval($_POST['fb_comm_nr']),
    'fb_admins'		=> empty($_POST['fb_admins']) ? '' : clean_slweg($_POST['fb_admins']),
    'fb_app_id'		=> empty($_POST['fb_app_id']) ? '' : clean_slweg($_POST['fb_app_id']),
    'fb_locale'		=> empty($_POST['fb_locale']) ? 'en_US' : clean_slweg($_POST['fb_locale'])
	);
	
	if(empty($plugin_fb['data']['fb_name'])) {
		$plugin_fb['error']['fb_name'] = 1;
	}
	if(empty($plugin_fb['data']['values']['fb_title']) && empty($plugin_fb['data']['values']['fb_tit_fix'])) {
		$plugin_fb['error']['fb_title'] = 1;
	}
	if(empty($plugin_fb['data']['values']['fb_site']) && empty($plugin_fb['data']['values']['fb_site_fix'])) {
		$plugin_fb['error']['fb_site'] = 1;
	}
//	if(empty($plugin_fb['data']['values']['fb_app_id'])) {
//		$plugin_fb['error']['fb_app_id'] = 1;
//	}
	//enym new create error
	if(empty($plugin_fb['data']['values']['fb_site_url']) && empty($plugin_fb['data']['values']['fb_site_url_fix'])) {
		$plugin_fb['error']['fb_site_url'] = 1;
	}
  $regex = "^((ht|f)tp(s?))\:\/\/([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$";
	if(!preg_match("/^$regex$/", $plugin_fb['data']['values']['fb_site_url']) && !empty($plugin_fb['data']['values']['fb_site_url'])) {
		$plugin_fb['error']['fb_site_url'] = 1;
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
				$sql .= "'fb_comm', ";
				$sql .= "'".aporeplace(serialize( $plugin_fb['data']['values'] ))."'";
				$sql .= ')';
				$result = _dbQuery($sql, 'INSERT');
				
				if( !empty($result['INSERT_ID']) ) {
					$plugin_fb['data']['fb_id']	= $result['INSERT_ID'];
				}

			}

			// save and back to listing mode
			if( isset($_POST['save']) ) {
				headerRedirect( fb_map_url('controller=likeit', '') );
			} else {
				headerRedirect( fb_map_url( array('controller=fb_comm', 'edit='.$plugin_fb['data']['fb_id']), '') );
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
		} else {
			headerRedirect( fb_map_url('controller=likeit', '') );
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
  	'fb_cat'		=> 'fb_comm',
  	'fb_name'		=> '',
  	'fb_status'		=> 0
	);
	$plugin_fb['data']['values'] = array(
    'fb_site'		=> '',
    'fb_site_fix'		=> 0,
    'fb_title'		=> '',
    'fb_tit_fix'		=> 0,
    'fb_site_url'		=> PHPWCMS_URL,
    'fb_site_url_fix'		=> 1,
    'fb_colorscheme'	=> 'light',
    'fb_output_type'	=> 'xfbml',
    'fb_width'		=> 500,
    'fb_comm_nr'		=> 5,
    'fb_admins'			=> '',
    'fb_app_id'		=> '',
    'fb_locale'		=> 'en_US'
	);
}
?>