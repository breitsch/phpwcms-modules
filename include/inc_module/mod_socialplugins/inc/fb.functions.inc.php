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

function fb_map_url($get='', $type='htmlentities') {
	$base = MODULE_HREF;
	if(is_array($get) && count($get)) {
		$get = implode('&', $get);
	} elseif(empty($get)) {
		$get = '';
	}
	if($get) $get = '&'.$get;
	if(empty($type) || $type != 'htmlentities') {
		$base = str_replace('&amp;', '&', MODULE_HREF);
	} else {
		$get = htmlentities($get);
	}
	return $base.$get;
}

function roundAll($a) {
	$a = floatval($a);
	return round($a, 2);
}

if(!function_exists("bcdiv")){
  function bcdiv($first, $second, $accuracy=2){
    $res = $first/$second;
    return round($res, $accuracy);
  }
}

function _error($msg, $title='NOTICE'){
		$errorMsg = "<div><strong>{$title}: </strong>{$msg}</div>";
	return $errorMsg;
}

function get_fb_loc($sel){
  $plugin_fb_loc = "https://www.facebook.com/translations/FacebookLocales.xml";
  $plugin_fb_loc_xml = simplexml_load_file($plugin_fb_loc);
  $plugin_fb_loc_opts = "";
  $plugin_fb_loc_arr = array();
  foreach($plugin_fb_loc_xml as $k=>$v){
    $plugin_fb_loc_arr[(string) $v->englishName] = (string) $v->codes->code->standard->representation;
  }
  ksort($plugin_fb_loc_arr);
  foreach($plugin_fb_loc_arr as $k=>$v){
    $plugin_fb_loc_opts .='<option value="'.$v.'" '.is_selected($v,$sel,1,0).'>'.$k.'</option>'.LF;
  }
	return $plugin_fb_loc_opts;
}

function jqs_is_valid($val,$true='true',$false='false') {
  $result = $false;
  if( $val==0 ) $result = $false;
  if( $val==1 ) $result = $true;
  return $result;
}

function gm_detect_lang() {
  preg_match_all('/([a-z]{1,2}(-[a-z]{1,2})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
  if (count($lang_parse[1])) {
    $detected_lang = $lang_parse[1][0];
  } else {
    $detected_lang = 'en-us';
  }
  return $detected_lang;
}

?>