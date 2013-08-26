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

$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE fb_cat = "twoclick" LIMIT 1';
$data = _dbQuery($sql);
		if( isset($data[0]) ) {
			$plugin_fb['data'] =$data[0];
			$plugin_fb['data']['values'] = unserialize($plugin_fb['data']['fb_values']);
    }
$controller_link = fb_map_url('controller=twoclick');
// default values
if(empty($plugin_fb['data'])) {

	$plugin_fb['data'] = array(
	  'fb_id' => 0,
    'fb_created'	=> '',
    'fb_changed'	=> date('Y-m-d H:i:s'),
  	'fb_cat'		=> 'google',
  	'fb_name'		=> 'twoclick',
  	'fb_status'		=> 0
	);
	$plugin_fb['data']['values'] = array(
    'fb_twoclick_lnk'	=> 'http://heise.de/-1333879',
    'fb_twoclick_txt_but'	=> '2 Klicks f&uuml;r mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie k&ouml;nnen Ihre Empfehlung an den Anbieter senden. Schon beim Aktivieren werden Daten an Dritte &uuml;bertragen.',
    'fb_twoclick_txt_set'	=> 'Wenn Sie diese Felder durch einen Klick aktivieren, werden Informationen an Facebook, Twitter oder Google &uuml;bertragen und unter Umst&auml;nden auch dort gespeichert.',
    'fb_twoclick_lbl_set'	=> 'Dauerhaft aktivieren und Daten&uuml;bertragung zustimmen:',
    'fb_twoclick_perm_fb'	=> 1,
    'fb_twoclick_perm_tw'	=> 1,
    'fb_twoclick_perm_go'	=> 1
	);
}
?>

<form action="<?php echo fb_map_url( array('controller=twoclick', 'edit='.$plugin_fb['data']['fb_id']) ) ?>" name="frmtwoclick" method="post">
<input type="hidden" name="fb_id" value="<?php echo $plugin_fb['data']['fb_id'] ?>" />
<table width="100%" summary="" class="br_module_table">
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
		<td></td>
		<td><input type="hidden" name="fb_name" id="param_fb_name" value="twoclick" />
    </td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td></td>
		<td><a class="br_module_a" href="http://heise.de/-1333879" target="_blank"><?php echo $BLM['fb_twoclick_more'] ?></a>, Solution provided by Heise.</td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BLM['fb_twoclick_lnk'] ?>:</td>
		<td><input type="text" name="fb_twoclick_lnk" id="param_fb_twoclick_lnk" value="<?php echo $plugin_fb['data']['values']['fb_twoclick_lnk'] ?>" class="<?php

		//error class
		if(!empty($plugin_fb['error']['fb_twoclick_lnk'])) echo ' errorInputText';

		?>" maxlength="255" /></td>
  </tr>

	<tr>
		<td valign="top"><?php echo $BLM['fb_twoclick_info_set'] ?>:</td>
		<td><textarea class="f11" cols="30" id="param_fb_twoclick_txt_set" name="fb_twoclick_txt_set" rows="3" style="width:350px"><?php echo  html_specialchars($plugin_fb['data']['values']['fb_twoclick_txt_set']); ?></textarea></td>
  </tr>
	<tr>
		<td><?php echo $BLM['fb_twoclick_lbl_set'] ?>:</td>
		<td><input type="text" name="fb_twoclick_lbl_set" id="param_fb_twoclick_lbl_set" value="<?php echo $plugin_fb['data']['values']['fb_twoclick_lbl_set'] ?>" maxlength="255" /></td>
  </tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
  	<td></td>
		<td><?php echo $BLM['fb_twoclick_perm'] ?>:</td>
  </tr>
	<tr>
		<td></td>
    <td><input type="checkbox" name="fb_twoclick_perm_fb" id="param_fb_twoclick_perm_fb" value="1"<?php is_checked($plugin_fb['data']['values']['fb_twoclick_perm_fb'], 1) ?> />
    <label for="param_fb_twoclick_perm_fb"><?php echo $BLM['fb_twoclick_perm_fb'] ?></label></td>
  </tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="fb_twoclick_perm_tw" id="param_fb_twoclick_perm_tw" value="1"<?php is_checked($plugin_fb['data']['values']['fb_twoclick_perm_tw'], 1) ?> />
    <label for="param_fb_twoclick_perm_tw"><?php echo $BLM['fb_twoclick_perm_tw'] ?></label></td>
  </tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="fb_twoclick_perm_go" id="param_fb_twoclick_perm_go" value="1"<?php is_checked($plugin_fb['data']['values']['fb_twoclick_perm_go'], 1) ?> />
    <label for="param_fb_twoclick_perm_go"><?php echo $BLM['fb_twoclick_perm_go'] ?></label></td>
  </tr>

  <tr><td colspan="2"><div class="br_module_spaceh10"></div></td></tr>

	<tr>
		<td valign="top"><?php echo $BLM['fb_twoclick_info_but'] ?>:</td>
		<td><textarea class="f11" cols="30" id="param_fb_twoclick_txt_but" name="fb_twoclick_txt_but" rows="3" style="width:350px"><?php echo  html_specialchars($plugin_fb['data']['values']['fb_twoclick_txt_but']); ?></textarea></td>
  </tr>
	<tr>
		<td></td>
		<td ><?php echo $BLM['fb_twoclick_empty'] ?></td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td></td>
		<td><span><?php echo $BLM['fb_twoclick_js'] ?></span></td>
  </tr>

	<tr>
		<td></td>
		<td><span><?php echo $BLM['fb_twoclick_info02'] ?></span></td>
  </tr>

	<tr><td colspan="2"><div class="br_module_spacedot"></div></td></tr>

	<tr>
		<td><?php echo $BL['be_ftptakeover_status'] ?>:</td>
		<td>
      <input type="checkbox" name="fb_status" id="fb_status" value="1"<?php is_checked($plugin_fb['data']['fb_status'], 1) ?> />
      <label for="fb_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
	</tr>

	<tr>
		<td></td>
		<td>
			<input name="submit2" id="sub1" type="submit" value="<?php echo empty($plugin_fb['data']['fb_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
		</td>
	</tr>
</table>

</form>