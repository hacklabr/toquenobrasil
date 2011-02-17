<?php

$curauth = $wp_query->queried_object;

$medias = get_posts("post_type=images&meta_key=_media_index&author={$curauth->ID}&orderby=menu_order&order=ASC");

echo '<h1>Fotos de ' . $curauth->display_name . '</h1>';

echo '<p><a href="' . get_author_posts_url($curauth->ID) . '">Voltar ao perfil</a></p>';

$images_url = get_option('siteurl') . '/wp-content/uploads/';

foreach ($medias as $media) {
    
    /*
    $meta = get_post_meta($media->ID, '_wp_attachment_metadata');
    $img = $images_url . $meta[0]['file'];
    
    echo "<img src='$img' />";
    */
    
    echo wp_get_attachment_image( $media->ID, 'full', true );
    
    echo '<hr/>';
        
    
}

?>
