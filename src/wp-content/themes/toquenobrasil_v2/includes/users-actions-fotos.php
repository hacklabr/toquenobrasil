<?php

switch($_REQUEST['tnb_user_action']){
    case 'insert-image':
//          UPLOAD_ERR_CANT_WRITE   = 7
//          UPLOAD_ERR_EXTENSION    = 8
//          UPLOAD_ERR_FORM_SIZE    = 2
//          UPLOAD_ERR_INI_SIZE     = 1
//          UPLOAD_ERR_NO_FILE      = 4
//          UPLOAD_ERR_NO_TMP_DIR   = 6
//          UPLOAD_ERR_OK           = 0
//          UPLOAD_ERR_PARTIAL      = 3
           
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
            
            $gal_image = tnb_get_artista_galeria($profileuser->ID, 'images');
            
            $acceptedFormats = array('image/gif', 'image/png', 'image/jpeg',
                                      'image/pjpeg', 'image/x-png');
            
            if (in_array($_FILES['image']['type'], $acceptedFormats)) {
                 $post = array(
                    "post_title"    => $_POST['image_title'],
                    "post_content"  => $_FILES['image']['name'],
                    "post_excerpt"  => $_FILES['image']['name'],
                    "post_author"   => $profileuser->ID
                 );
                unset($GLOBALS['post']);
                $media_id = media_handle_upload('image', $gal_image->ID, $post);
                if ($media_id->errors)
                    $msg['error'][] = implode(' ', $media_id->errors['upload_error']);
                
                if(!$msg['error']){
                    
                    $menu_order = count(tnb_get_artista_fotos($profileuser->ID));
                    
                    $update = array(
                        'ID' => $media_id,
                        'menu_order'	=> $menu_order
                    ); 
            	    
                    wp_update_post($update);
                    
                    tnb_cache_unset("ARTISTAS_FOTOS", $profileuser->ID);
                } 
            } else {
                
                $msg['error'][] = __('Tipo de arquivo não permitido','tnb');
            }
        }
        
    break;
    
    case 'edit-image-save':
    
        $update = array(
            'ID' => $_POST['mid'],
            'post_title' => $_POST['image_title']
        ); 
        
        wp_update_post($update);
        
        $msg['success'][] = __('Foto atualizada','tnb');
        break;
        
    case 'edit-image':
        
        $edit = true;
        $image_edit = get_post($_REQUEST['mid']);
        if (is_object($image_edit)) {
            $image_edit_id = $image_edit->ID;
            $image_edit_title = $image_edit->post_title;
        }
        
    break;
    
    
}
