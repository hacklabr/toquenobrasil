<?php

$banner = addslashes(preg_replace('/\.\.\//', '', $_GET['banner']));
$id = $_GET['id'];
$posicao_ID = $_GET['posicao_ID'];

$uploadPath = substr(__FILE__, 0, strpos(__FILE__, 'wp-content') + 11) . 'banners/';

if($_GET['logged'] != 1){
    
    $fp = fopen("../../../wp-config.php", "r");
    $varcount = 0;
    $config = array();
    
    while ($varcount < 4 && !feof($fp)) {
        $line = fgets($fp);
        if (preg_match("/^define\('DB_([A-Z]+)',\s*['\"](.*?)['\"]/", $line, $m)) {
            $varname = $m[1];
            $value = $m[2];
    
            if ($varname == 'NAME' || $varname == 'USER' || $varname == 'PASSWORD' || $varname == 'HOST') {
                $config[$varname] = $value;
                $varcount++;
            }
        }
    }
    
    if ($varcount == 4) {
        mysql_connect($config['HOST'],
                      $config['USER'],
                      $config['PASSWORD']);
        mysql_select_db($config['NAME']);
    
        mysql_query("UPDATE wp_wpeb_banners SET views = views + 1 WHERE ID = $id");
        
    }
}
$imagetypes = array('gif' => 'gif', 'jpg' => 'jpeg', 'png' => 'png', 'peg' => 'jpeg');

header('Content-type: image/', $imagetypes[substr($banner, -3)]);
if(!is_file($uploadPath . $banner))
    readfile('pixel.gif');
else
    readfile($uploadPath . $banner);



?>
