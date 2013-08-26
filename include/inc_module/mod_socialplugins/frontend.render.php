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

$msp_js = array();

	function replace_fb_share_tag($fb_share_id) {

		$fb_share_id = intval(trim($fb_share_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $module_meta_thumb = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_share_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

      //if dynamic use basic otherwise use given value
      if ($fbsp['data']['values']['fb_site_url_fix'] == 1) {
        $fbsp['data']['values']['fb_url'] = fbsp_get_pageurl();
      } else {
        if ( strlen($fbsp['data']['values']['fb_site_url']) == strcspn($fbsp['data']['values']['fb_site_url'],"'\"\\|<>") ) {
          $fbsp['data']['values']['fb_url'] = $fbsp['data']['values']['fb_site_url'];
        }
      }

      //own image
      if ($fbsp['data']['values']['fb_id_img']) {
        $module_meta_thumb = fbsp_get_module_image ($fbsp['data']['values']['fb_id_img'], false, $fbsp['data']['values']['fb_img_width'], $fbsp['data']['values']['fb_img_height'], $fbsp['data']['values']['fb_img_crop']);
        //if($module_meta_thumb) {
      }

      _set_meta_url('og:url',$fbsp['data']['values']['fb_url'],'property');

      if($fbsp['data']['values']['fb_share_dialog'] == 1) {
        $fbsp_output = '<a href="#" onclick="window.open(\'https://www.facebook.com/sharer/sharer.php?u='.urlencode($fbsp['data']['values']['fb_url']).'\',\'facebook-share-dialog\',\'width=626,height=436\');return false;" class="modsp_facebook_share">';
        if ($fbsp['data']['values']['fb_id_img'] && $module_meta_thumb) {
          $fbsp_output .= '<img src="'.PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0].'" alt="'.$fbsp['data']['values']['fb_link'].'" title="'.$fbsp['data']['values']['fb_link'].'" />';
        } else {
          $fbsp_output .= $fbsp['data']['values']['fb_link'];
        }
        $fbsp_output .= '</a>';
      } else {
        $fbsp_output = '<a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($fbsp['data']['values']['fb_url']).'" target="_blank" class="modsp_facebook_share">';
        if ($fbsp['data']['values']['fb_id_img'] && $module_meta_thumb) {
          $fbsp_output .= '<img src="'.PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0].'" alt="'.$fbsp['data']['values']['fb_link'].'" title="'.$fbsp['data']['values']['fb_link'].'" />';
        } else {
          $fbsp_output .= $fbsp['data']['values']['fb_link'];
        }
        $fbsp_output .= '</a>';
      }
    }
    return $fbsp_output;

  } //end replace_fb_share_tag

	function replace_fb_recom_tag($fb_recom_id) {

    global $msp_js;
		$fb_recom_id = intval(trim( $fb_recom_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_recom_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

        $fbsp['data']['values']['fb_url'] = PHPWCMS_URL;

      _set_meta_url('og:url',$fbsp['data']['values']['fb_url'],'property');
      $fbsp['data']['values']['fb_header'] = ($fbsp['data']['values']['fb_header'] == 1) ? 'true' : 'false' ;
      if($fbsp['data']['values']['fb_output_type'] == 'iframe') {
        $fbsp_output = '  <iframe src="http://www.facebook.com/plugins/recommendations.php?site='.urlencode($fbsp['data']['values']['fb_url']).'&amp;width='.$fbsp['data']['values']['fb_width'].'&amp;height='.$fbsp['data']['values']['fb_height'].'&amp;header='.$fbsp['data']['values']['fb_header'].'&amp;font='.$fbsp['data']['values']['fb_font'].'&amp;colorscheme='.$fbsp['data']['values']['fb_colorscheme'].'&amp;ref='.$fbsp['data']['values']['fb_ref'].'&amp;locale='.$fbsp['data']['values']['fb_locale'].'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$fbsp['data']['values']['fb_width'].'px; height:'.$fbsp['data']['values']['fb_height'].'px;" allowTransparency="true"></iframe>';

      } else if ($fbsp['data']['values']['fb_output_type'] == 'xfbml'){
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1"></script><div id="fb-root"></div><fb:recommendations site="'.$fbsp['data']['values']['fb_url'].'" width="'.$fbsp['data']['values']['fb_width'].'" height="'.$fbsp['data']['values']['fb_height'].'" header="'.$fbsp['data']['values']['fb_header'].'" font="'.$fbsp['data']['values']['fb_font'].'" colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" border_color="#'.$fbsp['data']['values']['fb_border_color'].'" ref="'.$fbsp['data']['values']['fb_ref'].'"></fb:recommendations>';
        $fbsp_output = '<div id="fb-root"></div><fb:recommendations site="'.$fbsp['data']['values']['fb_url'].'" width="'.$fbsp['data']['values']['fb_width'].'" height="'.$fbsp['data']['values']['fb_height'].'" header="'.$fbsp['data']['values']['fb_header'].'" font="'.$fbsp['data']['values']['fb_font'].'" colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" ref="'.$fbsp['data']['values']['fb_ref'].'"></fb:recommendations>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      } else if ($fbsp['data']['values']['fb_output_type'] == 'html5'){
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1"></script><div id="fb-root"></div><div class="fb-recommendations" data-site="'.$fbsp['data']['values']['fb_url'].'" data-width="'.$fbsp['data']['values']['fb_width'].'" data-height="'.$fbsp['data']['values']['fb_height'].'" data-header="'.$fbsp['data']['values']['fb_header'].'" data-font="'.$fbsp['data']['values']['fb_font'].'" data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" data-border_color="#'.$fbsp['data']['values']['fb_border_color'].'" data-ref="'.$fbsp['data']['values']['fb_ref'].'"></div>';
        $fbsp_output = '<div id="fb-root"></div><div class="fb-recommendations" data-site="'.$fbsp['data']['values']['fb_url'].'" data-width="'.$fbsp['data']['values']['fb_width'].'" data-height="'.$fbsp['data']['values']['fb_height'].'" data-header="'.$fbsp['data']['values']['fb_header'].'" data-font="'.$fbsp['data']['values']['fb_font'].'" data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" data-ref="'.$fbsp['data']['values']['fb_ref'].'"></div>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }
    }
    return $fbsp_output;

  } //end replace_fb_recom_tag
  
  //ACTIVITY
	function replace_fb_activity_tag($fb_activity_id) {

    global $msp_js;
		$fb_activity_id = intval(trim( $fb_activity_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_activity_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;
    
    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted
    
      //enysm replace
      //$fbsp['data']['values']['fb_url'] = PHPWCMS_URL;
      //if dynamic use basic otherwise use given value
      if ($fbsp['data']['values']['fb_site_url_fix'] == 1) {
        $fbsp['data']['values']['fb_url'] = PHPWCMS_URL;
      } else {
        if ( strlen($fbsp['data']['values']['fb_site_url']) == strcspn($fbsp['data']['values']['fb_site_url'],"'\"\\|<>") ) {
          $fbsp['data']['values']['fb_url'] = $fbsp['data']['values']['fb_site_url'];
        }
      }
      //enysm replace end      
      
      _set_meta_url('og:url',$fbsp['data']['values']['fb_url'],'property');
      $fbsp['data']['values']['fb_header'] = ($fbsp['data']['values']['fb_header'] == 1) ? 'true' : 'false' ;
      $fbsp['data']['values']['fb_show_recom'] = ($fbsp['data']['values']['fb_show_recom'] == 1) ? 'true' : 'false' ;
      if($fbsp['data']['values']['fb_output_type'] == 'iframe') {
        $fbsp_output = '  <iframe src="http://www.facebook.com/plugins/activity.php?site='.urlencode($fbsp['data']['values']['fb_url']).'&amp;width='.$fbsp['data']['values']['fb_width'].'&amp;height='.$fbsp['data']['values']['fb_height'].'&amp;header='.$fbsp['data']['values']['fb_header'].'&amp;font='.$fbsp['data']['values']['fb_font'].'&amp;colorscheme='.$fbsp['data']['values']['fb_colorscheme'].'&amp;recommendations='.$fbsp['data']['values']['fb_show_recom'].'&amp;ref='.$fbsp['data']['values']['fb_ref'].'&amp;locale='.$fbsp['data']['values']['fb_locale'].'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$fbsp['data']['values']['fb_width'].'px; height:'.$fbsp['data']['values']['fb_height'].'px;" allowTransparency="true"></iframe>';
      }else if ($fbsp['data']['values']['fb_output_type'] == 'xfbml'){
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1"></script><div id="fb-root"></div><fb:activity site="'.$fbsp['data']['values']['fb_url'].'" width="'.$fbsp['data']['values']['fb_width'].'" height="'.$fbsp['data']['values']['fb_height'].'" header="'.$fbsp['data']['values']['fb_header'].'" font="'.$fbsp['data']['values']['fb_font'].'" colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" border_color="#'.$fbsp['data']['values']['fb_border_color'].'" recommendations="'.$fbsp['data']['values']['fb_show_recom'].'" ref="'.$fbsp['data']['values']['fb_ref'].'"></fb:activity>';
        $fbsp_output = '  <div id="fb-root"></div><fb:activity site="'.$fbsp['data']['values']['fb_url'].'" width="'.$fbsp['data']['values']['fb_width'].'" height="'.$fbsp['data']['values']['fb_height'].'" header="'.$fbsp['data']['values']['fb_header'].'" font="'.$fbsp['data']['values']['fb_font'].'" colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" recommendations="'.$fbsp['data']['values']['fb_show_recom'].'" ref="'.$fbsp['data']['values']['fb_ref'].'"></fb:activity>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }else if ($fbsp['data']['values']['fb_output_type'] == 'html5'){
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1"></script><div id="fb-root"></div><div class="fb-activity" data-site="'.$fbsp['data']['values']['fb_url'].'" data-width="'.$fbsp['data']['values']['fb_width'].'" data-height="'.$fbsp['data']['values']['fb_height'].'" data-header="'.$fbsp['data']['values']['fb_header'].'" data-font="'.$fbsp['data']['values']['fb_font'].'" data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" data-border_color="#'.$fbsp['data']['values']['fb_border_color'].'" data-recommendations="'.$fbsp['data']['values']['fb_show_recom'].'" data-ref="'.$fbsp['data']['values']['fb_ref'].'"></div>';
        $fbsp_output = '  <div id="fb-root"></div><div class="fb-activity" data-site="'.$fbsp['data']['values']['fb_url'].'" data-width="'.$fbsp['data']['values']['fb_width'].'" data-height="'.$fbsp['data']['values']['fb_height'].'" data-header="'.$fbsp['data']['values']['fb_header'].'" data-font="'.$fbsp['data']['values']['fb_font'].'" data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" data-recommendations="'.$fbsp['data']['values']['fb_show_recom'].'" data-ref="'.$fbsp['data']['values']['fb_ref'].'"></div>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }
    }
    return $fbsp_output;

  } //end replace_fb_activity_tag


  //TWITTER
	function replace_twitter_tag($fb_twitter_id) {

		global $msp_js;

		$fb_twitter_id = intval(trim( $fb_twitter_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_twitter_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    $module_meta_thumb = array();
    $fbsp_data_text = '';
    $fbsp_data_url = '';
    $fbsp_data_via = '';
    $fbsp_data_related = '';
    $fbsp_data_counturl = '';
    $fbsp_data_hashtags = '';
    $fbsp_data_icn = '';
    $fbsp_data_count = '';

    $fbsp_usr_url = '';
    $fbsp_usr_hashtags = '';
    $fbsp_usr_text = '';
    $fbsp_usr_via = '';
    $fbsp_usr_related = '';
    $fbsp_usr_counturl = '';

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

      //url of actual page
      $fbsp['data']['values']['fb_pageurl'] = fbsp_get_pageurl();
      //main url
      $fbsp['data']['values']['fb_siteurl'] = PHPWCMS_URL;
      //set default page url
      $fbsp_data_counturl = ' data-counturl="'.$fbsp['data']['values']['fb_siteurl'].'"';
      $fbsp_usr_counturl = '&amp;counturl='.urlencode($fbsp['data']['values']['fb_siteurl']);

      //text
      if ($fbsp['data']['values']['fb_tweet_title'] == 1 && !empty($fbsp['data']['values']['fb_tweet_titletxt'])) {
        $fbsp_data_text = ' data-text="'.$fbsp['data']['values']['fb_tweet_titletxt'].'"';
        $fbsp_usr_text = '&amp;text='.urlencode($fbsp['data']['values']['fb_tweet_titletxt']);
      }

      //hashtag
      if ( !empty($fbsp['data']['values']['fb_tweet_hashtags']) ) {
        $fbsp['data']['values']['fb_tweet_hashtags'] = preg_replace('/[^0-9A-Za-z_,:]/m', '', $fbsp['data']['values']['fb_tweet_hashtags']);
        $fbsp_data_hashtags = ' data-hashtags="'.$fbsp['data']['values']['fb_tweet_hashtags'].'"';
        $fbsp_usr_hashtags = '&amp;hashtags='.urlencode($fbsp['data']['values']['fb_tweet_hashtags']);
      }

      //int url -> then set the base url
      if ($fbsp['data']['values']['fb_tweet_domain'] == 0 ) {
        $fbsp_data_url = ' data-url="'.$fbsp['data']['values']['fb_pageurl'].'"';
        $fbsp_usr_url = 'url='.urlencode($fbsp['data']['values']['fb_pageurl']);
      } else if ($fbsp['data']['values']['fb_tweet_domain'] == 1 && !empty($fbsp['data']['values']['fb_tweet_url'])) { //ext url
        if ( strlen($fbsp['data']['values']['fb_tweet_url']) == strcspn($fbsp['data']['values']['fb_tweet_url'],"'\"\\|<>") ) {
          $fbsp_data_url = ' data-url="'.$fbsp['data']['values']['fb_tweet_url'].'"';
          $fbsp_usr_url = 'url='.urlencode($fbsp['data']['values']['fb_tweet_url']);
        }
      }

      //recomm
      $fbsp_recom1output="";
      if (!empty($fbsp['data']['values']['fb_tweet_recom1'])) {
        $fbsp_recom1output = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_recom1']);
        $fbsp_data_via = ' data-via="'.$fbsp_recom1output.'"';
        $fbsp_usr_via = '&amp;via='.urlencode($fbsp_recom1output);
      }
      $fbsp_recom2output="";
      if (!empty($fbsp['data']['values']['fb_tweet_recom2'])) {
        $fbsp_recom2 = explode(":", $fbsp['data']['values']['fb_tweet_recom2'],2);
        if( isset($fbsp_recom2[1]) && $fbsp_recom2[1]){
          $fbsp_recom2[0] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp_recom2[0]);
          $fbsp_recom2[1] = preg_replace('/[\"\<\>\/\\\,]/m', '', $fbsp_recom2[1]);
          $fbsp_recom2output = implode(':', $fbsp_recom2);
        } else {
          $fbsp_recom2output =  preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_recom2']);
        }
        $fbsp_data_related = ' data-related="'.$fbsp_recom2output.'"';
        $fbsp_usr_related = '&amp;related='.urlencode($fbsp_recom2output);
      }

      //($fbsp['data']['values']['fb_tweet_button_count'] == 1) ? $fbsp_data_count = ' data-count="none"': $fbsp_data_count = '';
      if ( isset($fbsp['data']['values']['fb_tweet_count']) ) {
        $fbsp_data_count = ' data-count="'.$fbsp['data']['values']['fb_tweet_count'].'"';
      } else {
        $fbsp_data_count = ' data-count="none"';
      }

      //own image
      if ($fbsp['data']['values']['fb_id_img'] && $fbsp['data']['values']['fb_tweet_button']=='user') {
        $module_meta_thumb = fbsp_get_module_image ($fbsp['data']['values']['fb_id_img'], false, $fbsp['data']['values']['fb_img_width'], $fbsp['data']['values']['fb_img_height'], $fbsp['data']['values']['fb_img_crop']);
        if($module_meta_thumb) {
        //output own button
        $GLOBALS['block']['custom_htmlhead']["twitter_css"] = '  <style type="text/css" media="screen">
    #custom-tweet-button {
      width:'.$module_meta_thumb[1].'px;
      height:'.$module_meta_thumb[2].'px;
      position:relative;
      display: block;
      cursor:pointer;
      background: url(\''.PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0].'\') center center no-repeat;
    }
  </style>';
        $fbsp_output = '  <div id="custom-tweet-button" onclick="javascript: window.open(\'http://twitter.com/share?'.$fbsp_usr_url.$fbsp_usr_text.$fbsp_usr_hashtags.$fbsp_usr_via.$fbsp_usr_related.$fbsp_usr_counturl.'\', \'tweet\', \'location=1,status=1,width=550,height=450\');"></div>';
        }

      } else {

      ($fbsp['data']['values']['fb_tweet_button'] == 'large') ? $fbsp_data_icn = ' data-size="large"': $fbsp_data_icn = '';
        //output script version
        $fbsp_output = '  <a href="https://twitter.com/share" class="twitter-share-button" data-lang="'.$fbsp['data']['values']['fb_tweet_locale'].'"'.$fbsp_data_url.$fbsp_data_text.$fbsp_data_hashtags.$fbsp_data_count.$fbsp_data_via.$fbsp_data_related.$fbsp_data_icn.$fbsp_data_counturl.'>Tweet</a>';
        $msp_js['msp_tw-js'] = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
      }

    }

    return $fbsp_output;

  } //end replace_twitter_tag

  //TWITTER HASHTAG
	function replace_tw_hashtag($fb_twitter_id) {

		global $msp_js;

		$fb_twitter_id = intval(trim( $fb_twitter_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_twitter_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    $fbsp_data_hashtags = '';
    $fbsp_data_url = '';
    $fbsp_data_text = '';
    $fbsp_data_icn = '';
    $fbsp_data_related = '';


    if ( $fbsp['data']['fb_status'] == 1 && !empty($fbsp['data']['values']['fb_tweet_hashtags']) ) { //0=inactive, 1=active, 9=deleted

      //hashtag
      $fbsp['data']['values']['fb_tweet_hashtags'] = preg_replace('/[^0-9A-Za-z_:]/m', '', $fbsp['data']['values']['fb_tweet_hashtags']);
      $fbsp_data_hashtags = 'button_hashtag='.urlencode($fbsp['data']['values']['fb_tweet_hashtags']);

      //url of actual page
      $fbsp['data']['values']['fb_pageurl'] = fbsp_get_pageurl();
      //main url
      $fbsp['data']['values']['fb_siteurl'] = PHPWCMS_URL;
      //int url -> then set the base url
      // if fb_tweet_domain'] == 2 -> no url -> $fbsp_data_url remains =''
      if($fbsp['data']['values']['fb_tweet_domain'] == 0) {
        $fbsp_data_url = ' data-url="'.$fbsp['data']['values']['fb_pageurl'].'"';
      } else if ($fbsp['data']['values']['fb_tweet_domain'] == 1 && !empty($fbsp['data']['values']['fb_tweet_url'])) { //ext url
        if ( strlen($fbsp['data']['values']['fb_tweet_url']) == strcspn($fbsp['data']['values']['fb_tweet_url'],"'\"\\|<>") ) {
          $fbsp_data_url = ' data-url="'.$fbsp['data']['values']['fb_tweet_url'].'"';
        }
      }

      //text
      if ($fbsp['data']['values']['fb_tweet_title']==0){
        $fbsp_data_text = '&text='.urlencode(fbsp_get_pagetitle());
      } else if ($fbsp['data']['values']['fb_tweet_title'] == 1 && !empty($fbsp['data']['values']['fb_tweet_titletxt'])) {
        $fbsp_data_text = '&text='.urlencode($fbsp['data']['values']['fb_tweet_titletxt']);
      }

      //size
      ($fbsp['data']['values']['fb_tweet_button'] == 'large') ? $fbsp_data_icn = ' data-size="large"': $fbsp_data_icn = '';

      //recomm
      $fbsp_recomarr=array();
      if (!empty($fbsp['data']['values']['fb_tweet_recom1'])) {
        $fbsp_recom1 = explode(":", $fbsp['data']['values']['fb_tweet_recom1'],2);
        if($fbsp_recom1[1]){
          $fbsp_recom1[0] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp_recom1[0]);
          $fbsp_recom1[1] = preg_replace('/[\"\<\>\/\\\,]/m', '', $fbsp_recom1[1]);
          $fbsp_recomarr[] = implode(':', $fbsp_recom1);
        } else {
          $fbsp_recomarr[] =  preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_recom1']);
        }
      }
      if (!empty($fbsp['data']['values']['fb_tweet_recom2'])) {
        $fbsp_recom2 = explode(":", $fbsp['data']['values']['fb_tweet_recom2'],2);
        if($fbsp_recom2[1]){
          $fbsp_recom2[0] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp_recom2[0]);
          $fbsp_recom2[1] = preg_replace('/[\"\<\>\/\\\,]/m', '', $fbsp_recom2[1]);
          $fbsp_recomarr[] = implode(':', $fbsp_recom2);
        } else {
          $fbsp_recomarr[] =  preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_recom2']);
        }
      }
      if( count($fbsp_recomarr) ){
        $fbsp_data_related = ' data-related="';
        $fbsp_data_related .= implode(',', $fbsp_recomarr);
        $fbsp_data_related .= '"';
      }

      //output script version
      $fbsp_output = '  <a href="https://twitter.com/intent/tweet?'.$fbsp_data_hashtags.$fbsp_data_text.'" class="twitter-hashtag-button" data-lang="'.$fbsp['data']['values']['fb_tweet_locale'].'"'.$fbsp_data_url.$fbsp_data_icn.$fbsp_data_related.'>Tweet</a>';
      $msp_js['msp_tw-js'] = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';

    }

    return $fbsp_output;

  } //end replace_tw_hashtag

  //TWITTER MENTION
	function replace_tw_mention($fb_twitter_id) {

		global $msp_js;

		$fb_twitter_id = intval(trim( $fb_twitter_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_twitter_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    $fbsp_data_tweetto = '';
    $fbsp_data_text = '';
    $fbsp_data_icn = '';
    $fbsp_data_related = '';

    if ( $fbsp['data']['fb_status'] == 1 && !empty($fbsp['data']['values']['fb_tweet_tweetto']) ) { //0=inactive, 1=active, 9=deleted

      //mention
      $fbsp['data']['values']['fb_tweet_tweetto'] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_tweetto']);
      $fbsp_data_tweetto = 'screen_name='.urlencode($fbsp['data']['values']['fb_tweet_tweetto']);

      //text
      if ($fbsp['data']['values']['fb_tweet_title']==0){
        $fbsp_data_text = '&amp;text='.urlencode(fbsp_get_pagetitle());
      } else if ($fbsp['data']['values']['fb_tweet_title'] == 1 && !empty($fbsp['data']['values']['fb_tweet_titletxt'])) {
        $fbsp_data_text = '&amp;text='.urlencode($fbsp['data']['values']['fb_tweet_titletxt']);
      }

      //size
      ($fbsp['data']['values']['fb_tweet_button'] == 'large') ? $fbsp_data_icn = ' data-size="large"': $fbsp_data_icn = '';

      //recomm
      $fbsp_recomarr=array();
      if (!empty($fbsp['data']['values']['fb_tweet_recom1'])) {
        $fbsp_recom1 = explode(":", $fbsp['data']['values']['fb_tweet_recom1'],2);
        if($fbsp_recom1[1]){
          $fbsp_recom1[0] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp_recom1[0]);
          $fbsp_recom1[1] = preg_replace('/[\"\<\>\/\\\,]/m', '', $fbsp_recom1[1]);
          $fbsp_recomarr[] = implode(':', $fbsp_recom1);
        } else {
          $fbsp_recomarr[] =  preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_recom1']);
        }
      }
      if (!empty($fbsp['data']['values']['fb_tweet_recom2'])) {
        $fbsp_recom2 = explode(":", $fbsp['data']['values']['fb_tweet_recom2'],2);
        if($fbsp_recom2[1]){
          $fbsp_recom2[0] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp_recom2[0]);
          $fbsp_recom2[1] = preg_replace('/[\"\<\>\/\\\,]/m', '', $fbsp_recom2[1]);
          $fbsp_recomarr[] = implode(':', $fbsp_recom2);
        } else {
          $fbsp_recomarr[] =  preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_recom2']);
        }
      }
      if( count($fbsp_recomarr) ){
        $fbsp_data_related = ' data-related="';
        $fbsp_data_related .= implode(',', $fbsp_recomarr);
        $fbsp_data_related .= '"';
      }

      //output script version
      $fbsp_output = '  <a href="https://twitter.com/intent/tweet?'.$fbsp_data_tweetto.$fbsp_data_text.'" class="twitter-mention-button" data-lang="'.$fbsp['data']['values']['fb_tweet_locale'].'"'.$fbsp_data_icn.$fbsp_data_related.'>Tweet @'.$fbsp['data']['values']['fb_tweet_tweetto'].'</a>';
      $msp_js['msp_tw-js'] = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';

    }

    return $fbsp_output;

  } //end replace_tw_mention

  //TWITTER FOLLOW
	function replace_tw_follow($fb_twitter_id) {

		global $msp_js;

		$fb_twitter_id = intval(trim( $fb_twitter_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_twitter_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    $fbsp_data_followuser = '';
    $fbsp_data_show_screen_name = '';
    $fbsp_data_count = ' data-show-count="false"';
    $fbsp_data_icn = '';

    if ($fbsp['data']['fb_status'] == 1 && !empty($fbsp['data']['values']['fb_tweet_followuser']) ) { //0=inactive, 1=active, 9=deleted

      //follow user
      $fbsp['data']['values']['fb_tweet_followuser'] = preg_replace('/[^0-9A-Za-z_]/m', '', $fbsp['data']['values']['fb_tweet_followuser']);
      $fbsp_data_followuser = $fbsp['data']['values']['fb_tweet_followuser'];

      //show_screen_name
      ($fbsp['data']['values']['fb_tweet_showusername'] != 1) ? $fbsp_data_show_screen_name = ' data-show-screen-name="false"': $fbsp_data_show_screen_name = '';

      //size
      ($fbsp['data']['values']['fb_tweet_button'] == 'large') ? $fbsp_data_icn = ' data-size="large"': $fbsp_data_icn = '';

      //count
      if ( isset($fbsp['data']['values']['fb_tweet_count']) && $fbsp['data']['values']['fb_tweet_count'] == 'true' ) {
        $fbsp_data_count = ' data-count="true"';
      }

      //output script version
      $fbsp_output = '  <a href="https://twitter.com/'.$fbsp_data_followuser.'" class="twitter-follow-button" data-lang="'.$fbsp['data']['values']['fb_tweet_locale'].'"'.$fbsp_data_show_screen_name.$fbsp_data_icn.$fbsp_data_count.'>Follow @'.$fbsp_data_followuser.'</a>';
      $msp_js['msp_tw-js'] = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';

    }

    return $fbsp_output;

  } //end replace_tw_follow

  //TWITTERFOLLOW
	function replace_twitterfollow_tag($fb_twitterfollow_id) {

		$fb_twitterfollow_id = intval(trim( $fb_twitterfollow_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_twitterfollow_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted
      $GLOBALS['block']['custom_htmlhead']['css_twitter_follow'] = '    <style type="text/css">
        .twitter-follow-button img { border:none; }
    </style>
';
    $fbsp_output = '<span class="twitter-follow-button">'.$fbsp['data']['values']['fb_twitter_followcode'].'</span>';
    }
    return $fbsp_output;

  } //end replace_twitterfollow_tag


	function replace_twitterwidget_tag($fb_twitterwidget_id) {

		$fb_twitterwidget_id = intval(trim( $fb_twitterwidget_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_twitterwidget_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted
      $fbsp_output = $fbsp['data']['values']['fb_twitter_widgetcode'];
    }
    return $fbsp_output;

  } //end replace_twitterfollow_tag



  //LIKE
	function replace_fb_likeit_tag($fb_likeit_id) {

		global $content;
		global $news;
		global $phpwcms;
    global $msp_js;

		$fbsp_like_id = intval(trim( $fb_likeit_id[1]));

    $fbsp = array();
    $fbsp_output = '';
    
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fbsp_like_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    $fb_meta_thumb = '';
    $news_meta_thumb = false;
    $article_meta_thumb = false;
    $module_meta_thumb = false;

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

      if ($fbsp['data']['values']['fb_tit_fix']==1){
        // check if we are in single news view, then set news title, else set the articletitle
        if(isset($GLOBALS["_getVar"]["newsdetail"])){
          $fbsp['data']['values']['fb_title'] = ($news['result'][0]['cnt_title']) ? $news['result'][0]['cnt_title'] : $content["article_title"];
        } else if ($content["article_title"]) {
          $fbsp['data']['values']['fb_title'] = $content["article_title"];
        } //else it's the value from the module
      }
      if ($fbsp['data']['values']['fb_site_fix']==1){
        // check if we are in single news view, then set news title, else set the articletitle
        if(isset($GLOBALS['pagelayout']['layout_title'])){
          $fbsp['data']['values']['fb_site'] = $GLOBALS['pagelayout']['layout_title'];
        }  //else it's the value from the module
      }
      $fbsp['data']['values']['fb_show_faces'] = ($fbsp['data']['values']['fb_show_faces'] == 1) ? 'true':'false';

      //if dynamic use basic otherwise use given value
      if ($fbsp['data']['values']['fb_site_url_fix'] == 1) {
        $fbsp['data']['values']['fb_url'] = fbsp_get_pageurl();
      } else {
        if ( strlen($fbsp['data']['values']['fb_site_url']) == strcspn($fbsp['data']['values']['fb_site_url'],"'\"\\|<>") ) {
          $fbsp['data']['values']['fb_url'] = $fbsp['data']['values']['fb_site_url'];
        }
      }
      //enym new end

      _set_meta('og:title',$fbsp['data']['values']['fb_title'],'property');
      _set_meta('og:type',$fbsp['data']['values']['fb_type'],'property');
      _set_meta_url('og:url',$fbsp['data']['values']['fb_url'],'property');
      _set_meta('og:site_name',$fbsp['data']['values']['fb_site'],'property');
      _set_meta('fb:admins',$fbsp['data']['values']['fb_admins'],'property');
      _set_meta('fb:app_id',$fbsp['data']['values']['fb_app_id'],'property');
     // _set_meta('og:description',"blabla",'property');
      _set_meta('og:latitude',$fbsp['data']['values']['fb_latitude'],'property');
      _set_meta('og:longitude',$fbsp['data']['values']['fb_longitude'],'property');
      _set_meta('og:street-address',$fbsp['data']['values']['fb_streetaddress'],'property');
      _set_meta('og:locality',$fbsp['data']['values']['fb_locality'],'property');
      _set_meta('og:region',$fbsp['data']['values']['fb_region'],'property');
      _set_meta('og:postal-code',$fbsp['data']['values']['fb_postal'],'property');
      _set_meta('og:country-name',$fbsp['data']['values']['fb_country'],'property');
      _set_meta('og:email',$fbsp['data']['values']['fb_email'],'property');
      _set_meta('og:phone_number',$fbsp['data']['values']['fb_phonenumber'],'property');
      _set_meta('og:fax_number',$fbsp['data']['values']['fb_faxnumber'],'property');


      $fbsp_send = "false";
      $fbsp_output = "";
      $fbsp_fb_app_id = '';
      $fbsp_fb_app_id2 = '';
      if( isset($fbsp['data']['values']['fb_app_id']) && $fbsp['data']['values']['fb_app_id']){
        $fbsp_fb_app_id = '&appId='.$fbsp['data']['values']['fb_app_id'];
        $fbsp_fb_app_id2 = '&amp;appId='.$fbsp['data']['values']['fb_app_id'];
      }

      if($fbsp['data']['values']['fb_output_type'] == 'iframe') {
        //$fbsp_output = '  <iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($fbsp['data']['values']['fb_url']).'&amp;send=false&amp;layout='.$fbsp['data']['values']['fb_layout'].'&amp;show_faces='.$fbsp['data']['values']['fb_show_faces'].'&amp;width='.$fbsp['data']['values']['fb_width'].'&amp;action='.$fbsp['data']['values']['fb_action'].'&amp;font='.$fbsp['data']['values']['fb_font'].'&amp;colorscheme='.$fbsp['data']['values']['fb_colorscheme'].'&amp;height='.$fbsp['data']['values']['fb_height'].'&amp;ref='.$fbsp['data']['values']['fb_ref'].'&amp;locale='.$fbsp['data']['values']['fb_locale'].'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$fbsp['data']['values']['fb_width'].'px; height:'.$fbsp['data']['values']['fb_height'].'px;'.$fbsp['data']['values']['fb_iframe_style'].'" allowTransparency="true"></iframe>';
        $fbsp_output = '  <iframe src="//www.facebook.com/plugins/like.php?href='.urlencode($fbsp['data']['values']['fb_url']).'&amp;width='.$fbsp['data']['values']['fb_width'].'&amp;height='.$fbsp['data']['values']['fb_height'].'&amp;colorscheme='.$fbsp['data']['values']['fb_colorscheme'].'&amp;layout='.$fbsp['data']['values']['fb_layout'].'&amp;action='.$fbsp['data']['values']['fb_action'].'&amp;show_faces='.$fbsp['data']['values']['fb_show_faces'].'&amp;send=false&amp;font='.$fbsp['data']['values']['fb_font'].'&amp;ref='.$fbsp['data']['values']['fb_ref'].'&amp;locale='.$fbsp['data']['values']['fb_locale'].$fbsp_fb_app_id2.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$fbsp['data']['values']['fb_width'].'px; height:'.$fbsp['data']['values']['fb_height'].'px;'.$fbsp['data']['values']['fb_iframe_style'].'" allowTransparency="true"></iframe>';
      } else if ($fbsp['data']['values']['fb_output_type'] == 'xfbml'){
        if ( $fbsp['data']['values']['fb_send'] == 1 ) {
          $fbsp_send = "true";
        }
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"></script><div id="fb-root"></div><fb:like href="'.$fbsp['data']['values']['fb_url'].'" send="'.$fbsp_send.'" layout="'.$fbsp['data']['values']['fb_layout'].'" show_faces="'.$fbsp['data']['values']['fb_show_faces'].'" width="'.$fbsp['data']['values']['fb_width'].'" action="'.$fbsp['data']['values']['fb_action'].'" font="'.$fbsp['data']['values']['fb_font'].'" colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" ref="'.$fbsp['data']['values']['fb_ref'].'"></fb:like>';
        $fbsp_output = '  <div id="fb-root"></div><fb:like href="'.$fbsp['data']['values']['fb_url'].'" send="'.$fbsp_send.'" layout="'.$fbsp['data']['values']['fb_layout'].'" show_faces="'.$fbsp['data']['values']['fb_show_faces'].'" width="'.$fbsp['data']['values']['fb_width'].'" action="'.$fbsp['data']['values']['fb_action'].'" font="'.$fbsp['data']['values']['fb_font'].'" colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" ref="'.$fbsp['data']['values']['fb_ref'].'"></fb:like>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }  else if ($fbsp['data']['values']['fb_output_type'] == 'html5'){
        if ( $fbsp['data']['values']['fb_send'] == 1 ) {
          $fbsp_send = "true";
        }
        $fbsp_output = '  <div id="fb-root"></div><div class="fb-like" data-href="'.$fbsp['data']['values']['fb_url'].'" data-send="'.$fbsp_send.'" data-layout="'.$fbsp['data']['values']['fb_layout'].'" data-width="'.$fbsp['data']['values']['fb_width'].'" data-show-faces="'.$fbsp['data']['values']['fb_show_faces'].'" data-font="'.$fbsp['data']['values']['fb_font'].'" data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'" data-ref="'.$fbsp['data']['values']['fb_ref'].'"></div>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }

      if($fbsp['data']['values']['fb_img_fix'] == 1) {
        //news image
        $news_meta_thumb = fbsp_get_news_image(true);
        //article image
        $article_meta_thumb = fbsp_get_article_image(true);
      }

      $module_meta_thumb = fbsp_get_module_image($fbsp['data']['values']['fb_id_img'], true);

      if($fbsp['data']['values']['fb_img_fix'] == 1) {

        if($news_meta_thumb != false) {
           $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$news_meta_thumb[0];
        } else if($article_meta_thumb != false) {
           $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$article_meta_thumb[0];
        } else if($module_meta_thumb != false) {
           $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0];
        } else {
           $fb_meta_thumb = '';
        }
      } else if($module_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0];
      } else {
          $fb_meta_thumb = '';
      }

      if($fb_meta_thumb){
        _set_meta('og:image',$fb_meta_thumb,'property');
        $GLOBALS['block']['custom_htmlhead']["image_src"] = '  <link rel="image_src" href="'.$fb_meta_thumb.'" />';
      }
    }

    return $fbsp_output;
	}


  //FB COMM
	function replace_fb_comm_tag($fb_comm_id) {

		global $content;
    global $msp_js;
		$fb_comm_id = intval(trim( $fb_comm_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_comm_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;


    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted
      if ($fbsp['data']['values']['fb_tit_fix']==1){
        // check if we are in single news view, then set news title, else set the articletitle
        if(isset($GLOBALS["_getVar"]["newsdetail"])){
          $fbsp['data']['values']['fb_title'] = ($news['result'][0]['cnt_title']) ? $news['result'][0]['cnt_title'] : $content["article_title"];
        } else if ($content["article_title"]) {
          $fbsp['data']['values']['fb_title'] = $content["article_title"];
        } //else it's the value from the module
      }
      if ($fbsp['data']['values']['fb_site_fix']==1){
        // check if we are in single news view, then set news title, else set the articletitle
        if($GLOBALS['pagelayout']['layout_title']){
          $fbsp['data']['values']['fb_site'] = $GLOBALS['pagelayout']['layout_title'];
        }  //else it's the value from the module
      }

      //enym replace
      //if dynamic use basic otherwise use given value
      if ($fbsp['data']['values']['fb_site_url_fix'] == 1) {
          $fbsp['data']['values']['fb_url'] = fbsp_get_pageurl();
      } else {
        if ( strlen($fbsp['data']['values']['fb_site_url']) == strcspn($fbsp['data']['values']['fb_site_url'],"'\"\\|<>") ) {
          $fbsp['data']['values']['fb_url'] = $fbsp['data']['values']['fb_site_url'];
        }
      }
      //enym replace end


      _set_meta('og:title',$fbsp['data']['values']['fb_title'],'property');
      _set_meta_url('og:url',$fbsp['data']['values']['fb_url'],'property');
      _set_meta('og:site_name',$fbsp['data']['values']['fb_site'],'property');
      _set_meta('fb:admins',$fbsp['data']['values']['fb_admins'],'property');
      _set_meta('fb:app_id',$fbsp['data']['values']['fb_app_id'],'property');

      $fbsp_fb_app_id = '';
      if( isset($fbsp['data']['values']['fb_app_id']) && $fbsp['data']['values']['fb_app_id']){
        $fbsp_fb_app_id = '&appId='.$fbsp['data']['values']['fb_app_id'];
      }

      if ($fbsp['data']['values']['fb_output_type'] == 'xfbml'){
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#appId='.$fbsp['data']['values']['fb_app_id'].'&amp;xfbml=1"></script><div id="fb-root"></div><fb:comments href="'.$fbsp['data']['values']['fb_url'].'" num_posts="'.$fbsp['data']['values']['fb_comm_nr'].'" width="'.$fbsp['data']['values']['fb_width'].' "colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'"></fb:comments>'; //enym added "colorscheme='.$fbsp['data']['values']['fb_colorscheme'].'
        $fbsp_output = '  <div id="fb-root"></div><fb:comments href="'.$fbsp['data']['values']['fb_url'].'" num_posts="'.$fbsp['data']['values']['fb_comm_nr'].'" width="'.$fbsp['data']['values']['fb_width'].' "colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'"></fb:comments>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }else if ($fbsp['data']['values']['fb_output_type'] == 'html5'){
        //$fbsp_output = '  <script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#appId='.$fbsp['data']['values']['fb_app_id'].'&amp;xfbml=1"></script><div id="fb-root"></div><div class="fb-comments" data-href="'.$fbsp['data']['values']['fb_url'].'" data-num_posts="'.$fbsp['data']['values']['fb_comm_nr'].'" data-width="'.$fbsp['data']['values']['fb_width'].' "data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'"></div>';
        $fbsp_output = '  <div id="fb-root"></div><div class="fb-comments" data-href="'.$fbsp['data']['values']['fb_url'].'" data-num_posts="'.$fbsp['data']['values']['fb_comm_nr'].'" data-width="'.$fbsp['data']['values']['fb_width'].' "data-colorscheme="'.$fbsp['data']['values']['fb_colorscheme'].'"></div>';
        $msp_js['msp_fb-js'] = '(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1&status=0"; fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
      }

    }
    return $fbsp_output;
	}
	
  //GOOGLE
	function replace_google_tag($fb_google_id) {

		$fb_google_id = intval(trim( $fb_google_id[1]));
    $fbsp = array();
    $fbsp_output = '';
    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
		$sql .= "fb_id = " . aporeplace($fb_google_id) . ' LIMIT 1';
		$fbsp['data'] = _dbQuery($sql);

		if( isset($fbsp['data'][0]) ) {
			$fbsp['data'] = $fbsp['data'][0];
			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
    } else return;

    $fb_meta_thumb = '';
    $news_meta_thumb = false;
    $article_meta_thumb = false;
    $module_meta_thumb = false;

    $fbsp_data_href = '';
    $fbsp_data_size = 'default';
    $fbsp_data_annotation = 'none';
    $fbsp_data_locale = 'en_US';

    if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

      //url of actual page
      $fbsp['data']['values']['fb_pageurl'] = fbsp_get_pageurl();

      //main url
      $fbsp['data']['values']['fb_siteurl'] = PHPWCMS_URL;
      //set href
      //int url -> then set the base url
      if ($fbsp['data']['values']['fb_google_domain'] == 0 ) {
        $fbsp_data_href = ' data-href="'.$fbsp['data']['values']['fb_pageurl'].'"';

      } else if ($fbsp['data']['values']['fb_google_domain'] == 1 && !empty($fbsp['data']['values']['fb_google_url'])) { //ext url
        if ( strlen($fbsp['data']['values']['fb_google_url']) == strcspn($fbsp['data']['values']['fb_google_url'],"'\"\\|<>") ) {
          $fbsp_data_href = ' data-href="'.$fbsp['data']['values']['fb_google_url'].'"';
        }
      }
      //set size
      $fbsp_data_size = ' data-size="'.$fbsp['data']['values']['fb_google_size'].'"';
      //set annotation
      $fbsp_data_annotation = ' data-annotation="'.$fbsp['data']['values']['fb_google_annotation'].'"';
      //set locale
      $fbsp_data_locale = $fbsp['data']['values']['fb_google_locale'];


      //set title - may conflict with other buttons
      if ($fbsp['data']['values']['fb_google_title'] == 1 && !empty($fbsp['data']['values']['fb_google_titletxt'])) {
        _set_meta('og:title',$fbsp['data']['values']['fb_google_titletxt'],'property');
      }

      //news image
      $news_meta_thumb = fbsp_get_news_image();

      //article image
      $article_meta_thumb = fbsp_get_article_image();

      //module image
      if ( $fbsp['data']['values']['fb_id_img'] ) {
        $module_meta_thumb = fbsp_get_module_image ($fbsp['data']['values']['fb_id_img'], true);
      }

      //take 1. module image, 2. News image, 3. Article Image
      if($module_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0];
      } else if($news_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$news_meta_thumb[0];
      } else if($article_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$article_meta_thumb[0];
      } else {
          $fb_meta_thumb = '';
      }

      //this may conflict with other buttons
      if($fb_meta_thumb){
        _set_meta('og:image',$fb_meta_thumb,'property');
      }


      //output script version
      $fbsp_output = "    <div class='g-plusone'".$fbsp_data_size.$fbsp_data_annotation.$fbsp_data_href."></div>
    <script type='text/javascript'>
      window.___gcfg = {
        lang: '".$fbsp_data_locale."'
      };
      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>".LF;

    }

    return $fbsp_output;

  } //end replace_google_tag


  function _set_meta($name='', $content='', $attribute = 'name') {
     if(empty($name) || empty($content)) {
        return NULL;
     }
     $GLOBALS['block']['custom_htmlhead']['meta.'.$name]  = '  <meta ';
     $GLOBALS['block']['custom_htmlhead']['meta.'.$name] .= $attribute;
     $GLOBALS['block']['custom_htmlhead']['meta.'.$name] .= '="' . $name . '" content="'.html_specialchars($content).'" />';
  }

  function _set_meta_url($name='', $content='', $attribute = 'name') {
     if(empty($name) || empty($content)) {
        return NULL;
     }
     $GLOBALS['block']['custom_htmlhead']['meta.'.$name]  = '  <meta ';
     $GLOBALS['block']['custom_htmlhead']['meta.'.$name] .= $attribute;
     $GLOBALS['block']['custom_htmlhead']['meta.'.$name] .= '="' . $name . '" content="'.$content.'" />';
  }

  function fbsp_get_news_image ($resize=false) {
		global $news;
    $news_img_size=array();
    $news_img_width = 500;
    $news_img_height = 500;
    $news_img_crop=true;
    $news_meta_thumb = false;

      //news image
      //if($news['result'][0]['cnt_title'] && count($news['result']) == 1) {
      if(!$news['list_mode'] && count($news['result']) <= 1) {

        $news_cnt_object = unserialize($news['result'][0]['cnt_object']);

        if($news_cnt_object['cnt_image']['id']) {

  		    $sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_trash=0 AND f_id='.aporeplace($news_cnt_object['cnt_image']['id']).' AND f_ext IN ("jpg", "png", "gif") ORDER BY f_name';
          $fbsp['img_news'] = _dbQuery($sql);
          if( isset($fbsp['img_news'][0]) ) $fbsp['img_news'] = $fbsp['img_news'][0];

          //check for dimensions
          $news_img_size = getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$fbsp['img_news']['f_hash'] . '.' . $fbsp['img_news']['f_ext']);

          if($resize){
                if ($news_img_size[0] >= $news_img_size[1]) { //landscape

                  if ($news_img_size[1] < $news_img_size[0] / 3) {
                  //aspect ratio over 1/3 - if height over 500px scale down and crop to 500x1500px
                  //else crop to max aspect ratio 1/3
                    $news_img_height = (($news_img_size[1]) > 500) ? 500 : $news_img_size[1];
                    $news_img_width = (($news_img_size[1]) > 500) ? 1500 : $news_img_size[1]*3;
                    $news_img_crop = true;
                  } else {
                  //aspect ratio OK just scale down if height over 500px
                    $news_img_height = ($news_img_size[1] > 500) ? 500 : $news_img_size[1];
                    $news_img_width = "";
                    $news_img_crop = false;
                  }
                } else { //portrait
                //crop to square with max size of 500x500px
                  $news_img_height = ($news_img_size[0] > 500) ? 500 : $news_img_size[0];
                  $news_img_width = ($news_img_size[0] > 500) ? 500 : $news_img_size[0];
                  $news_img_crop = true;
                }
          } else {
            //get values from original image
                $news_img_width = $news_img_size[0];
                $news_img_height = $news_img_size[1];
                $news_img_crop = false;
          }

          $news_meta_thumb = get_cached_image(
              array(   "target_ext"   =>   $fbsp['img_news']['f_ext'],
                    "image_name"   =>   $fbsp['img_news']['f_hash'] . '.' . $fbsp['img_news']['f_ext'],
                    "max_width"      =>   $news_img_width,
                    "max_height"   =>   $news_img_height,
                    "crop_image"   =>  $news_img_crop,
                    "thumb_name"   =>   md5($fbsp['img_news']['f_hash'].$news_img_width.$news_img_height.$GLOBALS['phpwcms']["sharpen_level"].$news_img_crop)
              ));

        }
      }
    return $news_meta_thumb;
  }

  function fbsp_get_article_image ($resize=false) {
		global $content;
    $article_img_size=array();
    $article_img_width = 500;
    $article_img_height = 500;
    $article_img_crop=true;
    $article_meta_thumb = false;
      //article image
        if(!empty($content['articles'][$content['article_id']]['article_image']["hash"])) {

            //check for dimensions
            $article_img_size = getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$content['articles'][$content['article_id']]['article_image']['hash'] . '.' . $content['articles'][$content['article_id']]['article_image']['ext']);
          if($resize){
                if ($article_img_size[0] >= $article_img_size[1]) { //landscape

                  if ($article_img_size[1] < $article_img_size[0] / 3) {
                  //aspect ratio over 1/3 - if height over 500px scale down and crop to 500x1500px
                  //else crop to max aspect ratio 1/3
                    $article_img_height = (($article_img_size[1]) > 500) ? 500 : $article_img_size[1];
                    $article_img_width = (($article_img_size[1]) > 500) ? 1500 : $article_img_size[1]*3;
                    $article_img_crop = true;
                  } else {
                  //aspect ratio OK just scale down if height over 500px
                    $article_img_height = ($article_img_size[0] > 500) ? 500 : $article_img_size[1];
                    $article_img_width = "";
                    $article_img_crop = false;
                  }
                } else { //portrait
                //crop to square with max size of 500x500px
                  $article_img_width = ($article_img_size[0] > 500) ? 500 : $article_img_size[0];
                  $article_img_height = ($article_img_size[0] > 500) ? 500 : $article_img_size[0];
                  $article_img_crop = true;
                }
          } else {
            //get values from orig image
                $article_img_width = $article_img_size[0];
                $article_img_height = $article_img_size[1];
                $article_img_crop = false;
          }
          $article_meta_thumb = get_cached_image(
                array(   "target_ext"   =>   $content['articles'][$content['article_id']]['article_image']['ext'],
                      "image_name"   =>   $content['articles'][$content['article_id']]['article_image']['hash'] . '.' . $content['articles'][$content['article_id']]['article_image']['ext'],
                      "max_width"      =>   $article_img_width,
                      "max_height"   =>   $article_img_height,
                      "crop_image"   =>  $article_img_crop,
                      "thumb_name"   =>   md5($content['articles'][$content['article_id']]['article_image']['hash'].$article_img_width.$article_img_height.$GLOBALS['phpwcms']["sharpen_level"])
                ));
        }

    return $article_meta_thumb;
  }


  function fbsp_get_module_image ($fb_id_img, $resize=false, $w='', $h='', $c=0) {
      $module_img_size=array();
      $module_img_width = 500;
      $module_img_height = 500;
      $module_img_crop=false;
      $module_meta_thumb = false;

      $sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_trash=0 AND f_id='.aporeplace($fb_id_img).' AND f_ext IN ("jpg", "png", "gif") ORDER BY f_name';
      $fbsp['img_module'] = _dbQuery($sql);
      if( isset($fbsp['img_module'][0]) ) {

          $fbsp['img_module'] = $fbsp['img_module'][0];

          //check for dimensions
          $module_img_size = getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$fbsp['img_module']['f_hash'] . '.' . $fbsp['img_module']['f_ext']);
          if($resize){
              if ($module_img_size[0] >= $module_img_size[1]) { //landscape
                if ($module_img_size[1] < $module_img_size[0] / 3) {
                  //aspect ratio over 1/3 - if height over 500px scale down and crop to 500x1500px
                  //else crop to max aspect ratio 1/3
                  $module_img_height = ($module_img_size[1] > 500) ? 500 : $module_img_size[1];
                  $module_img_width = ($module_img_size[1] > 500) ? 1500 : $module_img_size[1]*3;
                  $module_img_crop = true;
                } else {
                  //aspect ratio OK just scale down if height over 500px
                  $module_img_height = ($module_img_size[1] > 500) ? 500 : $module_img_size[1];
                  $module_img_width = "";
                  $module_img_crop = false;
                }
              } else { //portrait
                //crop to square with max size of 500x500px
                $module_img_width = ($module_img_size[0] > 500) ? 500 : $module_img_size[0];
                $module_img_height = ($module_img_size[0] > 500) ? 500 : $module_img_size[0];
                $module_img_crop = true;
              }
          } else {
            //get values from function call
                $module_img_width = (isset($w)) ? intval($w) : $module_img_size[0];
                $module_img_height = (isset($h)) ? intval($h) : $module_img_size[1];
                $module_img_crop =  (isset($c)) ? intval($c) : 0;
          }
          $module_meta_thumb = get_cached_image(
          array(   "target_ext"   =>   $fbsp['img_module']['f_ext'],
                "image_name"   =>   $fbsp['img_module']['f_hash'] . '.' . $fbsp['img_module']['f_ext'],
                "max_width"      =>   $module_img_width,
                "max_height"   =>   $module_img_height,
                "crop_image"   =>  $module_img_crop,
                "thumb_name"   =>   md5($fbsp['img_module']['f_hash'].$module_img_width.$module_img_height.$GLOBALS['phpwcms']["sharpen_level"].$module_img_crop)
          ));
      }
    return $module_meta_thumb;
  }

  //get the actual page url
  function fbsp_get_pageurl() {

		global $content;
    global $block;
    $fbsp_pageurl = PHPWCMS_URL;

    if (!isset($content['article_id'])) {
        $fbsp_pageurl = PHPWCMS_URL.'index.php?id='.$content['cat_id'];
    } else {
        if(isset($GLOBALS["_getVar"]["newsdetail"])) {
          $fbsp_pageurl = PHPWCMS_URL.'index.php?aid='.$content['article_id'].'&newsdetail='.$GLOBALS["_getVar"]["newsdetail"];
        } else {
          $fbsp_pageurl = PHPWCMS_URL.'index.php?aid='.$content['article_id'];
        }
    }

    if($content['set_canonical'] && $block['custom_htmlhead']['canonical']) {
      preg_match('/<link\b[^<>]*?\bhref\s*=\s*("([^"]*)"|(\'[^\']*)\'|([^\'">]+))/is',$block['custom_htmlhead']['canonical'], $matches);
      if ($matches[2]) {
        $fbsp_pageurl = $matches[2];
      }
    }
    return $fbsp_pageurl;
  }

  //get the actual page title
  function fbsp_get_pagetitle() {
    $fbsp_pagetitle = html_specialchars($GLOBALS["content"]["pagetitle"]);
    return $fbsp_pagetitle;
  }




	function replace_twoclick_tag($fbsp_twoclick_ids, $twoclick_fb) {
/*
$fbsp_twoclick_ids = array(fb => id_fb, tw => id_tw, go => id_go,)
$twoclick_fb = array( values twoclick entry db)
*/
		global $content;
		global $news;
		global $phpwcms;

    $fbsp_output = "";
    $fbsp_output_fb_js = "
    facebook : {
            'status' : 'off'
        },";
    $fbsp_output_tw_js = "
    twitter : {
            'status' : 'off'
        },";
    $fbsp_output_go_js = "
    gplus : {
            'status' : 'off'
        }";
    $fbsp = array();

//get tha actual page url
    $fbsp_pageurl = fbsp_get_pageurl();

//facebook likeit
    $fb_meta_thumb = '';
    $news_meta_thumb = false;
    $article_meta_thumb = false;
    $module_meta_thumb = false;
    if ( isset($fbsp_twoclick_ids['fb']) && $fbsp_twoclick_ids['fb'] > 0 ) {
  		$fbsp_like_id = intval(trim($fbsp_twoclick_ids['fb']));

      $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
  		$sql .= "fb_id = " . aporeplace($fbsp_like_id) . ' LIMIT 1';
  		$fbsp['data'] = _dbQuery($sql);
  		if( isset($fbsp['data'][0]) ) {
  			$fbsp['data'] = $fbsp['data'][0];
  			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
      }
        if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

          if ($fbsp['data']['values']['fb_tit_fix']==1){
            // check if we are in single news view, then set news title, else set the articletitle
            if(isset($GLOBALS["_getVar"]["newsdetail"])){
              $fbsp['data']['values']['fb_title'] = ($news['result'][0]['cnt_title']) ? $news['result'][0]['cnt_title'] : $content["article_title"];
            } else if ($content["article_title"]) {
              $fbsp['data']['values']['fb_title'] = $content["article_title"];
            } //else it's the value from the module
          }
          if ($fbsp['data']['values']['fb_site_fix']==1){
            // check if we are in single news view, then set news title, else set the articletitle
            if(isset($GLOBALS['pagelayout']['layout_title'])){
              $fbsp['data']['values']['fb_site'] = $GLOBALS['pagelayout']['layout_title'];
            }  //else it's the value from the module
          }

          if ($fbsp['data']['values']['fb_site_url_fix'] == 1) {
              $fbsp['data']['values']['fb_url'] = $fbsp_pageurl;
          } else {
              $fbsp['data']['values']['fb_url'] = $fbsp['data']['values']['fb_site_url'];
          }

          $fbsp['data']['values']['fb_show_faces'] = ($fbsp['data']['values']['fb_show_faces'] == 1) ? 'true':'false';
          $fbsp_twoclick_perm_fb = ($twoclick_fb['values']['fb_twoclick_perm_fb'] == 0) ? 'off' : 'on' ;
          $fbsp_send = "false";

          if ( $fbsp['data']['values']['fb_send'] == 1 && $fbsp['data']['values']['fb_output_type'] != 'iframe' ) {
            $fbsp_send = "true";
          }
          $fbsp_output_fb_js = "
      facebook : {
              'status'            : 'on',
              'dummy_img'         : '".$phpwcms['modules']['br_socialplugins']['dir']."template/socialshareprivacy/images/dummy_facebook.png',
              'txt_info'          : '".rawurlencode($twoclick_fb['values']['fb_twoclick_txt_but'])."',
              'txt_fb_off'        : 'not connected to Facebook',
              'txt_fb_on'         : 'connected to Facebook',
              'perma_option'      : '".$fbsp_twoclick_perm_fb."',
              'display_name'      : 'Facebook',
              'language'          : '".$fbsp['data']['values']['fb_locale']."',
              'href'              : '".urlencode($fbsp['data']['values']['fb_url'])."',
              'url'              : '".$fbsp['data']['values']['fb_url']."',
              'layout'            : '".$fbsp['data']['values']['fb_layout']."',
              'show_faces'        : '".$fbsp['data']['values']['fb_show_faces']."',
              'width'             : '".$fbsp['data']['values']['fb_width']."',
              'action'            : '".$fbsp['data']['values']['fb_action']."',
              'font'              : '".$fbsp['data']['values']['fb_font']."',
              'colorscheme'       : '".$fbsp['data']['values']['fb_colorscheme']."',
              'height'            : '".$fbsp['data']['values']['fb_height']."',
              'referrer_track'    : '".$fbsp['data']['values']['fb_ref']."',
              'send'              : '".$fbsp_send."',
              'appid'             : '".$fbsp['data']['values']['fb_app_id']."',
              'cssstyle'          : '".$fbsp['data']['values']['fb_iframe_style']."',
              'output'            : '".$fbsp['data']['values']['fb_output_type']."'
            },";

            _set_meta('og:title',$fbsp['data']['values']['fb_title'],'property');
            _set_meta('og:type',$fbsp['data']['values']['fb_type'],'property');
            _set_meta_url('og:url',$fbsp['data']['values']['fb_url'],'property');
            _set_meta('og:site_name',$fbsp['data']['values']['fb_site'],'property');
            _set_meta('fb:admins',$fbsp['data']['values']['fb_admins'],'property');
            _set_meta('fb:app_id',$fbsp['data']['values']['fb_app_id'],'property');
           // _set_meta('og:description',"blabla",'property');
            _set_meta('og:latitude',$fbsp['data']['values']['fb_latitude'],'property');
            _set_meta('og:longitude',$fbsp['data']['values']['fb_longitude'],'property');
            _set_meta('og:street-address',$fbsp['data']['values']['fb_streetaddress'],'property');
            _set_meta('og:locality',$fbsp['data']['values']['fb_locality'],'property');
            _set_meta('og:region',$fbsp['data']['values']['fb_region'],'property');
            _set_meta('og:postal-code',$fbsp['data']['values']['fb_postal'],'property');
            _set_meta('og:country-name',$fbsp['data']['values']['fb_country'],'property');
            _set_meta('og:email',$fbsp['data']['values']['fb_email'],'property');
            _set_meta('og:phone_number',$fbsp['data']['values']['fb_phonenumber'],'property');
            _set_meta('og:fax_number',$fbsp['data']['values']['fb_faxnumber'],'property');

            if($fbsp['data']['values']['fb_img_fix'] == 1) {
              //news image
              $news_meta_thumb = fbsp_get_news_image(true);
              //article image
              $article_meta_thumb = fbsp_get_article_image(true);
            }
            $module_meta_thumb = fbsp_get_module_image($fbsp['data']['values']['fb_id_img'], true);

            if($fbsp['data']['values']['fb_img_fix'] == 1) {

              if($news_meta_thumb != false) {
                 $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$news_meta_thumb[0];
              } else if($article_meta_thumb != false) {
                 $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$article_meta_thumb[0];
              } else if($module_meta_thumb != false) {
                 $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0];
              } else {
                 $fb_meta_thumb = '';
              }
            } else if($module_meta_thumb != false) {
                $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0];
            } else {
                $fb_meta_thumb = '';
            }

            if($fb_meta_thumb){
              _set_meta('og:image',$fb_meta_thumb,'property');
              $GLOBALS['block']['custom_htmlhead']["image_src"] = '  <link rel="image_src" href="'.$fb_meta_thumb.'" />';
            }

        $fbsp_output .= '<div id="fb-root"></div>';

        } //end if status 1
    } //end if id in RT
