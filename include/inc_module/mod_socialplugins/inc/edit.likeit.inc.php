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
$BE['HEADER'][] = '  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$BE['HEADER'][] = '  <link href="'.$phpwcms['modules'][$module]['dir'].'template/backend/css/modulebackend.css" rel="stylesheet" type="text/css">';
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
jQuery("#article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
jQuery("#fb_width,#fb_height").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwNumber(this)});
</script>';

//presettings for all vars added in update versions (compatibility issue)
$plugin_fb['data_default']['values'] = array(
  'fb_send' => 0,
  'fb_site_url' => '',
  'fb_site_url_fix' => 1,
  'fb_type' => 'article'
);
if ( isset($plugin_fb['data']) ) $plugin_fb['data']['values'] = array_merge($plugin_fb['data_default']['values'], $plugin_fb['data']['values']);

?>


<form action="<?php echo fb_map_url( array('controller=likeit', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmlikeit" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">
  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['tit_likeit']  ?>
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
<!-- Button Name -->
	<tr>
		<td><?php echo $BLM['fb_name'] ?>:&nbsp;</td>
		<td><input name="fb_name" id="article_title" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_name'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['fb_name'] ?>" maxlength="255" onblur="return set_article_alias(false);" />
    </td>
  </tr>
	<tr>
		<td><span class="toggle" id="t1"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" /></span>&nbsp;</td>
    <td align="left">
      <span>ref name</span>:&nbsp;<input type=text name="fb_ref" id="article_alias" value="<?php echo $plugin_fb['data']['values']['fb_ref'] ?>" readonly="readonly" style="font-size:10px;background:transparent;border:none;width:200px;" /></td>
  </tr>
	<tr>
    <td align="left" colspan="2">
      <div id="t1-content" class="togglecontent"><span>When a user clicks a link back to your website, Facebook will pass back this ref parameter in the referrer URL. Example:
      <br /><code>http://www.yourdomain.com/l.php?fb_ref=button-name&fb_source=profile_oneline</code></span></div></td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_inputvalues'] ?></div></td></tr>

  <!-- Site Name -->
	<tr>
		<td><?php echo $BLM['fb_sitename'] ?>:</td>
		<td>
      <input name="fb_site" id="param_fb_site" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_site'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['values']['fb_site'] ?>" maxlength="255" />
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
      <input name="fb_site_fix" id="param_fb_site_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_site_fix'], 1) ?> />
      <label for="param_fb_site_fix">Dynamic <a target="_new" href="phpwcms.php?do=admin&p=8" class="br_module_a">page layout title</a> instead.</label><?php

		//error class
		if(!empty($plugin_fb['error']['fb_site'])) echo '<br /><span style="color:#CC3300;">type site name or check dynamic</span>';

		?>
		</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>
<!-- Titel -->
  <tr>
		<td><?php echo $BLM['fb_tit'] ?>:</td>
		<td>
      <input name="fb_title" id="param_fb_title" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_title'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['values']['fb_title'] ?>" maxlength="255" /></td>
	</tr>
	<tr>
		<td><span id="t2" class="toggle"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" /></span></td>
    <td >
      <input name="fb_tit_fix" id="param_fb_tit_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_tit_fix'], 1) ?> />
      <label for="param_fb_tit_fix">Dynamic title instead.</label><?php
 		//error class
		if(!empty($plugin_fb['error']['fb_title'])) echo '<br /><span style="color:#CC3300;">type title or check dynamic</span>';

		?>
		</td>
  </tr>
  <tr>
		<td colspan="2"><div id="t2-content" class="togglecontent"><table border="0" cellpadding="0" cellspacing="0" width="" summary="">
    	<tr><td valign="top">Priority:&nbsp;</td>
          <td>1. news title (when in news detail view)<br />2. article title<br />3. the above text</td>
      </tr></table></div>
    </td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>
<!-- Like URL -->
  <tr>
		<td>Like URL:</td>
		<td>
      <input name="fb_site_url" id="param_fb_site_url" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_site_url'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['values']['fb_site_url'] ?>"  maxlength="255" />
		</td>
	</tr>
  <tr>
		<td></td>
		<td>
      <input name="fb_site_url_fix" id="param_fb_site_url_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_site_url_fix'], 1) ?> />
      <label for="param_fb_site_url_fix">Dynamically use current URL instead.</label><?php

		//error class
		if(!empty($plugin_fb['error']['fb_site_url'])) echo '<br /><span style="color:#CC3300;">type site url or check dynamic</span>';

		?>
		</td>
	</tr>
	<tr>
		<td></td>
    <td><?php echo $BLM['fb_page_warning'] ?></td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>
