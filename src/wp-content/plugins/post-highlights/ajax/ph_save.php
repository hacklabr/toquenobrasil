<?php 

require_once('../../../../wp-load.php');

if ($_POST["action"]=="highlight"){
	if(!get_post_meta($_POST["id"],"ph_order")) update_post_meta($_POST["id"],"ph_order",1);
}
if ($_POST["action"]=="unhighlight"){
	delete_post_meta($_POST["id"],"ph_order");
}

if ($_POST["action"]=="picture_url"){
	update_post_meta($_POST["id"],"ph_picture_url",$_POST["url"]);
	delete_post_meta($_POST["id"],"ph_picture_id");
}

if ($_POST["action"]=="picture_id"){
	update_post_meta($_POST["id"],"ph_picture_id",$_POST["picture_id"]);
	delete_post_meta($_POST["id"],"ph_picture_url");
}

if ($_POST["action"]=="headline"){
	update_post_meta($_POST["id"],"ph_headline",$_POST["txt"]);
}

if ($_POST["action"]=="order"){
	update_post_meta($_POST["id"],"ph_order",$_POST["order"]);
}

?>