//end facebook likeit

unset($fbsp['data']);
    $fb_meta_thumb = '';
    $news_meta_thumb = false;
    $article_meta_thumb = false;
    $module_meta_thumb = false;
//twitter tweet

    if ( isset($fbsp_twoclick_ids['tw']) && $fbsp_twoclick_ids['tw'] > 0 ) {
  		$fb_twitter_id = intval(trim($fbsp_twoclick_ids['tw']));

      $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
  		$sql .= "fb_id = " . aporeplace($fb_twitter_id) . ' LIMIT 1';
  		$fbsp['data'] = _dbQuery($sql);

  		if( isset($fbsp['data'][0]) ) {
  			$fbsp['data'] = $fbsp['data'][0];
  			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
      }

      if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

        $fbsp['data']['values']['fb_pageurl'] = $fbsp_pageurl;
          
        $fbsp_data_text = '';
        $fbsp_data_url = '';
        $fbsp_data_via = '';
        $fbsp_data_related = '';
        $fbsp_data_counturl = '';
        $fbsp_data_lang = 'en';

          //text
          if ($fbsp['data']['values']['fb_tweet_title'] == 1 && !empty($fbsp['data']['values']['fb_tweet_titletxt'])) {
            $fbsp_data_text = $fbsp['data']['values']['fb_tweet_titletxt'];
          }
          //int url -> then set the base url
          if ($fbsp['data']['values']['fb_tweet_domain'] == 0 ) {
            $fbsp_data_url = $fbsp['data']['values']['fb_pageurl'];
          } else if ($fbsp['data']['values']['fb_tweet_domain'] == 1 && !empty($fbsp['data']['values']['fb_tweet_url'])) { //ext url
            $fbsp_data_url = $fbsp['data']['values']['fb_tweet_url'];
          }
          if (!empty($fbsp['data']['values']['fb_tweet_recom1'])) {
            $fbsp_data_via = $fbsp['data']['values']['fb_tweet_recom1'];
          }
          if (!empty($fbsp['data']['values']['fb_tweet_recom2'])) {
            $fbsp_data_related = $fbsp['data']['values']['fb_tweet_recom2'];
          }

        $fbsp_data_counturl = PHPWCMS_URL;
        ($fbsp['data']['values']['fb_tweet_button'] == 'large') ? $fbsp_data_size = 'large': $fbsp_data_size = '';
        //($fbsp['data']['values']['fb_tweet_button_count'] == 1) ? $fbsp_data_count = 'none': $fbsp_data_count = '';
        $fbsp_data_count = $fbsp['data']['values']['fb_tweet_count'];
        $fbsp_data_lang = $fbsp['data']['values']['fb_tweet_locale'];

        $fbsp_twoclick_perm_tw = ($twoclick_fb['values']['fb_twoclick_perm_tw'] == 0) ? 'off' : 'on' ;


      $fbsp_output_tw_js = "
      twitter : {
              'status'            : 'on',
              'dummy_img'         : '".$phpwcms['modules']['br_socialplugins']['dir']."template/socialshareprivacy/images/dummy_twitter.png',
              'txt_info'          : '".rawurlencode($twoclick_fb['values']['fb_twoclick_txt_but'])."',
              'txt_fb_off'        : 'not connected to Twitter',
              'txt_fb_on'         : 'connected to Twitter',
              'perma_option'      : '".$fbsp_twoclick_perm_tw."',
              'display_name'      : 'Twitter',
              'counturl'  : '".urlencode($fbsp_data_counturl)."',
              'text'  : '".rawurlencode($fbsp_data_text)."',
              'url'  : '".urlencode($fbsp_data_url)."',
              'via'  : '".rawurlencode($fbsp_data_via)."',
              'related'  : '".rawurlencode($fbsp_data_related)."',
              'count'  : '".$fbsp_data_count."',
              'size'  : '".$fbsp_data_size."',
              'lang'  : '".$fbsp_data_lang."'
            },";



      }//end if status 1
    } //end if id in RT
