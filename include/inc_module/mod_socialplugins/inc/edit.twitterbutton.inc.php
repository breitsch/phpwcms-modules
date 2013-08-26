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
jQuery(".toggle").trigger("click");
jQuery("#param_fb_tweet_url").blur(function(){PHPWCMS_MODULE.SP.isValidURL(this)});
jQuery("#article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
jQuery("#param_fb_tweet_hashtags").on("keyup blur", function() {PHPWCMS_MODULE.SP.validTwValComma(this)});
jQuery("#param_fb_tweet_recom2").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwRecomm(this)});
jQuery("#param_fb_tweet_recom1").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwVal(this)});
jQuery("#param_fb_img_width,#param_fb_img_height").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwNumber(this)});
</script>';

//presettings for all vars added in update versions (compatibility issue)
$plugin_fb['data_default']['values'] = array(
    'fb_tweet_button_count' => 0,
    'fb_tweet_count' => 'horizontal',
    'fb_tweet_hashtags' => ''
);
if ( isset($plugin_fb['data']) ) $plugin_fb['data']['values'] = array_merge($plugin_fb['data_default']['values'], $plugin_fb['data']['values']);
if( $plugin_fb['data']['values']['fb_tweet_button_count']==1 ) {
  $plugin_fb['data']['values']['fb_tweet_count'] = "none";
}

?>


