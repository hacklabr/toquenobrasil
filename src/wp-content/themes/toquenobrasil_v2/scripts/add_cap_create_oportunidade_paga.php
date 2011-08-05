<?php
require_once('../../../../wp-load.php');

$user = get_user_by('id', 28);
if(method_exists($user, 'add_cap'))
    _pr($user);