//end twitter tweet

unset($fbsp['data']);
    $fb_meta_thumb = '';
    $news_meta_thumb = false;
    $article_meta_thumb = false;
    $module_meta_thumb = false;
//google+
   if ( isset($fbsp_twoclick_ids['go']) && $fbsp_twoclick_ids['go'] > 0 ) {
  		$fb_google_id = intval(trim($fbsp_twoclick_ids['go']));

      $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE ';
  		$sql .= "fb_id = " . aporeplace($fb_google_id) . ' LIMIT 1';
  		$fbsp['data'] = _dbQuery($sql);

  		if( isset($fbsp['data'][0]) ) {
  			$fbsp['data'] = $fbsp['data'][0];
  			$fbsp['data']['values'] = unserialize($fbsp['data']['fb_values']);
      }

      if ($fbsp['data']['fb_status'] == 1) { //0=inactive, 1=active, 9=deleted

        $fbsp_data_href = '';
        $fbsp_data_size = 'default';
        $fbsp_data_annotation = 'none';
        $fbsp_data_locale = 'en_US';

      //url of actual page
      $fbsp['data']['values']['fb_pageurl'] = $fbsp_pageurl;

      //main url
      $fbsp['data']['values']['fb_siteurl'] = PHPWCMS_URL;
      //set href
      //int url -> then set the base url
      if ($fbsp['data']['values']['fb_google_domain'] == 0 ) {
        $fbsp_data_href = $fbsp['data']['values']['fb_pageurl'];
      } else if ($fbsp['data']['values']['fb_google_domain'] == 1 && !empty($fbsp['data']['values']['fb_google_url'])) { //ext url
        $fbsp_data_href = $fbsp['data']['values']['fb_google_url'];
      }
      //set size
      $fbsp_data_size = $fbsp['data']['values']['fb_google_size'];
      //set annotation
      $fbsp_data_annotation = $fbsp['data']['values']['fb_google_annotation'];
      //set locale
      $fbsp_data_locale = $fbsp['data']['values']['fb_google_locale'];

      //set title - may conflict with other buttons
      if ($fbsp['data']['values']['fb_google_title'] == 1 && !empty($fbsp['data']['values']['fb_google_titletxt'])) {
        _set_meta('og:title',$fbsp['data']['values']['fb_google_titletxt'],'property');
      }

      //news image
      $news_meta_thumb =fbsp_get_news_image();
      //article image
      $article_meta_thumb = fbsp_get_article_image();

      //module image
      if ( $fbsp['data']['values']['fb_id_img'] ) {
        $module_meta_thumb = fbsp_get_module_image ($fbsp['data']['values']['fb_id_img']);
      }

      //take 1. module image, 2. News image, 3. Article Image
      if($module_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$module_meta_thumb[0];
      } else if($news_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$news_meta_thumb[0];
      } else if($article_meta_thumb != false) {
          $fb_meta_thumb = PHPWCMS_URL.PHPWCMS_IMAGES.$article_meta_thumb[0];
      } else {
          $fb_meta_thumb = '';
      }
      //this may conflict with other buttons
      if($fb_meta_thumb){
        _set_meta('og:image',$fb_meta_thumb,'property');
      }

      $fbsp_twoclick_perm_go = ($twoclick_fb['values']['fb_twoclick_perm_go'] == 0) ? 'off' : 'on' ;


      $fbsp_output_go_js = "
      gplus : {
            'status'            : 'on',
            'dummy_img'         : '".$phpwcms['modules']['br_socialplugins']['dir']."template/socialshareprivacy/images/dummy_gplus.png',
            'txt_info'          : '".rawurlencode($twoclick_fb['values']['fb_twoclick_txt_but'])."',
            'txt_gplus_off'     : 'not connected to Google+',
            'txt_gplus_on'      : 'connected to Google+',
            'perma_option'      : '".$fbsp_twoclick_perm_go."',
            'display_name'      : 'Google+',
            'referrer_track'    : '',
            'language'          : '".$fbsp_data_locale."',
            'href'              : '".urlencode($fbsp_data_href)."',
            'size'              : '".$fbsp_data_size."',
            'annotation'        : '".$fbsp_data_annotation."'
            }";

        $GLOBALS['block']['custom_htmlhead']['twoclick-google']  = "<script type='text/javascript' src='https://apis.google.com/js/plusone.js'>
          {'parsetags': 'explicit', lang: '".$fbsp_data_locale."'}
        </script>".LF;
          $fbsp_output .= '';

      }//end if status 1
    } //end if id in RT
