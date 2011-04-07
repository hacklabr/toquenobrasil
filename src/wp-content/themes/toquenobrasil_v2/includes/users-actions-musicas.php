<?php

switch($_REQUEST['tnb_user_action']){
    case 'insert-musica':
//          UPLOAD_ERR_CANT_WRITE   = 7
//          UPLOAD_ERR_EXTENSION    = 8
//          UPLOAD_ERR_FORM_SIZE    = 2
//          UPLOAD_ERR_INI_SIZE     = 1
//          UPLOAD_ERR_NO_FILE      = 4
//          UPLOAD_ERR_NO_TMP_DIR   = 6
//          UPLOAD_ERR_OK           = 0
//          UPLOAD_ERR_PARTIAL      = 3
           
        if(isset($_FILES['music']) && $_FILES['music']['error'] == 0){
            
            $gal_musica = tnb_get_artista_galeria($profileuser->ID, 'music');
            
            $acceptedFormats = array(	'audio/mpeg', 'audio/x-mpeg', 'audio/mp3',
                                 		'audio/x-mp3', 'audio/mpeg3', 'audio/x-mpeg3',
                                 		'audio/mpg', 'audio/x-mpg', 'audio/x-mpegaudio');
            
            if (in_array($_FILES['music']['type'], $acceptedFormats)) {
                 $post = array(
                    "post_title"    => $_POST['music_title'],
                    "post_content"  => $_FILES['music']['name'],
                    "post_excerpt"  => $_FILES['music']['name'],
                    "post_author"   => $profileuser->ID
                 );
                unset($GLOBALS['post']);
                $media_id = media_handle_upload('music', $gal_musica->ID, $post);
                if ($media_id->errors)
                    $msg['error'][] = implode(' ', $media_id->errors['upload_error']);
                
                if(!$msg['error']){
                    $meta = get_post_meta($media_id, '_wp_attached_file');
                    $sizes = get_media_file_sizes($upload_dir ."/". $meta[0]);
                    
                    $menu_order = count(tnb_get_artista_musicas($profileuser->ID));
                    
                    $update = array(
                        'ID' => $media_id,
                        'menu_order'	=> $menu_order
                    ); 
            	    
                    wp_update_post($update);
                    
                   //$albuns = get_user_meta($current_user->ID, 'albuns');
                    
                    
                    update_post_meta($media_id, '_filesize', $sizes['filesize']);
    	            update_post_meta($media_id, '_playtime', $sizes['playtime']);
    	            
    	            update_post_meta($media_id, '_album', $_POST['music_album']);
    	            update_post_meta($media_id, '_download', $_POST['music_download'] ? 1 : 0);
    	            
    	            if($_POST['music_principal'] || $menu_order == 0)
        	            tnb_set_artista_musica_principal($profileuser->ID, $media_id);
    	                    
                    tnb_cache_unset("ARTISTAS_MUSICAS", $profileuser->ID);
                } 
            } else {
                
                $msg['error'][] = __('Tipo de arquivo não permitido','tnb');
            }
        }
        
    break;
    
    case 'edit-musica-save':
    
        $update = array(
            'ID' => $_POST['mid'],
            'post_title' => $_POST['music_title']
        ); 
        
        wp_update_post($update);
        
        update_post_meta($_POST['mid'], '_album', $_POST['music_album']);
    	update_post_meta($_POST['mid'], '_download', $_POST['music_download'] ? 1 : 0);
        
        if ($_POST['music_principal'])
            tnb_set_artista_musica_principal($profileuser->ID, $_POST['mid']);
        
        $msg['success'][] = __('Música atualizada', 'tnb');
        break;
        
    case 'edit-musica':
        
        $edit = true;
        $musica_edit = get_post($_REQUEST['mid']);
        if (is_object($musica_edit)) {
            $musica_edit_id = $musica_edit->ID;
            $musica_edit_download = get_post_meta($musica_edit_id, '_download', true);
            $musica_edit_album = get_post_meta($musica_edit_id, '_album', true);
            $musica_edit_title = $musica_edit->post_title;
        }
        
    break;
    
    
}
