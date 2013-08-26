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
	headerRedirect( fb_map_url('controller=twitterbutton', '') );
}
if(isset($_GET['edit'])) {
	$cm['fb_id']		= intval($_GET['edit']);
} else {
	$cm['fb_id']		= 0;
}

if(isset($_POST['fb_name'])) {

  if (!preg_match("~^(?:f|ht)tps?://~i", $_POST['fb_tweet_url']) && !empty($_POST['fb_tweet_url']) ) {
        $_POST['fb_tweet_url'] = "http://" . $_POST['fb_tweet_url'];
  }

  $plugin_fb['data'] = array(
  	'fb_id' => intval($_POST['fb_id']),
  	'fb_changed'	=> date('Y-m-d H:i:s'),
  	'fb_name'		=> clean_slweg($_POST['fb_name']),
  	'fb_status'		=> empty($_POST['fb_status']) ? 0 : 1
	);


  $plugin_fb['data']['values'] = array(
    'fb_tweet_domain'		=> empty($_POST['fb_tweet_domain']) ? 0 : intval($_POST['fb_tweet_domain']),
    'fb_tweet_url'		=> empty($_POST['fb_tweet_url']) ? '' : clean_slweg($_POST['fb_tweet_url']),
    'fb_tweet_button'	=> empty($_POST['fb_tweet_button']) ? 'none' : clean_slweg($_POST['fb_tweet_button']),
    'fb_tweet_title'		=> empty($_POST['fb_tweet_title']) ? 0 : intval($_POST['fb_tweet_title']),
    'fb_tweet_titletxt'		=> empty($_POST['fb_tweet_titletxt']) ? '' : clean_slweg($_POST['fb_tweet_titletxt']),
    'fb_tweet_locale'		=> empty($_POST['fb_tweet_locale']) ? 'en' : clean_slweg($_POST['fb_tweet_locale']),
    'fb_tweet_recom1'		=> empty($_POST['fb_tweet_recom1']) ? '' : clean_slweg($_POST['fb_tweet_recom1']),
    'fb_tweet_recom2'		=> empty($_POST['fb_tweet_recom2']) ? '' : clean_slweg($_POST['fb_tweet_recom2']),
    'fb_tweet_hashtags'		=> empty($_POST['fb_tweet_hashtags']) ? '' : clean_slweg($_POST['fb_tweet_hashtags'])
	);

	if(empty($plugin_fb['data']['fb_name'])) {
		$plugin_fb['error']['fb_name'] = 1;
	}
	if(empty($plugin_fb['data']['values']['fb_tweet_hashtags'])) {
		$plugin_fb['error']['fb_tweet_hashtags'] = 1;
	}
  $regex = "^((ht|f)tp(s?))\:\/\/([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$";
	if(!preg_match("/^$regex$/", $plugin_fb['data']['values']['fb_tweet_url']) && !empty($plugin_fb['data']['values']['fb_tweet_url'])) {
		$plugin_fb['error']['fb_tweet_url'] = 1;
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
				$sql .= "'tw_hashtag', ";
				$sql .= "'".aporeplace(serialize( $plugin_fb['data']['values'] ))."'";
				$sql .= ')';
				$result = _dbQuery($sql, 'INSERT');
				
				if( !empty($result['INSERT_ID']) ) {
					$plugin_fb['data']['fb_id']	= $result['INSERT_ID'];
				}

			}

			// save and back to listing mode
			if( isset($_POST['save']) ) {
				headerRedirect( fb_map_url('controller=twitterbutton', '') );
			} else {
				headerRedirect( fb_map_url( array('controller=tw_hashtag', 'edit='.$plugin_fb['data']['fb_id']), '') );
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
			headerRedirect( fb_map_url('controller=twitterbutton', '') );
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
  	'fb_cat'		=> 'tw_hashtag',
  	'fb_name'		=> '',
  	'fb_status'		=> 0
	);
	$plugin_fb['data']['values'] = array(
    'fb_tweet_domain'	=> 2,
    'fb_tweet_url'		=> '',
    'fb_tweet_button'	=> 'none',
    'fb_tweet_title'	=> 2,
    'fb_tweet_titletxt'	=> '',
    'fb_tweet_locale'		=> 'en',
    'fb_tweet_recom1'		=> '',
    'fb_tweet_recom2'		=> '',
    'fb_tweet_hashtags'			=> ''
	);
}
?>