//end google+

unset($fbsp['data']);

//render js to head
    $GLOBALS['block']['custom_htmlhead']['twoclick']  = '<script type="text/javascript" src="'.$phpwcms['modules']['br_socialplugins']['dir'].'template/socialshareprivacy/jquery.socialshareprivacy.js"></script>';
    $GLOBALS['block']['custom_htmlhead']['twoclick-function']  = "<script type='text/javascript'>
  jQuery(document).ready(function($){
    if($('#socialshareprivacy').length > 0){
      $('#socialshareprivacy').socialSharePrivacy({
        services : {
".$fbsp_output_fb_js.$fbsp_output_tw_js.$fbsp_output_go_js."
        },
        'info_link'      : '".urlencode($twoclick_fb['values']['fb_twoclick_lnk'])."',
        'txt_help'       : '".rawurlencode(htmlspecialchars($twoclick_fb['values']['fb_twoclick_txt_set']))."',
        'settings_perma' : '".rawurlencode($twoclick_fb['values']['fb_twoclick_lbl_set'])."',
        'cookie_expires' : '365',
        'css_path'       : '".$phpwcms['modules']['br_socialplugins']['dir']."template/socialshareprivacy/socialshareprivacy.css',
        'uri'            : '".urlencode(PHPWCMS_URL)."'
      });
    }
  });

