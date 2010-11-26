<?php

require_once('../../../../wp-load.php');

if (!current_user_can('manage_options'))
    die ('sem permissao');

global $wpdb;

$posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_type IN ('images', 'music', 'rider', 'mapa_palco', 'attachment')");

$options = get_option('tantan_wordpress_s3');

foreach ($posts as $post) {    
    
    $data = array();
    
    $postID = $post->ID;
    
    $month = null;
    
    
    if (!$options['wp-uploads'] || !$options['bucket'] || !$options['secret']) {
        die('configuracoes do plugin nao encontradas');
    }
        
    add_filter('option_siteurl', 'tnb_script_upload_path');
    $uploadDir = wp_upload_dir();
    remove_filter('option_siteurl', 'tnb_script_upload_path');
    $parts = parse_url($uploadDir['url']);
    
    $prefix = substr($parts['path'], 1) .'/';

    $filename = get_attached_file($postID, true);
    
    preg_match('/2010\/(\d\d)\//', $filename, $m);
    
    if (is_array($m) && sizeof($m) > 1) {
        $month = $m[1];
        $prefix = preg_replace("|(2010/)(\d\d)|", "2010/$month", $prefix);
    }
    
    
    
    echo 'buscando ' . $filename . '<br>';

    if (file_exists($filename)) {
        
        $filename = basename($filename);
        
        $meta = array(
            'bucket' => $options['bucket'],
            'key' => $prefix.$filename
        );
        
        delete_post_meta($postID, 'amazonS3_info');
        add_post_meta($postID, 'amazonS3_info', $meta);
            
        echo 'adicionado meta ao post ID: ' . $postID . '<br/>';
        echo serialize($meta);
    }

    echo '<br><br>';

} //endforeach



function tnb_script_upload_path($path='') {
    global $current_blog;
    if (!$current_blog) return $path;
    if ($current_blog->path == '/' && ($current_blog->blog_id != 1)) {
        $dir = substr($current_blog->domain, 0, strpos($current_blog->domain, '.'));
    } else {
        // prepend a directory onto the path for vhosted blogs
        if (constant("VHOST") != 'yes') {
            $dir = '';
        } else {
            $dir = $current_blog->path;
        }
    }
    //echo trim($path.'/'.$dir, '/');
    if ($path == '') {
        $path = $current_blog->path;
    }
    return trim($path.'/'.$dir, '/');
}



?>
