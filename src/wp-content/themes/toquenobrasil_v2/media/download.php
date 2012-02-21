<?php


$file_url = wp_get_attachment_url($_GET['id']);


//checa se o download dessa música é permitido
$download = get_post_meta($_GET['id'], '_download', true);

if (!$download)
    die('Download não permitido');

do_action('download_music', $_GET['id']);

$mime = $post->post_mime_type;

$filename = preg_replace('|^http://[^/]+/wp-content/uploads/\d{4}/\d\d/(.+)(-[a-zA-Z0-9]{13}).mp3$|', "$1.mp3", $file_url);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: $mime");
header("Content-Transfer-Encoding: binary");

readfile($file_url);

