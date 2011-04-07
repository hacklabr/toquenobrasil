<?php
session_start();
if(!isset($_SESSION['hacklab'])){
    if(isset($_REQUEST['hacklab'])){
        $_SESSION['hacklab'] = true;
    }else{
        ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>TNB</title>
        <style type="text/css" media="screen">
            @font-face {
                font-family: 'Arista';
                src: url('arista-webfont.eot');
                src: url('arista-webfont.eot?iefix') format('eot'),
                     url('arista-webfont.woff') format('woff'),
                     url('arista-webfont.ttf') format('truetype'),
                     url('arista-webfont.svg#webfontWvctblUU') format('svg');
                font-weight: normal;
                font-style: normal;
            }
        
            * { margin:0; padding:0; }
            body { background:url(pattern.jpg) center center fixed; }
            .container { margin:0 auto; width:960px; }
            #tnb { left:50%; margin-left:-180px; margin-top:124px; position:absolute; }
            .content { bottom:124px; left:50%; margin-left:-480px; position:absolute; text-align:center; width:960px; }
            h1, h2 { font-family:"Arista"; text-transform:uppercase; }
            h1 { color:#04BAEE; font-size:38px; }
            h2 { color:#72BE44; font-size:30px; }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="tnb.png" id="tnb"/>
            <div class="content">
                <h1>estamos preparando novidades para você, aguarde!</h1>
                <h2>amanhã estaremos de volta</h2>
            </div>
        </div>
    </body>
</html>        
        
        
        <?php 
        die;
    }
}

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
 
/*
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
/* */

define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require('./wp-blog-header.php');

/*
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
_pr("This page was created in ".$totaltime." seconds - queries: ".$wpdb->num_queries.'<br /><br /><br />'); 
/* */
?>
