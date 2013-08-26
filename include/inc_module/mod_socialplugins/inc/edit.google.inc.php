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
jQuery("#param_fb_google_url").blur(function(){PHPWCMS_MODULE.SP.isValidURL(this)});
jQuery("#article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
</script>';

//presettings for all vars added in update versions (compatibility issue)
/*$plugin_fb['data_default']['values'] = array(
    'fb_google_button_count' => 0,
    'fb_google_size' => 'standard',
    'fb_google_domain' => 0,
    'fb_google_url' => 0,
    'fb_google_annotation' => 'none',
    'fb_google_title' => 0,
    'fb_google_titletxt' => '',
);
if ( isset($plugin_fb['data']) ) $plugin_fb['data']['values'] = array_merge($plugin_fb['data_default']['values'], $plugin_fb['data']['values']);
*/
?>


<form action="<?php echo fb_map_url( array('controller=google', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmgoogle" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">

  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['tit_google']; ?>
      <img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/googleplus_50.png" alt="Google+-Social-Plugin" title="Google+-Social-Plugin" border="0" height="50" width="50" style="float:right;" /></div>
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
		<td valign="top"><?php echo $BLM['fb_google_domain'] ?>:</td>
		<td><input type="radio" name="fb_google_domain" id="param_fb_google_domain_0" value="0" <?php is_checked('0', $plugin_fb['data']['values']['fb_google_domain']) ?> />
    <label for="param_fb_google_domain_0"><strong><?php echo PHPWCMS_URL ?></strong>...<br /><?php echo $BLM['fb_google_acturl'] ?></label></td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td><input type="radio" name="fb_google_domain" id="param_fb_google_domain_1" value="1" <?php is_checked('1', $plugin_fb['data']['values']['fb_google_domain']) ?> />
    <input type="text" name="fb_google_url" id="param_fb_google_url" value="<?php echo $plugin_fb['data']['values']['fb_google_url'] ?>" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_google_url'])) echo ' errorInputText';

		?>" maxlength="200" onfocus="document.getElementById('param_fb_google_domain_1').checked = true;" onblur="javascript: if(this.value=='') document.getElementById('param_fb_google_domain_0').checked = true;" /></td>
	</tr>

	<tr>
		<td></td>
		<td><?php echo $BLM['fb_google_validURL'] ?></td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_google_title'] ?>:</td>
		<td><input type="radio" name="fb_google_title" id="param_fb_google_title_0" value="0" <?php is_checked('0', $plugin_fb['data']['values']['fb_google_title']) ?> />
    <label for="param_fb_google_title_0"><?php echo $BLM['fb_tw_tit0'] ?></label></td>
	</tr>

	<tr>
		<td></td>
		<td><input type="radio" name="fb_google_title" id="param_fb_google_title_1" value="1" <?php is_checked('1', $plugin_fb['data']['values']['fb_google_title']) ?> />
    <input type="text" name="fb_google_titletxt" id="param_fb_google_titletxt" value="<?php echo $plugin_fb['data']['values']['fb_google_titletxt'] ?>" maxlength="200" onfocus="document.getElementById('param_fb_google_title_1').checked = true;" onblur="javascript: if(this.value=='') document.getElementById('param_fb_google_title_0').checked = true;" /></td>
	</tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_img'] ?>:</td>
		<td><table border="0" cellpadding="0" cellspacing="0" width="" summary="">
	<tr>
			<td><input name="fb_id_img" id="param_fb_id_img" type="hidden" value="<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" />
		<input name="fb_name_img" type="text" id="param_fb_name_img" class="imagename" value="<?php echo html_specialchars($plugin_fb['data']['values']['fb_name_img']) ?>" size="40" onfocus="this.blur();" /></td>
		<td>&nbsp;<a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('param_fb_name_img');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
		<td>&nbsp;<a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('param_fb_name_img', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr></table></td>
  </tr>
	<tr>
		<td></td>
		<td><table border="0" cellpadding="0" cellspacing="0" width="" summary="">
	<tr><td width="120" id="fb_img_preview" class="backend_preview_img" style="text-align:left;">
<img src="img/cmsimage.php/100x75/<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" border="0" alt="" />
    </td></tr></table>
    </td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_twitter_button'] ?></div></td></tr>

	<tr>
		<td valign="top"><?php echo $BLM['fb_twitter_but_size'] ?>:</td>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150">
            <label for="param_fb_google_size_10"><?php echo $BLM['fb_google_size_small'] ?> (24x15px)</label>
            <input type="radio" name="fb_google_size" id="param_fb_google_size_10" value="small" <?php is_checked('small', $plugin_fb['data']['values']['fb_google_size']) ?> /></td>
          <td><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_small.png" alt="" width="60" height="30" /></td>
        </tr>
        <tr>
          <td>
            <label for="param_fb_google_size_11"><?php echo $BLM['fb_google_size_medium'] ?> (32x20px)</label>
            <input type="radio" name="fb_google_size" id="param_fb_google_size_11" value="medium" <?php is_checked('medium', $plugin_fb['data']['values']['fb_google_size']) ?> /></td>
          <td><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_medium.png" alt="" width="60" height="30" /></td>
        </tr>
        <tr>
          <td>
            <label for="param_fb_google_size_12"><?php echo $BLM['fb_google_size_standard'] ?> (38x24px)</label>
            <input type="radio" name="fb_google_size" id="param_fb_google_size_12" value="standard" <?php is_checked('standard', $plugin_fb['data']['values']['fb_google_size']) ?> /></td>
          <td><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_standard.png" alt="" width="60" height="30" /></td>
        </tr>
        <tr>
          <td>
            <label for="param_fb_google_size_13"><?php echo $BLM['fb_google_size_tall'] ?> (50x20px)</label>
            <input type="radio" name="fb_google_size" id="param_fb_google_size_13" value="tall" <?php is_checked('tall', $plugin_fb['data']['values']['fb_google_size']) ?> /></td>
          <td><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_tall.png" alt="" width="60" height="30" /></td>
        </tr>
       </table>
    </td>
	</tr>

	<tr>
		<td valign="top"><?php echo $BLM['fb_google_annotation'] ?>:</td>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150">
            <label for="param_fb_google_annotation_10"><?php echo $BLM['fb_google_annotation_none'] ?></label>
            <input type="radio" name="fb_google_annotation" id="param_fb_google_annotation_10" value="none" <?php is_checked('none', $plugin_fb['data']['values']['fb_google_annotation']) ?> /></td>
          <td colspan="2"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_none.png" alt="" width="180" height="30" /></td>
        </tr>
        <tr>
          <td>
            <label for="param_fb_google_annotation_11"><?php echo $BLM['fb_google_annotation_bubble'] ?></label>
            <input type="radio" name="fb_google_annotation" id="param_fb_google_annotation_11" value="bubble" <?php is_checked('bubble', $plugin_fb['data']['values']['fb_google_annotation']) ?> /></td>
          <td colspan="2"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_bubble.png" alt="" width="180" height="30" /></td>
        </tr>
        <tr>
          <td>
            <label for="param_fb_google_annotation_12"><?php echo $BLM['fb_google_annotation_inline'] ?></label>
            <input type="radio" name="fb_google_annotation" id="param_fb_google_annotation_12" value="inline" <?php is_checked('inline', $plugin_fb['data']['values']['fb_google_annotation']) ?> /></td>
          <td><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/goo_inline.png" alt="" width="180" height="30" />
          <br />required width 450px</span></td>
        </tr>
       </table>
    </td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_lang'] ?>:</td>
		<td>This is the language that the button will render in on your website.</td>
	</tr>

	<tr>
		<td></td>
		<td><select name="fb_google_locale" id="param_fb_google_locale">
  <option value="af" <?php is_selected('af', $plugin_fb['data']['values']['fb_google_locale']) ?>>Afrikaans</option>
 	<option value="am" <?php is_selected('am', $plugin_fb['data']['values']['fb_google_locale']) ?>>Amharic</option>
  <option value="ar" <?php is_selected('ar', $plugin_fb['data']['values']['fb_google_locale']) ?>>Arabic</option>
  <option value="eu" <?php is_selected('eu', $plugin_fb['data']['values']['fb_google_locale']) ?>>Basque</option>
  <option value="bn" <?php is_selected('bn', $plugin_fb['data']['values']['fb_google_locale']) ?>>Bengali</option>
  <option value="bg" <?php is_selected('bg', $plugin_fb['data']['values']['fb_google_locale']) ?>>Bulgarian</option>
  <option value="ca" <?php is_selected('ca', $plugin_fb['data']['values']['fb_google_locale']) ?>>Catalan</option>
  <option value="zh-HK" <?php is_selected('zh-HK', $plugin_fb['data']['values']['fb_google_locale']) ?>>Chinese (Hong Kong)</option>
  <option value="zh-CN" <?php is_selected('zh-cn', $plugin_fb['data']['values']['fb_google_locale']) ?>>Simplified Chinese</option>
  <option value="zh-TW" <?php is_selected('zh-tw', $plugin_fb['data']['values']['fb_google_locale']) ?>>Traditional Chinese</option>
  <option value="hr" <?php is_selected('hr', $plugin_fb['data']['values']['fb_google_locale']) ?>>Croatian</option>
  <option value="cs" <?php is_selected('cs', $plugin_fb['data']['values']['fb_google_locale']) ?>>Czech</option>
  <option value="da" <?php is_selected('da', $plugin_fb['data']['values']['fb_google_locale']) ?>>Dansk</option>
  <option value="nl" <?php is_selected('nl', $plugin_fb['data']['values']['fb_google_locale']) ?>>Dutch</option>
  <option value="en-GB" <?php is_selected('en-GB', $plugin_fb['data']['values']['fb_google_locale']) ?>>English (UK)</option>
  <option value="en-US" <?php is_selected('en-US', $plugin_fb['data']['values']['fb_google_locale']) ?>>English (US)</option>
  <option value="et" <?php is_selected('et', $plugin_fb['data']['values']['fb_google_locale']) ?>>Estonian</option>
  <option value="fil" <?php is_selected('fil', $plugin_fb['data']['values']['fb_google_locale']) ?>>Filipino</option>
  <option value="fi" <?php is_selected('fi', $plugin_fb['data']['values']['fb_google_locale']) ?>>Suomi</option>
  <option value="fr" <?php is_selected('fr', $plugin_fb['data']['values']['fb_google_locale']) ?>>Fran&ccedil;ais</option>
  <option value="fr-CA" <?php is_selected('fr-CA', $plugin_fb['data']['values']['fb_google_locale']) ?>>French (Canadian)</option>
  <option value="gl" <?php is_selected('gl', $plugin_fb['data']['values']['fb_google_locale']) ?>>Galician</option>
  <option value="de" <?php is_selected('de', $plugin_fb['data']['values']['fb_google_locale']) ?>>Deutsch</option>
  <option value="el" <?php is_selected('el', $plugin_fb['data']['values']['fb_google_locale']) ?>>Greek</option>
  <option value="gu" <?php is_selected('gu', $plugin_fb['data']['values']['fb_google_locale']) ?>>Gujarati</option>
  <option value="iw" <?php is_selected('iw', $plugin_fb['data']['values']['fb_google_locale']) ?>>Hebrew</option>
  <option value="hi" <?php is_selected('hi', $plugin_fb['data']['values']['fb_google_locale']) ?>>Hindi</option>
  <option value="hu" <?php is_selected('hu', $plugin_fb['data']['values']['fb_google_locale']) ?>>Hungarian</option>
  <option value="is" <?php is_selected('is', $plugin_fb['data']['values']['fb_google_locale']) ?>>Icelandic</option>
  <option value="id" <?php is_selected('id', $plugin_fb['data']['values']['fb_google_locale']) ?>>Indonesian</option>
  <option value="it" <?php is_selected('it', $plugin_fb['data']['values']['fb_google_locale']) ?>>Italiano</option>
  <option value="ja" <?php is_selected('ja', $plugin_fb['data']['values']['fb_google_locale']) ?>>Japanese</option>
  <option value="kn" <?php is_selected('kn', $plugin_fb['data']['values']['fb_google_locale']) ?>>Kannada</option>
  <option value="ko" <?php is_selected('ko', $plugin_fb['data']['values']['fb_google_locale']) ?>>Korean</option>
  <option value="lv" <?php is_selected('lv', $plugin_fb['data']['values']['fb_google_locale']) ?>>Latvian</option>
  <option value="lt" <?php is_selected('lt', $plugin_fb['data']['values']['fb_google_locale']) ?>>Lithuanian</option>
  <option value="ms" <?php is_selected('ms', $plugin_fb['data']['values']['fb_google_locale']) ?>>Melayu</option>
  <option value="ml" <?php is_selected('ml', $plugin_fb['data']['values']['fb_google_locale']) ?>>Malayalam</option>
  <option value="mr" <?php is_selected('mr', $plugin_fb['data']['values']['fb_google_locale']) ?>>Marathi</option>
  <option value="no" <?php is_selected('no', $plugin_fb['data']['values']['fb_google_locale']) ?>>Norsk</option>
  <option value="fa" <?php is_selected('fa', $plugin_fb['data']['values']['fb_google_locale']) ?>>Persian</option>
  <option value="pl" <?php is_selected('pl', $plugin_fb['data']['values']['fb_google_locale']) ?>>Polski</option>
  <option value="pt-BR" <?php is_selected('pt-BR', $plugin_fb['data']['values']['fb_google_locale']) ?>>Portuguese (Brazil)</option>
  <option value="pt-PT" <?php is_selected('pt-PT', $plugin_fb['data']['values']['fb_google_locale']) ?>>Portugu&#281;s</option>
  <option value="ro" <?php is_selected('ro', $plugin_fb['data']['values']['fb_google_locale']) ?>>Romanian</option>
  <option value="ru" <?php is_selected('ru', $plugin_fb['data']['values']['fb_google_locale']) ?>>Russian</option>
  <option value="sr" <?php is_selected('sr', $plugin_fb['data']['values']['fb_google_locale']) ?>>Serbian</option>
  <option value="sk" <?php is_selected('sk', $plugin_fb['data']['values']['fb_google_locale']) ?>>Slovak</option>
  <option value="sl" <?php is_selected('sl', $plugin_fb['data']['values']['fb_google_locale']) ?>>Slovenian</option>
  <option value="es" <?php is_selected('es', $plugin_fb['data']['values']['fb_google_locale']) ?>>Espa&#324;ol</option>
  <option value="es-419" <?php is_selected('es-419', $plugin_fb['data']['values']['fb_google_locale']) ?>>Spanish (Latin America)</option>
  <option value="sw" <?php is_selected('sw', $plugin_fb['data']['values']['fb_google_locale']) ?>>Swahili</option>
  <option value="sv" <?php is_selected('sv', $plugin_fb['data']['values']['fb_google_locale']) ?>>Svenska</option>
  <option value="ta" <?php is_selected('ta', $plugin_fb['data']['values']['fb_google_locale']) ?>>Tamil</option>
  <option value="te" <?php is_selected('te', $plugin_fb['data']['values']['fb_google_locale']) ?>>Telugu</option>
  <option value="th" <?php is_selected('th', $plugin_fb['data']['values']['fb_google_locale']) ?>>Thai</option>
  <option value="tr" <?php is_selected('tr', $plugin_fb['data']['values']['fb_google_locale']) ?>>T&uuml;rk&ccedil;e</option>
  <option value="uk" <?php is_selected('uk', $plugin_fb['data']['values']['fb_google_locale']) ?>>Ukrainian</option>
  <option value="ur" <?php is_selected('ur', $plugin_fb['data']['values']['fb_google_locale']) ?>>Urdu</option>
  <option value="vi" <?php is_selected('vi', $plugin_fb['data']['values']['fb_google_locale']) ?>>Vietnamese</option>
  <option value="zu" <?php is_selected('zu', $plugin_fb['data']['values']['fb_google_locale']) ?>>Zulu</option>
						</select>
		</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td><input type="checkbox" name="fb_status" id="fb_status" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> />
    <label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
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