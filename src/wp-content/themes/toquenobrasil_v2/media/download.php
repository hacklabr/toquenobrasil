<?php

$file_url = wp_get_attachment_url($_GET['id']);

$clean_name = str_replace(dirname($file_url) . '/', '', $file_url);
$clean_name = preg_replace('/(-[0-9a-f]{13}.mp3)$/', '.mp3', $clean_name);

//checa se o download dessa música é permitido
$download = get_post_meta($_GET['id'], '_download', true);

if (!$download)
    die('Download não permitido');

do_action('download_music', $_GET['id']);

$mime = $post->post_mime_type;

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mime");
header("Content-Disposition: attachment; filename=$clean_name");
header("Content-Transfer-Encoding: binary");

readfile($file_url);