<form action="<?php echo fb_map_url( array('controller=twitterbutton', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmtwitterbutton" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">

  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['tit_twitter']; ?>
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
		<td><?php echo $BLM['fb_twitter_domain'] ?>:</td>
		<td><input type="radio" name="fb_tweet_domain" id="param_fb_tweet_domain_0" value="0" <?php is_checked('0', $plugin_fb['data']['values']['fb_tweet_domain']) ?> /><label for="param_fb_tweet_domain_0"><strong><?php echo PHPWCMS_URL ?></strong>...(Page URL)</label></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="radio" name="fb_tweet_domain" id="param_fb_tweet_domain_1" value="1" <?php is_checked('1', $plugin_fb['data']['values']['fb_tweet_domain']) ?> /><input type="text" name="fb_tweet_url" id="param_fb_tweet_url" value="<?php echo $plugin_fb['data']['values']['fb_tweet_url'] ?>" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_tweet_url'])) echo ' errorInputText';

		?>" maxlength="255" onfocus="document.getElementById('param_fb_tweet_domain_1').checked = true;" onblur="javascript: if(this.value=='') document.getElementById('param_fb_tweet_domain_0').checked = true;" /><br /><?php echo $BLM['fb_twitter_validURL'] ?></td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_twitter_title'] ?>:</td>
		<td><input type="radio" name="fb_tweet_title" id="param_fb_tweet_title_0" value="0" <?php is_checked('0', $plugin_fb['data']['values']['fb_tweet_title']) ?> /><label for="param_fb_tweet_title_0"><?php echo $BLM['fb_tw_tit0'] ?></label></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="radio" name="fb_tweet_title" id="param_fb_tweet_title_1" value="1" <?php is_checked('1', $plugin_fb['data']['values']['fb_tweet_title']) ?> /><input type="text" name="fb_tweet_titletxt" id="param_fb_tweet_titletxt" value="<?php echo $plugin_fb['data']['values']['fb_tweet_titletxt'] ?>" maxlength="200" onfocus="document.getElementById('param_fb_tweet_title_1').checked = true;" onblur="javascript: if(this.value=='') document.getElementById('param_fb_tweet_title_0').checked = true;" /><br />This is the text included in the tweet when people share from your website.</td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td valign="top">Hashtag&nbsp;#</td>
		<td><input type="text" name="fb_tweet_hashtags" id="param_fb_tweet_hashtags" value="<?php echo $plugin_fb['data']['values']['fb_tweet_hashtags'] ?>" placeholder="hashtag,hashtag" maxlength="255" /><br />Comma separated hashtags appended to tweet text</td>
	</tr>


	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_twitter_button'] ?></div></td></tr>

	<tr>
		<td colspan="2"><table cellspacing="0" cellpadding="0">
      <tr>
        <td width="110"></td>
        <td width="80" style="border-right:1px solid #cdcdcd;"><label for="param_fb_tweet_button_21"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_count_none.gif" alt="" width="66" height="70" /></label></td>
        <td width="110" style="border-right:1px solid #cdcdcd;"><label for="param_fb_tweet_button_22"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_count_horizontal.gif" alt="" width="106" height="70" /></label></td>
        <td width="80"><label for="param_fb_tweet_button_23"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_count_vertical.gif" alt="" width="66" height="70" /></label></td>
      </tr>
      <tr>
        <td><?php echo $BLM['fb_twitter_but_showcount'] ?></td>
        <td style="text-align:center;border-right:1px solid #cdcdcd;"><input type="radio" name="fb_tweet_count" id="param_fb_tweet_button_21" value="none" <?php is_checked('none', $plugin_fb['data']['values']['fb_tweet_count']) ?> /><label for="param_fb_tweet_button_21">none</label></td>
        <td style="text-align:center;border-right:1px solid #cdcdcd;"><input type="radio" name="fb_tweet_count" id="param_fb_tweet_button_22" value="horizontal" <?php is_checked('horizontal', $plugin_fb['data']['values']['fb_tweet_count']) ?> /><label for="param_fb_tweet_button_22">horizontal</label></td>
        <td style="text-align:center;"><input type="radio" name="fb_tweet_count" id="param_fb_tweet_button_23" value="vertical" <?php is_checked('vertical', $plugin_fb['data']['values']['fb_tweet_count']) ?> /><label for="param_fb_tweet_button_23">vertical</label></td>
      </tr>
      </table>
    </td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

  <tr>
		<td colspan="2" style="text-align:left;"><table cellspacing="0" cellpadding="0">
      <tr>
        <td width="110"></td>
        <td width="80" style="text-align:center;vertical-align:bottom;border-right:1px solid #cdcdcd;"><label for="param_fb_tweet_button_31"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_size_medium.gif" alt="" width="80" height="30" /><br />height 20px</label></td>
        <td width="110" style="text-align:center;vertical-align:bottom;border-right:1px solid #cdcdcd;"><label for="param_fb_tweet_button_32"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/tw_size_large.gif" alt="" width="80" height="30" /><br />height 28px</label></td>
        <td width="5"></td>
        <td width="220" height="80" id="fb_img_preview" class="backend_preview_img" style="text-align:left;outline:3px dashed #dedede;background: url(include/inc_module/mod_socialplugins/img/image_preview.jpg) left top no-repeat;"><img src="img/cmsimage.php/100x75/<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" border="0" alt="" /></td>
      </tr>
      <tr>
        <td><?php echo $BLM['fb_twitter_but_size'] ?></td>
        <td style="text-align:center;border-right:1px solid #cdcdcd;"><input type="radio" name="fb_tweet_button" id="param_fb_tweet_button_31" value="none" <?php is_checked('none', $plugin_fb['data']['values']['fb_tweet_button']) ?> /></td>
        <td style="text-align:center;border-right:1px solid #cdcdcd;"><input type="radio" name="fb_tweet_button" id="param_fb_tweet_button_32" value="large" <?php is_checked('large', $plugin_fb['data']['values']['fb_tweet_button']) ?> /></td>
        <td></td>
        <td><input type="radio" name="fb_tweet_button" id="param_fb_tweet_button_33" value="user" <?php is_checked('user', $plugin_fb['data']['values']['fb_tweet_button']) ?> /><label for="param_fb_tweet_button_33"><?php echo $BLM['fb_tw_userimg'] ?></label></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td style="border-right:1px solid #cdcdcd;"></td>
        <td></td>
        <td><input name="fb_name_img" type="text" id="param_fb_name_img" style="width:150px;" value="<?php echo html_specialchars($plugin_fb['data']['values']['fb_name_img']) ?>" size="20" onfocus="this.blur();" />
          <input name="fb_id_img" id="param_fb_id_img" type="hidden" value="<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" />
          &nbsp;<a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('param_fb_name_img');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a>
          &nbsp;<a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('param_fb_name_img', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
        </td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td style="border-right:1px solid #cdcdcd;"></td>
        <td></td>
        <td>
          <input name="fb_img_width" id="param_fb_img_width" value="<?php echo $plugin_fb['data']['values']['fb_img_width'] ?>" type="text" maxlength="4" style="width:30px;" />x<input name="fb_img_height" id="param_fb_img_height" value="<?php echo $plugin_fb['data']['values']['fb_img_height'] ?>" type="text" maxlength="4" style="width:30px;" /><?php echo $BLM['fb_lyt_wxh'] ?><br />
          <input name="fb_img_crop" id="param_fb_img_crop" value="1"<?php is_checked($plugin_fb['data']['values']['fb_img_crop'], 1) ?> type="checkbox"><label for="param_fb_img_crop"><?php echo $BLM['fb_tw_crop'] ?></label>
        </td>
      </tr>
      </table>
    </td>
	</tr>

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
	<tr><td colspan="2"><div class="br_module_subtitle">Related Fields</div></td></tr>

	<tr>
		<td>Via&nbsp;@</td>
		<td><input type="text" name="fb_tweet_recom1" id="param_fb_tweet_recom1" value="<?php echo $plugin_fb['data']['values']['fb_tweet_recom1'] ?>" placeholder="username" maxlength="250" /></td>
	</tr>
	<tr>
		<td>Recommend&nbsp;@</td>
		<td><input type="text" name="fb_tweet_recom2" id="param_fb_tweet_recom2" value="<?php echo $plugin_fb['data']['values']['fb_tweet_recom2'] ?>" placeholder="username:optional text" maxlength="250" /></td>
	</tr>

	<tr>
		<td colspan="2" style="text-align:left;">Using the related fields you can suggest accounts for a user to follow once they have sent a Tweet using your Tweet Button.
These suggested accounts and their basic information are shown on the last page of the Share Box flow.
<br /><br />
Only two accounts are displayed and by default the via user is shown first with the first recommended account shown afterwards.
If the user is a follower of the via user the Share Box will instead show the first two recommended accounts the user isn't a follower of.
No accounts are displayed if the user follows all of the suggested accounts (via and related).
<br /><br />
You can add your own summary of a recommended user by adding some text after their screen name, separated using a colon.
For example, to add a summary 'The Javascript API' to the recommended user '@anywhere' you would use:<br />
anywhere:The Javascript API</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td><input type="checkbox" name="fb_status" id="fb_status" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> /><label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
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
<?php
$BE['BODY_CLOSE'][] = '<script type="text/javascript">

	var site_url	= "'.PHPWCMS_URL.'";
	var max_img_w	= '.$phpwcms['img_list_width'].';
	var max_img_h	= '.$phpwcms['img_list_height'].';
	var image_entry	= new Array();
		function openImageFileBrowser(image_number) {
		tmt_winOpen("filebrowser.php?opt=8&target=nolist&entry_id="+image_number,"imageBrowser","width=420,height=300,left=8,top=8,scrollbars=yes,resizable=yes",1);
		return false;
	}
	function setImgIdName(image_number, file_id, file_name) {
		if(file_id == null || file_name == null) return null;
		document.getElementById("param_fb_id_img").value = file_id;
		document.getElementById("param_fb_name_img").value = file_name;
		updatePreviewImage(file_id);
	}
	function deleteImageData(image_number, e) {
		document.getElementById("param_fb_name_img").value="";
		document.getElementById("param_fb_id_img").value="0";
		e.blur();
		updatePreviewImage(image_number);
		return false;
	}
	function updatePreviewImage(image_number) {
		var preview = "";
		if(document.getElementById("param_fb_id_img")) {
			var image_file_id = document.getElementById("param_fb_id_img").value;
			preview += getBackendImgSrc( image_file_id );
		}
    	document.getElementById("fb_img_preview").innerHTML = preview;
	}
	function getBackendImgSrc(image_file_id) {
		var image_file_id = parseInt(image_file_id);
		if(image_file_id) {
			return "<"+"img src=\'"+site_url+"img/cmsimage.php/"+max_img_w+"x"+max_img_h+"/"+image_file_id+"\' border=\'0\' alt=\'\' /"+"> ";
		}
		return "";
	}
</script>';
?>