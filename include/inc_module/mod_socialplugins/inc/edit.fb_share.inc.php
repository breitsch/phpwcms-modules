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
//jQuery(".toggle").trigger("click");
jQuery("#param_fb_site_url").blur(function(){PHPWCMS_MODULE.SP.isValidURL(this)});
jQuery("#param_fb_name").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
jQuery("#param_fb_img_width,#param_fb_img_height").on("keyup blur", function(){PHPWCMS_MODULE.SP.validTwNumber(this)});

  var site_url	= "'.PHPWCMS_URL.'";
	var max_img_w	= '.$phpwcms['img_list_width'].';
	var max_img_h	= '.$phpwcms['img_list_height'].';

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
	function deleteImageData(image_number) {
		document.getElementById("param_fb_name_img").value="";
		document.getElementById("param_fb_id_img").value="0";
		updatePreviewImage(0);
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


<form action="<?php echo fb_map_url( array('controller=fb_share', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmfb_share" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">

  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['tit_share']; ?>
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
		<td><?php echo $BLM['fb_share_name'] ?>:</td>
		<td><input name="fb_name" id="param_fb_name" type="text" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_name'])) echo ' errorInputText';

		?>" value="<?php echo $plugin_fb['data']['fb_name'] ?>" maxlength="255" />
    </td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

  <tr>
		<td><?php echo $BLM['fb_share_domain'] ?>:</td>
		<td>
      <input name="fb_site_url" id="param_fb_site_url" type="text" value="<?php echo $plugin_fb['data']['values']['fb_site_url'] ?>" maxlength="255" placeholder="http://www.example.com" /><?php

		if(!empty($plugin_fb['error']['fb_site_url'])) {
      echo '<br /><span style="color:#CC3300;">type site url or check dynamic</span>';
    }

		?></td>
	</tr>

	<tr>
		<td></td>
    <td><input name="fb_site_url_fix" id="param_fb_site_url_fix" type="checkbox" value="1"<?php is_checked($plugin_fb['data']['values']['fb_site_url_fix'], 1) ?> /><label for="param_fb_site_url_fix">Dynamically use current URL instead.</label></td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_share_link'] ?>:</td>
		<td><input name="fb_link" id="param_fb_link" type="text" value="<?php echo $plugin_fb['data']['values']['fb_link'] ?>" maxlength="255" />
    </td>
  </tr>
	<tr>
		<td></td>
		<td>Becomes alt and title attribute when an image is used</td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_share_image'] ?>:</td>
		<td><table width="" summary="">
	<tr>
			<td><input name="fb_name_img" type="text" id="param_fb_name_img" class="" value="<?php echo html_specialchars($plugin_fb['data']['values']['fb_name_img']) ?>" size="20" onfocus="this.blur();" /><input name="fb_id_img" id="param_fb_id_img" type="hidden" value="<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" /></td>
		<td>&nbsp;<a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('param_fb_name_img');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
		<td>&nbsp;<a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('param_fb_name_img', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr></table></td>
  </tr>
	<tr>
		<td></td>
		<td><table width="" summary="">
      <tr>
        <td width="120" height="75" id="fb_img_preview" class="backend_preview_img" style="text-align:left;outline:3px dashed #dedede;background: url(include/inc_module/mod_socialplugins/img/image_preview.jpg) left top no-repeat;">
          <img src="img/cmsimage.php/100x75/<?php echo $plugin_fb['data']['values']['fb_id_img'] ?>" border="0" alt="" />
        </td>
        <td width="10"></td>
        <td valign="top">
          <input name="fb_img_width" id="param_fb_img_width" type="text" value="<?php echo $plugin_fb['data']['values']['fb_img_width'] ?>" class="br_module_w40" maxlength="4" />
      &nbsp;x&nbsp;
          <input name="fb_img_height" id="param_fb_img_height" type="text" value="<?php echo $plugin_fb['data']['values']['fb_img_height'] ?>" class="br_module_w40" maxlength="4" />
      &nbsp;<?php echo $BLM['fb_lyt_wxh'] ?><br />
          <input name="fb_img_crop" id="param_fb_img_crop" value="1"<?php is_checked($plugin_fb['data']['values']['fb_img_crop'], 1) ?> type="checkbox"><label for="param_fb_img_crop"><?php echo $BLM['fb_share_crop'] ?></label>
        </td>
      </tr>
    </table></td>
	</tr>

  <tr>
		<td><?php echo $BLM['fb_share_dialog'] ?>:</td>
		<td>
    <input type="radio" name="fb_share_dialog" id="fb_share_dialog1" value="0" <?php is_checked($plugin_fb['data']['values']['fb_share_dialog'], 0) ?> /><label for="fb_share_dialog1"><?php echo $BLM['fb_share_dialog0'] ?></label>
    <input type="radio" name="fb_share_dialog" id="fb_share_dialog2" value="1" <?php is_checked($plugin_fb['data']['values']['fb_share_dialog'], 1) ?> /><label for="fb_share_dialog2"><?php echo $BLM['fb_share_dialog1'] ?></label>
    </td>
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