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

//header/body additions
//module related css
$BE['HEADER'][] ='  <link href="'.$phpwcms['modules'][$module]['dir'].'template/backend/css/modulebackend.css" rel="stylesheet" type="text/css">';
//jquery latest
$BE['BODY_CLOSE'][] = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>';
//module related js
$BE['BODY_CLOSE'][] = '<script type="text/javascript" src="'.$phpwcms['modules'][$module]['dir'].'template/backend/js/phpwcms_modules.js"></script>
<script type="text/javascript" src="'.$phpwcms['modules'][$module]['dir'].'template/backend/js/phpwcms_module_sp.js"></script>';
//inline js
$BE['BODY_CLOSE'][] = '<script type="text/javascript">
jQuery.noConflict();
jQuery("#param_fb_tweet_followuser").on("keyup blur", function() {PHPWCMS_MODULE.SP.validTwVal(this)});
jQuery("#param_fb_tweet_followuser,#article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
</script>';

//presettings for all vars added in update versions (compatibility issue)

?>


<form action="<?php echo fb_map_url( array('controller=twitterfollow', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmtwitter_follow" method="post">
<input type="hidden" name="fb_id" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">

  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['fb_twitterfollow_title']; ?>
      <img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/twitter_50.png" alt="Twitter-Social-Plugin" title="Twitter-Social-Plugin" border="0" height="50" width="50" style="float:right;" /></div>
    </td>
	</tr>

  <tr> 
		<td class="br_module_firsttdwidth"><?php echo $BL['be_cnt_last_edited']  ?>:</td>
		<td><?php
		echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin_fb['data']['fb_changed']))) ;
		if(!empty($plugin_fb['data']['fb_created'])) {
		?>		
		&nbsp;&nbsp;&nbsp;<span><?php echo $BL['be_fprivedit_created']  ?>:</span> 
		<?php 
				echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin_fb['data']['fb_created'])));
		}
		
		?></td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_twitter_name'] ?>:</td>
		<td><input name="fb_name" id="article_title" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_name'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['fb_name'] ?>" maxlength="255" />
    </td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_inputvalues'] ?></div></td></tr>

	<tr>
		<td>User to follow&nbsp;@</td>
		<td><input type="text" name="fb_tweet_followuser" id="param_fb_tweet_followuser" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_tweet_followuser'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['values']['fb_tweet_followuser'] ?>" placeholder="username" maxlength="200" /></td>
	</tr>
	<tr>
		<td></td>
    <td><input name="fb_tweet_showusername" id="param_fb_tweet_showusername" value="1"<?php is_checked($plugin_fb['data']['values']['fb_tweet_showusername'], 1) ?> type="checkbox" /><label for="param_fb_tweet_showusername"><?php echo $BLM['fb_tweet_showusername'] ?><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_follow_username.gif" alt="" width="135" height="30" /></label>
    </td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_twitter_button'] ?></div></td></tr>

	<tr>
		<td colspan="2"><table cellspacing="0" cellpadding="0">
      <tr>
        <td width="110"></td>
        <td width="140" style="border-right:1px solid #cdcdcd;"><label for="param_fb_tweet_button_21"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_follow_username.gif" alt="" width="135" height="30" /></label></td>
        <td width="225"><label for="param_fb_tweet_button_22"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_follow_username_count.gif" alt="" width="220" height="30" /></label></td>
      </tr>
      <tr>
        <td><?php echo $BLM['fb_twitter_but_showcount'] ?></td>
        <td style="text-align:center;border-right:1px solid #cdcdcd;"><input type="radio" name="fb_tweet_count" id="param_fb_tweet_button_21" value="false" <?php is_checked('false', $plugin_fb['data']['values']['fb_tweet_count']) ?> /><label for="param_fb_tweet_button_21">false</label></td>
        <td style="text-align:center;"><input type="radio" name="fb_tweet_count" id="param_fb_tweet_button_22" value="true" <?php is_checked('true', $plugin_fb['data']['values']['fb_tweet_count']) ?> /><label for="param_fb_tweet_button_22">true</label></td>
      </tr>
      </table>
    </td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

  <tr>
		<td colspan="2" style="text-align:left;"><table cellspacing="0" cellpadding="0">
      <tr>
        <td width="110"></td>
        <td width="80" style="text-align:center;vertical-align:bottom;border-right:1px solid #cdcdcd;"><label for="param_fb_tweet_button_31"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_follow_medium.gif" alt="" width="66" height="30" /><br />height 20px</label></td>
        <td width="110" style="text-align:center;vertical-align:bottom;"><label for="param_fb_tweet_button_32"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_follow_large.gif" alt="" width="90" height="30" /><br />height 28px</label></td>
      </tr>
      <tr>
        <td><?php echo $BLM['fb_twitter_but_size'] ?></td>
        <td style="text-align:center;border-right:1px solid #cdcdcd;"><input type="radio" name="fb_tweet_button" id="param_fb_tweet_button_31" value="medium" <?php is_checked('medium', $plugin_fb['data']['values']['fb_tweet_button']) ?> /></td>
        <td style="text-align:center;"><input type="radio" name="fb_tweet_button" id="param_fb_tweet_button_32" value="large" <?php is_checked('large', $plugin_fb['data']['values']['fb_tweet_button']) ?> /></td>
      </tr>
      </table>
    </td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_lang'] ?>:</td>
		<td><select name="fb_tweet_locale" id="param_fb_tweet_locale">
      <option value="ar" <?php is_selected('ar', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Arabic</option>
      <option value="id" <?php is_selected('id', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Bahasa Indonesia</option>
      <option value="msa" <?php is_selected('msa', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Malay - Bahasa Melayu</option>
      <option value="eu" <?php is_selected('eu', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Basque - Euskara</option>
      <option value="ca" <?php is_selected('ca', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Catalan</option>
      <option value="zh-tw" <?php is_selected('zh-tw', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Traditional Chinese</option>
      <option value="zh-cn" <?php is_selected('zh-cn', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Simplified Chinese</option>
      <option value="cs" <?php is_selected('cs', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Czech</option>
      <option value="da" <?php is_selected('da', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Danish - Dansk</option>
      <option value="de" <?php is_selected('de', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>German - Deutsch</option>
      <option value="nl" <?php is_selected('nl', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Dutch - Nederlands</option>
      <option value="en" <?php is_selected('en', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>English</option>
      <option value="en-gb" <?php is_selected('en-gb', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>English UK</option>
      <option value="es" <?php is_selected('es', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Spanish - Espa&#324;ol</option>
      <option value="fa" <?php is_selected('fa', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Farsi</option>
      <option value="fil" <?php is_selected('fil', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Filipino</option>
      <option value="fr" <?php is_selected('fr', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>French - Fran&ccedil;ais</option>
      <option value="gl" <?php is_selected('gl', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Galician - Galego</option>
      <option value="el" <?php is_selected('el', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Greek</option>
      <option value="he" <?php is_selected('he', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Hebrew</option>
      <option value="hi" <?php is_selected('hi', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Hindi</option>
      <option value="hu" <?php is_selected('hu', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Hungarian - Magyar</option>
      <option value="it" <?php is_selected('it', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Italian - Italiano</option>
      <option value="ja" <?php is_selected('ja', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Japanese</option>
      <option value="ko" <?php is_selected('ko', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Korean</option>
      <option value="xx-lc" <?php is_selected('xx-lc', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Lolcat</option>
      <option value="no" <?php is_selected('no', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Norwegian - Norsk</option>
      <option value="pl" <?php is_selected('pl', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Polish - Polski</option>
      <option value="pt" <?php is_selected('pt', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Portuguese - Portugu&#281;s</option>
      <option value="ro" <?php is_selected('ro', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Romanian</option>
      <option value="ru" <?php is_selected('ru', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Russian</option>
      <option value="fi" <?php is_selected('fi', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Finnish - Suomi</option>
      <option value="sv" <?php is_selected('sv', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Swedish - Svenska</option>
      <option value="tr" <?php is_selected('tr', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Turkish - T&uuml;rk&ccedil;e</option>
      <option value="th" <?php is_selected('th', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Thai</option>
      <option value="uk" <?php is_selected('uk', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Ukrainian</option>
      <option value="ur" <?php is_selected('ur', $plugin_fb['data']['values']['fb_tweet_locale']) ?>>Urdu</option>		</select></td>
	</tr>
	<tr>
		<td></td>
		<td><span>This is the language that the button will render in on your website. People will see the Tweet dialog in their selected language for Twitter.com.</span></td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td><input name="fb_status" id="fb_status" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> /><label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
	</tr>
	
	<tr> 
		<td></td>
		<td>
			<input name="submit2" id="sub1" type="submit" value="<?php echo empty($plugin_fb['data']['fb_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" id="sub2" type="submit" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			<input name="close" type="submit" value="<?php echo $BL['be_admin_struct_close'] ?>" />
		</td>
	</tr>
</table>

</form>