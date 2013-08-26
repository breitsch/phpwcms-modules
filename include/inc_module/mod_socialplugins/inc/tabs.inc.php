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

?>

<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>
<div id="tabsG" style="height:20px;">
	<ul>
		<li<?php if($controller == 'likeit' || $controller == 'fb_share' || $controller == 'fb_recom' || $controller == 'fb_activity' || $controller == 'fb_comm') echo ' class="activeTab"'; ?>><a href="<?php echo fb_map_url('controller=likeit') ?>"><span><?php echo $BLM['tab_facebook'] ?></span></a></li>
		<li<?php if($controller == 'twitterbutton' || $controller == 'twitterfollow' || $controller == 'twitterwidget' || $controller == 'tw_hashtag' || $controller == 'tw_mention') echo ' class="activeTab"'; ?>><a href="<?php echo fb_map_url('controller=twitterbutton') ?>"><span><?php echo $BLM['tab_twitter'] ?></span></a></li>
		<li<?php if($controller == 'google') echo ' class="activeTab"'; ?>><a href="<?php echo fb_map_url('controller=google') ?>"><span><?php echo $BLM['tab_google'] ?></span></a></li>
		<li<?php if($controller == 'twoclick') echo ' class="activeTab"'; ?>><a href="<?php echo fb_map_url('controller=twoclick') ?>"><span><?php echo $BLM['tab_twoclick'] ?></span></a></li>
		<li<?php if($controller == 'about') echo ' class="activeTab"'; ?>><a href="<?php echo fb_map_url('controller=about') ?>"><span><?php echo $BLM['tab_about'] ?></span></a></li>
	</ul>
	<br class="clear" />
</div>