<?php
require_once('../../../../wp-load.php');
global $wpdb;

$attas = $wpdb->get_results("
SELECT 
	`$wpdb->users`.user_login,
	`$wpdb->posts`.ID,
	`$wpdb->posts`.post_author,
	`$wpdb->posts`.guid,
	`$wpdb->posts`.post_date
	
	
FROM  `$wpdb->posts`, `$wpdb->users`
WHERE 
	`$wpdb->posts`.`post_type` = 'attachment' AND
	`$wpdb->posts`.`post_mime_type` <> '' AND
	`$wpdb->users`.ID = `$wpdb->posts`.post_author
ORDER BY `$wpdb->posts`.post_date ASC
");

$posts = array();
$sobrescritos = array();
foreach ($attas as $atta){
    if(isset($posts[$atta->guid]) && $atta->post_author != $posts[$atta->guid]->post_author){
        $sobrescrito = array();
        $sobrescrito['de'] = $posts[$atta->guid]; 
        $sobrescrito['por'] = $atta;
        $sobrescritos[] = $sobrescrito;
    }
    $posts[$atta->guid] = $atta;
}

_pr($sobrescritos);