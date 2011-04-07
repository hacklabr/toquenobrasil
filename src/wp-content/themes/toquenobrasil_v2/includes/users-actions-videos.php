<?php

switch($_REQUEST['tnb_user_action']){
    case 'insert-video':
        
        
        if(strlen($_POST['video_url'])>0 && ( preg_match("/\/watch\?v=/", $_POST['video_url']) || preg_match("/vimeo.com\/\d+$/", $_POST['video_url'])  ) ) {
            $post = array(
                "post_title"    => $_POST['video_title'],
                "post_content"    => $_POST['video_content'],
                "post_excerpt"  => $_POST['video_url'],
                "post_author"   => $profileuser->ID,
                "post_type" => 'videos',
                "post_status" => 'publish'
            );
            
            $post_id = wp_insert_post($post);
            
            $menu_order = count(tnb_get_artista_videos($profileuser->ID));
            
            if($_POST['video_principal'] || $menu_order == 0)
                tnb_set_artista_video_principal($profileuser->ID, $post_id);
                
            tnb_cache_unset("ARTISTAS_VIDEOS", $profileuser->ID);
        } else {
            $msg['error'][] = __('URL do vídeo inválida. Use o endereço de um vídeo no youtube ou vimeo', 'tnb');
        }
        
        
    break;
    
    case 'delete-video':
    
        if ($_GET['mid']) {
        
            if (current_user_can('delete_post', $_GET['mid']))
                wp_delete_post($_GET['mid']);
        
        }
    
    break;
    
    case 'edit-video-save':
    
        if(strlen($_POST['video_url'])>0 && ( preg_match("/\/watch\?v=/", $_POST['video_url']) || preg_match("/vimeo.com\/\d+$/", $_POST['video_url'])  ) ) {
            $update = array(
                'ID' => $_POST['mid'],
                'post_title' => $_POST['video_title'],
                'post_content'    => $_POST['video_description'],
                'post_excerpt'  => $_POST['video_url'],
            ); 
            
            wp_update_post($update);
        } else {
            $msg['error'][] = __('URL do vídeo inválida. Use o endereço de um vídeo no youtube ou vimeo', 'tnb');
        }
        
        $msg['success'][] = __('Vídeo atualizado', 'tnb');
        break;
        
    case 'edit-video':
        
        $edit = true;
        $video_edit = get_post($_REQUEST['mid']);
        
        if (is_object($video_edit)) {
            $video_edit_id = $video_edit->ID;
            $video_edit_title = $video_edit->post_title;
            $video_edit_description = $video_edit->post_content;
            $video_edit_url = $video_edit->post_excerpt;
        }
        
    break;

    
    
}
