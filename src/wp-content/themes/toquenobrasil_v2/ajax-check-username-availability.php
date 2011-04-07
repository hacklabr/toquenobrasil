<?php 

define('SHORTINIT', true);

require('../../../wp-load.php');

global $wpdb;

$id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_login = %s", $_POST['username']));


if (is_numeric($id))
    echo 0;
else
    echo 1;
