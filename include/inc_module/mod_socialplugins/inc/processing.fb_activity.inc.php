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
    'fb_width' => empty($_POST['fb_width']) ? 300 : intval($_POST['fb_width']),
    'fb_height' => empty($_POST['fb_height']) ? 300 : intval($_POST['fb_height']),
    'fb_font'		=> empty($_POST['fb_font']) ? 'arial' : clean_slweg($_POST['fb_font']),
    'fb_colorscheme'		=> empty($_POST['fb_colorscheme']) ? 'light' : clean_slweg($_POST['fb_colorscheme']),
    'fb_site_url_fix'		=> empty($_POST['fb_site_url_fix']) ? 0 : 1,                                                        //enym new
    'fb_site_url'		=> empty($_POST['fb_site_url']) ? '' : clean_slweg($_POST['fb_site_url']),                              //enym new
    'fb_header'	=> empty($_POST['fb_header']) ? 0 : 1,
    'fb_show_recom'	=> empty($_POST['fb_show_recom']) ? 0 : 1,
//    'fb_border_color'		=>(empty($_POST['fb_border_color']) || !preg_match('/^[A-Fa-f0-9]{6}$/', $_POST['fb_border_color'])) ? '000000' : clean_slweg($_POST['fb_border_color']),
    'fb_output_type'		=> empty($_POST['fb_output_type']) ? 'iframe' : clean_slweg($_POST['fb_output_type']),
    'fb_locale'		=> empty($_POST['fb_locale']) ? 'en_US' : clean_slweg($_POST['fb_locale']),
    'fb_ref'		=> empty($_POST['fb_ref']) ? '' : clean_slweg($_POST['fb_ref'])

	);
	
	if(empty($plugin_fb['data']['fb_name'])) {
		$plugin_fb['error']['fb_name'] = 1;
	}
	//enym new create error
	if(empty($plugin_fb['data']['values']['fb_site_url']) && empty($plugin_fb['data']['values']['fb_site_url_fix'])) {
		$plugin_fb['error']['fb_site_url'] = 1;
	}
  $regex = "^((ht|f)tp(s?))\:\/\/([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$";
	if(!preg_match("/$regex/", $plugin_fb['data']['values']['fb_site_url']) && !empty($plugin_fb['data']['values']['fb_site_url'])) {
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
				$sql .= "'fb_activity', ";
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
				headerRedirect( fb_map_url( array('controller=fb_activity', 'edit='.$plugin_fb['data']['fb_id']), '') );
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
  	'fb_cat'		=> 'fb_activity',
  	'fb_name'		=> '',
  	'fb_status'		=> 0
	);
	$plugin_fb['data']['values'] = array(
    'fb_width'		=> 300,
    'fb_height'		=> 300,
    'fb_font'		=> 'arial',
    'fb_site_url'		=> '',    //enym new
    'fb_site_url_fix'		=> 1,      //enym new
    'fb_colorscheme'	=> 'light',
    'fb_header'	=> 1,
    'fb_show_recom'	=> 1,
    //'fb_border_color'	=> '000000',
    'fb_output_type'		=> 'iframe',
    'fb_locale'		=> 'en_US',
    'fb_ref'		=> ''
	);
}
?>