<!-- Website Typ -->
	<tr>
		<td><?php echo $BLM['fb_type'] ?>:</td>
		<td><select name="fb_type">
    <optgroup label="Activities">
 							<option value="activity"<?php is_selected('activity', $plugin_fb['data']['values']['fb_type']) ?>>activity</option>
							<option value="sport"<?php is_selected('sport', $plugin_fb['data']['values']['fb_type']) ?>>sport</option>
    </optgroup>
    <optgroup label="Businesses">
							<option value="bar"<?php is_selected('bar', $plugin_fb['data']['values']['fb_type']) ?>>bar</option>
							<option value="company"<?php is_selected('company', $plugin_fb['data']['values']['fb_type']) ?>>company</option>
							<option value="hotel"<?php is_selected('hotel', $plugin_fb['data']['values']['fb_type']) ?>>hotel</option>							<option value="cafe"<?php is_selected('cafe', $plugin_fb['data']['values']['fb_type']) ?>>cafe</option>
							<option value="restaurant"<?php is_selected('restaurant', $plugin_fb['data']['values']['fb_type']) ?>>restaurant</option>
    </optgroup>
    <optgroup label="Groups">
							<option value="cause"<?php is_selected('cause', $plugin_fb['data']['values']['fb_type']) ?>>cause</option>
							<option value="sports_league"<?php is_selected('sports_league', $plugin_fb['data']['values']['fb_type']) ?>>sports_league</option>
							<option value="sports_team"<?php is_selected('sports_team', $plugin_fb['data']['values']['fb_type']) ?>>sports_team</option>
    </optgroup>
    <optgroup label="Organizations">
							<option value="band"<?php is_selected('band', $plugin_fb['data']['values']['fb_type']) ?>>band</option>
							<option value="government"<?php is_selected('government', $plugin_fb['data']['values']['fb_type']) ?>>government</option>
							<option value="non_profit"<?php is_selected('non_profit', $plugin_fb['data']['values']['fb_type']) ?>>non_profit</option>
							<option value="school"<?php is_selected('school', $plugin_fb['data']['values']['fb_type']) ?>>school</option>
							<option value="university"<?php is_selected('university', $plugin_fb['data']['values']['fb_type']) ?>>university</option>
    </optgroup>
    <optgroup label="People">
							<option value="actor"<?php is_selected('actor', $plugin_fb['data']['values']['fb_type']) ?>>actor</option>
							<option value="athlete"<?php is_selected('athlete', $plugin_fb['data']['values']['fb_type']) ?>>athlete</option>
							<option value="author"<?php is_selected('author', $plugin_fb['data']['values']['fb_type']) ?>>author</option>
							<option value="director"<?php is_selected('director', $plugin_fb['data']['values']['fb_type']) ?>>director</option>
							<option value="musician"<?php is_selected('musician', $plugin_fb['data']['values']['fb_type']) ?>>musician</option>
							<option value="politician"<?php is_selected('politician', $plugin_fb['data']['values']['fb_type']) ?>>politician</option>
							<option value="profile"<?php is_selected('profile', $plugin_fb['data']['values']['fb_type']) ?>>profile</option>
							<option value="public_figure"<?php is_selected('public_figure', $plugin_fb['data']['values']['fb_type']) ?>>public_figure</option>
    </optgroup>
    <optgroup label="Places">
							<option value="city"<?php is_selected('city', $plugin_fb['data']['values']['fb_type']) ?>>city</option>
							<option value="country"<?php is_selected('country', $plugin_fb['data']['values']['fb_type']) ?>>country</option>
							<option value="landmark"<?php is_selected('landmark', $plugin_fb['data']['values']['fb_type']) ?>>landmark</option>
							<option value="state_province"<?php is_selected('state_province', $plugin_fb['data']['values']['fb_type']) ?>>state_province</option>
    </optgroup>
    <optgroup label="Products and Entertainment">
							<option value="album"<?php is_selected('album', $plugin_fb['data']['values']['fb_type']) ?>>album</option>
							<option value="book"<?php is_selected('book', $plugin_fb['data']['values']['fb_type']) ?>>book</option>
							<option value="drink"<?php is_selected('drink', $plugin_fb['data']['values']['fb_type']) ?>>drink</option>
							<option value="food"<?php is_selected('food', $plugin_fb['data']['values']['fb_type']) ?>>food</option>
							<option value="game"<?php is_selected('game', $plugin_fb['data']['values']['fb_type']) ?>>game</option>
							<option value="movie"<?php is_selected('movie', $plugin_fb['data']['values']['fb_type']) ?>>movie</option>
							<option value="product"<?php is_selected('product', $plugin_fb['data']['values']['fb_type']) ?>>product</option>
  						<option value="song"<?php is_selected('song', $plugin_fb['data']['values']['fb_type']) ?>>song</option>
							<option value="tv_show"<?php is_selected('tv_show', $plugin_fb['data']['values']['fb_type']) ?>>tv_show</option>
    </optgroup>
    <optgroup label="Websites">
							<option value="article" <?php is_selected('article', $plugin_fb['data']['values']['fb_type']) ?>>article</option>
							<option value="blog"<?php is_selected('blog', $plugin_fb['data']['values']['fb_type']) ?>>blog</option>
							<option value="website"<?php is_selected('website', $plugin_fb['data']['values']['fb_type']) ?>>website</option>
    </optgroup>
		</select>
		</td>
	</tr>
  <tr>
		<td></td>
		<td>Information about Open Graph can be found on <a class="br_module_a" target="_new" href="http://developers.facebook.com/docs/opengraph">here</a>.</td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>
  <!-- Image -->
	<tr>
		<td><?php echo $BLM['fb_img'] ?>:</td>
		<td><table summary="">
	    <tr>
			<td><input name="fb_id_img" id="param_fb_id_img" type="hidden" value="<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" />
		<input name="fb_name_img" type="text" id="param_fb_name_img" class="" value="<?php echo html_specialchars($plugin_fb['data']['values']['fb_name_img']) ?>" size="40" onfocus="this.blur();" /></td>
		<td>&nbsp;<a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('param_fb_name_img');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
		<td>&nbsp;<a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('param_fb_name_img', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr></table></td>
  </tr>
	<tr>
		<td></td>
		<td><table summary="">
      <tr>
        <td width="120" id="fb_img_preview" class="backend_preview_img" style="text-align:left;outline:3px dashed #dedede;background: url(include/inc_module/mod_socialplugins/img/image_preview.jpg) left top no-repeat;">
          <img src="img/cmsimage.php/100x75/<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" border="0" alt="" />
        </td>
        <td with="10"></td>
        <td>Images must be at least 200 by 200 pixels. Square images work best, or up to aspect ratio 1/3. The System will automatically crop images with aspect ratio over 1/3 and scale down images with smaller size bigger than 500px. </td>
      </tr>
    </table></td>
	</tr>
  <tr>
		<td><span id="t3" class="toggle"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" /></span></td>
		<td>
      <input name="fb_img_fix" id="param_fb_img_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_img_fix'], 1) ?> /><label for="param_fb_img_fix">Dynamic image instead.</label>
    </td>
	</tr>
  <tr>
		<td colspan="2"><div id="t3-content" class="togglecontent"><table border="0" cellpadding="0" cellspacing="0" width="" summary="">
    	<tr><td valign="top">Priority:&nbsp;</td>
        <td>1. news image (when in news detail view)<br />2. article detail image<br />3. the above image</td>
      </tr></table></div>
    </td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>
  <!-- layout settings -->
	<tr><td colspan="2"><div class="br_module_subtitle"><?php echo $BLM['fb_lyt_cust'] ?></div></td></tr>

  <tr>
		<td><?php echo $BLM['fb_lyt_style'] ?>:</td>
		<td><select name="fb_layout" id="fb_layout">
							<option value="standard" <?php is_selected('standard', $plugin_fb['data']['values']['fb_layout']) ?>>standard</option>
							<option value="button_count" <?php is_selected('button_count', $plugin_fb['data']['values']['fb_layout']) ?>>button_count</option>
							<option value="box_count" <?php is_selected('box_count', $plugin_fb['data']['values']['fb_layout']) ?>>box_count</option>
						</select>&nbsp;<span id="t4" class="toggle"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" /></span>
		</td>
	</tr>

  <tr>
		<td colspan="2"><div id="t4-content" class="togglecontent"><table border="0" cellpadding="0" cellspacing="0" width="" summary="">
      <tr>
    		<td valign="top" width="110">standard - &nbsp;</td>
    		<td>displays social text to the right of the button and friends' profile photos below. Minimum width: 225 pixels. Default width: 450 pixels. Height: min. 35 pixels (without photos) or min. 80 pixels (with photos). To enable user-comments the width must be min. 450px</td>
    	</tr>
      <tr>
    		<td valign="top">button_count - &nbsp;</td>
    		<td>displays the total number of likes to the right of the button. Minimum width: 90 pixels. Default width: 90 pixels. Height: min. 20 pixels.</td>
    	</tr>
      <tr>
    		<td valign="top">box_count - &nbsp;</td>
    		<td>displays the total number of likes above the button. Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels.</td>
    	</tr></table></div>
    </td>
	</tr>

	<tr>
		<td>Send button:</td>
		<td><input name="fb_send" id="param_fb_send" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_send'], 1) ?> /><label for="param_fb_send">Send Button (XFBML or HTML5 Only)</label>
		</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_lyt_faces'] ?>:</td>
		<td><input name="fb_show_faces" id="fb_show_faces" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_show_faces'], 1) ?> /><label for="fb_show_faces">Show profile pictures below the button.</label>
		</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_lyt_w'] ?>:</td>
		<td><input name="fb_width" id="fb_width" type="text" value="<?php echo $plugin_fb['data']['values']['fb_width'] ?>" class="br_module_w40" maxlength="4" />&nbsp;x&nbsp;<input name="fb_height" id="fb_height" type="text" value="<?php echo $plugin_fb['data']['values']['fb_height'] ?>" class="br_module_w40" maxlength="4" />&nbsp;<?php echo $BLM['fb_lyt_wxh'] ?></td>
	</tr>
	<tr>
		<td></td>
		<td>If you want to show faces don't make this to small. The height value is only used in the iFrame version of the button, the XFBML and the HTML5 version set the height according to the content.</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_verb'] ?>:</td>
		<td><select name="fb_action" id="fb_action">
							<option value="like" <?php is_selected('like', $plugin_fb['data']['values']['fb_action']) ?>>like</option>
							<option value="recommend" <?php is_selected('recommend', $plugin_fb['data']['values']['fb_action']) ?>>recommend</option>
						</select>
    </td>
	</tr>
	<tr>
		<td></td>
		<td>
      <span>The verb to display in the button. Currently only 'like' and 'recommend' are supported.</span>
    </td>
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
		<td><?php echo $BLM['fb_css'] ?>:</td>
		<td><input name="fb_iframe_style" id="fb_iframe_style" type="text" value="<?php echo $plugin_fb['data']['values']['fb_iframe_style'] ?>" class="" />&nbsp;<span>Extra css styling.</span>
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
  <!-- developper -->
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
		<td><input name="fb_app_id" id="param_fb_app_id" type="text" value="<?php echo $plugin_fb['data']['values']['fb_app_id'] ?>" maxlength="255" />
		</td>
	</tr>

  <tr>
		<td valign="top" style="padding:5px 5px 0 0;"><?php echo $BLM['fb_ads'] ?>:</td>
		<td>
      <input name="fb_streetaddress" id="param_fb_street-address" value="<?php echo $plugin_fb['data']['values']['fb_streetaddress'] ?>" type="text" maxlength="255" />
      <span>street-address *</span><br />
      <input name="fb_locality" id="param_fb_locality" value="<?php echo $plugin_fb['data']['values']['fb_locality'] ?>" type="text" maxlength="255" />
      <span>locality *</span><br />
      <input name="fb_region" id="param_fb_region" value="<?php echo $plugin_fb['data']['values']['fb_region'] ?>" type="text" maxlength="255" />
      <span>region</span><br />
      <input name="fb_postal" id="param_fb_postal" value="<?php echo $plugin_fb['data']['values']['fb_postal'] ?>" type="text" maxlength="255" />
      <span>postal-code</span><br />
      <input name="fb_country" id="param_fb_country" value="<?php echo $plugin_fb['data']['values']['fb_country'] ?>" type="text" maxlength="255" />
      <span>country-name *</span>
		</td>
  </tr>

  <tr>
    <td></td>
		<td>
      <input name="fb_latitude" id="param_fb_latitude" value="<?php echo $plugin_fb['data']['values']['fb_latitude'] ?>" type="text" maxlength="255" />
      <span>latitude</span><br />
      <input name="fb_longitude" id="param_fb_longitude" value="<?php echo $plugin_fb['data']['values']['fb_longitude'] ?>" type="text" maxlength="255" />
      <span>longitude</span>
		</td>
  </tr>

  <tr>
    <td><input type="hidden" id="param_fb_lat" value="<?php echo $plugin_fb['data']['values']['fb_latitude'] ?>">
      <input type="hidden" id="param_fb_lng" value="<?php echo $plugin_fb['data']['values']['fb_longitude'] ?>"></td>
    <td><span id="t5map" class="togglemap"><img src="<?php echo $phpwcms['modules'][$module]['dir'] ?>img/icon_info.gif" border="0" alt="help" />where is this?</span>&nbsp;&nbsp;&nbsp;<span style="text-decoration:underline;cursor:pointer;" onclick="getLatLong('<?php echo $plugin_fb['data']['values']['fb_country'] ?>, <?php echo $plugin_fb['data']['values']['fb_locality'] ?>, <?php echo $plugin_fb['data']['values']['fb_streetaddress'] ?>')">get latitude/longitude from *-values</span> (save first)</td>
	</tr>

  <tr>
    <td></td>
    <td><div id="t5map-container"><div id="map_canvas" style="width:320px;"></div></div></td>
	</tr>

  <tr>
    <td></td>
    <td>
      <input name="fb_email" id="param_fb_email" value="<?php echo $plugin_fb['data']['values']['fb_email'] ?>" type="text" maxlength="255" />
      <span>email</span><br />
      <input name="fb_phonenumber" id="param_fb_phone_number" value="<?php echo $plugin_fb['data']['values']['fb_phonenumber'] ?>" type="text" maxlength="255" />
      <span>phone_number</span><br />
      <input name="fb_faxnumber" id="param_fb_fax_number" value="<?php echo $plugin_fb['data']['values']['fb_faxnumber'] ?>" type="text" maxlength="255" />
      <span>fax_number</span>
		</td>
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

