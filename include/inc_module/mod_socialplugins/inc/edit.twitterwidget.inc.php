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
jQuery("#article_title").on("keyup blur", function(){PHPWCMS_MODULE.SP.requiredInput(this)});
</script>';
?>


<form action="<?php echo fb_map_url( array('controller=twitterwidget', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmtwitter_widget" method="post">
<input name="fb_id" type="hidden" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">

  <tr>
		<td colspan="2">
      <div class="br_module_title"><?php echo $BLM['fb_twitterwidget_title']; ?>
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

	<tr>
		<td><?php echo $BLM['fb_twitterwidget_title'] ?>:</td>
		<td>Widgets let you display Twitter updates on your website.</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td>Since the Twitter Widgets depend only on your Twitter account and not on your website please create the widget with the preferred look directly on the <a class="br_module_a" target="_blank" href="https://twitter.com/settings/widgets">twitter resources website</a>.
    <br />Copy the code there, return here and paste the code in the field below.</td>
	</tr>

	<tr>
		<td><?php echo $BLM['fb_twitterfollow_code'] ?>:</td>
		<td><textarea name="fb_twitter_widgetcode" id="param_fb_twitter_widgetcode" rows="20" class="msgtext" style="width:340px;"><?php echo html_entities($plugin_fb['data']['values']['fb_twitter_widgetcode']) ?></textarea></td>
	</tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td><input type="checkbox" name="fb_status" id="fb_status" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> /><label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
	</tr>
	
	<tr> 
		<td>&nbsp;</td>
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
    <td align="left"><div id="fb_plugin_preview" style="outline:3px dashed #dedede;"><?php
    if (!empty($plugin_fb['data']['values']['fb_twitter_widgetcode'])) {
      echo $plugin_fb['data']['values']['fb_twitter_widgetcode'];
    }else {
      echo empty($plugin_fb['data']['fb_id']) ? "create to see preview" : "add code and update to see preview";
    }
    ?></div></td>
	</tr>
</table>
