<?php
define('WP_USE_THEMES', FALSE);
require('../../../wp-config.php');

global $wpeb_banners, $user_ID;
if (!is_object($wpeb_banners))
    exit; // avoid error

$banner = $wpeb_banners->get_item((int) $_GET['banner']);

if (!$user_ID) {

	$banner->info->clicks ++;
	$banner->save();
}

header("location: " . $banner->info->link);

?>
