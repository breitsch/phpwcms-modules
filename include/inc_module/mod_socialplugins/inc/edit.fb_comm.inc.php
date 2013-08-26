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
//module related js +css
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
jQuery("#param_fb_site_url").blur(function(){PHPWCMS_MODULE.SP.isValidURL(this)});
jQuery("#fb_article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
jQuery("#fb_width,#param_fb_comm_nr").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwNumber(this)});
</script>';

//presettings for all vars added in update versions (compatibility issue)
$plugin_fb['data_default']['values'] = array(
  'fb_site_url' => PHPWCMS_URL,
  'fb_site_url_fix' => 1,
  'fb_colorscheme'	=> 'light',
  'fb_output_type'	=> 'xfbml'
);
if ( isset($plugin_fb['data']) ) $plugin_fb['data']['values'] = array_merge($plugin_fb['data_default']['values'], $plugin_fb['data']['values']);

?>


<form action="<?php echo fb_map_url( array('controller=fb_comm', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmfb_comm" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">

  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['tit_comm']; ?>
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
		<td><?php echo $BLM['fb_name'] ?>:</td>
		<td><input name="fb_name" id="fb_article_title" type="text" class="<?php

		if(!empty($plugin_fb['error']['fb_name'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['fb_name'] ?>" maxlength="255" />
    </td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_inputvalues'] ?></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_sitename'] ?>:</td>
		<td>
      <input name="fb_site" id="param_fb_site" type="text" value="<?php echo $plugin_fb['data']['values']['fb_site'] ?>" class="<?php

		if(!empty($plugin_fb['error']['fb_site'])) echo ' errorInputText';

		?>" maxlength="255" /><?php

		if(!empty($plugin_fb['error']['fb_site'])) echo '<br /><span style="color:#CC3300;">type site name or check dynamic</span>';

		?></td>
	</tr>
	<tr>
		<td></td>
		<td>
      <input name="fb_site_fix" id="param_fb_site_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_site_fix'], 1) ?> /><label for="param_fb_site_fix">Dynamic <a class="br_module_a" target="_blank" href="phpwcms.php?do=admin&p=8">page layout title</a> instead.</label>
		</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

  <tr>
		<td><?php echo $BLM['fb_tit'] ?>:</td>
		<td>
      <input name="fb_title" id="param_fb_title" type="text" value="<?php echo $plugin_fb['data']['values']['fb_title'] ?>" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_title'])) echo ' errorInputText';

		?>" maxlength="255" /><?php

		//error class
		if(!empty($plugin_fb['error']['fb_title'])) echo '<br /><span style="color:#CC3300;">type title or check dynamic</span>';

		?></td>
	</tr>
	<tr>
		<td><span id="t2" class="toggle"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" /></span></td>
    <td>
      <input name="fb_tit_fix" id="param_fb_tit_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_tit_fix'], 1) ?> /><label for="param_fb_tit_fix">Dynamic title instead.</label>
		</td>
  </tr>
  <tr>
		<td colspan="2"><div id="t2-content" class="togglecontent"><table summary="">
    	<tr><td valign="top">Priority:&nbsp;</td>
          <td>1. news title (when in news detail view)<br />2. article title<br />3. the above text</td>
      </tr></table></div>
    </td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh5"></div></td></tr>

  <tr>
		<td>Comment URL:</td>
		<td>
      <input name="fb_site_url" id="param_fb_site_url" type="text" value="<?php echo $plugin_fb['data']['values']['fb_site_url'] ?>" class="<?php

		if(!empty($plugin_fb['error']['fb_site_url'])) echo ' errorInputText';

		?>" maxlength="255" /><?php

		if(!empty($plugin_fb['error']['fb_site_url'])) echo '<br /><span style="color:#CC3300;">type comment url or check dynamic</span>';

		?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
      <input name="fb_site_url_fix" id="param_fb_site_url_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_site_url_fix'], 1) ?> /><label for="param_fb_site_url_fix">Dynamically use a different comment box for each URL instead.</label>
      <br />To use the moderation tool you'll have to check this option, the url of the page the comments box is on!
		</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_lyt_cust'] ?></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_comm_cont'] ?>:</td>
		<td><input name="fb_width" id="fb_width" type="text" value="<?php echo $plugin_fb['data']['values']['fb_width'] ?>" class="br_module_w40" maxlength="4" />&nbsp;<?php echo $BLM['fb_comm_width'] ?></td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_comm_nr'] ?>:</td>
		<td><input name="fb_comm_nr" id="param_fb_comm_nr" type="text" value="<?php echo $plugin_fb['data']['values']['fb_comm_nr'] ?>" class="br_module_w40" maxlength="4" /></td>
	</tr>

  <tr>
		<td><?php echo $BLM['fb_color'] ?>:</td>
		<td><select name="fb_colorscheme" id="fb_colorscheme">
							<option value="light" <?php is_selected('light', $plugin_fb['data']['values']['fb_colorscheme']) ?>>light</option>
							<option value="dark" <?php is_selected('dark', $plugin_fb['data']['values']['fb_colorscheme']) ?>>dark</option>
						</select>&nbsp;<span>The color scheme of the plugin (dark needs dark bg).</span>
		</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_outtype'] ?>:</td>
		<td><select name="fb_output_type" id="param_fb_output_type">
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
		<td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_adv'] ?></div></td>
  </tr>

	<tr>
		<td></td>
		<td><a class="br_module_a" href="https://developers.facebook.com/" target="_blank">See Facebook Developers page</a></td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_admin'] ?>:</td>
		<td><input name="fb_admins" id="param_fb_admins" type="text" value="<?php echo $plugin_fb['data']['values']['fb_admins'] ?>" maxlength="255" /></td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_app'] ?>:</td>
		<td><input name="fb_app_id" id="param_fb_app_id" type="text" value="<?php echo $plugin_fb['data']['values']['fb_app_id'] ?>" maxlength="255" /></td>
	</tr>
	<tr>
		<td></td>
		<td>To use the moderation tool you'll have to add an App ID. The app can be set up for the domain, so you can administer all the comment boxes within that domain.
    <br />To add new Apps go <a class="br_module_a" href="https://developers.facebook.com/apps" target="_blank">here</a>
    <br />You can moderate all your comment plugins <a class="br_module_a" href="http://developers.facebook.com/tools/comments" target="_blank">here</a>
    <br />More info about this <a class="br_module_a" href="http://www.phpwcms-howto.de/wiki/doku.php/3rd-party-modules/social_plugins/social-plugins-docu/social-plugins-docu-facebook/social-plugins-docu-facebook-comments" target="_blank">here</a></td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td><input name="fb_status" id="fb_status" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> /><label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
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