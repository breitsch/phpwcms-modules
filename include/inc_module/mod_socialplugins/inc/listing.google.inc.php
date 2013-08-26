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

//header additions
//module related js +css
$BE['HEADER'][] ='<link href="'.$phpwcms['modules'][$module]['dir'].'template/backend/css/modulebackend.css" rel="stylesheet" type="text/css">';

$_entry['query']			= '';

// create pagination
if(isset($_GET['c'])) {
	$_SESSION['list_user_count_fbgoo'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
	if( $_GET['c'] == 'all'){
    $_POST['filter'] = '';
    unset($_SESSION['filter_fbgoo']);
  }
}

if(isset($_GET['page'])) {
	$_SESSION['fb_page_fbgoo'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count_fbgoo'])) {
	$_SESSION['list_user_count_fbgoo'] = 25;
}

// set value for sorting
if(empty($_SESSION['list_user_sort_fbgoo']) || (isset($sort) && $sort==2)) {
	$_SESSION['list_user_sort_fbgoo'] = 2;
}
if(isset($sort) && $sort==1) {
	$_SESSION['list_user_sort_fbgoo'] = 1;
}
if(isset($sort) && $sort==3) {
	$_SESSION['list_user_sort_fbgoo'] = 3;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active_fbgoo']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive_fbgoo']	= empty($_POST['showinactive']) ? 0 : 1;

	$_SESSION['filter_fbgoo']			= clean_slweg($_POST['filter']);

	if(empty($_SESSION['filter_fbgoo']) || $_SESSION['filter_fbgoo']=='') {
		unset($_SESSION['filter_fbgoo']);
	} else {
		$_SESSION['filter_fbgoo']	= convertStringToArray($_SESSION['filter_fbgoo'], ' ');
		$_POST['filter']	= $_SESSION['filter_fbgoo'];
	}
	
	$_SESSION['fb_page_fbgoo'] = intval($_POST['page']);

}

if(empty($_SESSION['fb_page_fbgoo'])) {
	$_SESSION['fb_page_fbgoo'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active_fbgoo'])	? $_SESSION['list_active_fbgoo']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive_fbgoo'])	? $_SESSION['list_inactive_fbgoo']	: 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {
	
	if(!$_entry['list_active']) {
		$_entry['query'] .= 'fb_status=0';
	}
	if(!$_entry['list_inactive']) {
		$_entry['query'] .= 'fb_status=1';
	}
	
} else {
	$_entry['query'] .= 'fb_status!=9';
}

if(isset($_SESSION['filter_fbgoo']) && is_array($_SESSION['filter_fbgoo']) && count($_SESSION['filter_fbgoo'])) {
	
	$_entry['filter_array'] = array();

	foreach($_SESSION['filter_fbgoo'] as $_entry['filter']) {
		//
		$_entry['filter_array'][] = "fb_name LIKE '%".aporeplace($_entry['filter'])."%'";

	}
	if(count($_entry['filter_array'])) {
		
		$_SESSION['filter_fbgoo'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter_fbgoo'];
	
	}

} elseif(isset($_SESSION['filter_fbgoo']) && is_string($_SESSION['filter_fbgoo'])) {

	$_entry['query'] .= $_SESSION['filter_fbgoo'];

}

// paginating values
$_entry['count_total'] = _dbQuery('SELECT COUNT(fb_id) FROM '.DB_PREPEND.'phpwcms_modfb WHERE '.$_entry['query'].' AND fb_cat IN ("google")', 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count_fbgoo']);
if($_SESSION['fb_page_fbgoo'] > $_entry['pages_total']) {
	$_SESSION['fb_page_fbgoo'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}

$BE['HEADER'][] = '<style type="text/css">
table tbody th.sortme { cursor:pointer; }
table thead th { border-bottom:1px solid #92A1AF;border-top:1px solid #92A1AF;height:20px; }
table tbody th.sorted { text-decoration:underline; }
</style>
';

?>

<div class="br_module_listing_new">
	<a href="<?php echo fb_map_url('controller=google') ?>&amp;edit=0" title="<?php echo $BLM['create_new_google'] ?>"><img src="<?php echo $phpwcms['modules'][$module]['dir'].'img/'; ?>add.gif" alt="Add" border="0" /><span><?php echo $BLM['create_new_google'] ?></span></a>
</div>
<div style="clear:both;height:20px;"></div>

<form action="<?php echo fb_map_url('controller=google') ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
<table width="100%" summary="">
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> /></td>
				<td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
				<td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
				<td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

<?php 
if($_entry['pages_total'] > 1) {

	echo '<td>|&nbsp;</td>';
	echo '<td>';
	if($_SESSION['fb_page_fbgoo'] > 1) {
		echo '<a href="'. fb_map_url( array('controller=google', 'page='.($_SESSION['fb_page_fbgoo']-1)) ) . '">';
		echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['fb_page_fbgoo'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td>/'.$_entry['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['fb_page_fbgoo'] < $_entry['pages_total']) {
		echo '<a href="'.fb_map_url( array('controller=google', 'page='.($_SESSION['fb_page_fbgoo']+1)) ) .'">';
		echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/action_forward.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/action_forward.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td><td>&nbsp;|&nbsp;</td>';

} else {

	echo '<td>|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>
				<td><input type="text" name="filter" id="filter" size="10" value="<?php 
				
				if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
					echo html_specialchars(implode(' ', $_POST['filter']));
				}
				
				?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results" /></td>
				<td><input type="image" name="gofilter" src="<?php echo $phpwcms['modules'][$module]['dir'].'img/'; ?>action_go.gif" style="margin-right:3px;" /></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php if (isset($_SESSION['filter_fbgoo'])) echo $BLM['filtered_list'].' - <a href="'. fb_map_url(array('controller=google', 'c=all')).'">'.$BLM['show_all'].'</a>'; ?> </td>
			</tr>
		</table></td>

	<td align="right">
		<a href="<?php echo fb_map_url(array('controller=google', 'c=10')) ?>">10</a>
		<a href="<?php echo fb_map_url(array('controller=google', 'c=25')) ?>">25</a>
		<a href="<?php echo fb_map_url(array('controller=google', 'c=50')) ?>">50</a>
		<a href="<?php echo fb_map_url(array('controller=google', 'c=all')) ?>"><?php echo $BL['be_ftptakeover_all'] ?></a>
	</td>

	</tr>
</table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="" class="br_module_listing">
  <thead>
	<tr class="br_module_listing_headrow"><th class="nosort">&nbsp;</th><th align="left" class="sortme<?php echo ($_SESSION['list_user_sort_fbgoo'] == 1) ? ' sorted' : ''; ?>"><a href="<?php echo fb_map_url('controller=google'); ?>&amp;sort=1"><?php echo $BLM['fb_listing_plugin']; ?></a></th><th align="left" class="sortme<?php echo ($_SESSION['list_user_sort_fbgoo'] == 2) ? ' sorted' : ''; ?>"><a href="<?php echo fb_map_url('controller=google'); ?>&amp;sort=2"><?php echo $BLM['fb_listing_name'] ?></a></th><th align="left" class="sortme<?php echo ($_SESSION['list_user_sort_fbgoo'] == 3) ? ' sorted' : ''; ?>"><a href="<?php echo fb_map_url('controller=google'); ?>&amp;sort=3"><?php echo $BLM['fb_listing_rt'] ?></a></th><th class="nosort">&nbsp;</th></tr>
  </thead>
  <tbody>
  <?php
// loop listing available languages
$row_count = 0;                
switch ($_SESSION['list_user_sort_fbgoo']) {
      case 1:
        $orderby = 'fb_cat, fb_name';
      break;
      case 3:
        $orderby = 'fb_id, fb_name';
      break;
      default:
        $orderby = 'fb_name';
    	break;
    }
$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_modfb WHERE '.$_entry['query'];
$sql .= ' AND fb_cat IN ("google") ORDER BY '.$orderby;
$sql .= ' LIMIT '.(($_SESSION['fb_page_fbgoo']-1) * $_SESSION['list_user_count_fbgoo']).','.$_SESSION['list_user_count_fbgoo'];
$data = _dbQuery($sql);

$controller_link = fb_map_url('controller=google');
$rt_text =  '';
if ($data) {
  foreach($data as $row) {
    $controller_link =  fb_map_url('controller='.$row["fb_cat"]);
    switch ($row["fb_cat"]) {
      case 'google':
        $rt_text = 'GOOGLE';
        $img_text = 'Google+';
      break;
      default:
        $rt_text = 'GOOGLE';
        $img_text = 'Google+';
    	break;
    }
    echo '<tr class="br_module_listing_tablerow';
    if ($row_count % 2) echo ' even';
    echo '">'.LF;
  	echo '<td width="25" style="padding:2px 3px 2px 4px;">';
  	echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/googleplus_25.png" alt="'.$BLM['fb_google_icon'].'" /></td>'.LF;
  	echo '<td class="dir">'.$img_text."&nbsp;</td>\n";
  	echo '<td class="dir">'.html_specialchars($row["fb_name"])."</td>\n";
  	echo '<td class="" width="30%"><input type=text onclick=select() value="{MSP_'.$rt_text.':'.$row["fb_id"].'}" readonly="readonly" style="font-size:10px;background:transparent;border:none;width:180px;" />'."</td>\n";
  	echo '<td align="right" nowrap="nowrap" class="button_td">';
  	echo '<a href="'.$controller_link.'&amp;edit='.$row["fb_id"].'">';
  	echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/edit_22x13.gif" border="0" alt="" /></a>';
   //if ($row["cm_cat_id"]!=1) {	
  	echo '<a href="'.fb_map_url('controller=google').'&amp;verify=' . $row["fb_id"] . '-' . $row["fb_status"] .'">';
  	echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/aktiv_12x13_'.$row["fb_status"].'.gif" border="0" alt="" /></a>';
  	echo '<a href="'.fb_map_url('controller=google').'&amp;delete='.$row["fb_id"];
  	echo '" title="delete: '.html_specialchars($row["fb_name"]).'"';
  	echo ' onclick="return confirm(\''.$BLM['delete_entry'].' '.html_specialchars(addslashes($row["fb_name"])).'\');">';
  	echo '<img src="'.$phpwcms['modules'][$module]['dir'].'img/trash_13x13_1.gif" border="0" alt=""></a>';
   //} 
  	echo "</td>\n</tr>\n";
  	$row_count++;
  }
} else {
  echo '<tr><td colspan="6">'.$BLM['no_entry'].'</td></tr>';
}
?>
<tr class="br_module_listing_footrow"><td colspan="6">Resources:<br />
<a class="br_module_a" href="https://developers.google.com/+/plugins/+1button/" target="_blank">Google+ Developers Resources</a></td></tr>
  </tbody>
</table>