</script>".LF;

      // SDK is loaded from js
      //$fbsp_output = '<div id="fb-root"></div><script src="http://connect.facebook.net/'.$fbsp['data']['values']['fb_locale'].'/all.js#xfbml=1"></script>';

      $fbsp_output .= '<div id="socialshareprivacy"></div>';


return $fbsp_output;

}

//check for twoclick solution
$plugin_fb = array();
$plugin_fb['data'] = array();
$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE fb_cat = "twoclick" LIMIT 1';
$data = _dbQuery($sql);
		if( isset($data[0]) ) {
			$plugin_fb['data'] =$data[0];
			$plugin_fb['data']['values'] = unserialize($plugin_fb['data']['fb_values']);
    }
unset($sql, $data);

//check for jslib=jquery
$fbsp_jslib = ( strpos($GLOBALS["block"]["jslib"], 'jquery') !== false ) ? "jquery" : "mootools" ;

//render twocklick solution if ready
if ( isset($plugin_fb['data']['fb_status']) && $plugin_fb['data']['fb_status']==1 && $fbsp_jslib == "jquery") {

  //find the RT's in the code and extract the id's from them
  $fbsp_fb_id = array();
	preg_match('/\{MSP_FB_LIKE:(.*?)\}/i', $content['all'], $fbsp_fb_id);
  $fbsp_tw_id = array();
	preg_match('/\{MSP_TW_TWEET:(.*?)\}/i', $content['all'], $fbsp_tw_id);
  $fbsp_go_id = array();
	preg_match('/\{MSP_GOOGLE:(.*?)\}/i', $content['all'], $fbsp_go_id);

  //prepare array for function
  $fbsp_twoclick_ids = array();
  if(isset($fbsp_fb_id[1])) $fbsp_twoclick_ids['fb'] = intval($fbsp_fb_id[1]);
  if(isset($fbsp_tw_id[1])) $fbsp_twoclick_ids['tw'] = intval($fbsp_tw_id[1]);
  if(isset($fbsp_go_id[1])) $fbsp_twoclick_ids['go'] = intval($fbsp_go_id[1]);

  //call function to render twoclick solution
  $ttt = replace_twoclick_tag($fbsp_twoclick_ids, $plugin_fb['data']);

  //if there is a RT for twoclick solution then render it and delete all remaining buttons RT's
  if( strpos($content['all'], '{MSP_TWOCLICK}') ) {
      $content['all'] = preg_replace('/\{MSP_TWOCLICK}/i', $ttt, $content['all']);
      $content['all'] = preg_replace('/\{MSP_FB_LIKE:(.*?)\}/i', '', $content['all']);
      $content['all'] = preg_replace('/\{MSP_TW_TWEET:(.*?)\}/i', '', $content['all']);
      $content['all'] = preg_replace('/\{MSP_GOOGLE:(.*?)\}/i', '', $content['all']);
  } else {
  //else render it to the first encounter of a  button RT

      if(isset($fbsp_fb_id[1])) { //Facebook RT exists
        $content['all'] = preg_replace('/\{MSP_FB_LIKE:(.*?)\}/i', $ttt, $content['all']);
        //now check for remaining RT's and delete them so they won't be rendered later on
        if(isset($fbsp_tw_id[1])) {
          $content['all'] = preg_replace('/\{MSP_TW_TWEET:(.*?)\}/i', '', $content['all']);
        }
        if(isset($fbsp_go_id[1])) {
          $content['all'] = preg_replace('/\{MSP_GOOGLE:(.*?)\}/i', '', $content['all']);
        }
      } else if(isset($fbsp_tw_id[1])) { //Twitter RT exists
        $content['all'] = preg_replace('/\{MSP_TW_TWEET:(.*?)\}/i', $ttt, $content['all']);
        if(isset($fbsp_go_id[1])) {
          $content['all'] = preg_replace('/\{MSP_GOOGLE:(.*?)\}/i', '', $content['all']);
        }
      } else if(isset($fbsp_go_id[1])) { //Google RT exists
        $content['all'] = preg_replace('/\{MSP_GOOGLE:(.*?)\}/i', $ttt, $content['all']);
      }

  }
  unset($fbsp_fb_id, $fbsp_tw_id, $fbsp_go_id, $fbsp_twoclick_ids);
} //end render twoclick


	// Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_FB_LIKE:(.*?)\}/i', 'replace_fb_likeit_tag', $content['all'], 1);
  //only the first encounter should be renderes all others get deleted below
  $content['all'] = preg_replace('/\{MSP_FB_LIKE:(.*?)\}/i', '', $content['all']);

	// Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_FB_RECOM:(.*?)\}/i', 'replace_fb_recom_tag', $content['all'], 1);
  //only the first encounter should be renderes all others get deleted below
  $content['all'] = preg_replace('/\{MSP_FB_RECOM:(.*?)\}/i', '', $content['all']);

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_FB_ACTIV:(.*?)\}/i', 'replace_fb_activity_tag', $content['all'], 1);
  //only the first encounter should be renderes all others get deleted below
  $content['all'] = preg_replace('/\{MSP_FB_ACTIV:(.*?)\}/i', '', $content['all']);

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_FB_COMM:(.*?)\}/i', 'replace_fb_comm_tag', $content['all'], 1);
  //only the first encounter should be renderes all others get deleted below
  $content['all'] = preg_replace('/\{MSP_FB_COMM:(.*?)\}/i', '', $content['all']);

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_FB_SHARE:(.*?)\}/i', 'replace_fb_share_tag', $content['all'], 1);
  //only the first encounter should be rendered all others get deleted below
  $content['all'] = preg_replace('/\{MSP_FB_SHARE:(.*?)\}/i', '', $content['all']);

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_TW_TWEET:(.*?)\}/i', 'replace_twitter_tag', $content['all'], 1);
  //only the first encounter should be renderes all others get deleted below
  $content['all'] = preg_replace('/\{MSP_TW_TWEET:(.*?)\}/i', '', $content['all']);

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_TW_FOLLOW:(.*?)\}/i', 'replace_tw_follow', $content['all']);
  //all encounters get rendered

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_TW_WIDGET:(.*?)\}/i', 'replace_twitterwidget_tag', $content['all']);
  //all encounters get rendered

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_TW_HASHTAG:(.*?)\}/i', 'replace_tw_hashtag', $content['all']);
  //all encounters get rendered

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_TW_MENTION:(.*?)\}/i', 'replace_tw_mention', $content['all']);
  //all encounters get rendered

  // Search for social plugin tags
	$content['all'] = preg_replace_callback('/\{MSP_GOOGLE:(.*?)\}/i', 'replace_google_tag', $content['all'], 1);
  //only the first encounter should be rendered all others get deleted below
  $content['all'] = preg_replace('/\{MSP_GOOGLE:(.*?)\}/i', '', $content['all']);

  //delete remainig RT
  $content['all'] = preg_replace('/\{MSP_TWOCLICK}/i', '', $content['all']);


// set Inline JS
if(count($msp_js)) {
	$content['all'] .= '<script type="text/javascript">'.LF;
	$content['all'] .= implode(LF, $msp_js);
	$content['all'] .= LF.'</script>';
}

?>