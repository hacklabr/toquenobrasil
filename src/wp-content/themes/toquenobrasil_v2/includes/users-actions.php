<?php
global $current_user;

if($current_user && isset($_REQUEST['tnb_user_action'])){
    require_once( ABSPATH . WPINC . '/registration.php' );
    require_once(ABSPATH . '/wp-admin/includes/media.php');
    require_once(ABSPATH . '/wp-admin/includes/file.php');
    require_once(ABSPATH . '/wp-admin/includes/image.php');
    
    if(isset($_FILES))
        foreach ($_FILES as $fname => $file)
            $_FILES[$fname]['name'] = toquenobrasil_sanitize_file_name($file['name']);
    
            
    $upload_dir = WP_CONTENT_DIR.'/uploads';
    
    
    switch($_REQUEST['tnb_user_action']){
    	case 'contrato-oportunidade-aceitar':
    		set_contrato_inscricao_aceito($_POST['evento_id']);
    	break;
    	
    	case 'contrato-oportunidade-recusar':
    		set_contrato_inscricao_recusado($_POST['evento_id']);
    	break;
    	
        case 'delete-media':
            
            $media_id = $_REQUEST['mid'];
            $media_type = $_REQUEST['mtype'];
            
            if (current_user_can('delete_post', $media_id)) {
                
                $post = get_post($media_id);
                if($post){
                    $types = array(
                        'mapa_palco' => __('Mapa do palco','tnb'),
                        'rider' => __('Rider','tnb'),
                        'images' => __('Imagem','tnb'),
                        'music' => __('Música','tnb'),
                    );
                    $msg['notice'][] = sprintf(__('%s (%s) excluído com sucesso.', 'tnb'), $types[$media_type],$post->post_title);
        
                    wp_delete_attachment($media_id);
                }
            }
        break;
        
        case 'save-order':
        
        
            $order = $_POST['ordem'];
            
            $i = 0;
            
            $order = explode(',', $order);
            
            if (is_array($order) && sizeof($order) > 0 ) {
                global $wpdb;
                foreach ($order as $o) {
                
                    $p_id = str_replace('media_', '', $o);
                    $wpdb->update($wpdb->posts, array('menu_order' => $i), array('ID' => $p_id));
                    $i++;
                
                }
            }
        
        
        break;
        
    }
}
