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

//module related js +css
$BE['HEADER'][] ='<link href="'.$phpwcms['modules'][$module]['dir'].'template/backend/css/modulebackend.css" rel="stylesheet" type="text/css">';

//jquery latest
$BE['BODY_CLOSE'][] = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>';
//module related js
$BE['BODY_CLOSE'][] = '<script type="text/javascript" src="'.$phpwcms['modules'][$module]['dir'].'template/backend/js/phpwcms_modules.js"></script>
<script type="text/javascript" src="'.$phpwcms['modules'][$module]['dir'].'template/backend/js/phpwcms_module_sp.js"></script>';
//inline js
$BE['BODY_CLOSE'][] = '<script type="text/javascript">
jQuery.noConflict();
jQuery(".toggle").trigger("click");
jQuery("#fb_article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
jQuery("#fb_width,#fb_height").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwNumber(this)});
</script>';

?>

<form action="<?php echo fb_map_url( array('controller=fb_recom', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmfb_recom" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">
  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['tit_recom']  ?>
      <img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/facebook_50.png" alt="Facebook-Social-Plugin" title="Facebook-Social-Plugin" border="0" height="50" width="50" style="float:right;" /></div>
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
		<td><?php echo $BLM['fb_recom_name'] ?>:</td>
		<td><input name="fb_name" id="fb_article_title" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_name'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['fb_name'] ?>" maxlength="255" onblur="return PHPWCMS_MODULE.SP.set_ref(false,'fb_article_title','fb_article_alias');" />
    </td>
  </tr>
	<tr>
		<td><span id="t1" class="toggle"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" /></span></td>
    <td align="left" >
      <span>ref name</span>:&nbsp;<input type=text name="fb_ref" id="fb_article_alias" value="<?php echo $plugin_fb['data']['values']['fb_ref'] ?>" readonly="readonly" style="font-size:10px;background:transparent;border:none;width:200px;" /></td>
  </tr>
	<tr>
    <td align="left" colspan="2" >
      <div id="t1-content" class="togglecontent"><span>When a user clicks a link back to your website, Facebook will pass back this ref parameter in the referrer URL. Example:
      <br />http://www.yourdomain.com/l.php?fb_ref=button-name&fb_source=profile_oneline</span></div></td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_inputvalues'] ?></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_recom_domain'] ?>:</td>
		<td><strong><?php echo PHPWCMS_URL ?></strong>
<input name="fb_site_url" id="param_fb_site_url" type=hidden value="<?php echo urlencode(PHPWCMS_URL) ?>" />
<input name="fb_site_url_fix" id="param_fb_site_url_fix" type=hidden value="0" />
<input name="fb_phpwcms_url" id="param_fb_phpwcms_url" type=hidden value="<?php echo urlencode(PHPWCMS_URL) ?>" />
    </td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_lyt_cust'] ?></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_recom_size'] ?>:</td>
		<td><input name="fb_width" id="fb_width" type="text" value="<?php echo $plugin_fb['data']['values']['fb_width'] ?>" class="br_module_w40" maxlength="4" />
&nbsp;x&nbsp;<input name="fb_height" id="fb_height" type="text" value="<?php echo $plugin_fb['data']['values']['fb_height'] ?>" class="br_module_w40" maxlength="4" />
&nbsp;<?php echo $BLM['fb_lyt_wxh'] ?></td>
	</tr>
	<tr>
		<td></td>
		<td><span>Defaults to 300px x 300px</span></td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_font'] ?>:</td>
		<td><select name="fb_font" id="fb_font">
							<option value="arial" <?php is_selected('arial', $plugin_fb['data']['values']['fb_font']) ?>>arial</option>
							<option value="lucida grande" <?php is_selected('lucida grande', $plugin_fb['data']['values']['fb_font']) ?>>lucida grande</option>
							<option value="segoe ui" <?php is_selected('segoe ui', $plugin_fb['data']['values']['fb_font']) ?>>segoe ui</option>
							<option value="tahoma" <?php is_selected('tahoma', $plugin_fb['data']['values']['fb_font']) ?>>tahoma</option>
							<option value="trebuchet ms" <?php is_selected('trebuchet ms', $plugin_fb['data']['values']['fb_font']) ?>>trebuchet ms</option>
							<option value="verdana" <?php is_selected('verdana', $plugin_fb['data']['values']['fb_font']) ?>>verdana</option>
						</select>&nbsp;<span>the font of the plugin</span>
		</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_color'] ?>:</td>
		<td><select name="fb_colorscheme" id="fb_colorscheme">
							<option value="light" <?php is_selected('light', $plugin_fb['data']['values']['fb_colorscheme']) ?>>light</option>
							<option value="dark" <?php is_selected('dark', $plugin_fb['data']['values']['fb_colorscheme']) ?>>dark</option>
						</select>&nbsp;<span>The color scheme of the plugin.</span>
		</td>
	</tr>

  <tr>
		<td><?php echo $BLM['fb_recom_title'] ?>:</td>
		<td>
      <input name="fb_header" id="param_fb_header" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_header'], 1) ?> /><label for="param_fb_header">Show Header</label>
    </td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_outtype'] ?>:</td>
		<td><select name="fb_output_type" id="param_fb_output_type">
							<option value="iframe" <?php is_selected('iframe', $plugin_fb['data']['values']['fb_output_type']) ?>>iframe</option>
							<option value="xfbml" <?php is_selected('xfbml', $plugin_fb['data']['values']['fb_output_type']) ?>>XFBML</option>
              <option value="html5" <?php is_selected('html5', $plugin_fb['data']['values']['fb_output_type']) ?>>HTML 5</option>
						</select>
		</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_lang'] ?>:</td>
		<td><select name="fb_locale" id="param_fb_locale">
<?php
echo get_fb_loc($plugin_fb['data']['values']['fb_locale']);
?>
						</select>
		</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td><input type="checkbox" name="fb_status" id="fb_status" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> /><label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>
	
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

<table width="100%" summary="" class="br_module_table_preview">
	<tr><td><div class="br_module_subtitle"><?php echo $BLM['fb_subtit_preview'] ?></div></td></tr>
	<tr>
		<td>
      <input type="checkbox" value="1" class="" onclick="PHPWCMS_MODULE.SP.load_preview('recomm');" id="prev_check" />
      <label for="prev_check"><?php echo $BLM['fb_preview'] ?></label>
      <span id="fb_plugin_preview_update"><a class="br_module_a" href="#" onclick="PHPWCMS_MODULE.SP.load_preview('activity'); return false;">update</a></span>
    </td>
  </tr>
	<tr>
    <td align="left" ><div id="fb_plugin_preview" style="outline:3px dashed #dedede;"></div></td>
  </tr>
  <tr><td><?php echo $BLM['fb_preview_1'] ?></td></tr>
</table>