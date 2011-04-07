<?php

add_action('download_music',    'tnb_stats_add_download_music');
add_action('play_music',        'tnb_stats_add_play_music');
add_action('profile_view',      'tnb_stats_add_profile_view');
add_action('eventos_view',      'tnb_stats_add_eventos_view');


function tnb_stats_add_download_music($id) {
    tnb_stats_add('downloads', $id);
}

function tnb_stats_add_play_music($id) {
    tnb_stats_add('plays', $id);
}

function tnb_stats_add_profile_view($id) {
    tnb_stats_add('profile_views', $id);
}

function tnb_stats_add_eventos_view($id) {
    tnb_stats_add('eventos_views', $id);
    
}


/* Soma um as estatísticas
 * $type: tipo de estatística (eventos_views, profile_views, downloads, plays)
 * $object_id: ID do post ou usuário
 * $day: yyyy-mm-dd - dia ao qual deve ser somado mais um
 */

function tnb_stats_add($type, $object_id, $day = null) {

    // Não contar para admin
    if (current_user_can('manage_options'))
        return false;

    $day = is_null($day) ? date('Y-m-d') : $day;
    
    global $wpdb;
    
    if ($current = $wpdb->get_row("SELECT ID, count FROM {$wpdb->prefix}tnb_stats WHERE day = '$day' AND object_id = $object_id AND type = '$type'")) {
        
        $wpdb->update( $wpdb->prefix . 'tnb_stats', array('count' => $current->count + 1), array('ID' => $current->ID) );
    } else {
        $wpdb->insert( $wpdb->prefix . 'tnb_stats', array('count' => 1, 'day' => $day, 'object_id' => $object_id, 'type' => $type) );
    }
    
    switch ($type) {
    
        case 'plays':
            
            $plays = get_post_meta($object_id, '_plays', true);
            $plays = is_numeric($plays) ? $plays = (int) $plays + 1 : 1;
            update_post_meta($object_id, '_plays', $plays);
            
            break;
        
        case 'downloads':
        
            $downloads = get_post_meta($object_id, '_downloads', true);
            $downloads = is_numeric($downloads) ? $downloads = (int) $downloads + 1 : 1;
            update_post_meta($object_id, '_downloads', $downloads);
            
            break;
        
        case 'eventos_views':
        
            $views = get_post_meta($object_id, '_views', true);
            $views = is_numeric($views) ? $views = (int) $views + 1 : 1;
            update_post_meta($object_id, '_views', $views);
            
            break;
            
        case 'profile_views':
        
            $views = get_user_meta($object_id, '_views', true);
            $views = is_numeric($views) ? $views = (int) $views + 1 : 1;
            update_user_meta($object_id, '_views', $views);
            
            break;
    
    }
    

}



