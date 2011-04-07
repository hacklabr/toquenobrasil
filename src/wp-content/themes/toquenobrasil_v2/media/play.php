<?php

$url = wp_get_attachment_url($_GET['id']);

do_action('play_music', $_GET['id']);

header('location:' . $url);
