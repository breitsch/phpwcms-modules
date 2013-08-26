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

?>

<form action="<?php echo fb_map_url('controller=about') ?>" method="post" name="formsitemaplisting" id="formsitemaplisting">
<img src="<?php echo $phpwcms['modules'][$module]['dir'].'img/header_sp_about.jpg'; ?>" alt="" width="540" height="92">
<table width="100%" summary="" class="br_module_table">

	<tr>
    <td valign="top" class="br_module_firsttdwidth">Version:</td>
    <td>Thank you for using the phpwcms-module <br /><strong>Social Plugins Version 1.0.5</strong></td>
  </tr>
	<tr>
    <td valign="top">Licence:</td>
    <td>The module was written by casa-loca in February 2011 and released on 14-04-2011 under the GNU General Public Licence.</td>
  </tr>
  <tr>
    <td valign="top">Updates:</td>
    <td>Update 1.0.1 in May 2012<br />Update 1.0.2 in May 2012<br />Update 1.0.3 in June 2012<br />Update 1.0.5 in August 2013</td>
  </tr>

	<tr>
    <td valign="top">Docu:</td>
    <td>You find a detailed documentation and changelog of all functions of the module in the <a href="http://www.phpwcms-howto.de/wiki/doku.php/3rd-party-modules" target="_blank">phpwcms-howto:wiki</a>.
There you'll find other modules to use in phpwcms as well as a <strong>donation button</strong>.</td>
  </tr>

</table>
</form>