<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">
	<tr>
	<td colspan="1">
	<script type="text/javascript">
	<!--

	var site_url	= '<?php echo PHPWCMS_URL; ?>';
	var max_img_w	= <?php echo $phpwcms['img_list_width']; ?>;
	var max_img_h	= <?php echo $phpwcms['img_list_height']; ?>;
	var image_entry	= new Array();

	function openImageFileBrowser(image_number) {
		tmt_winOpen('filebrowser.php?opt=8&target=nolist&entry_id='+image_number,'imageBrowser','width=380,height=300,left=8,top=8,scrollbars=yes,resizable=yes',1);
		return false;
	}
	function setImgIdName(image_number, file_id, file_name) {
		if(file_id == null || file_name == null) return null;
		document.getElementById('param_fb_id_img').value = file_id;
		document.getElementById('param_fb_name_img').value = file_name;
    updatePreviewImage(file_id);
	}
	function deleteImageData(image_number, e) {
		document.getElementById('param_fb_name_img').value='';
		document.getElementById('param_fb_id_img').value='0';
		e.blur();
		updatePreviewImage(image_number);
		return false;
	}

	function updatePreviewImage(image_number) {
		var preview = '';
		if(document.getElementById('param_fb_id_img')) {
			var image_file_id = document.getElementById('param_fb_id_img').value;
			preview += getBackendImgSrc( image_file_id );
		}
    	document.getElementById('fb_img_preview').innerHTML = preview;
	}

	function getBackendImgSrc(preview) {
		var image_file_id = parseInt(preview);
		if(image_file_id) {
      var img = document.createElement('img');
      img.onload = function () { if(img.width <= 200 || img.height <= 200) alert('Watch imagesize:' + img.width + ' x ' + img.height + 'px'); };
      img.src = site_url+'img/cmsimage.php/10000x10000x0/'+image_file_id;
			return '<'+'img src="'+site_url+'img/cmsimage.php/'+max_img_w+'x'+max_img_h+'/'+image_file_id+'" border="0" alt="" /'+'> ';
		}

		return '';
	}
function getLatLong(address){
      var geo = new google.maps.Geocoder;
  var map;
      geo.geocode({'address':address},function(results, status){
              if (status == google.maps.GeocoderStatus.OK) {
                var ltln = results[0].geometry.location;
                var lat = ltln.lat();
                var lng = ltln.lng();
                document.getElementById('param_fb_latitude').value = lat;
                document.getElementById('param_fb_longitude').value = lng;
                document.getElementById('param_fb_lat').value = lat;
                document.getElementById('param_fb_lng').value = lng;
                if (results.length > 1){
                alert("Geocode found " + results.length + " results, try to be more specific in the adress values. I took the values for the first found entry.");
                }
                //inimap();
              } else {
                alert("Geocode was not successful for the following reason: " + status);
              }

       });

  }
  function inimap() {
    var lat = document.getElementById('param_fb_lat').value;
    var lng = document.getElementById('param_fb_lng').value;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom: 14,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
              var marker = new google.maps.Marker({
              map: map,
              position: latlng
          });
  }

	//-->
	</script>
	</td>
</tr>
</table>